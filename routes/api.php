<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DistribucionController;
use App\Http\Controllers\Api\UsuarioFiscalizacionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// API SIN AUTENTICACIÓN (PÚBLICO)
// ============================================

// Distribuciones routes
Route::apiResource('distribuciones', DistribucionController::class);

// Usuarios Fiscalizacion routes
Route::apiResource('usuarios-fiscalizacion', UsuarioFiscalizacionController::class);

// Additional routes for usuarios fiscalizacion
Route::patch('usuarios-fiscalizacion/{usuarioFiscalizacion}/toggle-status', [UsuarioFiscalizacionController::class, 'toggleStatus'])
    ->name('usuarios-fiscalizacion.toggle-status');

// Login route
Route::post('usuarios-fiscalizacion/login', [UsuarioFiscalizacionController::class, 'login'])
    ->name('usuarios-fiscalizacion.login');

// Profile and password update routes
Route::put('usuarios-fiscalizacion/{usuarioFiscalizacion}/profile', [UsuarioFiscalizacionController::class, 'updateProfile'])
    ->name('usuarios-fiscalizacion.update-profile');

Route::patch('usuarios-fiscalizacion/{usuarioFiscalizacion}/password', [UsuarioFiscalizacionController::class, 'updatePassword'])
    ->name('usuarios-fiscalizacion.update-password');

// Admin routes
Route::patch('usuarios-fiscalizacion/{usuarioFiscalizacion}/reset-password', [UsuarioFiscalizacionController::class, 'resetPassword'])
    ->name('usuarios-fiscalizacion.admin-reset-password');

// Nested routes - usuarios by distribucion
Route::get('distribuciones/{distribucion}/usuarios', function (Request $request, $distribucionId) {
    $query = \App\Models\UsuarioFiscalizacion::where('distribucion_id', $distribucionId);

    if ($request->has('status')) {
        $query->where('status', $request->get('status'));
    }

    $perPage = $request->get('per_page', 15);
    $usuarios = $query->paginate($perPage);

    return \App\Http\Resources\UsuarioFiscalizacionResource::collection($usuarios);
})->name('distribuciones.usuarios');

// ============================================
// RUTAS DE AUTENTICACIÓN (OPCIONALES)
// ============================================
// Descomenta estas rutas si quieres usar autenticación más adelante

/*
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
*/