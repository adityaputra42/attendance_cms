#!/bin/bash
echo "Starting Attendance System..."
echo "Admin URL: http://localhost:8000/admin"
echo "Login: admin@attendance.com / password"

echo "Starting Database (Docker)..."
docker-compose up -d
echo "Waiting for database to be ready..."
sleep 5

echo "Starting Server..."
php artisan serve
