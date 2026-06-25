<?php

namespace App\Listeners;

use App\Events\StockInCreated;
use App\Events\StockOutCreated;
use App\Events\PurchaseOrderCreated;
use Illuminate\Support\Facades\Cache;

class InvalidateAnalyticsCache
{
    public function handle(StockInCreated|StockOutCreated|PurchaseOrderCreated $event): void
    {
        Cache::forget('advanced_analytics_data_30');
        Cache::forget('advanced_analytics_data_90');
        Cache::forget('advanced_analytics_data_365');
    }
}
