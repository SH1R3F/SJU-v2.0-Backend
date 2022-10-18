<?php

use App\Models\Course\Type;
use Illuminate\Http\Request;
use App\Models\Course\Gender;
use App\Models\Course\Category;
use App\Models\Course\Location;
use App\Models\Course\Template;
use App\Models\Course\Questionnaire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Admin\MenuController;
use App\Http\Controllers\Api\Admin\PageController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Resources\Admin\Course\NamingResource;
use App\Http\Controllers\Api\Admin\MemberController;
use App\Http\Controllers\Api\Admin\StudioController;
use App\Http\Resources\Admin\Course\TemplateResource;
use App\Http\Controllers\Api\Admin\BlogPostController;
use App\Http\Controllers\Api\Admin\Auth\AuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\VolunteerController;
use App\Http\Controllers\Api\Admin\SiteOptionController;
use App\Http\Controllers\Api\Admin\SubscriberController;
use App\Http\Controllers\Api\Admin\Course\TypeController;
use App\Http\Controllers\Api\Admin\BlogCategoryController;
use App\Http\Resources\Admin\Course\QuestionnaireResource;
use App\Http\Controllers\Api\Admin\Course\CourseController;
use App\Http\Controllers\Api\Admin\Course\GenderController;
use App\Http\Controllers\Api\Admin\Course\CategoryController;
use App\Http\Controllers\Api\Admin\Course\LocationController;
use App\Http\Controllers\Api\Admin\Course\TemplateController;
use App\Http\Controllers\Api\Admin\TechnicalSupportController;
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

Route::get('/', function () {
  return 'Welcome to api';
});

Route::get('/menus', [HomeController::class, 'menus']);
Route::get('/home', [HomeController::class, 'index']);

Route::prefix('/admin')->group(function() {
  Route::post('/login', [AuthController::class, 'login']);

  Route::middleware('auth:api-admins')->group(function() {

    /**
     * Dashboard route
     * Returning stats
     */
    Route::get('dashboard', [ DashboardController::class, 'index' ]);


    /**
     * Moderators routes
     * list roles, list, show, update, store, and delete
     * Middleware permissions: read-moderator, create-moderator, update-moderator, delete-moderator
     */
    Route::get('admins/roles', [ AdminController::class, 'roles' ]);
    Route::resource('admins', AdminController::class)->except(['edit', 'create']);
  
    /**
     * Roles routes
     * list permissions, update permissions, list, show, update, store, and delete
     * Middleware permissions: manage-roles
     */
    Route::get('roles/{role}/permissions', [ RoleController::class, 'get_permissions' ]);
    Route::post('roles/{role}/permissions', [ RoleController::class, 'update_permissions' ]);
    Route::resource('roles', RoleController::class)->except(['edit', 'create']);
  
    /**
     * Site options routes
     * list, show, update, store, and delete
     * Middleware permissions: manage-options
     */
    Route::resource('site_options', SiteOptionController::class);

    /**
     * Members routes
     * list, show, update, store, and delete
     * Middleware permissions: read-member, create-member, update-member, delete-member
     */
    Route::resource('members', MemberController::class)->except(['edit', 'create']);
  
    /**
     * Subscribers routes
     * list, show, update, store, and delete
     * Middleware permissions: read-susbcriber, create-susbcriber, update-susbcriber, delete-susbcriber
     */
    Route::get('subscribers/show/{subscriber}/courses', [ SubscriberController::class, 'courses' ]);
    Route::resource('subscribers', SubscriberController::class)->except(['edit', 'create']);
  
    /**
     * Volunteers routes
     * list, show, update, store, and delete
     * Middleware permissions: read-volunteer, create-volunteer, update-volunteer, delete-volunteer
     */
    Route::get('volunteers/show/{volunteer}/courses', [ VolunteerController::class, 'courses' ]);
    Route::resource('volunteers', VolunteerController::class)->except(['edit', 'create']);
  
    Route::prefix('courses')->group(function () {
  
      /**
       * Naming routes
       * Middleware permissions: manage-namings
       */
      Route::middleware('permission:read-manage-namings')->prefix('namings')->group(function () {
  
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
  
        /**
         * All Namings
         */
        Route::get('all', function() {
  
          return response()->json([
            'types' => NamingResource::collection(Type::all()),
            'categories' => NamingResource::collection(Category::all()),
            'genders' => NamingResource::collection(Gender::all()),
            'locations' => NamingResource::collection(Location::all()),
            'templates' => TemplateResource::collection(Template::all()),
            'questionnaires' => QuestionnaireResource::collection(Questionnaire::all()),
          ]);
  
        });
  
      });// namings
  
        /**
         * Templates routes
         * Middleware permissions: read-template, create-template, update-template, delete-template
         */
        Route::resource('templates', TemplateController::class)->except(['edit', 'create']);
  
        /**
         * Questionnaires routes
         * Middleware permissions: read-questionnaire, create-questionnaire, update-questionnaire, delete-questionnaire
         */
        Route::resource('questionnaires', QuestionnaireController::class)->except(['edit', 'create']);
  
    }); // courses

    /**
     * Courses routes
     * Middleware permissions: read-course, create-course, update-course, delete-course
     */
    Route::get('/courses/{course}/enrollers', [courseController::class, 'enrollers']);
    Route::post('/courses/{course}/enrollers/{type}/{id}', [courseController::class, 'togglePass']);
    Route::delete('/courses/{course}/enrollers/{type}/{id}', [courseController::class, 'deleteEnroller']);
    Route::resource('/courses', CourseController::class)->except(['edit', 'ceate']);
  

    /**
     * Technical Support routes
     * Middleware permissions: manage-membersupport, manage-subscribersupport, manage-volunteersupport NOT YET
     */
    Route::get('/support', [ TechnicalSupportController::class , 'index' ]);
    Route::get('/support/{ticket}', [ TechnicalSupportController::class , 'chat' ]);
    Route::post('/support/{ticket}', [ TechnicalSupportController::class , 'message' ]);
    Route::put('/support/{ticket}', [ TechnicalSupportController::class , 'toggle' ]);
    Route::delete('/support/{ticket}', [ TechnicalSupportController::class , 'destroy' ]);

    /**
     * Studio routes
     * Middleware permissions: manage-studio
     */
    Route::get('/studio', [ StudioController::class , 'index' ]);
    Route::post('/studio/{type}', [ StudioController::class , 'store' ]);
    Route::delete('/studio/{item}', [ StudioController::class , 'destroy' ]);

    /**
     * Pages routes
     * Middleware permissions: read-page, create-page, update-page, delete-page
     */
    Route::resource('/pages', PageController::class)->except(['edit', 'create']);

    /**
     * Posts routes
     * Middleware permissions: read-post, create-post, update-post, delete-post
     */
    Route::resource('/blog/posts', BlogPostController::class)->except(['edit', 'create']);

    /**
     * Categories routes
     * Middleware permissions: manage-settings
     */
    Route::put('/blog/categories', [ BlogCategoryController::class, 'reorder' ]);
    Route::resource('/blog/categories', BlogCategoryController::class)->except(['edit', 'create']);

    /**
     * Menus routes
     * Middleware permissions: read-menu, create-menu, update-menu, delete-menu
     */
    Route::put('/menus', [ MenuController::class, 'reorder' ]);
    Route::resource('/menus', MenuController::class)->except(['edit', 'create']);

  });

}); // admin 