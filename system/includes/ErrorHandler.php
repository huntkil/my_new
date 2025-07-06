<?php
class ErrorHandler {
    private static $instance = null;
    private $logFile;
    
    private function __construct() {
        $this->logFile = __DIR__ . '/../../config/logs/error.log';
        $this->setupErrorHandling();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function setupErrorHandling() {
        // Set custom error handler
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
    
    public function handleError($severity, $message, $file, $line) {
        $errorLog = [
            'type' => 'Error',
            'severity' => $severity,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'timestamp' => date('Y-m-d H:i:s'),
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        $this->logError($errorLog);
        
        if (IS_LOCAL) {
            echo "<div style='background: #ffebee; color: #c62828; padding: 10px; margin: 10px; border-left: 4px solid #c62828;'>";
            echo "<strong>Error:</strong> {$message}<br>";
            echo "<strong>File:</strong> {$file}<br>";
            echo "<strong>Line:</strong> {$line}<br>";
            echo "</div>";
        }
        
        return true;
    }
    
    public function handleException($exception) {
        $errorLog = [
            'type' => 'Exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s'),
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        $this->logError($errorLog);
        
        if (IS_LOCAL) {
            echo "<div style='background: #ffebee; color: #c62828; padding: 10px; margin: 10px; border-left: 4px solid #c62828;'>";
            echo "<strong>Exception:</strong> {$exception->getMessage()}<br>";
            echo "<strong>File:</strong> {$exception->getFile()}<br>";
            echo "<strong>Line:</strong> {$exception->getLine()}<br>";
            echo "<strong>Trace:</strong><pre>{$exception->getTraceAsString()}</pre>";
            echo "</div>";
        } else {
            http_response_code(500);
            echo "An error occurred. Please try again later.";
        }
        
        exit;
    }
    
    public function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $errorLog = [
                'type' => 'Fatal Error',
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'timestamp' => date('Y-m-d H:i:s'),
                'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ];
            
            $this->logError($errorLog);
            
            if (!IS_LOCAL) {
                http_response_code(500);
                echo "A fatal error occurred. Please try again later.";
            }
        }
    }
    
    private function logError($errorLog) {
        $logEntry = sprintf(
            "[%s] %s: %s in %s:%d\n",
            $errorLog['timestamp'],
            $errorLog['type'],
            $errorLog['message'],
            $errorLog['file'],
            $errorLog['line']
        );
        
        if (isset($errorLog['trace'])) {
            $logEntry .= "Stack trace:\n" . $errorLog['trace'] . "\n";
        }
        
        $logEntry .= "User IP: " . $errorLog['user_ip'] . "\n";
        $logEntry .= "User Agent: " . $errorLog['user_agent'] . "\n";
        $logEntry .= str_repeat('-', 80) . "\n";
        
        // Ensure log directory exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public function logCustomError($message, $context = []) {
        $errorLog = [
            'type' => 'Custom',
            'message' => $message,
            'context' => json_encode($context),
            'timestamp' => date('Y-m-d H:i:s'),
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        $this->logError($errorLog);
    }
    
    // Prevent cloning
    public function __clone() {
        throw new Exception("Cannot clone singleton");
    }
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Initialize error handler
ErrorHandler::getInstance();
?> 