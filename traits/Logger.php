<?php
// traits/Logger.php

trait Logger {
    /**
     * Logs a message to a file.
     * @param string $message The message to log.
     * @param string $level The log level (e.g., 'INFO', 'WARNING', 'ERROR').
     */
    protected function log($message, $level = 'INFO') {
        $logFile = __DIR__ . '/../logs/app.log'; // Path to your log file
        // Ensure the logs directory exists
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level]: $message" . PHP_EOL;
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
