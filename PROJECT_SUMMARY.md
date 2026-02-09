# Project Summary - Sistem Absensi Laravel

## Overview
Project sistem absensi karyawan menggunakan Laravel 12 dengan PostgreSQL database. Sistem ini terdiri dari Backend API untuk mobile app dan CMS Admin untuk manajemen.

## Struktur Project

### Backend Components

#### Models
1. **User** (`app/Models/User.php`)
   - Fields: name, email, password, phone, avatar, role, is_active, address
   - Relationships: hasMany Attendances
   - Methods: isAdmin()

2. **Attendance** (`app/Models/Attendance.php`)
   - Fields: check-in/out data, GPS coordinates, photos, work description
   - Relationships: belongsTo User
   - Methods: calculateWorkDuration(), getFormattedWorkDuration()
   - Scopes: todayForUser, dateRange, checkedIn, checkedOut

3. **AttendanceSetting** (`app/Models/AttendanceSetting.php`)
   - Fields: key, value, type, description
   - Methods: getValue(), setValue(), getAllSettings()

#### Controllers

**API Controllers** (`app/Http/Controllers/Api/`)
1. **AuthController**
   - register() - Registrasi user baru
   - login() - Login dan generate token
   - logout() - Revoke token
   - me() - Get user info
   - updateProfile() - Update profile + avatar
   - changePassword() - Ganti password

2. **AttendanceController**
   - checkIn() - Absen masuk + validasi lokasi
   - checkOut() - Absen keluar + deskripsi pekerjaan
   - todayStatus() - Status absensi hari ini
   - history() - Riwayat absensi (paginated)
   - show() - Detail absensi
   - statistics() - Statistik bulanan
   - settings() - Get attendance settings

**Admin Controllers** (`app/Http/Controllers/Admin/`)
1. **DashboardController**
   - index() - Dashboard dengan statistik

2. **UserController**
   - CRUD operations untuk user
   - toggleStatus() - Aktifkan/nonaktifkan user

3. **AttendanceController**
   - index() - List attendance dengan filter
   - show() - Detail attendance
   - export() - Export ke CSV
   - report() - Laporan bulanan

4. **SettingController**
   - index() - Tampilkan settings
   - update() - Update settings

### Database

#### Migrations
1. `create_users_table` - Tabel users default Laravel
2. `add_fields_to_users_table` - Tambah field: phone, avatar, role, is_active, address
3. `create_attendances_table` - Tabel attendance lengkap
4. `create_attendance_settings_table` - Tabel settings + default values
5. `create_personal_access_tokens_table` - Sanctum tokens

#### Seeders
- **AdminUserSeeder** - Create admin + sample employees
  - admin@attendance.com / password (role: admin)
  - john@attendance.com / password (role: employee)
  - jane@attendance.com / password (role: employee)

### Routes

#### API Routes (`routes/api.php`)
```
POST   /register
POST   /login
POST   /logout (auth)
GET    /me (auth)
POST   /update-profile (auth)
POST   /change-password (auth)

POST   /attendance/check-in (auth)
POST   /attendance/check-out (auth)
GET    /attendance/today-status (auth)
GET    /attendance/history (auth)
GET    /attendance/statistics (auth)
GET    /attendance/settings (auth)
GET    /attendance/{id} (auth)
```

#### Web Routes (`routes/web.php`)
```
GET    /admin/dashboard
CRUD   /admin/users
POST   /admin/users/{id}/toggle-status
GET    /admin/attendances
GET    /admin/attendances/report
GET    /admin/attendances/export
GET    /admin/attendances/{id}
GET    /admin/settings
POST   /admin/settings
```

## Key Features

### 1. Location-Based Attendance
- Menggunakan Haversine Formula untuk validasi jarak
- Default radius: 500 meter dari kantor
- Koordinat kantor default: Jakarta (-6.200000, 106.816666)

### 2. Photo Upload
- Check-in photo (required)
- Check-out photo (required)
- Avatar photo (optional)
- Storage: local storage (`storage/app/public/`)
- Max size: 5MB (attendance), 2MB (avatar)

### 3. Work Description
- Required saat check-out
- Minimum 10 karakter
- Untuk dokumentasi pekerjaan harian

### 4. Work Duration Calculation
- Otomatis dihitung dari check-in ke check-out
- Disimpan dalam menit
- Helper method untuk format "X jam Y menit"

### 5. Authentication
- Laravel Sanctum untuk API
- Token-based authentication
- Auto-revoke previous tokens saat login

### 6. Admin Features
- Dashboard dengan statistik
- User management (CRUD)
- Attendance monitoring
- CSV export
- Monthly reports
- Settings management

## Configuration Files

### Environment (.env)
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=attendance_db
DB_USERNAME=postgres
DB_PASSWORD=postgres

FILESYSTEM_DISK=local
```

### Default Settings (attendance_settings table)
- office_latitude: -6.200000
- office_longitude: 106.816666
- allowed_radius_meters: 500
- work_start_time: 08:00
- work_end_time: 17:00

## Helper Files

1. **README.md** - Dokumentasi lengkap instalasi dan penggunaan
2. **API_DOCUMENTATION.md** - Dokumentasi API detail dengan contoh request/response
3. **Attendance_API.postman_collection.json** - Postman collection untuk testing
4. **setup-database.sh** - Script otomatis setup database

## Installation Steps

1. Install dependencies: `composer install && npm install`
2. Setup PostgreSQL dan buat database
3. Run setup script: `./setup-database.sh`
4. Atau manual:
   - `php artisan migrate:fresh --seed`
   - `php artisan storage:link`
5. Run server: `php artisan serve`

## Testing

### API Testing
- Import Postman collection
- Login untuk mendapatkan token
- Token otomatis tersimpan di collection variables
- Test semua endpoints

### Manual Testing
1. Login via API: `POST /api/login`
2. Check-in: `POST /api/attendance/check-in` (dengan foto + GPS)
3. Check status: `GET /api/attendance/today-status`
4. Check-out: `POST /api/attendance/check-out` (dengan foto + GPS + deskripsi)
5. View history: `GET /api/attendance/history`

## Next Development Steps

### Frontend Admin Panel
- [ ] Create Blade templates untuk admin
- [ ] Dashboard dengan charts
- [ ] User management UI
- [ ] Attendance monitoring UI
- [ ] Settings page
- [ ] Export/Report UI

### Mobile App
- [ ] Develop Flutter/React Native app
- [ ] Implement camera untuk foto
- [ ] GPS location tracking
- [ ] Push notifications
- [ ] Offline support

### Additional Features
- [ ] Email notifications
- [ ] Leave management
- [ ] Overtime tracking
- [ ] Multi-office support
- [ ] QR Code check-in
- [ ] Face recognition
- [ ] Shift management
- [ ] Payroll integration

## Security Considerations

1. **Authentication**: Sanctum tokens dengan auto-revoke
2. **Authorization**: Role-based (admin/employee)
3. **Validation**: Comprehensive input validation
4. **File Upload**: Type dan size validation
5. **Location**: Server-side distance validation
6. **Password**: Hashed dengan bcrypt
7. **CSRF**: Laravel default protection

## Performance Optimization

1. **Database Indexes**: 
   - user_id + attendance_date
   - status
   - Unique key pada settings

2. **Eager Loading**: 
   - Attendance with User
   - Prevent N+1 queries

3. **Pagination**: 
   - Default 15 items per page
   - Configurable via query params

4. **Caching**: 
   - Settings dapat di-cache
   - Token validation via Sanctum

## Maintenance

### Database Backup
```bash
pg_dump -U postgres attendance_db > backup.sql
```

### Database Restore
```bash
psql -U postgres attendance_db < backup.sql
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Update Dependencies
```bash
composer update
npm update
```

## Support & Documentation

- **README.md**: Setup dan instalasi
- **API_DOCUMENTATION.md**: API reference lengkap
- **Postman Collection**: Testing tool
- **Code Comments**: Inline documentation

## Project Status

✅ Backend API - Complete
✅ Database Schema - Complete
✅ Authentication - Complete
✅ Attendance System - Complete
✅ Admin Controllers - Complete
✅ Documentation - Complete
⏳ Admin Frontend Views - Pending
⏳ Mobile App - Pending

## Technologies Used

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: PostgreSQL 14+
- **Authentication**: Laravel Sanctum
- **Storage**: Local File System
- **API**: RESTful JSON API
- **Documentation**: Markdown, Postman

---

**Created**: 2026-02-09
**Version**: 1.0.0
**Author**: Development Team
