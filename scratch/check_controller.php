<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$class = new ReflectionClass('App\Http\Controllers\AdminController');
foreach ($class->getMethods() as $m) {
    if ($m->getDeclaringClass()->getName() === 'App\Http\Controllers\AdminController') {
        echo $m->getStartLine() . ': ' . $m->getName() . PHP_EOL;
    }
}
