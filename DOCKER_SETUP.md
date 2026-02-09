# Docker Setup Guide

Anda menggunakan Docker Image `postgres:16` untuk database. File `docker-compose.yml` telah dipersiapkan untuk menjalankan environment database ini dengan mudah.

## 1. Menjalankan Database
Pastikan Docker Desktop/Daemon sudah berjalan, lalu jalankan perintah berikut di terminal:

```bash
# Start PostgreSQL container di background
docker-compose up -d
```

Ini akan membuat container bernama `attendance_db` yang berjalan di port `5432`.

## 2. Kredensial Database
Konfigurasi database otomatis menyesuaikan dengan file `.env` project Anda:

- **Host**: `127.0.0.1` (localhost)
- **Port**: `5432`
- **Database**: `attendance_db`
- **Username**: `postgres`
- **Password**: `postgres` (Sesuai `.env`)

## 3. Cek Status Database
Untuk melihat apakah database sudah berjalan:

```bash
docker ps
```

Anda harus melihat container `postgres:16` berjalan dengan nama `attendance_db`.

## 4. Reset Database (Opsional)
Jika Anda ingin menghapus semua data database dan mulai dari awal:

```bash
# Stop dan hapus container beserta volumenya
docker-compose down -v

# Start ulang
docker-compose up -d

# Run migrations lagi
php artisan migrate:fresh --seed
```

## 5. Mengakses Database CLI
Jika ingin masuk ke console database langsung:

```bash
docker exec -it attendance_db psql -U postgres -d attendance_db
```
