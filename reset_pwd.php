<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = \App\Models\User::where('email', 'gudang@mitrausaha2.com')->first();
if ($u) {
    $u->password = bcrypt('password');
    $u->save();
    echo 'OK';
}
