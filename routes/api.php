<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoFormularioController;
use App\Http\Controllers\CampoController;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\FichajeController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ImagenController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    //Usuarios solo admin
    Route::middleware('rol:admin')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index']);
        Route::post('/usuarios', [UsuarioController::class, 'store']);
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
        Route::patch('/usuarios/{id}/activar', [UsuarioController::class, 'activar']);
        Route::get('/roles', [UsuarioController::class, 'roles']);
    });

    //Tipos de formulario admin gestiona
    Route::get('/tipos-formulario', [TipoFormularioController::class, 'index']);
    Route::get('/tipos-formulario/{id}', [TipoFormularioController::class, 'show']);
    Route::middleware('rol:admin')->group(function () {
        Route::post('/tipos-formulario', [TipoFormularioController::class, 'store']);
        Route::put('/tipos-formulario/{id}', [TipoFormularioController::class, 'update']);
        Route::delete('/tipos-formulario/{id}', [TipoFormularioController::class, 'destroy']);
        Route::post('/campos', [CampoController::class, 'store']);
        Route::put('/campos/{id}', [CampoController::class, 'update']);
        Route::delete('/campos/{id}', [CampoController::class, 'destroy']);
    });

    //Formularios
    Route::get('/formularios', [FormularioController::class, 'index']);
    Route::get('/formularios/{id}', [FormularioController::class, 'show']);
    Route::post('/formularios', [FormularioController::class, 'store']);
    Route::put('/formularios/{id}', [FormularioController::class, 'update']);
    Route::patch('/formularios/{id}/estado', [FormularioController::class, 'cambiarEstado']);

    //PDF
    Route::get('/formularios/{id}/pdf', [FormularioController::class, 'exportarPdf']);
    
    //Imagenes
    Route::post('/formularios/{id}/imagenes', [ImagenController::class, 'store']);
    Route::delete('/imagenes/{id}', [ImagenController::class, 'destroy']);
    
    //Para estadisiticas
    Route::get('/estadisticas', [FormularioController::class, 'estadisticas']);

    //Fichajes
    Route::get('/fichajes', [FichajeController::class, 'index']);
    Route::post('/fichajes', [FichajeController::class, 'fichar']);

    //Auditoría
    Route::middleware('rol:admin,supervisor')->group(function () {
        Route::get('/auditoria', [AuditoriaController::class, 'index']);
        Route::get('/auditoria/formulario/{id}', [AuditoriaController::class, 'porFormulario']);
        Route::get('/agentes', [FormularioController::class, 'agentes']); 
    });
});