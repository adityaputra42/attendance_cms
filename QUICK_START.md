# Quick Start Guide - Attendance System

## Prerequisites Check

Pastikan Anda sudah menginstall:
- ✅ PHP 8.2 atau lebih tinggi
- ✅ Composer
- ✅ PostgreSQL 14 atau lebih tinggi
- ✅ Node.js & NPM

## Setup dalam 5 Langkah

### 1️⃣ Install Dependencies
```bash
composer install
npm install
```

### 2️⃣ Start PostgreSQL
```bash
# Ubuntu/Debian
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Atau gunakan Docker
docker run --name postgres-attendance \
  -e POSTGRES_PASSWORD=postgres \
  -e POSTGRES_DB=attendance_db \
  -p 5432:5432 \
  -d postgres:14
```

### 3️⃣ Setup Database (Otomatis)
```bash
chmod +x setup-database.sh
./setup-database.sh
```

**Atau Manual:**
```bash
# Buat database
createdb -U postgres attendance_db

# Run migrations
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link
```

### 4️⃣ Start Server
```bash
php artisan serve
```

Server akan berjalan di: **http://localhost:8000**

### 5️⃣ Test API
```bash
# Login untuk mendapatkan token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@attendance.com",
    "password": "password"
  }'
```

## Default Login Credentials

### 🔐 Admin
- **Email**: admin@attendance.com
- **Password**: password
- **URL**: http://localhost:8000/admin/dashboard

### 👤 Employee (untuk testing API)
- **Email**: john@attendance.com
- **Password**: password

atau

- **Email**: jane@attendance.com
- **Password**: password

## Testing dengan Postman

1. Import file: `Attendance_API.postman_collection.json`
2. Klik pada collection → Variables
3. Set `base_url` = `http://localhost:8000/api`
4. Run request "Login" untuk mendapatkan token
5. Token akan otomatis tersimpan di collection variables
6. Test endpoints lainnya

## Contoh Penggunaan API

### 1. Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@attendance.com",
    "password": "password"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "token": "1|xxxxxxxxxxxxx"
  }
}
```

### 2. Check In
```bash
curl -X POST http://localhost:8000/api/attendance/check-in \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "latitude=-6.200000" \
  -F "longitude=106.816666" \
  -F "address=Jakarta Office" \
  -F "photo=@/path/to/photo.jpg"
```

### 3. Check Out
```bash
curl -X POST http://localhost:8000/api/attendance/check-out \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "latitude=-6.200000" \
  -F "longitude=106.816666" \
  -F "address=Jakarta Office" \
  -F "work_description=Completed all tasks for today" \
  -F "photo=@/path/to/photo.jpg"
```

### 4. Get Today Status
```bash
curl -X GET http://localhost:8000/api/attendance/today-status \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 5. Get History
```bash
curl -X GET http://localhost:8000/api/attendance/history \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Konfigurasi Lokasi Kantor

Default lokasi kantor adalah Jakarta. Untuk mengubahnya:

### Via Database
```sql
UPDATE attendance_settings 
SET value = 'YOUR_LATITUDE' 
WHERE key = 'office_latitude';

UPDATE attendance_settings 
SET value = 'YOUR_LONGITUDE' 
WHERE key = 'office_longitude';

UPDATE attendance_settings 
SET value = '500' 
WHERE key = 'allowed_radius_meters';
```

### Via Admin Panel (Coming Soon)
Akses: http://localhost:8000/admin/settings

## Troubleshooting

### ❌ PostgreSQL Connection Error
```bash
# Cek status PostgreSQL
sudo systemctl status postgresql

# Start PostgreSQL
sudo systemctl start postgresql

# Cek database
psql -U postgres -l
```

### ❌ Migration Error
```bash
# Reset database
php artisan migrate:fresh --seed
```

### ❌ Storage Link Error
```bash
# Hapus link lama
rm public/storage

# Buat link baru
php artisan storage:link
```

### ❌ Permission Error
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## File Struktur

```
attendance/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/              # API Controllers
│   │   └── Admin/            # Admin Controllers
│   └── Models/               # Eloquent Models
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/              # Database Seeders
├── routes/
│   ├── api.php              # API Routes
│   └── web.php              # Web Routes
├── storage/
│   └── app/public/          # Uploaded Files
├── README.md                # Full Documentation
├── API_DOCUMENTATION.md     # API Reference
├── PROJECT_SUMMARY.md       # Project Overview
└── setup-database.sh        # Setup Script
```

## Next Steps

### Untuk Backend Developer
1. ✅ Backend API sudah siap digunakan
2. 📝 Baca `API_DOCUMENTATION.md` untuk detail API
3. 🧪 Test semua endpoints dengan Postman
4. 🎨 Develop Admin Panel Views (optional)

### Untuk Mobile Developer
1. 📱 Develop mobile app (Flutter/React Native)
2. 🔌 Integrate dengan API endpoints
3. 📸 Implement camera untuk foto
4. 📍 Implement GPS tracking
5. 🔔 Add push notifications

### Untuk Frontend Developer
1. 🎨 Create admin panel views
2. 📊 Add charts untuk dashboard
3. 📋 Create forms untuk CRUD operations
4. 📤 Implement export/report features

## Dokumentasi Lengkap

- **README.md** - Instalasi dan setup lengkap
- **API_DOCUMENTATION.md** - API reference dengan contoh
- **PROJECT_SUMMARY.md** - Overview project
- **QUICK_START.md** - Panduan cepat (file ini)

## Support

Jika ada pertanyaan atau issue:
1. Cek dokumentasi di folder project
2. Review error logs di `storage/logs/laravel.log`
3. Hubungi development team

---

**Happy Coding! 🚀**
