<?php
/**
 * Web Routes
 */

require_once APP_ROOT . '/routes/Router.php';

// ==========================================
// Public Routes
// ==========================================

// Home / Landing
Router::get('/', 'HomeController@index');

// Job Search (public)
Router::get('/jobs', 'JobSearchController@index');
Router::get('/jobs/{id}', 'JobSearchController@show');

// ==========================================
// Auth Routes (Guest only)
// ==========================================

Router::get('/login', 'AuthController@showLogin');
Router::post('/login', 'AuthController@login');
Router::get('/register', 'AuthController@showRegister');
Router::post('/register', 'AuthController@register');
Router::get('/logout', 'AuthController@logout');

// ==========================================
// Dashboard (Authenticated)
// ==========================================

Router::get('/dashboard', 'DashboardController@index', ['AuthMiddleware']);

// ==========================================
// Admin Chart Data API
// ==========================================
Router::get('/admin/chart-data', 'DashboardController@chartData', ['AuthMiddleware']);

// ==========================================
// Profile Routes
// ==========================================

Router::get('/profile', 'UserController@profile', ['AuthMiddleware']);
Router::post('/profile/update-field', 'UserController@updateField', ['AuthMiddleware']);
Router::post('/profile/deactivate', 'UserController@deactivateAccount', ['AuthMiddleware']);
Router::post('/profile/update-password', 'UserController@updatePassword', ['AuthMiddleware']);

// ==========================================
// Employer Routes
// ==========================================

Router::get('/employer/jobs', 'JobController@index', [['AuthMiddleware', 'employer']]);
Router::get('/employer/jobs/create', 'JobController@create', [['AuthMiddleware', 'employer']]);
Router::post('/employer/jobs/store', 'JobController@store', [['AuthMiddleware', 'employer']]);
Router::get('/employer/jobs/{id}', 'JobController@show', [['AuthMiddleware', 'employer']]);
Router::get('/employer/jobs/{id}/edit', 'JobController@edit', [['AuthMiddleware', 'employer']]);
Router::post('/employer/jobs/{id}/update', 'JobController@update', [['AuthMiddleware', 'employer']]);
Router::post('/employer/jobs/{id}/delete', 'JobController@delete', [['AuthMiddleware', 'employer']]);
Router::get('/employer/jobs/{id}/toggle', 'JobController@toggleActive', [['AuthMiddleware', 'employer']]);

// ==========================================
// Admin Routes
// ==========================================

Router::get('/admin/jobs', 'AdminController@jobs', [['AuthMiddleware', 'admin']]);
Router::get('/admin/jobs/{id}/toggle', 'AdminController@toggleJob', [['AuthMiddleware', 'admin']]);
Router::post('/admin/jobs/{id}/delete', 'AdminController@deleteJob', [['AuthMiddleware', 'admin']]);
Router::get('/admin/users', 'AdminController@users', [['AuthMiddleware', 'admin']]);
Router::get('/admin/users/{id}/toggle', 'AdminController@toggleUser', [['AuthMiddleware', 'admin']]);

// Reference table management
Router::get('/admin/reference/{table}', 'AdminController@referenceList', [['AuthMiddleware', 'admin']]);
Router::post('/admin/reference/{table}/create', 'AdminController@referenceCreate', [['AuthMiddleware', 'admin']]);
Router::post('/admin/reference/{table}/{id}/delete', 'AdminController@referenceDelete', [['AuthMiddleware', 'admin']]);

// ==========================================
// API Routes (AJAX)
// ==========================================

Router::get('/api/cities', 'ApiController@getCities');
Router::get('/api/districts', 'ApiController@getDistricts');
Router::get('/api/job-titles', 'ApiController@getJobTitles');


