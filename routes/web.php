<?php

use Illuminate\Support\Facades\Route;
use App\Notifications\RegistroFluxoNotification;

Route::get('/', function () {
    return view('welcome');
});

route::get('/teste-safe', function(){
 $alunoNome = "joão";
 $tipo = "Entrada";

//  simula um úsuario responsavel
$user = App\Models\User::first();

if (!$user) return "Crie um usuário no banco primeiro (php artisan make:filament-user)";

    $user->notify(new RegistroFluxoNotification($alunoNome, $tipo));

    return "Notificação enviada! Verifique o Mailpit (porta 2525) e o storage/logs/laravel.log";
});