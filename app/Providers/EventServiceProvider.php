<?php

namespace App\Providers;

use App\Events\StockInCreated;
use App\Events\StockOutCreated;
use App\Events\PurchaseOrderCreated;
use App\Events\PurchaseOrderApproved;
use App\Events\PurchaseOrderRejected;
use App\Listeners\LogAuditTrail;
use App\Listeners\InvalidateAnalyticsCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StockInCreated::class => [
            [LogAuditTrail::class, 'handleStockIn'],
            [InvalidateAnalyticsCache::class, 'handle'],
        ],
        StockOutCreated::class => [
            [LogAuditTrail::class, 'handleStockOut'],
            [InvalidateAnalyticsCache::class, 'handle'],
        ],
        PurchaseOrderCreated::class => [
            [LogAuditTrail::class, 'handlePoCreated'],
            [InvalidateAnalyticsCache::class, 'handle'],
        ],
        PurchaseOrderApproved::class => [
            [LogAuditTrail::class, 'handlePoApproved'],
        ],
        PurchaseOrderRejected::class => [
            [LogAuditTrail::class, 'handlePoRejected'],
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
