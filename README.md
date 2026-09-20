# EAZWHEELS Precision Automotive Catalog (`eazwheels-mysql`)

A standalone, ultra-reliable Laravel, MySQL, and Redis API and storefront built without Eloquent ORM.

---

## System Requirements
- **PHP**: 8.2 or 8.3 with `pdo_mysql` and `phpredis` extensions enabled.
- **MySQL**: 8.0+ (Mandatory for DDL `CHECK` constraints).
- **Redis**: 6.0+ (Local instance on port 6379).
- **Composer**: 2.6+.

---

## Local Setup

```bash
# 1. Clone & Enter
git clone <repo-url> eazwheels-mysql
cd eazwheels-mysql

# 2. Dependencies
composer install
npm install && npm run build

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Configure Database in .env
# Set DB_DATABASE=eazwheels_db, DB_USERNAME, DB_PASSWORD
# Verify REDIS_HOST=127.0.0.1, REDIS_PORT=6379

# 5. Run Database Migrations (Pure SQL)
php artisan migrate

# 6. Execute Test Suite
php artisan test