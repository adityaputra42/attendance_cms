# Sistem Absensi Laravel

Sistem absensi berbasis Laravel 12 dengan backend API untuk mobile app dan CMS Admin untuk manajemen.

## Fitur

### Backend API (Mobile)
- ✅ Autentikasi (Register, Login, Logout)
- ✅ Manajemen Profil User
- ✅ Check-in dengan foto dan lokasi GPS
- ✅ Check-out dengan foto, lokasi GPS, dan deskripsi pekerjaan
- ✅ Validasi lokasi (radius dari kantor)
- ✅ Riwayat absensi
- ✅ Statistik absensi bulanan

### CMS Admin
- ✅ Dashboard dengan statistik
- ✅ Manajemen User (CRUD)
- ✅ Manajemen Absensi
- ✅ Laporan Absensi (Export CSV)
- ✅ Pengaturan lokasi kantor dan radius
- ✅ Pengaturan jam kerja

## Tech Stack

- **Framework**: Laravel 12
- **Database**: PostgreSQL
- **Authentication**: Laravel Sanctum
- **Storage**: Local Storage (untuk foto absensi)

## Struktur Database

### Tabel Users
- id, name, email, password
- phone, avatar, address
- role (admin/employee)
- is_active

### Tabel Attendances
- user_id
- check_in_time, check_in_latitude, check_in_longitude, check_in_photo, check_in_address
- check_out_time, check_out_latitude, check_out_longitude, check_out_photo, check_out_address
- work_description
- work_duration_minutes
- status (checked_in/checked_out)
- attendance_date

### Tabel Attendance Settings
- key, value, type, description
- Default settings: office location, allowed radius, work hours

## Instalasi

### Prerequisites
- PHP 8.2+
- Composer
- PostgreSQL 14+
- Node.js & NPM (untuk frontend assets)

### Langkah Instalasi

1. **Clone atau setup project**
```bash
cd /home/aditya/DevLaravel/attendance
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
# File .env sudah dikonfigurasi dengan:
# - DB_CONNECTION=pgsql
# - DB_DATABASE=attendance_db
# - DB_USERNAME=postgres
# - DB_PASSWORD=postgres
```

4. **Start PostgreSQL**
```bash
# Ubuntu/Debian
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Atau jika menggunakan Docker
docker run --name postgres-attendance \
  -e POSTGRES_PASSWORD=postgres \
  -e POSTGRES_DB=attendance_db \
  -p 5432:5432 \
  -d postgres:14
```

5. **Buat database**
```bash
# Jika PostgreSQL sudah running
createdb -U postgres attendance_db

# Atau login ke PostgreSQL dan buat manual
psql -U postgres
CREATE DATABASE attendance_db;
\q
```

6. **Jalankan migration dan seeder**
```bash
php artisan migrate:fresh --seed
```

7. **Buat symbolic link untuk storage**
```bash
php artisan storage:link
```

8. **Jalankan development server**
```bash
php artisan serve
```

Server akan berjalan di: http://localhost:8000

## Default Users

Setelah menjalankan seeder, Anda dapat login dengan:

### Admin
- Email: `admin@attendance.com`
- Password: `password`

### Employee (untuk testing mobile app)
- Email: `john@attendance.com`
- Password: `password`

atau

- Email: `jane@attendance.com`
- Password: `password`

## API Endpoints

Base URL: `http://localhost:8000/api`

### Authentication
```
POST   /register              - Register user baru
POST   /login                 - Login
POST   /logout                - Logout (requires auth)
GET    /me                    - Get user info (requires auth)
POST   /update-profile        - Update profile (requires auth)
POST   /change-password       - Change password (requires auth)
```

### Attendance
```
POST   /attendance/check-in      - Check in (requires auth)
POST   /attendance/check-out     - Check out (requires auth)
GET    /attendance/today-status  - Status absensi hari ini (requires auth)
GET    /attendance/history       - Riwayat absensi (requires auth)
GET    /attendance/statistics    - Statistik absensi (requires auth)
GET    /attendance/settings      - Get attendance settings (requires auth)
GET    /attendance/{id}          - Detail absensi (requires auth)
```

### Headers untuk API yang memerlukan autentikasi
```
Authorization: Bearer {token}
Accept: application/json
```

## Admin Routes

Base URL: `http://localhost:8000/admin`

```
GET    /admin/dashboard                    - Dashboard
GET    /admin/users                        - List users
GET    /admin/users/create                 - Form create user
POST   /admin/users                        - Store user
GET    /admin/users/{id}                   - Show user detail
GET    /admin/users/{id}/edit              - Form edit user
PUT    /admin/users/{id}                   - Update user
DELETE /admin/users/{id}                   - Delete user
POST   /admin/users/{id}/toggle-status     - Toggle user status

GET    /admin/attendances                  - List attendances
GET    /admin/attendances/report           - Attendance report
GET    /admin/attendances/export           - Export to CSV
GET    /admin/attendances/{id}             - Show attendance detail

GET    /admin/settings                     - Settings page
POST   /admin/settings                     - Update settings
```

## Contoh Request API

### 1. Register
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "081234567890"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@attendance.com",
    "password": "password"
  }'
```

Response:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "1|xxxxxxxxxxxxx"
  }
}
```

### 3. Check In
```bash
curl -X POST http://localhost:8000/api/attendance/check-in \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "latitude=-6.200000" \
  -F "longitude=106.816666" \
  -F "address=Jakarta Office" \
  -F "photo=@/path/to/photo.jpg"
```

### 4. Check Out
```bash
curl -X POST http://localhost:8000/api/attendance/check-out \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "latitude=-6.200000" \
  -F "longitude=106.816666" \
  -F "address=Jakarta Office" \
  -F "work_description=Completed daily tasks and meetings" \
  -F "photo=@/path/to/photo.jpg"
```

### 5. Get Today Status
```bash
curl -X GET http://localhost:8000/api/attendance/today-status \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 6. Get History
```bash
curl -X GET "http://localhost:8000/api/attendance/history?month=2&year=2026&per_page=15" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Konfigurasi

### Pengaturan Lokasi Kantor
Anda dapat mengubah lokasi kantor dan radius melalui:
1. Admin Panel: `/admin/settings`
2. Atau langsung di database tabel `attendance_settings`

Default settings:
- Office Latitude: -6.200000 (Jakarta)
- Office Longitude: 106.816666 (Jakarta)
- Allowed Radius: 500 meters
- Work Start Time: 08:00
- Work End Time: 17:00

### Upload File
- Avatar: max 2MB (jpeg, png, jpg)
- Attendance Photo: max 5MB (jpeg, png, jpg)
- Storage: `storage/app/public/`
- Public URL: `http://localhost:8000/storage/`

## Validasi Lokasi

Sistem menggunakan **Haversine Formula** untuk menghitung jarak antara lokasi user dengan lokasi kantor. User hanya bisa check-in/check-out jika berada dalam radius yang ditentukan (default: 500 meter).

## Development

### Struktur Folder
```
app/
├── Http/
│   └── Controllers/
│       ├── Api/
│       │   ├── AuthController.php
│       │   └── AttendanceController.php
│       └── Admin/
│           ├── DashboardController.php
│           ├── UserController.php
│           ├── AttendanceController.php
│           └── SettingController.php
└── Models/
    ├── User.php
    ├── Attendance.php
    └── AttendanceSetting.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_02_09_023529_create_attendances_table.php
│   ├── 2026_02_09_023530_add_fields_to_users_table.php
│   └── 2026_02_09_023532_create_attendance_settings_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── AdminUserSeeder.php

routes/
├── api.php      # API routes untuk mobile
└── web.php      # Web routes untuk admin panel
```

## Testing

Untuk testing API, Anda bisa menggunakan:
- Postman
- Insomnia
- curl
- atau mobile app yang Anda develop

## Troubleshooting

### PostgreSQL Connection Error
```bash
# Pastikan PostgreSQL running
sudo systemctl status postgresql

# Jika belum running
sudo systemctl start postgresql

# Cek apakah database sudah dibuat
psql -U postgres -l
```

### Storage Link Error
```bash
# Hapus link lama jika ada
rm public/storage

# Buat link baru
php artisan storage:link
```

### Migration Error
```bash
# Reset database
php artisan migrate:fresh --seed
```

## Next Steps

1. **Frontend Admin Panel**: Buat views untuk admin panel (dashboard, users, attendances, settings)
2. **Mobile App**: Develop mobile app (Flutter/React Native) yang consume API ini
3. **Notifications**: Tambahkan notifikasi untuk reminder check-in/check-out
4. **Reports**: Tambahkan lebih banyak laporan dan analytics
5. **Export**: Tambahkan export ke PDF selain CSV

## License

This project is open-sourced software.

## Support

Untuk pertanyaan atau issue, silakan hubungi developer.
