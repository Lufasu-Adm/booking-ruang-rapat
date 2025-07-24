# Sistem Booking Ruang Rapat

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)

Sistem Booking Ruang Rapat adalah aplikasi web yang dibangun menggunakan Laravel untuk memfasilitasi proses pemesanan ruang rapat di sebuah organisasi. Aplikasi ini dirancang dengan sistem multi-peran (multi-role) untuk mengatur hak akses yang berbeda bagi setiap pengguna.

![Tampilan Dashboard Admin](https://github.com/Lufasu-Adm/booking-ruang-rapat/blob/main/UI/Admin.png)
![Tampilan Dashboard Super Admin](https://github.com/Lufasu-Adm/booking-ruang-rapat/blob/main/UI/superadmin.png)

---

## 🚀 Fitur Utama

Aplikasi ini dilengkapi dengan berbagai fitur untuk menunjang kebutuhan manajemen dan pemesanan ruangan, antara lain:

* **Autentikasi Multi-Peran**: Sistem login yang aman dengan dua tingkat hak akses:
    * **SuperAdmin**: Memiliki kontrol penuh atas semua divisi, pengguna, ruangan, serta dapat menyetujui booking dan membuat laporan rekapitulasi dari semua divisi.
    * **Admin**: Dapat melakukan pemesanan ruangan dan melihat riwayat booking pribadi.
* **Manajemen Booking**:
    * Formulir pemesanan dengan pilihan tanggal dan jam.
    * Pengecekan jadwal yang tumpang tindih (overlap) untuk mencegah booking ganda.
    * Sistem persetujuan (approval) oleh Admin.
* **Manajemen Ruangan & Divisi**:
    * Admin dapat menambah, mengubah, dan menghapus divisi serta ruangan.
* **Laporan PDF Dinamis**:
    * Fitur untuk membuat laporan rekapitulasi booking dalam format PDF.
    * Filter laporan berdasarkan rentang tanggal.
    * Laporan PDF menampilkan data yang dikelompokkan dengan **tabel terpisah untuk setiap divisi**, membuatnya sangat rapi dan mudah dibaca.

![Tampilan Halaman Filter](https://github.com/Lufasu-Adm/booking-ruang-rapat/blob/main/UI/Filter.png)

---

## 🛠️ Teknologi yang Digunakan

* **Backend**: PHP 8.x, Laravel 10.x
* **Frontend**: HTML, CSS, JavaScript, Vite
* **Database**: MySQL, PostgreSQL
* **Laporan PDF**: `barryvdh/laravel-dompdf`

---

## ⚙️ Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/Lufasu-Adm/booking-ruang-rapat.git](https://github.com/Lufasu-Adm/booking-ruang-rapat.git)
    cd booking-ruang-rapat
    ```

2.  **Instal Dependensi**
    Pastikan Anda memiliki Composer terinstal.
    ```bash
    composer install
    ```

3.  **Buat File `.env`**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan konfigurasi database Anda.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6.  **Jalankan Migrasi**
    Perintah ini akan membuat semua tabel yang diperlukan di database Anda.
    ```bash
    php artisan migrate
    ```
    *Jika Anda memiliki seeder, jalankan juga `php artisan db:seed`.*

7.  **Jalankan Server Pengembangan**
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang dapat diakses di `http://127.0.0.1:8000`.

---

## 👤 Contoh Akun Pengguna

Anda dapat menggunakan akun berikut untuk menguji coba aplikasi (pastikan Anda sudah membuatnya melalui seeder atau registrasi manual):

* **SuperAdmin**:
    * **Email**: `superadmin@example.com`
    * **Password**: `password`
* **Admin**:
    * **Email**: `kapal_perangr@example.com`
    * **Password**: `password`

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE.md).

---

Dibuat dengan ❤️ oleh **Lufasu-Adm**.
