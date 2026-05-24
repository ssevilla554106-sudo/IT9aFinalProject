# Laravel Event Management System - Setup Guide

## Requirements
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL (via XAMPP/WAMP/phpMyAdmin)
- Laravel 10+

---

## Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel event-management
cd event-management
```

## Step 2: Install Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
```

## Step 3: Configure Database (.env)

Open `.env` and update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=root
DB_PASSWORD=
```

> Create a database named `event_management` in phpMyAdmin first.

## Step 4: Copy All Files

Copy each file from this package into your Laravel project following the directory structure shown in each file's header comment.

## Step 5: Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

## Step 6: Storage Link (for event images)

```bash
php artisan storage:link
```

## Step 7: Run the App

```bash
php artisan serve
npm run dev
```

Visit: http://localhost:8000

---

## Default Admin Account (after seeding)
- Email: admin@events.com
- Password: password

---

## Features
- User Authentication (Register/Login/Logout) via Breeze
- Dashboard with stats
- Create, Edit, Delete Events
- Event categories & status management
- Register/Unregister for events
- Admin panel for managing all events
- Search & filter events
- Responsive, professional UI
