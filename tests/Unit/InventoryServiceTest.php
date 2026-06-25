<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Services\InventoryService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_recalculate_all_returns_count(): void
    {
        Product::factory()->count(3)->create([
            'tipe_safety_stock' => 'manual',
            'safety_stock' => 5,
            'lead_time_hari' => 3,
        ]);

        $service = new InventoryService();
        $count = $service->recalculateAll();

        $this->assertEquals(3, $count);
    }

    public function test_recalculate_for_product_without_sales(): void
    {
        $product = Product::factory()->create([
            'tipe_safety_stock' => 'manual',
            'safety_stock' => 10,
            'lead_time_hari' => 5,
        ]);

        $service = new InventoryService();
        $result = $service->recalculateForProduct($product);

        $this->assertArrayHasKey('rop', $result);
        $this->assertArrayHasKey('eoq', $result);
        $this->assertArrayHasKey('safety_stock', $result);
        $this->assertEquals(10, $result['safety_stock']);
    }

    public function test_safety_stock_uses_manual_value(): void
    {
        $product = Product::factory()->create([
            'tipe_safety_stock' => 'manual',
            'safety_stock' => 25,
        ]);

        $service = new InventoryService();
        $result = $service->recalculateForProduct($product);

        $this->assertEquals(25, $result['safety_stock']);
    }
}
