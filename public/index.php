<?php
/**
 * Front Controller - Entry point for all requests
 * Run with: php -S localhost:8000 -t public
 */

session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set default time zone for consistency

// Load config FIRST (without APP_ROOT)
require_once __DIR__ . '/../config/config.php';

// Load core files
// require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/helpers/functions.php';
require_once APP_ROOT . '/helpers/Validator.php';
require_once APP_ROOT . '/middleware/AuthMiddleware.php';

// Simple Router
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove trailing slash
$requestUri = rtrim($requestUri, '/') ?: '/';

// Load routes
require_once APP_ROOT . '/routes/web.php';

// Try to match route
$matched = Router::dispatch($requestUri, $requestMethod);

if (!$matched) {
    http_response_code(404);
    require APP_ROOT . '/views/errors/404.php';
}
