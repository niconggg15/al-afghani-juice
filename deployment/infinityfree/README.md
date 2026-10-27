# Menyiapkan upload InfinityFree

InfinityFree tidak menjalankan konfigurasi Railway. Siapkan file secara lokal, lalu unggah melalui File Manager atau FTP.

## 1. Buat paket upload

Jalankan PowerShell dari folder project:

```powershell
powershell -ExecutionPolicy Bypass -File deployment\infinityfree\build.ps1
```

Composer harus tersedia di komputer. Script membuat paket di `storage\app\infinityfree-build-<waktu>\infinityfree-upload.zip`. Paket berisi aplikasi pada folder `laravel_app`, file publik Laravel di root arsip, dan dependency produksi. File `.env` sengaja tidak disertakan.

## 2. Unggah file

1. Buat akun situs dan database MySQL dari panel InfinityFree.
2. Unggah `infinityfree-upload.zip` dan ekstrak langsung ke folder `htdocs` situs.
3. Pastikan `index.php`, `.htaccess`, dan folder `images` ada langsung di `htdocs`; folder aplikasi harus berada di `htdocs\laravel_app`.
4. Di `htdocs\laravel_app`, buat `.env` secara manual. Jangan unggah `.env` ke GitHub atau membagikannya.
5. Atur folder `htdocs\images` dan `htdocs\laravel_app\storage` agar dapat ditulis aplikasi melalui permission File Manager bila fitur itu tersedia.

Folder `laravel_app` memiliki `.htaccess` yang menolak akses web langsung ke source code dan `.env`. Jangan hapus file itu.

## 3. Atur `.env` di hosting

Isi konfigurasi dasar berikut dengan nilai dari panel hosting:

```dotenv
APP_NAME="Al Afghani Juice"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://DOMAIN-SITUS-KAMU

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=HOST-MYSQL-DARI-PANEL
DB_PORT=3306
DB_DATABASE=NAMA-DATABASE-DARI-PANEL
DB_USERNAME=USERNAME-DARI-PANEL
DB_PASSWORD=PASSWORD-DARI-PANEL
DB_SUPPORTS_ORDER_ROUTINES=false

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Buat `APP_KEY` di komputer dengan `php artisan key:generate --show`, lalu masukkan hasilnya hanya ke `.env` di hosting. Jangan memakai `APP_KEY` lokal yang sudah pernah dipakai aplikasi lain.

## 4. Ekspor dan impor database

1. Buka phpMyAdmin lokal XAMPP, pilih database `kedai_juice`, lalu pilih **Export → Custom → SQL**.
2. Pilih semua tabel dan ekspor **structure and data**, termasuk tabel `migrations`; jangan ekspor trigger, procedure, atau function.
3. Simpan file SQL hanya di komputer, lalu impor melalui phpMyAdmin yang disediakan InfinityFree. Jangan masukkan file SQL dump ke GitHub.
4. Database lokal yang diekspor harus berisi kategori, produk, akun demo, dan tidak berisi pesanan transaksi yang ingin dikosongkan.

`DB_SUPPORTS_ORDER_ROUTINES=false` mencegah migration membuat stored procedure/trigger yang mungkin tidak diizinkan pada database shared hosting. Pembatalan pesanan tetap mengembalikan stok lewat kode Laravel.

## 5. Tes situs

Buka domain HTTPS dan uji menu, login, tambah ke keranjang, checkout, konfirmasi kasir, penyelesaian pesanan, serta riwayat. Jika muncul error, ambil screenshot pesan error tanpa memperlihatkan `.env`, password, atau APP_KEY.
