<?php
require 'includes/db.php';

// Белый список категорий (защита от произвольных значений в GET)
$validCategories = [];
foreach ($pdo->query("SELECT slug FROM categories") as $row) {
    $validCategories[] = $row['slug'];
}

$activeSlug = isset($_GET['category']) && in_array($_GET['category'], $validCategories)
    ? $_GET['category']
    : null;

// Товары (с фильтром по категории)
if ($activeSlug) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE c.slug = ?
        ORDER BY p.popularity DESC
    ");
    $stmt->execute([$activeSlug]);
} else {
    $stmt = $pdo->query("
        SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
        FROM products p
        JOIN categories c ON c.id = p.category_id
        ORDER BY p.popularity DESC
    ");
}
$products = $stmt->fetchAll();

// Собираем product_id всех товаров на странице
$ids = array_column($products, 'id');

// Размеры и свойства одним запросом (если есть товары)
$sizesMap = [];
$featuresMap = [];

if ($ids) {
    $inClause = implode(',', array_fill(0, count($ids), '?'));

    $sStmt = $pdo->prepare("SELECT * FROM product_sizes WHERE product_id IN ($inClause) ORDER BY id");
    $sStmt->execute($ids);
    foreach ($sStmt->fetchAll() as $s) {
        $sizesMap[$s['product_id']][] = $s;
    }

    $fStmt = $pdo->prepare("SELECT * FROM features WHERE product_id IN ($inClause) ORDER BY id");
    $fStmt->execute($ids);
    foreach ($fStmt->fetchAll() as $f) {
        $featuresMap[$f['product_id']][] = $f;
    }
}

// Категории для сайдбара
$categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string {
    return number_format($price, 0, '.', ' ') . ' ₽';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Каталог — Ortobase</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- НАВБАР -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <i class="bi bi-heart-pulse-fill text-info me-2"></i>Ortobase
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.html">Главная</a></li>
        <li class="nav-item"><a class="nav-link" href="about.html">О нас</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Каталог</a></li>
        <li class="nav-item"><a class="nav-link" href="why.html">Почему мы</a></li>
        <li class="nav-item"><a class="nav-link" href="faq.html">FAQ</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ШАПКА -->
<section class="py-4 section-alt border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-1">
        <li class="breadcrumb-item">
          <a href="index.html" class="text-decoration-none" style="color:var(--color-sky-500)">Главная</a>
        </li>
        <?php if ($activeSlug): ?>
          <li class="breadcrumb-item">
            <a href="catalog.php" class="text-decoration-none" style="color:var(--color-sky-500)">Каталог</a>
          </li>
          <li class="breadcrumb-item active">
            <?= e(array_column($categories, 'name', 'slug')[$activeSlug] ?? '') ?>
          </li>
        <?php else: ?>
          <li class="breadcrumb-item active">Каталог</li>
        <?php endif; ?>
      </ol>
    </nav>
    <h1 class="mb-0">
      <?= $activeSlug
          ? e(array_column($categories, 'name', 'slug')[$activeSlug] ?? 'Каталог')
          : 'Каталог' ?>
    </h1>
  </div>
</section>

<!-- ОСНОВНОЕ -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">

      <!-- САЙДБАР -->
      <div class="col-lg-3 col-md-4">
        <div class="sticky-top" style="top:20px">
          <h6 class="fw-bold mb-3 text-uppercase small" style="letter-spacing:.05em;color:var(--color-navy-900)">
            Категории
          </h6>
          <div class="list-group category-list">
            <a href="catalog.php"
               class="list-group-item list-group-item-action <?= !$activeSlug ? 'active' : '' ?>">
              <i class="bi bi-grid me-2"></i>Все товары
            </a>
            <?php foreach ($categories as $cat): ?>
              <a href="catalog.php?category=<?= e($cat['slug']) ?>"
                 class="list-group-item list-group-item-action <?= $activeSlug === $cat['slug'] ? 'active' : '' ?>">
                <?php
                  $icons = ['insoles' => 'bi-person-walking', 'shoes' => 'bi-boot', 'corsets' => 'bi-bandaid'];
                  $icon  = $icons[$cat['slug']] ?? 'bi-box';
                ?>
                <i class="bi <?= $icon ?> me-2"></i><?= e($cat['name']) ?>
              </a>
            <?php endforeach; ?>
          </div>

          <div class="mt-4 p-3 rounded-3" style="background:var(--color-sky-50);border:1px solid var(--color-sky-200)">
            <p class="small fw-semibold mb-1" style="color:var(--color-blue-700)">
              <i class="bi bi-person-badge me-1"></i>Нужна консультация?
            </p>
            <p class="small text-muted mb-2">Подберём товар вместе — бесплатно.</p>
            <a href="tel:+74951234567" class="btn btn-primary btn-sm w-100">
              <i class="bi bi-telephone me-1"></i>Позвонить
            </a>
          </div>
        </div>
      </div>

      <!-- СЕТКА ТОВАРОВ -->
      <div class="col-lg-9 col-md-8">

        <?php if (empty($products)): ?>
          <div class="text-center py-5">
            <i class="bi bi-inbox display-4 text-muted"></i>
            <p class="text-muted mt-3">Товаров в этой категории пока нет.</p>
            <a href="catalog.php" class="btn btn-outline-primary">Показать все</a>
          </div>
        <?php else: ?>

          <p class="text-muted small mb-4">
            Найдено товаров: <strong><?= count($products) ?></strong>
          </p>

          <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
            <?php foreach ($products as $p):
              $sizes    = $sizesMap[$p['id']] ?? [];
              $features = array_slice($featuresMap[$p['id']] ?? [], 0, 3);
              $modalId  = 'modal-' . (int)$p['id'];
            ?>
            <div class="col">
              <div class="card product-card h-100">
                <img src="<?= e($p['image'] ?? '') ?>"
                     alt="<?= e($p['name']) ?>"
                     loading="lazy">
                <div class="card-body d-flex flex-column">

                  <!-- Категория + название -->
                  <span class="small text-muted mb-1"><?= e($p['cat_name']) ?></span>
                  <h6 class="fw-bold mb-2"><?= e($p['name']) ?></h6>

                  <!-- Цена -->
                  <div class="product-price mb-2"><?= formatPrice((float)$p['price']) ?></div>

                  <!-- Размеры -->
                  <?php if ($sizes): ?>
                  <div class="mb-2">
                    <?php foreach ($sizes as $s): ?>
                      <span class="size-badge <?= $s['in_stock'] ? '' : 'out-of-stock' ?>">
                        <?= e($s['size']) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                  <?php endif; ?>

                  <!-- Описание (обрезаем до 90 символов) -->
                  <p class="small text-muted mb-2" style="flex-grow:1">
                    <?= e(mb_strimwidth($p['description'] ?? '', 0, 90, '…')) ?>
                  </p>

                  <!-- Фичи-чипы -->
                  <?php if ($features): ?>
                  <div class="mb-3">
                    <?php foreach ($features as $f): ?>
                      <span class="feature-chip">
                        <i class="bi <?= e($f['icon'] ?? 'bi-check') ?>"></i>
                        <?= e($f['text']) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                  <?php endif; ?>

                  <!-- Кнопка -->
                  <button class="btn btn-primary btn-sm w-100 mt-auto"
                          data-bs-toggle="modal"
                          data-bs-target="#<?= $modalId ?>">
                    Подробнее
                  </button>
                </div>
              </div>
            </div>

            <!-- МОДАЛКА -->
            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                  <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body pt-0">
                    <div class="row g-4">
                      <div class="col-md-5">
                        <img src="<?= e($p['image'] ?? '') ?>"
                             alt="<?= e($p['name']) ?>"
                             class="img-fluid rounded-3 w-100"
                             style="height:280px;object-fit:cover;">
                      </div>
                      <div class="col-md-7">
                        <span class="badge rounded-pill mb-2 px-3" style="background:var(--color-sky-200);color:var(--color-blue-700)">
                          <?= e($p['cat_name']) ?>
                        </span>
                        <h4 class="fw-bold mb-1"><?= e($p['name']) ?></h4>
                        <div class="product-price mb-3"><?= formatPrice((float)$p['price']) ?></div>

                        <p class="text-muted small mb-3"><?= e($p['description'] ?? '') ?></p>

                        <?php if ($sizes): ?>
                        <p class="fw-semibold small mb-1">Доступные размеры:</p>
                        <div class="mb-3">
                          <?php foreach ($sizes as $s): ?>
                            <span class="size-badge <?= $s['in_stock'] ? '' : 'out-of-stock' ?>">
                              <?= e($s['size']) ?>
                            </span>
                          <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($features): ?>
                        <p class="fw-semibold small mb-2">Ключевые свойства:</p>
                        <div>
                          <?php foreach ($featuresMap[$p['id']] as $f): ?>
                            <div class="d-flex align-items-center gap-2 mb-1">
                              <i class="bi <?= e($f['icon'] ?? 'bi-check-circle') ?> text-info"></i>
                              <span class="small"><?= e($f['text']) ?></span>
                            </div>
                          <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer border-0 pt-0">
                    <a href="tel:+74951234567" class="btn btn-primary">
                      <i class="bi bi-telephone me-2"></i>Узнать наличие
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Закрыть</button>
                  </div>
                </div>
              </div>
            </div>

            <?php endforeach; ?>
          </div>

        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- ПОДВАЛ -->
<footer class="py-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h5 class="text-white fw-bold mb-2">
          <i class="bi bi-heart-pulse-fill text-info me-2"></i>Ortobase
        </h5>
        <p class="small">Ортопедические товары для здоровой и активной жизни.</p>
      </div>
      <div class="col-md-4">
        <h6 class="text-white mb-3">Навигация</h6>
        <a href="index.html" class="footer-link small">Главная</a>
        <a href="about.html" class="footer-link small">О нас</a>
        <a href="catalog.php" class="footer-link small">Каталог</a>
        <a href="why.html" class="footer-link small">Почему мы</a>
        <a href="faq.html" class="footer-link small">FAQ</a>
      </div>
      <div class="col-md-4">
        <h6 class="text-white mb-3">Контакты</h6>
        <p class="small mb-1"><i class="bi bi-geo-alt me-1"></i>ул. Здоровья, 12, Москва</p>
        <p class="small mb-1"><i class="bi bi-telephone me-1"></i>+7 (495) 123-45-67</p>
        <p class="small mb-1"><i class="bi bi-clock me-1"></i>Пн–Сб: 9:00–20:00</p>
      </div>
    </div>
    <hr class="mt-4 mb-3">
    <p class="text-center small mb-0">© 2025 Ortobase. Все права защищены.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
