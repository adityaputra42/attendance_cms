# Admin Panel User Guide

Panduan penggunaan Attendance System Admin Panel.

## Akses Admin Panel

- **URL**: `http://localhost:8000/admin`
- **Login Default**: 
  - Email: `admin@attendance.com`
  - Password: `password`

## Fitur & Cara Menggunakan

### 1. Dashboard
Halaman utama menampilkan ringkasan performa hari ini:
- Total karyawan aktif.
- Jumlah karyawan yang sudah Check-In dan Check-Out hari ini.
- Aktivitas terbaru (Log) absensi secara real-time.

### 2. Manajemen Karyawan (Employees)
Menu: **Management > Employees**

- **Lihat Daftar Karyawan**: Menampilkan semua karyawan dengan status aktif/non-aktif.
- **Tambah Karyawan**: Klik tombol "Add New Employee".
  - Isi nama, email, password, dan role (Employee/Admin).
  - Anda bisa upload foto profil.
- **Edit Karyawan**: Klik tombol pensil.
- **Detail Karyawan**: Klik tombol mata untuk melihat detail profil dan riwayat absensi individu.
- **Non-aktifkan Akun**: Klik tombol status (Check/Ban) untuk mengaktifkan atau menonaktifkan akses karyawan. Karyawan non-aktif tidak bisa login ke Mobile App.

### 3. Monitoring Absensi (Attendance)
Menu: **Management > Attendance**

- **Filter Data**: Gunakan filter di atas tabel untuk menyaring berdasarkan Karyawan, Status (In/Out), atau Rentang Tanggal.
- **Export Data**: Klik tombol hijau "Export Data" untuk mengunduh laporan dalam format CSV (Excel compatible).
- **Detail Absensi**: Klik "Details" pada baris absensi untuk melihat:
  - Waktu Check-In & Check-Out.
  - **Peta Lokasi**: Melihat posisi GPS karyawan saat absen check-in dan check-out di peta interaktif.
  - **Foto**: Melihat foto selfie/bukti absensi.
  - Durasi dan deskripsi pekerjaan.

### 4. Laporan Bulanan (Reports)
Menu: **Management > Reports**

- Halaman ini memberikan ringkasan statistik bulanan.
- **Metrics**: Total hari kerja, tingkat kehadiran (%), dan detail per karyawan.
- **Navigasi**: Pilih Bulan dan Tahun untuk melihat laporan periode lain.

### 5. Pengaturan Sistem (Settings)
Menu: **System > Settings**

Disini Anda mengatur parameter validasi absensi:
- **Lokasi Kantor**: Geser pin pada Peta Interaktif untuk menentukan titik koordinat kantor.
- **Radius**: Tentukan jarak maksimum (dalam meter) karyawan boleh absen dari titik kantor (Geo-fencing).
- **Jam Kerja**: Tentukan jam kerja normal (untuk referensi laporan).

---

## Troubleshooting

### Peta tidak muncul?
Pastikan Anda terhubung ke internet karena peta menggunakan OpenStreetMap CDN.

### Karyawan tidak bisa absen?
1. Pastikan status akun mereka "Active" di menu Employees.
2. Cek menu Settings, pastikan "Allowed Radius" cukup wajar (misal 50-100 meter).
3. Pastikan karyawan memberikan izin lokasi di HP mereka.

### Lupa Password Admin?
Gunakan database seeder untuk mereset:
```bash
php artisan db:seed --class=AdminUserSeeder
```
*Note: Ini akan membuat user admin default kembali jika belum ada.*
