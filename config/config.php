<?php
/**
 * Application Configuration
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_vacancy_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'JobConnect');
define('APP_URL', 'http://localhost:8000');
define('APP_ROOT', dirname(__DIR__));

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Pagination
define('ITEMS_PER_PAGE', 10);

// Max skills per job
define('MAX_SKILLS_PER_JOB', 5);
