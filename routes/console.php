<?php

use App\Models\Pedido;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Pedido::liberarReservasVencidas();
})->everyFiveMinutes()->name('pedidos:liberar-reservas-vencidas')->withoutOverlapping();
