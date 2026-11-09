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

CREATE TABLE IF NOT EXISTS favorites (
  user_id INT UNSIGNED NOT NULL,
  design_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, design_id),
  CONSTRAINT fk_favorites_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_favorites_design FOREIGN KEY (design_id) REFERENCES designs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  subject VARCHAR(160) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contact_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO designs (title, slug, description, image_path, category, price_base, sort_order) VALUES
('Лесной рассвет', 'forest-dawn', 'Мягкий зелёный фон с золотой рамкой — универсальный дизайн для любого повода.', 'assets/img/designs/01.jpg', 'Классика', 0, 1),
('Ночной бархат', 'velvet-night', 'Глубокий тёмный фон с шампанским золотом — для торжественных подарков.', 'assets/img/designs/02.jpg', 'Премиум', 200, 2),
('Цветочный сад', 'bloom-garden', 'Живые ботанические мотивы на светлом фоне — идеально для дня рождения.', 'assets/img/designs/03.jpg', 'Праздник', 100, 3),
('Городские огни', 'city-lights', 'Геометрия и современный ритм — для друзей, коллег и городских впечатлений.', 'assets/img/designs/04.jpg', 'Современный', 150, 4),
('Зимняя сказка', 'winter-tale', 'Снежные узоры и тёплая типографика — новогодний и зимний выбор.', 'assets/img/designs/05.jpg', 'Сезон', 100, 5),
('Минимал бумага', 'paper-minimal', 'Чистая композиция и акцентная линия — сдержанный вкус без лишнего декора.', 'assets/img/designs/06.jpg', 'Минимализм', 0, 6)
ON DUPLICATE KEY UPDATE title = VALUES(title), image_path = VALUES(image_path);
