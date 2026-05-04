# Бэкенд (PHP)

В варианте А PHP используется **только** на одной странице — `catalog.php`. Остальные 4 страницы — чистый HTML.

## `includes/db.php` — подключение
```php
<?php
$host = 'localhost';
$db   = 'ortobase';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $user, $pass, $options);
```

## `catalog.php` — что внутри

```
1. <?php
       require 'includes/db.php';

       // фильтр по категории
       $category = $_GET['category'] ?? null;

       // SQL: товары + категория
       if ($category) {
           $stmt = $pdo->prepare("
               SELECT p.*, c.slug AS cat_slug, c.name AS cat_name
               FROM products p
               JOIN categories c ON c.id = p.category_id
               WHERE c.slug = ?
           ");
           $stmt->execute([$category]);
       } else {
           $stmt = $pdo->query("
               SELECT p.*, c.slug AS cat_slug, c.name AS cat_name
               FROM products p
               JOIN categories c ON c.id = p.category_id
           ");
       }
       $products = $stmt->fetchAll();

       // подтянуть размеры и свойства разом одним запросом
       $sizes    = подгружаем для всех product_id
       $features = подгружаем для всех product_id

       // список категорий для сайдбара
       $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
   ?>

2. <!DOCTYPE html>
   ... тут HTML с навбаром, сайдбаром категорий и сеткой карточек
   ... в цикле foreach ($products as $p) рендерим карточку
```

## Безопасность (минимум для коляжа)
- Все запросы через **prepared statements** (`$pdo->prepare()` + `execute()`)
- Экранировать вывод: `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` — особенно для `name`, `description`, `feature.text`
- `?category=slug` — сверять с белым списком из таблицы `categories` (если значения нет — игнорировать фильтр)

## Чего НЕ делаем (это коляж, не магазин)
- ✗ Корзина и оформление заказа
- ✗ Авторизация/регистрация
- ✗ Админка для редактирования товаров (правим прямо в БД через phpMyAdmin)
- ✗ Платежи
- ✗ AJAX-фильтры (фильтр через обычный GET-запрос — перезагрузка страницы)
- ✗ Отдельная страница карточки товара (используем модалку Bootstrap)
- ✗ FAQ из БД — захардкожено в `faq.html`

## Минимальный JS (`assets/js/script.js`)
- Подсветка активного пункта в навбаре по текущему URL (берем `location.pathname`, добавляем класс `.active`)
- Bootstrap уже сам делает: тогглер мобильного меню, аккордеон FAQ, модалки
