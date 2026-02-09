#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Attendance System - Database Setup  ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if PostgreSQL is installed
if ! command -v psql &> /dev/null; then
    echo -e "${RED}PostgreSQL is not installed!${NC}"
    echo "Please install PostgreSQL first:"
    echo "  Ubuntu/Debian: sudo apt install postgresql postgresql-contrib"
    echo "  macOS: brew install postgresql"
    exit 1
fi

# Check if PostgreSQL is running
if ! pg_isready -q; then
    echo -e "${YELLOW}PostgreSQL is not running. Starting PostgreSQL...${NC}"
    
    # Try to start PostgreSQL
    if command -v systemctl &> /dev/null; then
        sudo systemctl start postgresql
    elif command -v service &> /dev/null; then
        sudo service postgresql start
    else
        echo -e "${RED}Cannot start PostgreSQL automatically.${NC}"
        echo "Please start PostgreSQL manually and run this script again."
        exit 1
    fi
    
    # Wait a bit for PostgreSQL to start
    sleep 2
    
    # Check again
    if ! pg_isready -q; then
        echo -e "${RED}Failed to start PostgreSQL.${NC}"
        echo "Please start PostgreSQL manually and run this script again."
        exit 1
    fi
fi

echo -e "${GREEN}✓ PostgreSQL is running${NC}"

# Database configuration from .env
DB_NAME="attendance_db"
DB_USER="postgres"
DB_PASSWORD="postgres"

# Check if database exists
if psql -U $DB_USER -lqt | cut -d \| -f 1 | grep -qw $DB_NAME; then
    echo -e "${YELLOW}Database '$DB_NAME' already exists.${NC}"
    read -p "Do you want to drop and recreate it? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "Dropping database..."
        dropdb -U $DB_USER $DB_NAME 2>/dev/null || true
        echo -e "${GREEN}✓ Database dropped${NC}"
    else
        echo "Keeping existing database."
    fi
fi

# Create database if it doesn't exist
if ! psql -U $DB_USER -lqt | cut -d \| -f 1 | grep -qw $DB_NAME; then
    echo "Creating database '$DB_NAME'..."
    createdb -U $DB_USER $DB_NAME
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Database created successfully${NC}"
    else
        echo -e "${RED}Failed to create database${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✓ Database exists${NC}"
fi

# Run migrations
echo ""
echo "Running migrations..."
php artisan migrate:fresh --seed

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed successfully${NC}"
else
    echo -e "${RED}Failed to run migrations${NC}"
    exit 1
fi

# Create storage link
echo ""
echo "Creating storage link..."
php artisan storage:link

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Setup completed successfully!        ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Default users created:"
echo ""
echo -e "${YELLOW}Admin:${NC}"
echo "  Email: admin@attendance.com"
echo "  Password: password"
echo ""
echo -e "${YELLOW}Employees:${NC}"
echo "  Email: john@attendance.com"
echo "  Password: password"
echo ""
echo "  Email: jane@attendance.com"
echo "  Password: password"
echo ""
echo "You can now run the application with:"
echo -e "${GREEN}php artisan serve${NC}"
echo ""
