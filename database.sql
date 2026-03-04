-- Esegui questo SQL in phpMyAdmin (MySQL 8)

CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE settings (
  `key` VARCHAR(50) PRIMARY KEY,
  `value` VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO settings (`key`,`value`) VALUES
('mode','auto'),
('default_capacity','50');

CREATE TABLE capacity_by_date (
  `date` DATE PRIMARY KEY,
  capacity INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(120) NOT NULL,
  booking_date DATE NOT NULL,
  booking_time TIME NOT NULL DEFAULT '19:00:00',
  people INT NOT NULL,
  status ENUM('pending','confirmed','rejected','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (email),
  INDEX (booking_date),
  INDEX (status)
) ENGINE=InnoDB;

-- Migration: add booking_time if table already exists
-- ALTER TABLE bookings ADD COLUMN booking_time TIME NOT NULL DEFAULT '19:00:00' AFTER booking_date;

CREATE TABLE IF NOT EXISTS time_slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slot_time VARCHAR(5) NOT NULL UNIQUE,
  capacity INT NOT NULL DEFAULT 30,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO time_slots (slot_time, capacity, sort_order) VALUES
('19:00', 30, 10), ('19:30', 30, 20), ('20:00', 30, 30), ('20:30', 30, 40),
('21:00', 30, 50), ('21:30', 30, 60), ('22:00', 30, 70), ('22:30', 30, 80);

CREATE TABLE IF NOT EXISTS closure_days (
  date DATE PRIMARY KEY,
  reason VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE events (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(30) NOT NULL,
  payload JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin di default (cambia password!)
-- username: admin
-- password: Admin123!
INSERT INTO admin_users (username, password_hash)
VALUES ('admin', '$2y$10$kFjJ0g2oYlYw4Tg1v3zW6eJm1nqQwz0UOOrw2S3cV8p8Q8lJ9bF2y');
