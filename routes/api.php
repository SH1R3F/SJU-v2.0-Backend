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
use App\Http\Controllers\Api\Admin\Course\TemplateController;
use App\Http\Controllers\Api\Admin\Course\QuestionnaireController;

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
  Route::get('subscribers/show/{subscriber}/courses', [ SubscriberController::class, 'courses' ]);
  Route::resource('subscribers', SubscriberController::class)->except(['edit', 'create']);

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
      Route::post('type/toggle/{type}', [ TypeController::class, 'toggle' ]);
      Route::resource('type', TypeController::class)->except(['edit', 'create']);

      /**
       * Categories routes
       */
      Route::post('category/toggle/{category}', [ CategoryController::class, 'toggle' ]);
      Route::resource('category', CategoryController::class)->except(['edit', 'create']);

      /**
       * Genders routes
       */
      Route::post('gender/toggle/{gender}', [ GenderController::class, 'toggle' ]);
      Route::resource('gender', GenderController::class)->except(['edit', 'create', 'update', 'store', 'delete']);

      /**
       * Locations routes
       */
      Route::post('location/toggle/{location}', [ LocationController::class, 'toggle' ]);
      Route::resource('location', LocationController::class)->except(['edit', 'create']);

    }); // namings

      /**
       * Templates routes
       */
      Route::resource('templates', TemplateController::class)->except(['edit', 'create']);

      /**
       * Questionnaires routes
       */
      Route::resource('questionnaires', QuestionnaireController::class)->except(['edit', 'create']);

  }); // courses

}); // admin 