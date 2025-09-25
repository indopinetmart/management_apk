<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Update currency rates from API';

    public function handle()
    {
        $pairs = [
            ['from' => 'IDR', 'to' => 'USD'],
            ['from' => 'USD', 'to' => 'IDR'],
            // tambahkan mata uang lain jika perlu
        ];

        foreach ($pairs as $pair) {
            CurrencyService::updateRate($pair['from'], $pair['to']);
            $this->info("Updated {$pair['from']} → {$pair['to']}");
        }

        $this->info('Currency rates updated successfully.');
    }
}
