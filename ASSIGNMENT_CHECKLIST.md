# Job Vacancy Management & Job Search System

Audit checklist based on the current repository contents.

Repository inspected on: 2026-05-04

## Status Legend

- `OK` = requirement is implemented in code
- `PARTIAL` = partially implemented or has a gap
- `MISSING` = not found in the current codebase

## Tech Stack

### Backend

- PHP 8.x
- Custom MVC architecture
- PDO for MySQL access
- Session-based authentication
- Server-side validation with custom `Validator`
- Custom routing layer

### Database

- MySQL / MariaDB
- Relational schema with foreign keys
- Lookup/reference tables for selectable fields
- Many-to-many bridge table for job skills
- Full-text index for job description search

### Frontend

- Server-rendered PHP views
- Vanilla JavaScript
- CSS3 custom styling
- Responsive UI
- Chart.js loaded from CDN for admin dashboard charts

### Tooling / Runtime

- Git version control
- PHP built-in development server
- SQL schema and seed files in `database/`

## Features Present in This Repository

### Authentication and Access Control

- User registration and login
- Password hashing and verification
- Role-based access control for employer, job seeker, and admin
- Guest-protected routes
- CSRF token generation and validation

### Employer Features

- Create job vacancy
- Edit job vacancy
- Delete job vacancy
- Activate / deactivate job vacancy
- View own postings
- Structured location and reference-driven form fields
- Dynamic required skills form

### Job Seeker Features

- Search jobs by multiple filters
- Keyword search
- Sort by newest, oldest, salary, and title
- View job detail page
- Read-only access

### Administrator Features

- Manage job postings
- Manage reference data
- Manage users
- View dashboard statistics
- View chart data

### Data / Schema Features

- Normalized lookup tables
- Structured location tables
- Skills many-to-many relation
- Salary range lookup
- Proficiency level lookup
- Degree level lookup
- Experience level lookup

## Assignment Checklist

### 1. Assignment Overview

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Employers can create and manage job postings | OK | [JobController](#employer-job-vacancy-management) | Implemented through employer CRUD routes and model methods |
| Job seekers can search and view vacancies | OK | [JobSearchController](#job-seeker-search-and-view) | Search page and detail page are present |
| CV creation and CV search are excluded | OK | [README](#project-readme) | Not part of this repo’s job-vacancy flow |

### 2. Learning Outcomes

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Structured job vacancy data models | OK | [Schema](#database-schema) | Vacancy fields are normalized and reference-based |
| Dynamic job posting forms | OK | [Job form](#dynamic-job-form) | Skill rows and cascading selects are dynamic |
| Controlled vocabularies / lookup tables | OK | [Reference model](#reference-data-management) | Reference tables drive select inputs |
| Multi-criteria job search | OK | [JobVacancyModel search](#job-search-logic) | Filters are combined with AND logic |
| Employer-centric CRUD workflows | OK | [Employer routes](#employer-job-vacancy-management) | Create, edit, delete, toggle, view own jobs |
| Optimized schema for searching | OK | [Schema](#database-schema) | Indexes and full-text index are present |

### 3. System Roles

| Role | Status | Proof | Notes |
|---|---:|---|---|
| Employer | OK | [Auth middleware](#authentication-and-rbac) | Employer-only routes are protected |
| Job Seeker | OK | [Job search](#job-seeker-search-and-view) | Public search and detail view only |
| Administrator | OK | [Admin controller](#administrator-features) | Admin management screens are present |

### 4. Functional Requirements

#### 4.1 Employer Features

##### 4.1.1 Authentication

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Employers must register and log in securely | OK | [AuthController](#authentication-and-rbac) | Registration and login are implemented |
| Role-based access control is required | OK | [AuthMiddleware](#authentication-and-rbac) | Employer routes are role-protected |

Proof:

```php
// controllers/AuthController.php
$userId = $this->getUserModel()->create($data);
$_SESSION['user_role'] = $data['role'];
```

```php
// models/UserModel.php
password_hash($data['password'], PASSWORD_DEFAULT)
```

```php
// middleware/AuthMiddleware.php
if (getUserRole() !== $role) {
    redirect('/');
}
```

##### 4.1.2 Job Vacancy Creation

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Structured form | OK | [Dynamic job form](#dynamic-job-form) | Form is split into sections A-F |
| Normalized and searchable fields | OK | [Schema](#database-schema) | Uses foreign keys instead of plain text location storage |
| Basic job information | OK | [Dynamic job form](#dynamic-job-form) | Title, category, employment type, industry, level, openings |
| Structured location, not single text | OK | [Schema](#database-schema) | Country, city, district, arrangement are separate columns |
| Salary range and salary type | OK | [Dynamic job form](#dynamic-job-form) | Select-based inputs |
| Benefits free text | OK | [Dynamic job form](#dynamic-job-form) | Textarea input |
| Job description free text | OK | [Dynamic job form](#dynamic-job-form) | Responsibilities, qualifications, preferred skills, notes |
| Required skills from predefined list | OK | [Dynamic job form](#dynamic-job-form) | Skill list comes from reference table |
| Max 5 required skills | OK | [Validation](#server-side-validation) | Enforced client-side and server-side |
| Minimum degree and experience | OK | [Dynamic job form](#dynamic-job-form) | Reference-driven selects |

Proof:

```php
// database/schema.sql
country_id INT NOT NULL,
city_id INT NOT NULL,
district_id INT DEFAULT NULL,
work_arrangement_id INT NOT NULL
```

```php
// controllers/JobController.php
if (empty($data['skills'])) {
    $errors['skills'] = 'At least one required skill must be specified.';
}
```

```php
// controllers/JobController.php
if (count($data['skills']) > MAX_SKILLS_PER_JOB) {
    $errors['skills'] = 'Maximum ' . MAX_SKILLS_PER_JOB . ' skills allowed.';
}
```

##### 4.1.3 Job Vacancy Management

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Create job postings | OK | [JobController](#employer-job-vacancy-management) | Store method exists |
| Edit job postings | OK | [JobController](#employer-job-vacancy-management) | Update method exists |
| Delete job postings | OK | [JobController](#employer-job-vacancy-management) | Delete method exists |
| Activate / deactivate postings | OK | [JobController](#employer-job-vacancy-management) | Toggle method exists |
| View own job postings | OK | [Employer job list](#employer-job-vacancy-management) | Employer index page exists |
| Cannot edit other employers' jobs | OK | [Ownership check](#employer-job-vacancy-management) | Checked by `employer_id` |

Proof:

```php
// controllers/JobController.php
if (!$job || $job['employer_id'] != getUserId()) {
    redirect('/employer/jobs');
}
```

```php
// controllers/JobController.php
$this->jobModel->delete($id, getUserId());
```

```php
// controllers/JobController.php
$this->jobModel->toggleActive($id, getUserId());
```

#### 4.2 Job Seeker Features

##### 4.2.1 Search Job Vacancies by Multiple Criteria

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Keyword search | OK | [Job search logic](#job-search-logic) | Matches title and description fields |
| Job category | OK | [Job search logic](#job-search-logic) | Filter by `job_category_id` |
| Location by country and city | OK | [Job search logic](#job-search-logic) | Separate filters exist |
| Required skills | OK | [Job search logic](#job-search-logic) | Uses EXISTS subquery |
| Employment type | OK | [Job search logic](#job-search-logic) | Filter by `employment_type_id` |
| Job level | OK | [Job search logic](#job-search-logic) | Filter by `job_level_id` |
| Salary range | OK | [Job search logic](#job-search-logic) | Filter by `salary_range_id` |
| Work arrangement | OK | [Job search logic](#job-search-logic) | Filter by `work_arrangement_id` |

##### 4.2.2 Combined Filters

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Multiple filters combinable with AND logic | OK | [Job search logic](#job-search-logic) | Conditions are joined with `AND` |

##### 4.2.3 Sorting Options

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Most recently posted jobs | OK | [Job search logic](#job-search-logic) | Default sort is newest |
| Salary ascending / descending | OK | [Job search logic](#job-search-logic) | `salary_asc`, `salary_desc` |
| Job title alphabetical | OK | [Job search logic](#job-search-logic) | `title_asc`, `title_desc` |

##### 4.2.4 Job Vacancy Viewing

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Job title | OK | [Job detail page](#job-seeker-search-and-view) | Shown at top of detail page |
| Employer information | OK | [Job detail page](#job-seeker-search-and-view) | Employer name and email shown |
| Location | OK | [Job detail page](#job-seeker-search-and-view) | Country, city, district shown |
| Salary range | OK | [Job detail page](#job-seeker-search-and-view) | Salary label and type shown |
| Required skills | OK | [Job detail page](#job-seeker-search-and-view) | Skill tags with proficiency shown |
| Job description | OK | [Job detail page](#job-seeker-search-and-view) | Responsibilities and qualifications shown |
| Posting date | OK | [Job detail page](#job-seeker-search-and-view) | Created date displayed |
| No job application workflow | OK | [Routes](#routes-and-navigation) | No apply route found |

#### 4.3 Administrator Features

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Manage job vacancies | OK | [AdminController](#administrator-features) | Admin jobs page exists |
| Manage job categories | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage job titles | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage skills | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage industries | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage locations | OK | [Reference tables](#reference-data-management) | Countries, cities, districts present |
| Manage employment types | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage job levels | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Manage salary ranges | OK | [Reference tables](#reference-data-management) | Table is in whitelist |
| Remove inappropriate or invalid postings | OK | [AdminController](#administrator-features) | Delete job exists |

Proof:

```php
// controllers/AdminController.php
public function referenceList($table)
public function referenceCreate($table)
public function referenceDelete($table, $id)
```

```php
// routes/web.php
Router::get('/admin/jobs', 'AdminController@jobs', [['AuthMiddleware', 'admin']]);
Router::post('/admin/reference/{table}/create', 'AdminController@referenceCreate', [['AuthMiddleware', 'admin']]);
```

### 5. Database & Data Requirements

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| Relational database | OK | [Database schema](#database-schema) | MySQL schema with foreign keys |
| Fully normalized vacancy data | OK | [Database schema](#database-schema) | Selectable fields are normalized |
| Reference tables for all selectable fields | OK | [Reference data management](#reference-data-management) | Lookup tables exist for major selects |
| Many-to-many between vacancies and skills | OK | [Database schema](#database-schema) | Bridge table `job_vacancy_skills` |
| No plain text / JSON for requirements or location | OK | [Database schema](#database-schema) | Structured foreign-key fields are used |

Proof:

```sql
-- database/schema.sql
CREATE TABLE job_vacancies (
    country_id INT NOT NULL,
    city_id INT NOT NULL,
    district_id INT DEFAULT NULL,
    work_arrangement_id INT NOT NULL,
    responsibilities TEXT NOT NULL,
    qualifications TEXT NOT NULL
);
```

```sql
-- database/schema.sql
CREATE TABLE job_vacancy_skills (
    job_vacancy_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level_id INT NOT NULL
);
```

```sql
-- database/schema.sql
FULLTEXT INDEX idx_fulltext_desc (responsibilities, qualifications, preferred_skills_text, additional_notes)
```

### 6. Technical Requirements

| Requirement | Status | Proof | Notes |
|---|---:|---|---|
| MVC architecture or equivalent | OK | [Front controller](#project-structure) | Controllers, models, views are separated |
| Dynamic forms for required skills | OK | [Dynamic job form](#dynamic-job-form) | JS adds and removes skill rows |
| Server-side validation | OK | [Validation](#server-side-validation) | Custom validator and job validation exist |
| Role-based access control | OK | [Authentication and RBAC](#authentication-and-rbac) | Middleware enforces role routes |
| Responsive UI | OK | [Layout](#frontend-layout-and-assets) | View layer includes mobile nav and responsive CSS |
| Version control using Git | OK | [Repository](#project-readme) | `.git` directory exists in repo |

### 7. Deliverables

| Item | Status | Proof | Notes |
|---|---:|---|---|
| Source code backend + frontend | OK | Repository tree | Controllers, models, views, public assets exist |
| Database design / SQL schema | OK | `database/schema.sql` | Schema file is present |
| Technical report | PARTIAL | `README.md`, `Analisis_Persyaratan.txt` | No formal report file found beyond notes |
| Demo video | MISSING | Not found | No video file in repo |
| Git repository with commit history | OK | `.git/` present | Repo is under Git |

### 8. Assessment Criteria Support

| Criterion | Status | Evidence |
|---|---:|---|
| Job vacancy creation & management | OK | Employer CRUD and structured form |
| Job search & filtering | OK | Multi-filter search and sorting |
| Database design & normalization | OK | Lookup tables and bridge table |
| Code structure & architecture | OK | MVC separation |
| UI & usability | OK | Responsive pages and dashboard UI |
| Documentation & demo | PARTIAL | README and analysis notes exist |

### 9. Constraints & Notes

| Constraint | Status | Proof | Notes |
|---|---:|---|---|
| No CV creation / CV search | OK | Repository scope | No CV module found |
| No job application workflow | OK | Routes and views | No apply route/view found |
| No matching / recommendation algorithm | OK | Search model only | No recommendation logic found |
| Maximum 5 required skills per job | OK | Config + validation | Enforced in JS and PHP |
| Employers manage only their own postings | OK | Ownership checks | Checked in controller/model |

## Evidence by Area

### Authentication and RBAC

Files:

- `controllers/AuthController.php`
- `models/UserModel.php`
- `middleware/AuthMiddleware.php`

Proof snippets:

```php
// controllers/AuthController.php
$_SESSION['user_role'] = $user['role'];
```

```php
// models/UserModel.php
password_hash($data['password'], PASSWORD_DEFAULT)
```

```php
// middleware/AuthMiddleware.php
if (getUserRole() !== $role) {
    redirect('/');
}
```

Notes:

- Login and register are implemented.
- Passwords are hashed.
- Role-based route protection exists.
- Guest routes are also handled.

### Employer Job Vacancy Management

Files:

- `controllers/JobController.php`
- `models/JobVacancyModel.php`
- `views/employer/jobs/create.php`
- `views/employer/jobs/edit.php`
- `views/employer/jobs/index.php`

Proof snippets:

```php
// controllers/JobController.php
$job = $this->jobModel->findById($id);
if (!$job || $job['employer_id'] != getUserId()) {
    redirect('/employer/jobs');
}
```

```php
// controllers/JobController.php
$data['employer_id'] = getUserId();
```

```php
// models/JobVacancyModel.php
public function create($data)
public function update($id, $data)
public function delete($id, $employerId = null)
public function toggleActive($id, $employerId = null)
```

```php
// views/employer/jobs/create.php
<select name="country_id" id="countrySelect" required onchange="loadCities(this.value)">
<select name="skills[0][skill_id]" class="form-control skill-select" required>
```

Notes:

- Create/edit/delete/toggle/view own postings are present.
- Ownership protection is implemented in the controller and model.
- Form is split into structured sections and uses reference tables.

### Dynamic Job Form

Files:

- `views/employer/jobs/create.php`
- `views/employer/jobs/edit.php`
- `public/js/app.js`

Proof snippets:

```javascript
// public/js/app.js
function addSkillRow() {
    if (currentRows.length >= MAX_SKILLS) return;
}
```

```javascript
// public/js/app.js
function loadCities(countryId) { fetch(`/api/cities?country_id=${countryId}`) }
function loadDistricts(cityId) { fetch(`/api/districts?city_id=${cityId}`) }
function loadJobTitles(categoryId) { fetch(`/api/job-titles?category_id=${categoryId}`) }
```

```php
// views/employer/jobs/create.php
const MAX_SKILLS = <?= MAX_SKILLS_PER_JOB ?>;
const skillOptions = <?= json_encode($skills) ?>;
```

Notes:

- Required skills section is dynamic.
- Country, city, district, and job title use cascading selects.
- Client-side limit matches backend max skill limit.

### Job Search Logic

Files:

- `controllers/JobSearchController.php`
- `models/JobVacancyModel.php`
- `views/jobseeker/search.php`
- `views/jobseeker/job-detail.php`

Proof snippets:

```php
// controllers/JobSearchController.php
$filters = [
    'keyword' => trim($_GET['keyword'] ?? ''),
    'job_category_id' => intval($_GET['job_category_id'] ?? 0),
    'country_id' => intval($_GET['country_id'] ?? 0),
];
```

```php
// models/JobVacancyModel.php
$conditions[] = "(jt.name LIKE ? OR jv.responsibilities LIKE ? OR jv.qualifications LIKE ?)";
$conditions[] = "EXISTS (SELECT 1 FROM job_vacancy_skills jvs WHERE jvs.job_vacancy_id = jv.id AND jvs.skill_id = ?)";
$whereClause = implode(' AND ', $conditions);
```

```php
// models/JobVacancyModel.php
case 'salary_asc':
case 'salary_desc':
case 'title_asc':
case 'title_desc':
```

```php
// views/jobseeker/job-detail.php
<?= h($job['job_title_name']) ?>
<?= h($job['salary_range_label']) ?>
<?= h($job['employer_email']) ?>
```

Notes:

- Search supports keyword, category, location, skill, employment type, level, salary, industry, and arrangement.
- Filters are combined using `AND`.
- Detail page displays employer, location, salary, skills, description, and posting date.

### Admin Features

Files:

- `controllers/AdminController.php`
- `views/admin/jobs.php`
- `views/admin/reference-list.php`
- `views/admin/users.php`

Proof snippets:

```php
// controllers/AdminController.php
public function jobs()
public function toggleJob($id)
public function deleteJob($id)
```

```php
// controllers/AdminController.php
public function referenceList($table)
public function referenceCreate($table)
public function referenceDelete($table, $id)
```

```php
// views/admin/reference-list.php
<form action="/admin/reference/<?= h($tableName) ?>/create" method="POST">
<form action="/admin/reference/<?= h($tableName) ?>/<?= $item['id'] ?>/delete" method="POST">
```

Notes:

- Admin can manage jobs, reference data, and users.
- Reference management is present for create and delete.
- I did not find an edit/update UI for reference data.

### Database Schema

Files:

- `database/schema.sql`
- `database/seed.sql`

Proof snippets:

```sql
-- database/schema.sql
CREATE TABLE job_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
```

```sql
-- database/schema.sql
CREATE TABLE job_vacancies (
    employer_id INT NOT NULL,
    job_title_id INT NOT NULL,
    job_category_id INT NOT NULL,
    country_id INT NOT NULL,
    city_id INT NOT NULL,
    district_id INT DEFAULT NULL
);
```

```sql
-- database/schema.sql
CREATE TABLE job_vacancy_skills (
    job_vacancy_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level_id INT NOT NULL
);
```

```sql
-- database/schema.sql
FULLTEXT INDEX idx_fulltext_desc (responsibilities, qualifications, preferred_skills_text, additional_notes)
```

Notes:

- Lookup tables exist for categories, titles, employment types, industries, job levels, salary ranges, salary types, skills, proficiency levels, countries, cities, districts, degree levels, experience levels, and work arrangements.
- Job vacancy data is normalized and reference-driven.
- Skill relation uses a bridge table.
- Description fields use text columns; no JSON storage was found for these requirements.

### Reference Data Management

Files:

- `models/ReferenceModel.php`
- `controllers/AdminController.php`
- `views/admin/reference-list.php`

Proof snippets:

```php
// models/ReferenceModel.php
private $allowedTables = [
    'job_categories',
    'job_titles',
    'employment_types',
    'industries'
];
```

```php
// models/ReferenceModel.php
public function getCitiesByCountry($countryId)
public function getDistrictsByCity($cityId)
public function getJobTitlesByCategory($categoryId)
```

```php
// controllers/AdminController.php
if ($table === 'salary_ranges') {
    $data['label'] = $name;
}
```

Notes:

- Reference tables are centrally managed.
- Cascading reference data is supported.
- Table whitelist prevents arbitrary table access.

### Project Structure

Files:

- `public/index.php`
- `routes/Router.php`

Proof snippets:

```php
// public/index.php
$matched = Router::dispatch($requestUri, $requestMethod);
```

```php
// routes/Router.php
public static function get($path, $handler, $middleware = [])
public static function post($path, $handler, $middleware = [])
```

Notes:

- The application uses a front controller.
- Routing is custom and controller-based.
- Middleware is executed during dispatch.

### Frontend Layout and Assets

Files:

- `views/layouts/main.php`
- `public/css/style.css`
- `public/js/app.js`

Proof snippets:

```php
// views/layouts/main.php
<link rel="stylesheet" href="/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/app.js"></script>
```

```php
// views/layouts/main.php
<?php if (isLoggedIn() && getUserRole() === 'employer'): ?>
<?php if (isLoggedIn() && getUserRole() === 'admin'): ?>
```

Notes:

- Navigation changes based on role.
- Static assets are separated under `public/`.
- The UI is responsive and script-driven.

### Routes and Navigation

Files:

- `routes/web.php`
- `routes/Router.php`
- `views/layouts/main.php`
- `public/index.php`

Proof snippets:

```php
// routes/web.php
Router::get('/jobs', 'JobSearchController@index');
Router::get('/jobs/{id}', 'JobSearchController@show');
```

```php
// routes/web.php
Router::get('/employer/jobs/create', 'JobController@create', [['AuthMiddleware', 'employer']]);
Router::post('/employer/jobs/store', 'JobController@store', [['AuthMiddleware', 'employer']]);
```

```php
// public/index.php
$matched = Router::dispatch($requestUri, $requestMethod);
```

```php
// views/layouts/main.php
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/app.js"></script>
```

Notes:

- The app uses a custom front controller and router.
- Navigation changes based on login role.
- Chart.js is loaded for admin analytics.

### Server-Side Validation

Files:

- `helpers/Validator.php`
- `controllers/JobController.php`
- `controllers/AuthController.php`

Proof snippets:

```php
// helpers/Validator.php
public function required($field, $label = null)
public function email($field, $label = null)
public function minLength($field, $min, $label = null)
```

```php
// controllers/JobController.php
if (!empty($data['skills']) && count($data['skills']) > MAX_SKILLS_PER_JOB) {
    $errors['skills'] = 'Maximum ' . MAX_SKILLS_PER_JOB . ' skills allowed.';
}
```

```php
// controllers/AuthController.php
if ($validator->fails()) {
    setErrors($validator->errors());
}
```

Notes:

- Server-side validation exists for auth and job posting.
- CSRF validation is used on POST actions.
- I also found a few GET state-changing routes, which is a security gap.

## Gaps Found During Inspection

These are not claimed as implemented because the code does not show them clearly.

| Gap | Status | Why it matters |
|---|---:|---|
| Admin chart endpoint protected by admin role | PARTIAL | Current route uses only auth, not admin role |
| Toggle routes use GET instead of POST | PARTIAL | CSRF / accidental state change risk |
| Reference table edit/update UI | MISSING | I found create and delete, but not edit/update screens |
| Formal technical report file | MISSING | Only README and analysis notes were found |
| Demo video file | MISSING | No video asset in repo |

## Short Conclusion

The repository already implements the main job vacancy management and job search workflow required by the assignment:

- employer authentication and CRUD
- structured vacancy creation
- multi-criteria job search and sorting
- admin job/reference management
- relational schema with lookup tables and skill mapping

The main gaps are documentation completeness and a few security / UX hardening points:

- admin chart endpoint should be restricted to admin
- state-changing actions should use POST with CSRF
- reference table edit/update is not visible in the current codebase

## Project Readme

Relevant repository files:

- `README.md`
- `Analisis_Persyaratan.txt`
- `database/schema.sql`
- `database/seed.sql`

Proof snippet from readme:

```md
Custom MVC architecture
Normalized database with 14+ reference tables
Many-to-many: Jobs ↔ Skills (max 5)
```
