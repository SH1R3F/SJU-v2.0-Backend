<?php

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/admin')->group(function() {
  Route::post('/login', [AuthController::class, 'login']);

  Route::get('/home', function() {
    return 'Home works only if authenticated!';
  })->middleware('auth:api-admins');


  Route::controller(SubscriberController::class)->prefix('subscribers')->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{subscriber}', 'show');
    Route::post('/update/{subscriber}', 'update');
    Route::get('/show/{subscriber}/courses', 'courses');
    Route::post('/store', 'store');
    Route::post('/delete/{subscriber}', 'destroy');
  });

});