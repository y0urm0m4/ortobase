-- ==========================================================
--  Ortobase — дамп БД
--  Импортировать через phpMyAdmin или: mysql -u root < ortobase.sql
-- ==========================================================

CREATE DATABASE IF NOT EXISTS ortobase
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ortobase;

-- ----------------------------------------------------------
CREATE TABLE categories (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(50)  UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL
);

-- ----------------------------------------------------------
CREATE TABLE products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name        VARCHAR(150) NOT NULL,
  price       DECIMAL(10,2) NOT NULL,
  image       VARCHAR(255),
  description TEXT,
  popularity  INT DEFAULT 0,
  created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- ----------------------------------------------------------
CREATE TABLE product_sizes (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  size       VARCHAR(20) NOT NULL,
  in_stock   TINYINT(1) DEFAULT 1,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------
CREATE TABLE features (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  icon       VARCHAR(50),
  text       VARCHAR(100) NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


-- ==========================================================
--  ДАННЫЕ
-- ==========================================================

INSERT INTO categories (slug, name) VALUES
  ('insoles', 'Стельки'),
  ('shoes',   'Обувь'),
  ('corsets', 'Корсеты и бандажи');


-- Стельки -------------------------------------------------------
INSERT INTO products (category_id, name, price, image, description, popularity) VALUES
  (1, 'Стельки Comfort+',    1490.00,
   'https://picsum.photos/seed/insoles1/600/400',
   'Анатомические стельки с жёстким супинатором для ежедневной носки. Снижают усталость ног при длительной ходьбе и нагрузке.',
   95),

  (1, 'Стельки ActiveRun',   1990.00,
   'https://picsum.photos/seed/insoles2/600/400',
   'Спортивные стельки с повышенной амортизацией. Разработаны совместно с ортопедами для бега и активных тренировок.',
   80),

  (1, 'Стельки KidsStep',     990.00,
   'https://picsum.photos/seed/insoles3/600/400',
   'Детские ортопедические стельки для формирования правильного свода стопы. Мягкий верхний слой из натуральной кожи.',
   70);

-- Обувь ---------------------------------------------------------
INSERT INTO products (category_id, name, price, image, description, popularity) VALUES
  (2, 'Кроссовки OrthoSoft Casual', 7490.00,
   'https://picsum.photos/seed/shoes1/600/400',
   'Повседневные кроссовки на анатомической колодке с жёстким задником. Подходят для ежедневной носки при плоскостопии.',
   88),

  (2, 'Сандалии HealthStep Summer',  4990.00,
   'https://picsum.photos/seed/shoes2/600/400',
   'Летние сандалии с регулируемыми ремнями и латексной подошвой. Обеспечивают правильное распределение нагрузки.',
   75),

  (2, 'Ботинки WarmCare Winter',     9990.00,
   'https://picsum.photos/seed/shoes3/600/400',
   'Зимние ортопедические ботинки с натуральным мехом и нескользящей подошвой. Жёсткий супинатор встроен в конструкцию.',
   82);

-- Корсеты и бандажи --------------------------------------------
INSERT INTO products (category_id, name, price, image, description, popularity) VALUES
  (3, 'Корсет BackFix Pro',        3490.00,
   'https://picsum.photos/seed/corset1/600/400',
   'Грудопоясничный корсет с 4 рёбрами жёсткости. Снимает нагрузку с позвоночника при сидячей работе и физических нагрузках.',
   85),

  (3, 'Бандаж KneePro Active',     1290.00,
   'https://picsum.photos/seed/corset2/600/400',
   'Наколенник с силиконовой стабилизирующей вставкой. Универсальный — подходит на оба колена, не сползает при движении.',
   78),

  (3, 'Корректор PostureLine',     1890.00,
   'https://picsum.photos/seed/corset3/600/400',
   'Тонкий корректор осанки, невидимый под одеждой. Плавно формирует правильное положение плеч и спины.',
   72);


-- Размеры -------------------------------------------------------
-- Стельки (числовые)
INSERT INTO product_sizes (product_id, size, in_stock) VALUES
  (1,'36',1),(1,'37',1),(1,'38',1),(1,'39',0),(1,'40',1),(1,'41',1),(1,'42',1),
  (2,'38',1),(2,'39',1),(2,'40',1),(2,'41',0),(2,'42',1),(2,'43',1),(2,'44',1),(2,'45',1),
  (3,'28',1),(3,'29',1),(3,'30',0),(3,'31',1),(3,'32',1),(3,'33',1),(3,'34',1),(3,'35',1),(3,'36',1);

-- Обувь (числовые)
INSERT INTO product_sizes (product_id, size, in_stock) VALUES
  (4,'36',1),(4,'37',1),(4,'38',1),(4,'39',1),(4,'40',0),(4,'41',1),(4,'42',1),(4,'43',1),(4,'44',1),
  (5,'36',1),(5,'37',1),(5,'38',1),(5,'39',0),(5,'40',1),(5,'41',1),(5,'42',1),(5,'43',1),
  (6,'37',1),(6,'38',1),(6,'39',0),(6,'40',1),(6,'41',1),(6,'42',1),(6,'43',1),(6,'44',1),(6,'45',1);

-- Корсеты (буквенные)
INSERT INTO product_sizes (product_id, size, in_stock) VALUES
  (7,'S',1),(7,'M',1),(7,'L',0),(7,'XL',1),
  (8,'S',1),(8,'M',1),(8,'L',1),
  (9,'S',1),(9,'M',1),(9,'L',0);


-- Свойства (визуальный хук) ------------------------------------
INSERT INTO features (product_id, icon, text) VALUES
  -- Comfort+
  (1,'bi-shield-check','Поддержка свода стопы'),
  (1,'bi-droplet','Антибактериальное покрытие'),
  (1,'bi-feather','Лёгкий вспененный материал'),
  -- ActiveRun
  (2,'bi-lightning-charge','Амортизация при каждом шаге'),
  (2,'bi-wind','Дышащий верхний слой'),
  (2,'bi-arrow-up-circle','Эффект отдачи при отрыве'),
  -- KidsStep
  (3,'bi-person-standing','Формирует правильный свод'),
  (3,'bi-flower1','Гипоаллергенный материал'),
  (3,'bi-patch-heart','Верх из натуральной кожи'),
  -- OrthoSoft Casual
  (4,'bi-shield','Жёсткий формозащитный задник'),
  (4,'bi-grid-1x2','Анатомическая колодка'),
  (4,'bi-wind','Дышащий сетчатый верх'),
  -- HealthStep Summer
  (5,'bi-sliders','Регулируемые ремни фиксации'),
  (5,'bi-circle','Латексная амортизирующая подошва'),
  (5,'bi-feather2','Сверхлёгкая конструкция'),
  -- WarmCare Winter
  (6,'bi-thermometer-snow','Натуральный мех внутри'),
  (6,'bi-grip-horizontal','Нескользящая подошва'),
  (6,'bi-shield-fill-check','Встроенный жёсткий супинатор'),
  -- BackFix Pro
  (7,'bi-bar-chart-line','4 ребра жёсткости'),
  (7,'bi-arrows-angle-expand','Регулировка по объёму'),
  (7,'bi-wind','Дышащий сетчатый материал'),
  -- KneePro Active
  (8,'bi-circle-fill','Силиконовая стабилизирующая вставка'),
  (8,'bi-pin-angle','Не сползает при движении'),
  (8,'bi-universal-access','Подходит на оба колена'),
  -- PostureLine
  (9,'bi-eye-slash','Невидим под одеждой'),
  (9,'bi-magnet','Магнитные корректирующие вставки'),
  (9,'bi-patch-check','Гипоаллергенная ткань');
