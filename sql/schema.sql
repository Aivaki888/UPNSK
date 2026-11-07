CREATE DATABASE IF NOT EXISTS giftshop
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE giftshop;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(32) DEFAULT NULL,
  city VARCHAR(120) DEFAULT NULL,
  theme_pref ENUM('light', 'dark', 'system') NOT NULL DEFAULT 'system',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS designs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(120) NOT NULL,
  slug VARCHAR(120) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  category VARCHAR(80) NOT NULL,
  price_base DECIMAL(10, 2) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS certificates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  design_id INT UNSIGNED NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  recipient_name VARCHAR(120) NOT NULL,
  recipient_email VARCHAR(190) DEFAULT NULL,
  occasion VARCHAR(80) NOT NULL,
  message VARCHAR(500) DEFAULT NULL,
  accent_color VARCHAR(32) NOT NULL DEFAULT 'forest',
  delivery_method ENUM('email', 'print') NOT NULL DEFAULT 'email',
  status ENUM('new', 'paid', 'sent', 'used') NOT NULL DEFAULT 'paid',
  certificate_code VARCHAR(24) NOT NULL UNIQUE,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_certificates_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_certificates_design FOREIGN KEY (design_id) REFERENCES designs(id)
) ENGINE=InnoDB;

INSERT INTO designs (title, slug, description, image_path, category, price_base, sort_order) VALUES
('Лесной рассвет', 'forest-dawn', 'Мягкий зелёный фон с золотой рамкой — универсальный дизайн для любого повода.', 'assets/img/designs/forest.svg', 'Классика', 0, 1),
('Ночной бархат', 'velvet-night', 'Глубокий тёмный фон с шампанским золотом — для торжественных подарков.', 'assets/img/designs/velvet.svg', 'Премиум', 200, 2),
('Цветочный сад', 'bloom-garden', 'Живые ботанические мотивы на светлом фоне — идеально для дня рождения.', 'assets/img/designs/bloom.svg', 'Праздник', 100, 3),
('Городские огни', 'city-lights', 'Геометрия и современный ритм — для друзей, коллег и городских впечатлений.', 'assets/img/designs/city.svg', 'Современный', 150, 4),
('Зимняя сказка', 'winter-tale', 'Снежные узоры и тёплая типографика — новогодний и зимний выбор.', 'assets/img/designs/winter.svg', 'Сезон', 100, 5),
('Минимал бумага', 'paper-minimal', 'Чистая композиция и акцентная линия — сдержанный вкус без лишнего декора.', 'assets/img/designs/minimal.svg', 'Минимализм', 0, 6)
ON DUPLICATE KEY UPDATE title = VALUES(title);
