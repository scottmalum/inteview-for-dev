<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[AuthController::class,'register']);
Route::get('/email/verify/{id}',[AuthController::class,'verifyEmail'])->name('verification.verify');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('some-data',[SearchController::class,'store'])->middleware(['jwt']);
Route::get('some-data',[SearchController::class,'index'])->middleware(['jwt']);
Route::get('search-data/query',[SearchController::class,'query'])->middleware(['jwt']);