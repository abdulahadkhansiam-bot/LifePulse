# LifePulse - Blood Donation Coordination Platform

[![Course](https://img.shields.io/badge/Course-CSE470%20Software%20Engineering-blue)](https://github.com/abdulahadkhansiam-bot/LifePulse)
[![Framework](https://img.shields.io/badge/Framework-Laravel%2011%20(MVC)-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%2F%208.3-indigo)](https://php.net)
[![Database](https://img.shields.io/badge/Database-MySQL%208.0-orange)](https://mysql.com)

A web-based centralized healthcare coordination platform designed to bridge the communication gap between emergency blood requesters, regional voluntary blood donors, and hospital blood banks.

---

## 👥 Project Information & Team Members

- **Course:** CSE470: Software Engineering
- **Section:** 12
- **Faculty:** SOSB

| Student ID | Student Name | Role / Domain Responsibility | Assigned Features (SRS) |
|---|---|---|---|
| **23301320** | **Rifaat Nuha** | **Donor Experience, Eligibility & Retention** | **F-01, F-02, F-09, F-12, F-13** |
| 23101011 | Md Abdul Ahad Khan Siam | Emergency Coordination & Donor Matching | F-03, F-04, F-05, F-06, F-15 |
| 22299053 | Mashud Hasan | Blood Bank Operations & Clinical Safety | F-07, F-08, F-10, F-11, F-20 |
| 23201674 | Reehay Sabah Samama | Governance, Security, Analytics & KYC | F-14, F-16, F-17, F-18, F-19 |

---

## 🏗️ Architectural Overview (MVC Pattern)

LifePulse adheres strictly to the **Model-View-Controller (MVC)** architectural pattern in full compliance with the CSE470 Project Rulebook:
- **Models (`app/Models/`):** Encapsulate schemas, Eloquent ORM relationships, and clinical business domain rules.
- **Views (`resources/views/`):** Server-rendered Blade templates (pure presentation; zero business logic or raw SQL).
- **Controllers (`app/Http/Controllers/`):** Handle routing, request validation via Form Requests, and domain coordination.

---

## 🚀 Local Setup Instructions

### Prerequisites
1. **PHP >= 8.2** (with extensions: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `zip`, `fileinfo`)
2. **Composer >= 2.6**
3. **Node.js >= 20.x & npm**
4. **MySQL >= 8.0** (e.g. via XAMPP)
5. **Git >= 2.40**

### 1. Clone & Navigate
```bash
git clone https://github.com/abdulahadkhansiam-bot/LifePulse.git
cd LifePulse
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database parameters:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lifepulse
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration
Ensure MySQL is running (e.g. in XAMPP) and the `lifepulse` database is created:
```sql
CREATE DATABASE IF NOT EXISTS lifepulse;
```
Then run migrations:
```bash
php artisan migrate
```

### 5. Build Assets & Start Development Server
```bash
npm run build
php artisan serve
```
Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.

---

## 📋 Git & Collaboration Rules (Course Rulebook Compliance)

1. **Continuous Commits:** Every team member must make descriptive, regular commits matching their assigned Feature IDs (e.g. `feat(F-01): implement donor health evaluation interval math`).
2. **Branching Strategy:** Work on dedicated feature branches (e.g. `feature/f01-donor-eligibility`) before merging into `main`.
3. **Never Commit Secrets:** `.env` is strictly git-ignored; all configuration keys belong in `.env.example`.
4. **Zero Cross-Impact:** Keep domain models and controllers modular so features do not break teammate workflows.
