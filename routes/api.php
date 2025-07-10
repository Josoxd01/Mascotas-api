<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\MascotaController;



Route::post('/register', [AuthController::class, 'newUser']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

Route::prefix('personas')->middleware('auth:api')->group(function () {
    Route::get('/', [PersonaController::class, 'selectAll']);
    Route::post('/', [PersonaController::class, 'newPerson']);
    Route::get('/{id}', [PersonaController::class, 'selectPerson']);

    Route::put('/{id}', [PersonaController::class, 'updatePerson']);
    Route::delete('/{id}', [PersonaController::class, 'deletePerson']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/mascotas', [MascotaController::class, 'selectAllMascotas']);
    Route::post('/mascotas', [MascotaController::class, 'newMascota']);
    Route::get('/mascotas/{id}', [MascotaController::class, 'selectMascota']);
    Route::put('/mascotas/{id}', [MascotaController::class, 'updateMascota']);
    Route::delete('/mascotas/{id}', [MascotaController::class, 'deleteMascota']);
});


/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */
