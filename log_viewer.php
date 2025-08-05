<?php

Route::get('/view-logs', function () {
    $logPath = storage_path('logs/laravel.log');
    
    if (!file_exists($logPath)) {
        return "No log file found at: $logPath";
    }
    
    $lines = file($logPath);
    $recentLines = array_slice($lines, -100); // Get last 100 lines
    
    $output = "<h2>Recent Log Entries (Last 100 lines)</h2>";
    $output .= "<pre style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; max-height: 600px; overflow-y: scroll;'>";
    
    foreach ($recentLines as $line) {
        // Color code different log levels
        if (strpos($line, 'ERROR') !== false) {
            $output .= "<span style='color: red;'>" . htmlspecialchars($line) . "</span>";
        } elseif (strpos($line, 'WARNING') !== false) {
            $output .= "<span style='color: orange;'>" . htmlspecialchars($line) . "</span>";
        } elseif (strpos($line, 'INFO') !== false) {
            $output .= "<span style='color: blue;'>" . htmlspecialchars($line) . "</span>";
        } else {
            $output .= htmlspecialchars($line);
        }
    }
    
    $output .= "</pre>";
    $output .= "<p><strong>Log file location:</strong> $logPath</p>";
    $output .= "<p><strong>Total file size:</strong> " . number_format(filesize($logPath) / 1024, 2) . " KB</p>";
    $output .= "<p><strong>Last modified:</strong> " . date('Y-m-d H:i:s', filemtime($logPath)) . "</p>";
    
    return $output;
});
