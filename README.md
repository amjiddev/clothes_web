# Laravel Project

## Installation

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup
Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Uni_project
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 5. Create Storage Link
```bash
php artisan storage:link
```

### 6. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Login Credentials

**Admin:**
```
Email: demo@demo.com
Password: demo
```

After login, you will be redirected to `/dashboard`

## Features
- Admin Dashboard
- User Management
- Role & Permission Management
- Responsive Design

---

**Version:** 1.0.0  
**Framework:** Laravel 9.x
