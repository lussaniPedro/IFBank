<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContaController;
use App\Http\Controllers\Api\InvestimentoController;
use App\Http\Controllers\Api\MovimentacaoController;
use App\Http\Controllers\Api\GerenteController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\SolicitacaoLimiteController;
use App\Http\Controllers\Api\AuditoriaController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get(
        '/conta',
        [ContaController::class, 'show']
    );

    Route::get(
        '/conta/extrato',
        [ContaController::class, 'extrato']
    );

    Route::post(
        '/conta/pix',
        [MovimentacaoController::class, 'pix']
    );

    Route::get(
        '/investimentos',
        [InvestimentoController::class, 'index']
    );

    Route::post(
        '/investimentos/aplicar',
        [InvestimentoController::class, 'aplicar']
    );

    Route::post(
        '/investimentos/resgatar',
        [InvestimentoController::class, 'resgatar']
    );

    Route::get(
        '/clientes',
        [ClienteController::class, 'index']
    );

    Route::post(
        '/clientes',
        [ClienteController::class, 'store']
    );

    Route::get(
        '/clientes/{cliente}',
        [ClienteController::class, 'show']
    );

    Route::put(
        '/clientes/{cliente}',
        [ClienteController::class, 'update']
    );

    Route::delete(
        '/clientes/{cliente}',
        [ClienteController::class, 'destroy']
    );

    Route::post(
        '/clientes/{cliente}/bloquear',
        [ClienteController::class, 'bloquear']
    );

    Route::post(
        '/clientes/{cliente}/desbloquear',
        [ClienteController::class, 'desbloquear']
    );

    Route::get(
        '/clientes/{cliente}/extrato',
        [ClienteController::class, 'extrato']
    );

    Route::get(
        '/clientes/{cliente}/limite',
        [ClienteController::class, 'limite']
    );

    Route::post(
        '/clientes/{cliente}/limite',
        [SolicitacaoLimiteController::class, 'store']
    );

    Route::get(
        '/gerentes',
        [GerenteController::class, 'index']
    );

    Route::post(
        '/gerentes',
        [GerenteController::class, 'store']
    );

    Route::get(
        '/gerentes/{gerente}',
        [GerenteController::class, 'show']
    );

    Route::put(
        '/gerentes/{gerente}',
        [GerenteController::class, 'update']
    );

    Route::delete(
        '/gerentes/{gerente}',
        [GerenteController::class, 'destroy']
    );

    Route::get(
        '/solicitacoes-limite',
        [SolicitacaoLimiteController::class, 'index']
    );

    Route::post(
        '/solicitacoes-limite/{solicitacao}/aprovar',
        [SolicitacaoLimiteController::class, 'aprovar']
    );

    Route::post(
        '/solicitacoes-limite/{solicitacao}/reprovar',
        [SolicitacaoLimiteController::class, 'reprovar']
    );

    Route::get(
        '/auditorias',
        [AuditoriaController::class, 'index']
    );
});
