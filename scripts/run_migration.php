<?php
// Load Laravel bootstrap
require_once __DIR__ . '/../bootstrap/app.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Register the console application
$status = $kernel->handle(
    $input = new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'migrate',
    ]),
    $output = new \Symfony\Component\Console\Output\BufferedOutput()
);

echo $output->fetch();
echo "\nMigration Status: " . ($status === 0 ? "SUCCESS" : "FAILED") . "\n";

$kernel->terminate($input, $status);

exit($status);
