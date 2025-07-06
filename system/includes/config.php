<?php
// Prevent multiple includes
if (defined('CONFIG_LOADED')) {
    return;
}
define('CONFIG_LOADED', true);

// Load credentials first
require_once __DIR__ . '/../../config/credentials/loader.php';

// Environment Detection
$isLocal = (
    $_SERVER['SERVER_NAME'] === 'localhost' ||
    $_SERVER['SERVER_NAME'] === '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], 'localhost') !== false
);

// Database Configuration from credentials
define('DB_TYPE', CREDENTIALS_DB_TYPE);
define('DB_FILE', CREDENTIALS_DB_FILE);
define('DB_HOST', CREDENTIALS_DB_HOST);
define('DB_USER', CREDENTIALS_DB_USER);
define('DB_PASS', CREDENTIALS_DB_PASS);
define('DB_NAME', CREDENTIALS_DB_NAME);

// Application URL
if ($isLocal) {
    define('APP_URL', 'http://localhost:8080');
} else {
    define('APP_URL', 'http://gukho.net');
}

// Application Configuration
define('APP_NAME', 'My Playground');
define('APP_VERSION', '1.0.0');
define('IS_LOCAL', $isLocal);

// Session Configuration (only if session not already started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_secure', 0); // HTTP only for now
    ini_set('session.cookie_samesite', 'Lax');
    session_name(CREDENTIALS_SESSION_NAME);
    session_start();
}

// Security Configuration from credentials
define('HASH_COST', CREDENTIALS_HASH_COST);

// API Keys from credentials
define('OPENAI_API_KEY', CREDENTIALS_OPENAI_API_KEY);
define('NEWS_API_KEY', CREDENTIALS_NEWS_API_KEY);

// Upload Configuration from credentials
define('UPLOAD_MAX_SIZE', CREDENTIALS_MAX_UPLOAD_SIZE);
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'doc', 'docx']);
define('UPLOAD_DIR', CREDENTIALS_UPLOAD_DIR);

// Error Handling
if ($isLocal) {
    // Development: Show errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    // Production: Log errors only
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../../config/logs/error.log');
}

// Database Settings
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// Auto-load essential classes
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/ErrorHandler.php';
require_once __DIR__ . '/Utils.php';
?> 