<?php

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\SubscriberController;
use App\Http\Controllers\Api\Admin\Course\TypeController;
use App\Http\Controllers\Api\Admin\Course\GenderController;
use App\Http\Controllers\Api\Admin\Course\CategoryController;
use App\Http\Controllers\Api\Admin\Course\LocationController;

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


  /**
   * Subscribers routes
   * list, show, update, store, and delete
   */
  Route::controller(SubscriberController::class)->prefix('subscribers')->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{subscriber}', 'show');
    Route::post('/update/{subscriber}', 'update');
    Route::get('/show/{subscriber}/courses', 'courses');
    Route::post('/store', 'store');
    Route::post('/delete/{subscriber}', 'destroy');
  });

  /**
   * Courses routes
   */
  Route::prefix('courses')->group(function () {

    /**
     * Naming routes
     */
    Route::prefix('namings')->group(function () {

      /**
       * Types routes
       */
      Route::controller(TypeController::class)->prefix('type')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{type}', 'show');
        Route::post('/update/{type}', 'update');
        Route::post('/toggle/{type}', 'toggle');
        Route::post('/store', 'store');
        Route::post('/delete/{type}', 'destroy');
      });

      /**
       * Categories routes
       */
      Route::controller(CategoryController::class)->prefix('category')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{category}', 'show');
        Route::post('/update/{category}', 'update');
        Route::post('/toggle/{category}', 'toggle');
        Route::post('/store', 'store');
        Route::post('/delete/{category}', 'destroy');
      });

      /**
       * Genders routes
       */
      Route::controller(GenderController::class)->prefix('gender')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{gender}', 'show');
        // Route::post('/update/{gender}', 'update');
        Route::post('/toggle/{gender}', 'toggle');
        // Route::post('/store', 'store');
        // Route::post('/delete/{gender}', 'destroy');
      });

      /**
       * Categories routes
       */
      Route::controller(LocationController::class)->prefix('location')->group(function () {
        Route::get('/', 'index');
        Route::get('/show/{location}', 'show');
        Route::post('/update/{location}', 'update');
        Route::post('/toggle/{location}', 'toggle');
        Route::post('/store', 'store');
        Route::post('/delete/{location}', 'destroy');
      });

    }); // namings

  }); // courses

}); // admin 