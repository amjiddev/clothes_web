<?php
header('Content-Type: text/plain');

$logFile = __DIR__ . '/../storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "Log file not found at: $logFile";
    exit;
}

// Get last 100 lines
$lines = file($logFile);
$lastLines = array_slice($lines, max(0, count($lines) - 100), 100);

echo implode('', $lastLines);
?>
