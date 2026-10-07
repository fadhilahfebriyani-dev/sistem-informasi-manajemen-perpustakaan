<?php

namespace App\Console\Commands;

use App\Services\DendaService;
use Illuminate\Console\Command;

class SyncDendaOverdue extends Command
{
    protected $signature = 'denda:sync';
    protected $description = 'Sinkronkan denda untuk semua peminjaman yang terlambat';

    public function handle(DendaService $dendaService): int
    {
        $dendaService->syncOverdue();
        $this->info('Sinkronisasi denda selesai.');
        return self::SUCCESS;
    }
}