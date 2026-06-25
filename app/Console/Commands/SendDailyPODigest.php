<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyPODigestMail;

class SendDailyPODigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:pending-po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan rekap (digest) harian berisi semua PO yang masih pending ke Owner';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mengecek PO Pending...');

        // 1. Ambil semua PO yang statusnya masih 'pending' atau 'approved'
        $pendingPOs = Transaction::with('supplier')
            ->where('jenis_transaksi', 'po')
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pendingPOs->isEmpty()) {
            $this->info('Tidak ada PO pending hari ini. Selesai.');
            return;
        }

        // 2. Ambil semua email owner dan admin
        $ownerEmails = User::whereIn('role', ['owner', 'admin'])
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->toArray();

        if (empty($ownerEmails)) {
            $this->error('Gagal: Tidak ada akun Owner dengan email yang valid.');
            return;
        }

        // 3. Kirim Email Massal ke para Owner (via Queue)
        Mail::to($ownerEmails)->queue(new DailyPODigestMail($pendingPOs));

        $this->info('Berhasil! Laporan rekap ' . $pendingPOs->count() . ' PO telah masuk antrean untuk dikirim ke: ' . implode(', ', $ownerEmails));
    }
}
