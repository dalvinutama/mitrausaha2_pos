<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = app()->make('App\Http\Controllers\AdvancedAnalyticsController');
    $request = Illuminate\Http\Request::create('/dashboard/advanced-analytics', 'GET', ['range' => 30]);
    $response = $controller->generateReport($request);
    print_r($response->getData(true));
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}
