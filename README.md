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

| Team Member | Student ID | Student Name | Role / Domain Responsibility | Assigned Features (SRS) |
|---|---|---|---|---|
| **Member 1** | 23101011 | Md Abdul Ahad Khan Siam | Compatibility, Inventory & Appointment Services | R4, R7, R9, R18, R12 |
| **Member 2** | **23301320** | **Rifaat Nuha** | **Donor Experience, Outreach & Automated Alerts** | **R1, R8, R5, R15, R17** |
| **Member 3** | 23201674 | Reehay Sabah Samama | Emergency Escalation, Verification & Analytics | R6, R2, R19, R13, R3 |
| **Member 4** | 22299053 | Mashud Hasan | Blood Allocation, Clinical Screening & Safety | R11, R20, R14, R16, R10 |

### 📌 Assigned Feature Division by Member

#### Member 1 — Md Abdul Ahad Khan Siam (23101011)
- **R4:** Biological Compatibility Match *(Hard, Sprint 2)*
- **R7:** Inventory Stock Management *(Medium-Hard, Sprint 1)*
- **R9:** Appointment Booking *(Medium, Sprint 2)*
- **R18:** Regulatory Action Auditing *(Medium, Sprint 4)*
- **R12:** Cooldown Reminders *(Easy, Sprint 3)*

#### Member 2 — Rifaat Nuha (23301320)
- **R5:** Batch Outreach Management *(Hard, Sprint 3)*
- **R15:** Coordinate Proximity Search *(Medium-Hard, Sprint 2)*
- **R8:** Automated Expiry Checks *(Medium, Sprint 3)*
- **R17:** User Notification Center *(Medium, Sprint 4)*
- **R1:** Donor Health Evaluation *(Easy, Sprint 1)*

#### Member 3 — Reehay Sabah Samama (23201674)
- **R6:** Emergency Escalation *(Hard, Sprint 3)*
- **R2:** Milestone & Card Generation *(Medium-Hard, Sprint 4)*
- **R19:** Institutional Verification *(Medium, Sprint 2)*
- **R13:** Donor Reliability Score *(Medium, Sprint 4)*
- **R3:** Blood Request Tracking *(Easy, Sprint 1)*

#### Member 4 — Mashud Hasan (22299053)
- **R11:** Blood Unit Allocation *(Hard, Sprint 3)*
- **R20:** Community Event Sourcing *(Medium-Hard, Sprint 3)*
- **R14:** Spam and Fraud Prevention *(Medium, Sprint 4)*
- **R16:** Performance Analytics *(Medium, Sprint 4)*
- **R10:** Clinical Donor Screening *(Easy, Sprint 2)*

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
