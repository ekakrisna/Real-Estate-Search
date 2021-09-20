CREATE TABLE `lp_scrapings` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `location` VARCHAR(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_no` BIGINT(20) NOT NULL,
  `minimum_price` INT(11) NOT NULL,
  `maximum_price` INT(11) NOT NULL,
  `minimum_land_area` DECIMAL(12,6) NOT NULL,
  `maximum_land_area` DECIMAL(12,6) NOT NULL,
  `building_area` DECIMAL(12,6) NOT NULL,
  `building_age` INT(11) NOT NULL,
  `house_layout` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connecting_road` VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contracted_years` DATETIME NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `scraping_publish_copy1` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `publication_destination` VARCHAR(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lp_scrapings_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scraping_publish_copy1_lp_scrapings_id_foreign` (`lp_scrapings_id`),
  CONSTRAINT `scraping_publish_copy1_lp_scrapings_id_foreign` FOREIGN KEY (`lp_scrapings_id`) REFERENCES `lp_scrapings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_scraping_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` INT(11) NOT NULL,
  `is_adapt` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `lp_scrapings_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lp_scraping_logs_lp_scrapings_id_foreign` (`lp_scrapings_id`),
  CONSTRAINT `lp_scraping_logs_lp_scrapings_id_foreign` FOREIGN KEY (`lp_scrapings_id`) REFERENCES `lp_scrapings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_scraping_file_upload_histories` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_property_statuses` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_property_convert_status` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_property_scraping_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_properties` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lp_scrapings_id` BIGINT(20) UNSIGNED NOT NULL,
  `lp_property_scraping_type_id` INT(10) UNSIGNED DEFAULT NULL,
  `lp_property_status_id` BIGINT(20) UNSIGNED DEFAULT NULL,
  `lp_property_convert_status_id` INT(10) UNSIGNED DEFAULT NULL,
  `property_no` BIGINT(20) UNSIGNED NOT NULL,
  `scraping_id` BIGINT(20) UNSIGNED NOT NULL,
  `scraping_type_id` INT(11) NOT NULL,
  `location` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_price` INT(11) NOT NULL,
  `maximum_price` INT(11) NOT NULL,
  `minimum_land_area` DECIMAL(12,6) NOT NULL,
  `maximum_land_area` DECIMAL(12,6) NOT NULL,
  `building_area` DECIMAL(12,6) NOT NULL,
  `building_age` INT(11) NOT NULL,
  `house_layout` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connecting_road` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contracted_years` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` DATETIME NOT NULL,
  `traffic` VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lp_properties_lp_scrapings_id_foreign` (`lp_scrapings_id`),
  KEY `lp_properties_lp_property_scraping_type_id_foreign` (`lp_property_scraping_type_id`),
  KEY `lp_properties_lp_property_status_id_foreign` (`lp_property_status_id`),
  KEY `lp_properties_lp_property_convert_status_id_foreign` (`lp_property_convert_status_id`),
  CONSTRAINT `lp_properties_lp_property_convert_status_id_foreign` FOREIGN KEY (`lp_property_convert_status_id`) REFERENCES `lp_property_convert_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `lp_properties_lp_property_scraping_type_id_foreign` FOREIGN KEY (`lp_property_scraping_type_id`) REFERENCES `lp_property_scraping_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `lp_properties_lp_property_status_id_foreign` FOREIGN KEY (`lp_property_status_id`) REFERENCES `lp_property_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `lp_properties_lp_scrapings_id_foreign` FOREIGN KEY (`lp_scrapings_id`) REFERENCES `lp_scrapings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lp_property_log_activities` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lp_properties_id` BIGINT(20) UNSIGNED NOT NULL,
  `before_update_text` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `after_update_text` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` DATETIME NOT NULL,
  `lp_property_scraping_types_id` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lp_property_log_activities_lp_properties_id_foreign` (`lp_properties_id`),
  KEY `lp_property_log_activities_lp_property_scraping_types_id_foreign` (`lp_property_scraping_types_id`),
  CONSTRAINT `lp_property_log_activities_lp_properties_id_foreign` FOREIGN KEY (`lp_properties_id`) REFERENCES `lp_properties` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `lp_property_log_activities_lp_property_scraping_types_id_foreign` FOREIGN KEY (`lp_property_scraping_types_id`) REFERENCES `lp_property_scraping_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

