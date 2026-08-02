-- Cambodia Administrative Divisions
-- Source: MEF Open Data Portal (data.mef.gov.kh) / NCDD Gazetteer

-- Provinces
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(2) NOT NULL UNIQUE,
  `name_en` VARCHAR(100) NOT NULL,
  `name_km` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `provinces` (`code`, `name_en`, `name_km`) VALUES
('01', 'Banteay Meanchey', 'បន្ទាយមានជ័យ'),
('02', 'Battambang', 'បាត់ដំបង'),
('03', 'Kampong Cham', 'កំពង់ចាម'),
('04', 'Kampong Chhnang', 'កំពង់ឆ្នាំង'),
('05', 'Kampong Speu', 'កំពង់ស្ពឺ'),
('06', 'Kampong Thom', 'កំពង់ធំ'),
('07', 'Kampot', 'កំពត'),
('08', 'Kandal', 'កណ្ដាល'),
('09', 'Koh Kong', 'កោះកុង'),
('10', 'Kratie', 'ក្រចេះ'),
('11', 'Mondulkiri', 'មណ្ឌលគិរី'),
('12', 'Phnom Penh', 'ភ្នំពេញ'),
('13', 'Preah Vihear', 'ព្រះវិហារ'),
('14', 'Prey Veng', 'ព្រៃវែង'),
('15', 'Pursat', 'ពោធិ៍សាត់'),
('16', 'Ratanakiri', 'រតនគិរី'),
('17', 'Siem Reap', 'សៀមរាប'),
('18', 'Preah Sihanouk', 'ព្រះសីហនុ'),
('19', 'Stung Treng', 'ស្ទឹងត្រែង'),
('20', 'Svay Rieng', 'ស្វាយរៀង'),
('21', 'Takeo', 'តាកែវ'),
('22', 'Oddar Meanchey', 'ឧត្តរមានជ័យ'),
('23', 'Kep', 'កែប'),
('24', 'Pailin', 'ប៉ៃលិន'),
('25', 'Tboung Khmum', 'ត្បូងឃ្មុំ');

-- Districts
CREATE TABLE IF NOT EXISTS `districts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(4) NOT NULL UNIQUE,
  `province_code` VARCHAR(2) NOT NULL,
  `name_en` VARCHAR(100) NOT NULL,
  `name_km` VARCHAR(100) NOT NULL,
  `type` VARCHAR(10) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`province_code`) REFERENCES `provinces`(`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Communes
CREATE TABLE IF NOT EXISTS `communes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(6) NOT NULL UNIQUE,
  `province_code` VARCHAR(2) NOT NULL,
  `district_code` VARCHAR(4) NOT NULL,
  `name_en` VARCHAR(100) NOT NULL,
  `name_km` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`district_code`) REFERENCES `districts`(`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Villages
CREATE TABLE IF NOT EXISTS `villages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(8) NOT NULL UNIQUE,
  `province_code` VARCHAR(2) NOT NULL,
  `district_code` VARCHAR(4) NOT NULL,
  `commune_code` VARCHAR(6) NOT NULL,
  `name_en` VARCHAR(100) NOT NULL,
  `name_km` VARCHAR(100) NOT NULL,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`commune_code`) REFERENCES `communes`(`code`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User Addresses (Billing / Shipping)
CREATE TABLE IF NOT EXISTS `user_addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `label` VARCHAR(50) DEFAULT 'Billing',
  `full_name` VARCHAR(100) DEFAULT NULL,
  `company` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `tax_id` VARCHAR(50) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `province_code` VARCHAR(2) DEFAULT NULL,
  `district_code` VARCHAR(4) DEFAULT NULL,
  `commune_code` VARCHAR(6) DEFAULT NULL,
  `village_code` VARCHAR(8) DEFAULT NULL,
  `street` VARCHAR(255) DEFAULT NULL,
  `zip_code` VARCHAR(10) DEFAULT NULL,
  `latitude` DECIMAL(10,7) DEFAULT NULL,
  `longitude` DECIMAL(10,7) DEFAULT NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
