<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiSurvivorController;
use App\Http\Controllers\SurvivorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SurvivorController::class, 'index']);

Route::get('/survivors/{id}', [SurvivorController::class, 'show']);
Route::get('/survivors/create', [SurvivorController::class, 'create']);
Route::post('/survivors', [SurvivorController::class, 'store']);
Route::get('/survivors/edit/{id}', [SurvivorController::class, 'edit']);
Route::put('/survivors/update/{id}', [SurvivorController::class, 'update']);
Route::delete('/survivors/{id}', [SurvivorController::class, 'destroy']);
Route::get('/survivors/edit/location/{id}', [SurvivorController::class, 'edit_location']);
Route::get('/survivors/mark/{id}', [SurvivorController::class, 'mark_infected']);
Route::get('/survivors/trade/{id}', [SurvivorController::class, 'trade']);
Route::post('/survivors/trade', [SurvivorController::class, 'do_trade']);
Route::get('/report', [SurvivorController::class, 'report']);

Route::get('/api/survivors', [ApiSurvivorController::class, 'index']);
Route::post('/api/survivor', [ApiSurvivorController::class, 'store']);
Route::get('/api/token', [ApiSurvivorController::class, 'token']);
Route::get('/api/survivor/{id}', [ApiSurvivorController::class, 'show']);
Route::put('/api/survivor/{id}', [ApiSurvivorController::class, 'update']);
Route::delete('/api/survivor/{id}', [ApiSurvivorController::class, 'destroy']);
Route::get('/api/survivor/mark/{id}', [ApiSurvivorController::class, 'mark_infected']);
Route::get('/api/survivor/trader/{id}', [ApiSurvivorController::class, 'trader']);
Route::get('/api/survivors/report', [ApiSurvivorController::class, 'report']);
