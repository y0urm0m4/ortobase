# Структура проекта

```
Orto-base/
│
├── план/                    ← документация (вы здесь)
│   ├── README.md
│   ├── CHECKLIST.md
│   ├── SITEMAP.md
│   ├── PAGES.md
│   ├── DESIGN.md
│   ├── DATABASE.md
│   ├── STRUCTURE.md
│   ├── PRODUCTS.md
│   └── BACKEND.md
│
├── public/                  ← корень сайта (DocumentRoot)
│   ├── index.html           ← Главная (статика)
│   ├── about.html           ← О нас (статика)
│   ├── why.html             ← Почему мы (статика)
│   ├── faq.html             ← FAQ (статика)
│   ├── catalog.php          ← Каталог — единственный PHP, тянет товары из БД
│   │
│   ├── includes/
│   │   └── db.php           ← подключение к БД (PDO) — нужен только для catalog.php
│   │
│   └── assets/
│       ├── css/
│       │   └── style.css    ← кастомные стили + переменные цвета
│       ├── js/
│       │   └── script.js    ← подсветка активного пункта меню, мелкие интерактивы
│       └── img/
│           ├── logo.svg
│           ├── hero.jpg
│           ├── insoles1.jpg
│           ├── shoes1.jpg
│           └── ...
│
├── sql/
│   └── ortobase.sql         ← дамп БД для импорта в phpMyAdmin
│
└── README.md                ← инструкция как запустить (для преподавателя)
```

## Про общий навбар и подвал
В HTML-страницах нет `include`, поэтому навбар и футер просто **копируем одинаковыми** в каждый из 5 файлов. Изменил в одном — продублируй в остальные. Для 5 страниц это терпимо.

> Альтернатива (если хочется не дублировать): подгружать `navbar.html` через `fetch()` в JS. Но для коляжа это лишнее.

## Где живет проект на машине
Если используется XAMPP:
- Скопировать содержимое `public/` в `C:\xampp\htdocs\ortobase\`
- Открыть `http://localhost/ortobase/`

> Важно: HTML-страницы можно открыть и двойным кликом (`file://...`), но `catalog.php` будет работать **только через Apache**. Поэтому всегда заходи через `http://localhost/...`.

Если OpenServer — папка `domains\ortobase.local\` и адрес `http://ortobase.local/`.

## Как запускать (для финального README)
1. Запустить XAMPP, поднять Apache и MySQL.
2. Открыть phpMyAdmin → импортировать `sql/ortobase.sql`.
3. Скопировать `public/*` в `htdocs/ortobase/`.
4. Открыть в браузере `http://localhost/ortobase/`.
