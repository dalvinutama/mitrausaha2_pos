<?php

namespace App\Listeners;

use App\Events\StockInCreated;
use App\Events\StockOutCreated;
use App\Events\PurchaseOrderCreated;
use App\Events\PurchaseOrderApproved;
use App\Events\PurchaseOrderRejected;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class LogAuditTrail
{
    public function handleStockIn(StockInCreated $event): void
    {
        $this->log('Create', 'Barang Masuk', 'Mencatat Barang Masuk: ' . $event->transaction->no_transaksi, $event->transaction->id);
    }

    public function handleStockOut(StockOutCreated $event): void
    {
        $this->log('Create', 'Barang Keluar', 'Mencatat Transaksi Keluar (Kasir) Nomor: ' . $event->transaction->no_transaksi, $event->transaction->id);
    }

    public function handlePoCreated(PurchaseOrderCreated $event): void
    {
        $status = $event->transaction->status === 'approved' ? 'PO' : 'Draft PO';
        $this->log('Create', 'Purchase Order', 'Membuat ' . $status . ' dengan Nomor ' . $event->transaction->no_transaksi, $event->transaction->id);
    }

    public function handlePoApproved(PurchaseOrderApproved $event): void
    {
        $this->log('Update', 'Purchase Order', 'Menyetujui Purchase Order Nomor ' . $event->transaction->no_transaksi, $event->transaction->id);
    }

    public function handlePoRejected(PurchaseOrderRejected $event): void
    {
        $this->log('Update', 'Purchase Order', 'Menolak Purchase Order Nomor ' . $event->transaction->no_transaksi, $event->transaction->id);
    }

    private function log(string $action, string $module, string $description, ?int $recordId): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'record_id' => $recordId,
        ]);
    }
}
