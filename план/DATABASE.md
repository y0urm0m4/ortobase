# База данных

БД: `ortobase`. Кодировка `utf8mb4_unicode_ci`.

> В варианте А БД использует только `catalog.php`. FAQ захардкожен в `faq.html` — таблица под него не нужна.

## Схема (4 таблицы)

### `categories` — категории товаров
| поле   | тип          | примечание         |
| ------ | ------------ | ------------------ |
| id     | INT PK AI    |                    |
| slug   | VARCHAR(50)  | для `?category=`   |
| name   | VARCHAR(100) | «Стельки», «Обувь» |

### `products` — товары
| поле          | тип           | примечание                            |
| ------------- | ------------- | ------------------------------------- |
| id            | INT PK AI     |                                       |
| category_id   | INT FK        | → `categories.id`                     |
| name          | VARCHAR(150)  | название модели                       |
| price         | DECIMAL(10,2) | цена в рублях                         |
| image         | VARCHAR(255)  | путь к картинке `/assets/img/...`     |
| description   | TEXT          | описание 2–4 предложения              |
| popularity    | INT           | для сортировки на главной (0–100)     |
| created_at    | DATETIME      | `DEFAULT CURRENT_TIMESTAMP`           |

### `product_sizes` — доступные размеры товара
| поле        | тип         | примечание           |
| ----------- | ----------- | -------------------- |
| id          | INT PK AI   |                      |
| product_id  | INT FK      | → `products.id`      |
| size        | VARCHAR(20) | «36», «M», «42-44»   |
| in_stock    | TINYINT(1)  | 1 = в наличии        |

### `features` — крутые свойства товара (визуальный хук)
| поле        | тип          | примечание                       |
| ----------- | ------------ | -------------------------------- |
| id          | INT PK AI    |                                  |
| product_id  | INT FK       | → `products.id`                  |
| icon        | VARCHAR(50)  | имя Bootstrap Icon (`bi-...`)    |
| text        | VARCHAR(100) | 1 предложение                    |

> На карточке показываем 2–3 свойства из этой таблицы.

---

## SQL для создания (черновик)

```sql
CREATE DATABASE IF NOT EXISTS ortobase
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ortobase;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  description TEXT,
  popularity INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE product_sizes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  size VARCHAR(20) NOT NULL,
  in_stock TINYINT(1) DEFAULT 1,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE features (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  icon VARCHAR(50),
  text VARCHAR(100) NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## Тестовые данные (краткий пример)

```sql
INSERT INTO categories (slug, name) VALUES
  ('insoles', 'Стельки'),
  ('shoes',   'Обувь'),
  ('corsets', 'Корсеты');

INSERT INTO products (category_id, name, price, image, description, popularity) VALUES
  (1, 'Ортопедические стельки Comfort+', 1490.00, '/assets/img/insoles1.jpg',
   'Анатомические стельки с супинатором. Подходят для ежедневной носки.', 90);

INSERT INTO product_sizes (product_id, size, in_stock) VALUES
  (1, '36', 1), (1, '37', 1), (1, '38', 1), (1, '39', 0), (1, '40', 1);

INSERT INTO features (product_id, icon, text) VALUES
  (1, 'bi-shield-check', 'Поддержка свода стопы'),
  (1, 'bi-droplet',      'Антибактериальное покрытие'),
  (1, 'bi-feather',      'Легкий вспененный материал');
```
