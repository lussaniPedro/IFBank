<?php

use App\Http\Controllers\GerenteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\SolicitacaoLimiteController;
use App\Http\Controllers\AuditoriaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function(){
    Route::resource('gerentes', GerenteController::class)
        ->parameters(['gerentes' => 'gerente']);
});

Route::middleware(['auth'])->group(function(){
    Route::resource('clientes', ClienteController::class);

    Route::post(
        'clientes/{cliente}/bloquear',
        [ClienteController::class, 'bloquear']
    )->name('clientes.bloquear');

    Route::post(
        'clientes/{cliente}/desbloquear',
        [ClienteController::class, 'desbloquear']
    )->name('clientes.desbloquear');

    Route::get(
        'clientes/{cliente}/extrato',
        [ClienteController::class, 'extrato']
    )->name('clientes.extrato');
});

Route::middleware(['auth'])->group(function(){
    Route::get(
        'clientes/{cliente}/limite',
        [SolicitacaoLimiteController::class, 'create']
    )->name('solicitacoes.create');

    Route::post(
        'clientes/{cliente}/limite',
        [SolicitacaoLimiteController::class, 'store']
    )->name('solicitacoes.store');

    Route::get(
        'solicitacoes-limite',
        [SolicitacaoLimiteController::class, 'index']
    )->name('solicitacoes.index');

    Route::post(
        'solicitacoes-limite/{solicitacao}/aprovar',
        [SolicitacaoLimiteController::class, 'aprovar']
    )->name('solicitacoes.aprovar');

    Route::post(
        'solicitacoes-limite/{solicitacao}/reprovar',
        [SolicitacaoLimiteController::class, 'reprovar']
    )->name('solicitacoes.reprovar');
});

Route::middleware(['auth'])->group(function(){
    Route::get(
        'auditorias',
        [AuditoriaController::class, 'index']
    )->name('auditorias.index');
});

require __DIR__.'/auth.php';
require __DIR__.'/app.php';
