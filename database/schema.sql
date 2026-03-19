-- ═══════════════════════════════════════════════════════════════
-- vCargo — Veritabanı Şeması (Full Migration)
-- Otogar Lojistik Yönetim Sistemi
-- ═══════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ── 1. Bölgeler ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `regions` (
    `region_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) COLLATE utf8mb4_turkish_ci NOT NULL,
    `code` VARCHAR(10) COLLATE utf8mb4_turkish_ci NOT NULL,
    `manager_name` VARCHAR(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`region_id`),
    UNIQUE KEY `uk_region_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 2. Şehirler ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cities` (
    `city_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) COLLATE utf8mb4_turkish_ci NOT NULL,
    `plate_code` VARCHAR(3) COLLATE utf8mb4_turkish_ci NOT NULL,
    `region_id` INT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`city_id`),
    UNIQUE KEY `uk_plate_code` (`plate_code`),
    KEY `idx_region` (`region_id`),
    CONSTRAINT `fk_cities_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 3. Şubeler ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `branches` (
    `branch_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) COLLATE utf8mb4_turkish_ci NOT NULL,
    `code` VARCHAR(20) COLLATE utf8mb4_turkish_ci NOT NULL,
    `type` ENUM('CORPORATE','FRANCHISE') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'FRANCHISE',
    `city_id` INT DEFAULT NULL,
    `region_id` INT DEFAULT NULL,
    `address` TEXT COLLATE utf8mb4_turkish_ci,
    `phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `email` VARCHAR(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `manager_user_id` INT DEFAULT NULL,
    `free_storage_hours` INT DEFAULT 4 COMMENT 'Ücretsiz emanet süresi (saat)',
    `storage_hourly_rate` DECIMAL(10,2) DEFAULT 2.00 COMMENT 'Saatlik emanet ücreti (₺)',
    `baggage_hourly_rate` DECIMAL(10,2) DEFAULT 3.00 COMMENT 'Yolcu bagaj saatlik ücreti (₺)',
    `status` ENUM('active','pending','suspended') COLLATE utf8mb4_turkish_ci DEFAULT 'active',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`branch_id`),
    UNIQUE KEY `uk_branch_code` (`code`),
    KEY `idx_city` (`city_id`),
    KEY `idx_region` (`region_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_branches_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_branches_region` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 4. Otobüs Firmaları ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `bus_companies` (
    `company_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(150) COLLATE utf8mb4_turkish_ci NOT NULL,
    `short_name` VARCHAR(50) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `commission_rate` DECIMAL(5,2) DEFAULT 15.00 COMMENT 'Komisyon oranı %',
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 5. Cari Hesaplar (Müşteriler) ───────────────────────────
CREATE TABLE IF NOT EXISTS `accounts` (
    `account_id` INT NOT NULL AUTO_INCREMENT,
    `type` ENUM('INDIVIDUAL','CORPORATE') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'INDIVIDUAL',
    `name` VARCHAR(200) COLLATE utf8mb4_turkish_ci NOT NULL,
    `tc_no` VARCHAR(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `tax_no` VARCHAR(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `tax_office` VARCHAR(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `email` VARCHAR(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `address` TEXT COLLATE utf8mb4_turkish_ci,
    `credit_limit` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Cari hesap limiti (₺)',
    `balance` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Mevcut bakiye (pozitif=alacak)',
    `branch_id` INT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`account_id`),
    KEY `idx_type` (`type`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_tc` (`tc_no`),
    KEY `idx_tax` (`tax_no`),
    CONSTRAINT `fk_accounts_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 6. Seferler (Trips) ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `trips` (
    `trip_id` INT NOT NULL AUTO_INCREMENT,
    `company_id` INT NOT NULL,
    `plate_no` VARCHAR(20) COLLATE utf8mb4_turkish_ci NOT NULL,
    `driver_name` VARCHAR(100) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `driver_phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `origin_city_id` INT DEFAULT NULL,
    `destination_city_id` INT DEFAULT NULL,
    `departure_time` DATETIME DEFAULT NULL,
    `arrival_time` DATETIME DEFAULT NULL,
    `total_cargo_fee` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Toplam kargo ücreti',
    `commission_amount` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Firma komisyonu',
    `net_payment` DECIMAL(12,2) DEFAULT 0.00 COMMENT 'Otobüse ödenecek net tutar',
    `status` ENUM('planned','departed','arrived','completed','cancelled') COLLATE utf8mb4_turkish_ci DEFAULT 'planned',
    `branch_id` INT NOT NULL,
    `created_by` INT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`trip_id`),
    KEY `idx_company` (`company_id`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_status` (`status`),
    KEY `idx_departure` (`departure_time`),
    CONSTRAINT `fk_trips_company` FOREIGN KEY (`company_id`) REFERENCES `bus_companies` (`company_id`),
    CONSTRAINT `fk_trips_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_trips_origin` FOREIGN KEY (`origin_city_id`) REFERENCES `cities` (`city_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_trips_dest` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`city_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 7. Kargo Kayıtları (Shipments) ──────────────────────────
CREATE TABLE IF NOT EXISTS `shipments` (
    `shipment_id` INT NOT NULL AUTO_INCREMENT,
    `tracking_no` VARCHAR(30) COLLATE utf8mb4_turkish_ci NOT NULL,
    
    -- Gönderici
    `sender_name` VARCHAR(150) COLLATE utf8mb4_turkish_ci NOT NULL,
    `sender_phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci NOT NULL,
    `sender_tc_no` VARCHAR(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `sender_account_id` INT DEFAULT NULL,

    -- Alıcı
    `receiver_name` VARCHAR(150) COLLATE utf8mb4_turkish_ci NOT NULL,
    `receiver_phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci NOT NULL,
    `receiver_tc_no` VARCHAR(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,

    -- Rota
    `origin_branch_id` INT NOT NULL,
    `destination_city_id` INT NOT NULL,
    `destination_branch_id` INT DEFAULT NULL,

    -- Kargo detayları
    `piece_count` INT DEFAULT 1,
    `weight` DECIMAL(8,2) DEFAULT NULL COMMENT 'kg',
    `desi` DECIMAL(8,2) DEFAULT NULL COMMENT 'Desi hacim',
    `content_description` VARCHAR(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    
    -- Ücret ve Ödeme
    `cargo_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `service_fee` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Ek hizmet (kurye vs)',
    `total_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `payment_type` ENUM('SENDER_PAYS','RECEIVER_PAYS','ACCOUNT') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'SENDER_PAYS',
    `payment_method` ENUM('CASH','CARD','ACCOUNT','SPLIT') COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `payment_status` ENUM('paid','pending','partial') COLLATE utf8mb4_turkish_ci DEFAULT 'pending',

    -- Sevk
    `trip_id` INT DEFAULT NULL,

    -- Durum
    `status` ENUM('accepted','dispatched','in_transit','at_branch','in_storage','delivered','cancelled','returned') COLLATE utf8mb4_turkish_ci DEFAULT 'accepted',
    
    -- Emanet
    `storage_start` DATETIME DEFAULT NULL COMMENT 'Emanete alınma zamanı',
    `storage_fee` DECIMAL(10,2) DEFAULT 0.00,
    `delivered_at` DATETIME DEFAULT NULL,

    -- Ek hizmetler
    `sms_notify` TINYINT(1) DEFAULT 0,
    `courier_pickup` TINYINT(1) DEFAULT 0,
    `vip_notify` TINYINT(1) DEFAULT 0,

    -- Meta
    `branch_id` INT NOT NULL COMMENT 'İşlemin yapıldığı şube',
    `created_by` INT DEFAULT NULL,
    `notes` TEXT COLLATE utf8mb4_turkish_ci,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`shipment_id`),
    UNIQUE KEY `uk_tracking` (`tracking_no`),
    KEY `idx_status` (`status`),
    KEY `idx_origin` (`origin_branch_id`),
    KEY `idx_dest_city` (`destination_city_id`),
    KEY `idx_dest_branch` (`destination_branch_id`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_trip` (`trip_id`),
    KEY `idx_sender_account` (`sender_account_id`),
    KEY `idx_payment_status` (`payment_status`),
    KEY `idx_created` (`created_at`),
    CONSTRAINT `fk_shipments_origin` FOREIGN KEY (`origin_branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_shipments_dest_city` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`city_id`),
    CONSTRAINT `fk_shipments_dest_branch` FOREIGN KEY (`destination_branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_shipments_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_shipments_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_shipments_sender_acct` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 8. Emanet Kayıtları (Bağımsız Bagaj) ────────────────────
CREATE TABLE IF NOT EXISTS `storage_records` (
    `storage_id` INT NOT NULL AUTO_INCREMENT,
    `record_no` VARCHAR(30) COLLATE utf8mb4_turkish_ci NOT NULL,
    `type` ENUM('cargo','baggage') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'baggage',
    `shipment_id` INT DEFAULT NULL COMMENT 'Kargo emaneti ise bağlı shipment',
    
    -- Sahibi
    `owner_name` VARCHAR(150) COLLATE utf8mb4_turkish_ci NOT NULL,
    `owner_phone` VARCHAR(20) COLLATE utf8mb4_turkish_ci NOT NULL,
    `owner_tc_no` VARCHAR(11) COLLATE utf8mb4_turkish_ci DEFAULT NULL,

    -- Eşya
    `item_type` ENUM('suitcase','bag','box','other') COLLATE utf8mb4_turkish_ci DEFAULT 'suitcase',
    `item_description` VARCHAR(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `piece_count` INT DEFAULT 1,
    
    -- Konum
    `location` VARCHAR(20) COLLATE utf8mb4_turkish_ci DEFAULT NULL COMMENT 'Raf/Dolap No',
    
    -- Süre ve ücret
    `check_in` DATETIME NOT NULL,
    `check_out` DATETIME DEFAULT NULL,
    `free_hours` INT DEFAULT 2 COMMENT 'Ücretsiz süre (saat)',
    `hourly_rate` DECIMAL(10,2) DEFAULT 3.00 COMMENT 'Saatlik ücret (₺)',
    `total_fee` DECIMAL(10,2) DEFAULT 0.00,
    `fee_discount` DECIMAL(10,2) DEFAULT 0.00,
    `discount_approved_by` INT DEFAULT NULL,
    
    -- Ödeme
    `payment_method` ENUM('CASH','CARD') COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `status` ENUM('stored','delivered','cancelled') COLLATE utf8mb4_turkish_ci DEFAULT 'stored',
    
    -- Meta
    `branch_id` INT NOT NULL,
    `created_by` INT DEFAULT NULL,
    `notes` TEXT COLLATE utf8mb4_turkish_ci,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`storage_id`),
    UNIQUE KEY `uk_record_no` (`record_no`),
    KEY `idx_status` (`status`),
    KEY `idx_type` (`type`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_shipment` (`shipment_id`),
    KEY `idx_checkin` (`check_in`),
    CONSTRAINT `fk_storage_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_storage_shipment` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`shipment_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 9. Finansal İşlemler (Transactions) ─────────────────────
CREATE TABLE IF NOT EXISTS `transactions` (
    `transaction_id` INT NOT NULL AUTO_INCREMENT,
    `type` ENUM('IN','OUT') COLLATE utf8mb4_turkish_ci NOT NULL,
    `category` ENUM('cargo_fee','storage_fee','bus_payment','expense','account_payment','refund','other') COLLATE utf8mb4_turkish_ci NOT NULL,
    `method` ENUM('CASH','CARD','ACCOUNT') COLLATE utf8mb4_turkish_ci NOT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `description` VARCHAR(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    
    -- İlişkili kayıt
    `shipment_id` INT DEFAULT NULL,
    `trip_id` INT DEFAULT NULL,
    `storage_id` INT DEFAULT NULL,
    `account_id` INT DEFAULT NULL,
    
    -- Meta
    `branch_id` INT NOT NULL,
    `created_by` INT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`transaction_id`),
    KEY `idx_type` (`type`),
    KEY `idx_category` (`category`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_created` (`created_at`),
    KEY `idx_shipment` (`shipment_id`),
    KEY `idx_trip` (`trip_id`),
    KEY `idx_storage` (`storage_id`),
    KEY `idx_account` (`account_id`),
    CONSTRAINT `fk_tx_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_tx_shipment` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`shipment_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tx_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tx_storage` FOREIGN KEY (`storage_id`) REFERENCES `storage_records` (`storage_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tx_account` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 10. Kasa Hareketleri (Vault) ─────────────────────────
CREATE TABLE IF NOT EXISTS `vault_transactions` (
    `vault_id` INT NOT NULL AUTO_INCREMENT,
    `transaction_id` INT DEFAULT NULL,
    `type` ENUM('IN','OUT') COLLATE utf8mb4_turkish_ci NOT NULL,
    `method` ENUM('CASH','CARD') COLLATE utf8mb4_turkish_ci NOT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `running_balance` DECIMAL(12,2) DEFAULT 0.00,
    `description` VARCHAR(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `branch_id` INT NOT NULL,
    `created_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`vault_id`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_type` (`type`),
    KEY `idx_created` (`created_at`),
    KEY `idx_tx` (`transaction_id`),
    CONSTRAINT `fk_vault_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`),
    CONSTRAINT `fk_vault_tx` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 11. Denetim Logları ──────────────────────────────────
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `log_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT DEFAULT NULL,
    `action` VARCHAR(100) COLLATE utf8mb4_turkish_ci NOT NULL,
    `entity_type` VARCHAR(50) COLLATE utf8mb4_turkish_ci DEFAULT NULL COMMENT 'shipment, user, branch, etc.',
    `entity_id` INT DEFAULT NULL,
    `old_value` JSON DEFAULT NULL,
    `new_value` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `user_agent` VARCHAR(255) COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `branch_id` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`log_id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_action` (`action`),
    KEY `idx_entity` (`entity_type`, `entity_id`),
    KEY `idx_branch` (`branch_id`),
    KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 12. Fiyatlandırma Tablosu ────────────────────────────
CREATE TABLE IF NOT EXISTS `pricing_rules` (
    `rule_id` INT NOT NULL AUTO_INCREMENT,
    `origin_city_id` INT DEFAULT NULL,
    `destination_city_id` INT DEFAULT NULL,
    `min_weight` DECIMAL(8,2) DEFAULT 0.00,
    `max_weight` DECIMAL(8,2) DEFAULT 999.00,
    `base_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `per_kg_price` DECIMAL(10,2) DEFAULT 0.00,
    `per_desi_price` DECIMAL(10,2) DEFAULT 0.00,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`rule_id`),
    KEY `idx_origin` (`origin_city_id`),
    KEY `idx_dest` (`destination_city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- ── 13. Sistem Ayarları ──────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
    `setting_id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) COLLATE utf8mb4_turkish_ci NOT NULL,
    `value` TEXT COLLATE utf8mb4_turkish_ci DEFAULT NULL,
    `group` VARCHAR(50) COLLATE utf8mb4_turkish_ci DEFAULT 'general',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`setting_id`),
    UNIQUE KEY `uk_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ═══════════════════════════════════════════════════════════════
-- SEED DATA
-- ═══════════════════════════════════════════════════════════════

-- Bölgeler
INSERT INTO `regions` (`name`, `code`) VALUES
('Marmara', 'MAR'),
('İç Anadolu', 'ICA'),
('Ege', 'EGE'),
('Akdeniz', 'AKD'),
('Karadeniz', 'KAR'),
('Doğu Anadolu', 'DOG'),
('Güneydoğu Anadolu', 'GAB');

-- Şehirler (büyük illerden başlayarak)
INSERT INTO `cities` (`name`, `plate_code`, `region_id`) VALUES
('İstanbul', '34', 1),
('Ankara', '06', 2),
('İzmir', '35', 3),
('Bursa', '16', 1),
('Antalya', '07', 4),
('Konya', '42', 2),
('Adana', '01', 4),
('Gaziantep', '27', 7),
('Trabzon', '61', 5),
('Kayseri', '38', 2),
('Eskişehir', '26', 2),
('Mersin', '33', 4),
('Diyarbakır', '21', 7),
('Samsun', '55', 5),
('Denizli', '20', 3);

-- Şubeler
INSERT INTO `branches` (`name`, `code`, `type`, `city_id`, `region_id`, `phone`, `status`, `free_storage_hours`, `storage_hourly_rate`) VALUES
('İstanbul Otogar', 'IST-001', 'CORPORATE', 1, 1, '0212 555 0101', 'active', 4, 2.00),
('Ankara AŞTİ', 'ANK-001', 'CORPORATE', 2, 2, '0312 555 0202', 'active', 4, 2.00),
('İzmir Otogar', 'IZM-001', 'FRANCHISE', 3, 3, '0232 555 0303', 'active', 4, 2.50),
('Bursa Otogar', 'BRS-001', 'FRANCHISE', 4, 1, '0224 555 0404', 'active', 4, 2.00),
('Antalya Otogar', 'ANT-001', 'FRANCHISE', 5, 4, '0242 555 0505', 'active', 3, 2.50),
('Konya Otogar', 'KNY-001', 'FRANCHISE', 6, 2, '0332 555 0606', 'active', 4, 2.00),
('Adana Otogar', 'ADA-001', 'FRANCHISE', 7, 4, '0322 555 0707', 'active', 4, 2.00),
('Gaziantep Otogar', 'GAZ-001', 'FRANCHISE', 8, 7, '0342 555 0808', 'pending', 4, 2.00),
('Trabzon Otogar', 'TRB-001', 'FRANCHISE', 9, 5, '0462 555 0909', 'active', 3, 3.00),
('Kayseri Otogar', 'KYS-001', 'FRANCHISE', 10, 2, '0352 555 1010', 'active', 4, 2.00);

-- Otobüs Firmaları
INSERT INTO `bus_companies` (`name`, `short_name`, `phone`, `commission_rate`) VALUES
('Metro Turizm', 'Metro', '0850 222 3456', 15.00),
('Pamukkale Turizm', 'Pamukkale', '0850 333 4567', 12.00),
('Kamil Koç', 'Kamil Koç', '0850 444 5678', 14.00),
('Uludağ Turizm', 'Uludağ', '0850 555 6789', 13.00),
('Süha Turizm', 'Süha', '0850 666 7890', 10.00),
('Nilüfer Turizm', 'Nilüfer', '0850 777 8901', 11.00);

-- Sistem Ayarları
INSERT INTO `settings` (`key`, `value`, `group`) VALUES
('company_name', 'vCargo Lojistik', 'general'),
('company_phone', '0212 555 00 00', 'general'),
('tax_no', '1234567890', 'general'),
('currency', 'TRY', 'general'),
('timezone', 'Europe/Istanbul', 'general'),
('barcode_format', 'CODE128', 'print'),
('label_size', '100x70', 'print'),
('sms_provider', 'netgsm', 'notification'),
('default_free_storage_hours', '4', 'storage'),
('default_storage_rate', '2.00', 'storage'),
('default_baggage_rate', '3.00', 'storage'),
('tracking_prefix', 'TRK', 'shipment'),
('session_timeout_minutes', '120', 'security');

-- Admin kullanıcısının branch_id'sini güncelle
UPDATE `users` SET `branch_id` = 1 WHERE `username` = 'admin';
