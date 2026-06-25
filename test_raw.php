<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Mail::raw('Halo Bos Dalvin, ini adalah tes email murni (tanpa HTML) untuk mengecek apakah firewall Google memblokir desain email kita. Jika ini masuk, berarti masalahnya ada di desain HTML / Link Localhost kita.', function($msg) {
        $msg->to(['dalvinr279@gmail.com', 'dalvinmensiku76@gmail.com'])->subject('Tes Koneksi Murni - MUSA');
    });
    echo "BERHASIL DIKIRIM RAW TEXT\n";
} catch (\Exception $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
}
