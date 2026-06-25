<?php

$product = App\Models\Product::where('sku', 'ELK-001')->first();
$inventoryService = new App\Services\InventoryService();
$res = $inventoryService->recalculateForProduct($product);
echo json_encode($res);
