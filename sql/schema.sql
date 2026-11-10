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
  accent_color VARCHAR(32) NOT NULL DEFAULT 'ps_blue',
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
('PS Pulse Blue', 'ps-pulse-blue', 'Синий pulse-дизайн в духе PlayStation — для баланса PSN и подписок.', 'assets/img/designs/01.jpg', 'PlayStation', 0, 1),
('PS Midnight', 'ps-midnight', 'Тёмный карточный стиль DualSense — строгий подарок для консоли PS5.', 'assets/img/designs/02.jpg', 'PlayStation', 150, 2),
('Xbox Green Core', 'xbox-green-core', 'Зелёный акцент Xbox — сертификат на игры, Game Pass и дополнения.', 'assets/img/designs/03.jpg', 'Xbox', 0, 3),
('Xbox Carbon', 'xbox-carbon', 'Матовый карбон с зелёной линией — премиальный вид для Series X|S.', 'assets/img/designs/04.jpg', 'Xbox', 150, 4),
('PC RGB Arena', 'pc-rgb-arena', 'RGB-подсветка и HUD-сетка — для Steam, Epic и любых PC-ключей.', 'assets/img/designs/05.jpg', 'PC', 100, 5),
('PC Clean Deck', 'pc-clean-deck', 'Минималистичный PC-дизайн без лишнего шума — чистый цифровой ваучер.', 'assets/img/designs/06.jpg', 'PC', 0, 6)
ON DUPLICATE KEY UPDATE
  title = VALUES(title),
  description = VALUES(description),
  image_path = VALUES(image_path),
  category = VALUES(category),
  price_base = VALUES(price_base),
  sort_order = VALUES(sort_order);
