<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trans = App\Models\Transaction::select('jenis_transaksi')->distinct()->get();
foreach($trans as $t) {
    echo "Jenis: " . $t->jenis_transaksi . "\n";
}
