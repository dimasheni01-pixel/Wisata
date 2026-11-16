# wisataKuningan — Sistem Booking Wisata

Project PHP Native + MySQL + HTML + CSS + JavaScript untuk dijalankan di XAMPP. Tidak menggunakan Bootstrap dan tidak menggunakan payment gateway.

## Fitur yang tersedia

### Pengunjung
- Registrasi, login, logout, dan session.
- Beranda, daftar wisata, pencarian destinasi, serta detail wisata.
- Detail lokasi, harga, jam buka, deskripsi, fasilitas, dan foto.
- Booking tanggal kunjungan dan jumlah tiket dengan perhitungan total.
- Profil pengguna: nama, email, username, nomor HP, foto profil, dan ganti password.
- Riwayat booking dan status pembayaran.
- Pembayaran transfer manual dengan rekening yang dapat berbeda untuk setiap wisata.
- Upload bukti pembayaran.

### Admin
- Dashboard ringkasan wisata, pengguna, booking, dan pembayaran menunggu verifikasi.
- CRUD destinasi wisata dan upload foto.
- **Di form Tambah/Edit Wisata tersedia nama pengelola, nomor WhatsApp, dan jam layanan.**
- Kontak tiap destinasi disimpan sendiri-sendiri dan tampil di halaman detail destinasi yang sesuai.
- Tombol WhatsApp untuk menghubungi pengelola destinasi.
- Pengaturan rekening bank per destinasi.
- Melihat booking dan bukti pembayaran, lalu menyetujui/menolak dengan catatan.
- Melihat daftar pengguna.

## Instalasi baru di XAMPP

1. Ekstrak ZIP. Pastikan folder hasil ekstrak bernama `wisataKuningan`.
2. Pindahkan folder tersebut ke `C:\xampp\htdocs\wisataKuningan`.
3. Jalankan Apache dan MySQL di XAMPP.
4. Buka `http://localhost/phpmyadmin`.
5. Pilih menu **Import**, lalu impor file `database.sql` dari folder `wisataKuningan`.
   - File ini membuat database `booking_wisata_baru` dan tabel-tabel awal.
   - **Jangan impor ulang ke database yang sudah berisi data**, karena skrip instalasi awal membuat ulang tabel.
6. Buka `http://localhost/wisataKuningan/`.
7. Login admin awal:
   - Username: `admin`
   - Password: `admin123`
8. Setelah berhasil, segera ganti password admin dari menu profil bila tersedia; jangan gunakan password contoh untuk website publik.

## Memperbarui database lama tanpa menghapus data

Jika database `booking_wisata_baru` sudah berisi data, **jangan impor `database.sql`**.

1. Backup database lewat phpMyAdmin → Export.
2. Pilih database `booking_wisata_baru`.
3. Buka tab **SQL**.
4. Buka dan salin seluruh isi file `database/migrations/20261104_upgrade_existing_database.sql`, lalu jalankan.
5. Migrasi ini hanya menambahkan kolom profil dan kontak yang belum ada. Tidak menghapus tabel atau data. File ini aman dijalankan ulang.

## Mengatur kontak admin untuk setiap wisata

1. Login sebagai admin.
2. Buka **Panel Admin → Kelola Wisata**.
3. Klik **Edit** pada destinasi atau isi form **Tambah Wisata**.
4. Isi bagian **Kontak admin/pengelola wisata**:
   - Nama admin/pengelola.
   - Nomor WhatsApp, bisa memakai format lokal seperti `081234567890` atau kode negara seperti `6281234567890`.
   - Jam layanan, contoh `08.00–17.00 WIB`.
5. Klik **Simpan wisata**.
6. Buka detail destinasi tersebut. Kontaknya akan tampil di destinasi itu saja, dengan tombol **Hubungi Admin via WhatsApp**.

Setiap destinasi mempunyai kontak terpisah. Wisata lama juga dapat diisi melalui menu Edit Wisata.

## Rekening dan pembayaran

- Atur rekening melalui **Admin → Rekening** untuk masing-masing destinasi.
- Pembayaran dilakukan dengan transfer manual.
- Pengunjung mengunggah bukti pembayaran, admin memeriksa lalu mengubah status.
- Ini **bukan** pembayaran otomatis, QRIS dinamis, atau integrasi payment gateway.

## Lokasi foto upload

- Foto wisata: `uploads/wisata/`
- Foto profil: `uploads/profil/`
- Bukti pembayaran: `uploads/pembayaran/`

Foto wisata juga dapat dipilih langsung dari form Tambah/Edit Wisata. Jangan memindahkan atau mengganti nama folder upload.

## Konfigurasi database

Konfigurasi default ada di `config/database.php`:
- Host: `localhost`
- Database: `booking_wisata_baru`
- Username: `root`
- Password: kosong

Sesuaikan jika konfigurasi MySQL di laptop berbeda.

## Pemeriksaan setelah instalasi

- Buka beranda dan daftar wisata.
- Login admin, edit satu wisata, isi kontak WhatsApp, simpan, lalu buka detail wisata tersebut.
- Tambah rekening untuk destinasi.
- Daftarkan akun pengguna, login, dan buat booking.
- Unggah bukti pembayaran.
- Login admin dan verifikasi pembayaran.
- Login kembali sebagai pengguna dan cek riwayat booking.
- Uji juga dari browser HP.

## Catatan

Project telah diperiksa untuk validitas arsip ZIP dan sintaks PHP. Pengujian koneksi database dan alur transaksi perlu dilakukan di XAMPP pada laptop karena kredensial dan data MySQL lokal berbeda-beda.
