    <?php
    // config.php
    // Only set headers if running in web context
    if (isset($_SERVER['REQUEST_METHOD'])) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    // Load environment variables from .env file
    function loadEnv($path) {
        if (!file_exists($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
        return true;
    }

    // Load .env file from project root
    $envPath = __DIR__ . '/../.env';
    loadEnv($envPath);

    // Use your existing db_connection.php
    require_once 'db_connection.php';

    // Start session if not already started (only in web context)
    if (isset($_SERVER['REQUEST_METHOD']) && session_status() == PHP_SESSION_NONE) {
        // Set secure session parameters (from your auth_check.php)
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
    }

    // Error reporting for debugging (remove in production)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ?>