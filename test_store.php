<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$req = Illuminate\Http\Request::create('/pesan/kirim', 'POST', ['content' => 'test']);
$req->headers->set('X-Requested-With', 'XMLHttpRequest');
$req->setUserResolver(function() { return \App\Models\User::first(); });
$res = $kernel->handle($req);
echo $res->getContent();
