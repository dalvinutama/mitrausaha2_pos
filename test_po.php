<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transactions = App\Models\TransactionItem::whereHas('transaction', function($q){
    $q->whereIn('jenis_transaksi', ['po', 'barang_masuk'])->whereNotNull('supplier_id');
})->with('transaction.supplier')->get();

echo "Count: " . count($transactions) . "\n";
if(count($transactions) > 0) {
    echo "First supplier: " . $transactions->first()->transaction->supplier->nama_supplier . "\n";
} else {
    echo "NO TRANSACTIONS FOUND WITH SUPPLIER ID\n";
    
    // Check if there are any PO or barang_masuk
    $trans = App\Models\Transaction::whereIn('jenis_transaksi', ['po', 'barang_masuk'])->get();
    echo "Total PO/Barang Masuk: " . count($trans) . "\n";
    if(count($trans) > 0) {
        echo "First transaction supplier_id: " . $trans->first()->supplier_id . "\n";
    }
}
