<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$satuans = ['Pcs', 'Sak', 'Batang', 'Kaleng', 'Meter', 'Dus / Box'];
foreach($satuans as $s) {
    \App\Models\Satuan::firstOrCreate(['nama_satuan' => $s]);
}

// Extract from existing products
$existing = \App\Models\Product::select('satuan')->distinct()->pluck('satuan')->toArray();
foreach($existing as $e) {
    if ($e) {
        \App\Models\Satuan::firstOrCreate(['nama_satuan' => $e]);
    }
}
echo "Seeded!";
