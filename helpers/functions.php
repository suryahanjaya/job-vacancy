<?php
/**
 * Helper Functions
 */

/**
 * Redirect to a URL
 */
function redirect($url)
{
    header("Location: $url");
    exit;
}

/**
 * Set flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current user role
 */
function getUserRole()
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Get current user ID
 */
function getUserId()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user name
 */
function getUserName()
{
    return $_SESSION['user_name'] ?? 'Guest';
}

/**
 * Sanitize output
 */
function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function csrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrf()
{
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * CSRF hidden input field
 */
function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Render a view with layout
 */
function view($viewPath, $data = [], $layout = 'main')
{
    // Make requestUri available in views for nav active states
    global $requestUri;
    $data['requestUri'] = $requestUri ?? '/';

    extract($data);
    $contentFile = APP_ROOT . '/views/' . $viewPath . '.php';

    if (!file_exists($contentFile)) {
        die("View not found: $viewPath");
    }

    ob_start();
    require $contentFile;
    $content = ob_get_clean();

    if ($layout) {
        require APP_ROOT . '/views/layouts/' . $layout . '.php';
    } else {
        echo $content;
    }
}

/**
 * Get old form input value
 */
function old($key, $default = '')
{
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Set old input for form repopulation
 */
function setOldInput($data)
{
    $_SESSION['old_input'] = $data;
}

/**
 * Clear old input
 */
function clearOldInput()
{
    unset($_SESSION['old_input']);
}

/**
 * Get validation errors
 */
function getErrors()
{
    $errors = $_SESSION['validation_errors'] ?? [];
    unset($_SESSION['validation_errors']);
    return $errors;
}

/**
 * Set validation errors
 */
function setErrors($errors)
{
    $_SESSION['validation_errors'] = $errors;
}

/**
 * Format date
 */
function formatDate($date)
{
    return date('M d, Y', strtotime($date));
}

/**
 * Time ago format
 */
function timeAgo($datetime)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0)
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0)
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0)
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0)
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0)
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

/**
 * Truncate text
 */
function truncate($text, $length = 150)
{
    if (strlen($text) <= $length)
        return $text;
    return substr($text, 0, $length) . '...';
}
