<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanOldMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:clean-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus pesan chat yang lebih dari 30 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = \App\Models\Message::where('created_at', '<', now()->subDays(30))->delete();
        $this->info("Berhasil menghapus {$deleted} pesan lama.");
    }
}
