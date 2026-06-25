<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting database import. This might take a minute...\n";

try {
    $sql = file_get_contents(__DIR__ . '/mitrausaha_export.sql');
    \Illuminate\Support\Facades\DB::unprepared($sql);
    echo "✅ Database successfully imported!\n";
} catch (\Exception $e) {
    echo "❌ Error importing database: " . $e->getMessage() . "\n";
}
