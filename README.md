# Sistem Booking Tiket Wisata — PHP Native

Project baru menggunakan PHP Native, MySQL, HTML, CSS, dan JavaScript tanpa Bootstrap. Ditujukan untuk latihan/proyek sekolah di XAMPP.

## Fitur
- Registrasi, login, logout, dan session.
- Beranda dan daftar destinasi dengan pencarian.
- Detail wisata, harga, lokasi, jam buka, fasilitas.
- Booking tanggal dan jumlah tiket, total otomatis.
- Pembayaran manual dengan rekening berbeda per wisata.
- Upload bukti pembayaran.
- Panel admin: dashboard, CRUD wisata + upload gambar, pengaturan rekening, verifikasi pembayaran, daftar pengguna.
- Riwayat booking pengguna.
- Validasi dasar upload gambar dan prepared statements PDO.

## Instalasi di XAMPP
1. Ekstrak folder `booking_wisata_baru` ke `C:\\xampp\\htdocs\\`.
2. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, lalu pilih file `database.sql` di folder project. SQL akan membuat database `booking_wisata_baru`.
5. Buka `http://localhost/booking_wisata_baru/`.
6. Login admin: username `admin`, password `admin123`.
7. Setelah login, masuk ke **Panel Admin → Kelola Wisata** untuk mengganti gambar/deskripsi/harga.
8. Masuk ke **Panel Admin → Rekening** untuk memasukkan rekening resmi setiap destinasi.

## Catatan
- Pastikan PHP XAMPP menyediakan PDO MySQL (umumnya sudah aktif).
- Pengaturan database default ada di `config/database.php`: host `localhost`, user `root`, password kosong. Sesuaikan jika MySQL Anda memakai password.
- Foto contoh sengaja berupa placeholder lokal agar tidak rusak karena tautan gambar eksternal. Admin bisa menggantinya dengan foto resmi melalui panel.
- Pembayaran adalah transfer manual, bukan payment gateway otomatis.
- Untuk penggunaan nyata, ganti password admin, aktifkan HTTPS, tambahkan perlindungan CSRF, kebijakan privasi, dan lakukan audit keamanan lebih lanjut.
- Penghapusan wisata dibatasi database bila wisata sudah memiliki transaksi, untuk menjaga riwayat pemesanan.
- Sistem ini merupakan starter project yang perlu diuji pada lingkungan XAMPP sebelum dipakai sebagai sistem produksi.

## Skenario pengujian
1. Buka beranda dan pastikan kartu wisata muncul.
2. Login admin dan ubah nama/deskripsi/gambar salah satu destinasi.
3. Tambah rekening untuk destinasi tersebut.
4. Daftar akun pengguna baru, lalu login.
5. Buka detail wisata dan buat booking dengan tanggal mendatang.
6. Buka pembayaran, pastikan rekening yang tampil sesuai destinasi, lalu upload gambar bukti pembayaran.
7. Logout user dan login sebagai admin; buka Pemesanan & Verifikasi, periksa bukti, lalu setujui/tolak.
8. Login kembali sebagai user dan cek status di Riwayat Booking.
9. Tes pencarian wisata dan tautan navigasi pada desktop serta HP.


## Versi pembayaran manual (tanpa payment gateway)
Versi ini tidak mengintegrasikan QRIS otomatis maupun API payment gateway. Pembayaran dilakukan melalui transfer bank sesuai rekening yang diatur admin untuk tiap wisata; pengunjung mengunggah bukti, lalu admin memeriksanya secara manual. Fitur profil pengguna tersedia di `profil.php`.

### Database untuk instalasi baru
Import `database.sql` melalui phpMyAdmin.

### Database yang sudah dipakai
Backup database terlebih dahulu, lalu jalankan `database/migrations/20261020_profile_only.sql` pada database `booking_wisata_baru`. Migrasi ini hanya menambahkan kolom nomor HP dan foto profil jika belum ada; tidak menghapus tabel atau data. Jangan impor ulang `database.sql` ke database yang sudah berisi data karena skrip awal membuat ulang tabel.


## Fitur kontak admin wisata
Jika memakai database lama yang sudah berjalan, buka phpMyAdmin, pilih database `booking_wisata_baru`, tab SQL, lalu jalankan isi file `database/migrations/20261102_kontak_admin_wisata.sql` satu kali. Jangan jalankan berulang kali karena kolom akan sudah ada. Setelah itu buka Admin → Kelola Wisata → Edit/Tambah Wisata, isi nama pengelola, nomor WhatsApp format internasional (contoh `6281234567890`) dan jam layanan, lalu simpan. Kontak akan tampil pada halaman detail destinasi masing-masing.
