<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Дэшборд — Ortobase Admin</title>

  <!-- Применяем тему ДО рендера, чтобы не моргало -->
  <script>
    (function(){var t=localStorage.getItem('admin-theme');if(t==='dark')document.documentElement.setAttribute('data-theme','dark')})();
  </script>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Стили админки -->
  <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<!-- Оверлей для мобильного сайдбара -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div id="wrapper">

  <!-- Сайдбар (загружается jQuery) -->
  <div id="sidebar-placeholder"></div>

  <!-- Основная зона -->
  <div id="content-wrapper">

    <!-- Топбар (загружается jQuery) -->
    <div id="topbar-placeholder"></div>

    <!-- Контент страницы -->
    <main class="page-main">

      <!-- Заголовок страницы -->
      <div class="page-header">
        <div>
          <h1 class="page-title">Дэшборд</h1>
          <p class="page-subtitle">Обзор каталога, активности и пользователей.</p>
        </div>
        <div class="page-header-actions">
          <button class="btn-admin-outline">
            <i class="bi bi-download me-1"></i>Экспорт
          </button>
          <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
            <i class="bi bi-plus me-1"></i>Добавить товар
          </button>
        </div>
      </div>

      <!-- KPI-карточки -->
      <div class="row g-3 mb-4">

        <!-- Товары -->
        <div class="col-6 col-md-3">
          <div class="kpi-card">
            <div class="kpi-label">Товары</div>
            <div class="kpi-icon rounded-3" style="background:#3FB68B22;">
              <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                <rect width="38" height="38" rx="9" fill="#3FB68B" fill-opacity="0.15"/>
                <rect x="10" y="10" width="18" height="18" rx="3" fill="#3FB68B"/>
              </svg>
            </div>
            <div class="kpi-number">9</div>
            <div class="kpi-change positive"><i class="bi bi-arrow-up-short"></i>+2 за неделю</div>
          </div>
        </div>

        <!-- Категории -->
        <div class="col-6 col-md-3">
          <div class="kpi-card">
            <div class="kpi-label">Категории</div>
            <div class="kpi-icon rounded-3">
              <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                <rect width="38" height="38" rx="9" fill="#94C5E8" fill-opacity="0.18"/>
                <rect x="10" y="10" width="18" height="18" rx="3" fill="#94C5E8"/>
              </svg>
            </div>
            <div class="kpi-number">3</div>
            <div class="kpi-change neutral">без изменений</div>
          </div>
        </div>

        <!-- Нет в наличии -->
        <div class="col-6 col-md-3">
          <div class="kpi-card">
            <div class="kpi-label">Нет в наличии</div>
            <div class="kpi-icon rounded-3">
              <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                <rect width="38" height="38" rx="9" fill="#E87174" fill-opacity="0.15"/>
                <rect x="10" y="10" width="18" height="18" rx="3" fill="#E87174"/>
              </svg>
            </div>
            <div class="kpi-number">8</div>
            <div class="kpi-change negative"><i class="bi bi-arrow-up-short"></i>+3 размера</div>
          </div>
        </div>

        <!-- Пользователи -->
        <div class="col-6 col-md-3">
          <div class="kpi-card">
            <div class="kpi-label">Пользователи</div>
            <div class="kpi-icon rounded-3">
              <svg width="38" height="38" viewBox="0 0 38 38" fill="none">
                <rect width="38" height="38" rx="9" fill="#3FB68B" fill-opacity="0.15"/>
                <rect x="10" y="10" width="18" height="18" rx="3" fill="#3FB68B"/>
              </svg>
            </div>
            <div class="kpi-number">6</div>
            <div class="kpi-change positive"><i class="bi bi-arrow-up-short"></i>+1 приглашение</div>
          </div>
        </div>

      </div><!-- /row KPI -->

      <!-- График + Топ категорий -->
      <div class="row g-3 mb-4">

        <!-- Бар-чарт -->
        <div class="col-12 col-lg-8">
          <div class="chart-card h-100">
            <div class="chart-header">
              <h2 class="chart-title">Просмотры каталога · <span id="chart-period-label">14 дней</span></h2>
              <div class="chart-tabs">
                <button class="chart-tab" data-period="7">7д</button>
                <button class="chart-tab active" data-period="14">14д</button>
                <button class="chart-tab" data-period="30">30д</button>
              </div>
            </div>
            <div class="chart-bars-wrapper">
              <div class="chart-bars" id="chart-bars">
                <!-- Заполняется JavaScript -->
              </div>
            </div>
          </div>
        </div>

        <!-- Топ категорий -->
        <div class="col-12 col-lg-4">
          <div class="categories-card h-100">
            <div class="categories-title">Топ категорий</div>

            <div class="category-row">
              <div class="category-row-header">
                <div class="category-dot"></div>
                <span class="category-name">Стельки</span>
                <span class="category-count">3 тов.</span>
              </div>
              <div class="category-progress">
                <div class="category-progress-bar" style="width:100%"></div>
              </div>
            </div>

            <div class="category-row">
              <div class="category-row-header">
                <div class="category-dot"></div>
                <span class="category-name">Обувь</span>
                <span class="category-count">3 тов.</span>
              </div>
              <div class="category-progress">
                <div class="category-progress-bar" style="width:100%"></div>
              </div>
            </div>

            <div class="category-row">
              <div class="category-row-header">
                <div class="category-dot"></div>
                <span class="category-name">Корсеты и бандажи</span>
                <span class="category-count">3 тов.</span>
              </div>
              <div class="category-progress">
                <div class="category-progress-bar" style="width:100%"></div>
              </div>
            </div>

          </div>
        </div>

      </div><!-- /row график -->

      <!-- Лента активности -->
      <div class="row g-3">
        <div class="col-12">
          <div class="activity-card">
            <div class="activity-header">
              <h2 class="activity-title">Лента активности</h2>
              <a href="#" class="activity-link">Все события →</a>
            </div>

            <div class="activity-item">
              <div class="activity-avatar" style="background:#4FA8F0;">АС</div>
              <div class="activity-body">
                <div class="activity-text">
                  <strong>Анна Соколова</strong> добавила товар «Стельки ActiveRun»
                </div>
                <div class="activity-time">5 мин назад</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-avatar" style="background:#3FB68B;">ДЗ</div>
              <div class="activity-body">
                <div class="activity-text">
                  <strong>Дмитрий Захаров</strong> обновил цену на «BackFix Pro»: 3 490 ₽
                </div>
                <div class="activity-time">40 мин назад</div>
              </div>
            </div>

            <div class="activity-item">
              <div class="activity-avatar" style="background:#4FA8F0;">АС</div>
              <div class="activity-body">
                <div class="activity-text">
                  <strong>Анна Соколова</strong> пригласила пользователя «Иван Орлов»
                </div>
                <div class="activity-time">1 ч назад</div>
              </div>
            </div>

          </div>
        </div>
      </div><!-- /row активность -->

    </main><!-- /page-main -->
  </div><!-- /content-wrapper -->
</div><!-- /wrapper -->

<!-- Модалка: Добавить товар (болванка) -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-labelledby="modalAddProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddProductLabel">Добавить товар</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        <form id="formAddProduct">
          <div class="mb-3">
            <label class="form-label">Название</label>
            <input type="text" class="form-control" placeholder="Стельки ActiveRun">
          </div>
          <div class="mb-3">
            <label class="form-label">Категория</label>
            <select class="form-select">
              <option value="">Выбрать...</option>
              <option>Стельки</option>
              <option>Обувь</option>
              <option>Корсеты и бандажи</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Цена, ₽</label>
            <input type="number" class="form-control" placeholder="2 490">
          </div>
          <div class="mb-3">
            <label class="form-label">URL картинки</label>
            <input type="url" class="form-control" placeholder="https://...">
          </div>
          <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea class="form-control" rows="3" placeholder="Краткое описание товара..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Отмена</button>
        <button type="button" class="btn-admin-primary">Сохранить</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Скрипты админки -->
<script src="assets/js/admin.js"></script>

</body>
</html>
