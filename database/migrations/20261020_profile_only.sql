-- Migrasi tambahan khusus fitur profil pengguna. Tidak menghapus data/tabel.
-- Pilih database booking_wisata_baru di phpMyAdmin sebelum menjalankan jika perlu.
USE booking_wisata_baru;

DELIMITER //
CREATE PROCEDURE add_profile_column_if_missing(IN p_table VARCHAR(64), IN p_column VARCHAR(64), IN p_definition TEXT)
BEGIN
  IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=p_table AND COLUMN_NAME=p_column) THEN
    SET @ddl = CONCAT('ALTER TABLE `', p_table, '` ADD COLUMN `', p_column, '` ', p_definition);
    PREPARE stmt FROM @ddl; EXECUTE stmt; DEALLOCATE PREPARE stmt;
  END IF;
END//
DELIMITER ;

CALL add_profile_column_if_missing('users','no_hp','VARCHAR(30) NULL AFTER `username`');
CALL add_profile_column_if_missing('users','foto_profil','VARCHAR(255) NULL AFTER `no_hp`');
DROP PROCEDURE add_profile_column_if_missing;
