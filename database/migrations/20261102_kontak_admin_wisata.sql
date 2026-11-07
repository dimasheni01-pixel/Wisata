-- Jalankan sekali pada database booking_wisata_baru yang sudah berisi data.
USE booking_wisata_baru;
ALTER TABLE wisata
  ADD COLUMN kontak_nama VARCHAR(120) DEFAULT NULL,
  ADD COLUMN kontak_wa VARCHAR(30) DEFAULT NULL,
  ADD COLUMN kontak_jam VARCHAR(100) DEFAULT NULL;
