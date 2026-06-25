<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stokMenipis = \App\Models\Product::whereColumn('stok', '<=', 'reorder_point')->get();
$hutangTempo = collect([]);
$besok = \Carbon\Carbon::tomorrow()->format('Y-m-d');
$transaksiTempo = \App\Models\Transaction::with('supplier')->where('jenis_transaksi', 'masuk')->where('catatan', 'LIKE', '%[Pembayaran TEMPO%')->get();
foreach($transaksiTempo as $trx) {
    preg_match('/Jatuh Tempo:\s*([^\]]+)/', $trx->catatan, $matches);
    if(isset($matches[1])) {
        $tglTempoDb = \Carbon\Carbon::createFromFormat('d/m/Y', trim($matches[1]))->format('Y-m-d');
        if($tglTempoDb <= $besok) {
            $trx->tanggal_tempo = $tglTempoDb; 
            $hutangTempo->push($trx);
        }
    }
}
$mail = new \App\Mail\HeaderNotificationMail($stokMenipis, $hutangTempo);

try {
    \Illuminate\Support\Facades\Mail::to(['dalvinr279@gmail.com', 'dalvinmensiku76@gmail.com'])->send($mail);
    echo "BERHASIL DIKIRIM KE dalvinr279@gmail.com dan dalvinmensiku76@gmail.com\n";
} catch (\Exception $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
}
