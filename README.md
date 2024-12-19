---

# NikleFleet  

Aplikasi manajemen reservasi kendaraan berbasis Laravel 10 dan PHP 8.3.9.  

---

## Persyaratan  
- **Laravel**: 10  
- **PHP**: 8.3.9  
- **Database**: MySQL  

---

## Instalasi  

Ikuti langkah-langkah berikut untuk menginstal aplikasi:  

1. Clone repositori:  
   ```bash  
   git clone <repository-url>  
   ```  

2. Instal dependensi:  
   ```bash  
   composer install  
   ```  

3. Salin file `.env` dari template:  
   ```bash  
   cp .env.example .env  
   ```  

4. Hasilkan kunci aplikasi:  
   ```bash  
   php artisan key:generate  
   ```  

5. Tambahkan package untuk grafik:  
   ```bash  
   composer require arielmejiadev/larapex-charts  
   php artisan vendor:publish --tag=larapex-charts-config  
   ```  

6. Tambahkan package untuk DataTables:  
   ```bash  
   composer require yajra/laravel-datatables:^10.0  
   php artisan vendor:publish --tag=datatables  
   ```  

7. Tambahkan package untuk export data ke Excel:  
   ```bash  
   composer require maatwebsite/excel  
   ```  

8. Konfigurasikan database di file `.env`:  
   ```env  
   DB_CONNECTION=mysql  
   DB_HOST=127.0.0.1  
   DB_PORT=3306  
   DB_DATABASE=nickledrive  
   DB_USERNAME=root  
   DB_PASSWORD=  
   ```  

9. Jalankan migrasi database dan seed data awal:  
   ```bash  
   php artisan migrate:fresh --seed  
   ```  

---

## Panduan Penggunaan Aplikasi  

### Role: Admin dan Approver  

#### **Admin**  
- **Login dengan kredensial berikut**:  
  - **Username**: `admin`  
  - **Password**: `password`  

- **Fitur Admin**:  
  - Mengelola data, termasuk menambahkan, mengedit, melihat detail, dan menghapus.  
  - Melihat data statistik dan grafik pada menu dashboard.  
  - Mengorganisir reservasi kendaraan pada menu "Reservasi Kendaraan".  
  - Mengekspor data ke format lain seperti Excel.  

#### **Approver**  
Approver memiliki dua tingkatan: **Approver Cabang** dan **Approver Pusat**.  

- **Login dengan kredensial berikut** (contoh):  
  - **Username**: `approver_0`, `approver_1`, `approver_2`, `approver_3`  
  - **Password**: `password`  

- **Fitur Approver Cabang**:  
  - Melakukan persetujuan tahap pertama pada menu "Reservasi Kendaraan" dengan menekan tombol "Daftar Pengajuan".  

- **Fitur Approver Pusat**:  
  - Melakukan persetujuan tahap kedua setelah persetujuan dari Approver Cabang.  

---

## Catatan  
- Pastikan semua dependensi telah diinstal dengan benar.  
- Periksa konfigurasi database di file `.env` sebelum menjalankan migrasi.  
- Gunakan kredensial login sesuai dengan role pengguna yang diinginkan.  

---
