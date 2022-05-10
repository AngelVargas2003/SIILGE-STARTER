<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\RefugiosController;
use Inertia\Inertia;
use App\Models\Refugio;
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
Route::get('/refugios', function () {
    return Refugio::all();
 });
Route::get('/', [ApplicationController::class, 'index']);
Route::resource('/refugio',RefugiosController::class);