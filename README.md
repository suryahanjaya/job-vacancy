# JobConnect — Job Vacancy Management & Job Search System

A full-featured job vacancy management system built with PHP + MySQL using custom MVC architecture.

## 🚀 Quick Start

### Prerequisites
- **PHP 8.0+** (with PDO MySQL extension)
- **MySQL 8.0+** (or MariaDB 10.5+)
- **Git** (for version control)

### 1. Database Setup

1. Start your MySQL server (XAMPP, WAMP, laragon, or standalone).

2. Create the database and tables:
```bash
mysql -u root -p < database/schema.sql
```

3. Insert seed data (reference tables + sample users):
```bash
mysql -u root -p < database/seed.sql
```

4. Update database credentials in `config/config.php` if needed:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_vacancy_system');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 2. Run the Application

Using PHP's built-in development server:
```bash
php -S localhost:8000 -t public
```

Then open **http://localhost:8000** in your browser.

### 3. Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@jobsystem.com | password |
| Employer | employer@techcorp.com | password |
| Job Seeker | seeker@email.com | password |

> **Note:** The seed file uses a bcrypt hash for "password". If you need different passwords, update the hash in `database/seed.sql`.

---

## 📁 Project Structure (MVC)

```
├── config/              # Database & app configuration
│   ├── config.php       # Constants & settings
│   └── database.php     # PDO singleton connection
├── controllers/         # Request handlers
│   ├── AuthController.php
│   ├── HomeController.php
│   ├── DashboardController.php
│   ├── JobController.php       # Employer CRUD
│   ├── JobSearchController.php # Job seeker search
│   ├── AdminController.php     # Admin panel
│   └── ApiController.php       # AJAX endpoints
├── models/              # Data access layer
│   ├── UserModel.php
│   ├── JobVacancyModel.php
│   └── ReferenceModel.php
├── views/               # HTML templates
│   ├── layouts/main.php
│   ├── auth/
│   ├── employer/
│   ├── jobseeker/
│   ├── admin/
│   └── errors/
├── public/              # Web root (entry point)
│   ├── index.php        # Front controller
│   ├── css/style.css
│   └── js/app.js
├── routes/
│   ├── Router.php       # Custom router
│   └── web.php          # Route definitions
├── helpers/
│   ├── functions.php    # Utility functions
│   └── Validator.php    # Server-side validation
├── middleware/
│   └── AuthMiddleware.php  # Auth & RBAC
└── database/
    ├── schema.sql       # Database schema
    └── seed.sql         # Reference data seeds
```

## ✅ Features Implemented

### Employer
- ✅ Registration & Login with role selection
- ✅ Create job vacancy with structured form (6 sections)
- ✅ Edit, delete, activate/deactivate job postings
- ✅ View own postings list with pagination
- ✅ Ownership protection (can only manage own jobs)

### Job Seeker
- ✅ Multi-criteria search (keyword, category, location, skills, etc.)
- ✅ Filters combinable with AND logic
- ✅ Sorting (date, salary, title)
- ✅ Job detail view with full information

### Admin
- ✅ System dashboard with statistics
- ✅ Manage all job postings (toggle/delete)
- ✅ Manage all 13 reference tables (CRUD)
- ✅ Manage users (activate/deactivate)

### Technical
- ✅ Custom MVC architecture
- ✅ Normalized database with 14+ reference tables
- ✅ Many-to-many: Jobs ↔ Skills (max 5)
- ✅ Cascading dropdowns (Country → City → District)
- ✅ Dynamic skill rows (add/remove)
- ✅ CSRF protection
- ✅ Server-side validation
- ✅ Role-based access control (RBAC)
- ✅ Responsive design (mobile-friendly)
- ✅ Full-text search support

## 🎨 Design

The UI uses a premium dark theme with:
- Modern Inter font from Google Fonts
- Gradient accents and glassmorphism effects
- Smooth micro-animations
- Fully responsive layout

## 📝 License

Academic project — HCMUT Web Programming course.
