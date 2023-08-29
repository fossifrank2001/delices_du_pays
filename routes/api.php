<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BeveurageController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CompteController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\MealController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\Utils\Utils;

use function Sodium\memcmp;

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


Route::middleware('api')->group(function(){
    Route::get('/redirect', function (){
        return redirect(Utils::$URL_FRONT . '/login', 302);
    })->name('redirect');

    //active an deactive an account
    Route::get('/accounts/activate-account/{account}-{fromEmailIbox}', [CompteController::class, 'activateCompte'])
        ->name('activate-new-account');
    Route::get('/accounts/deactivate-account/{account}', [CompteController::class, 'deactivateCompte'])->name('deactivate-account');

    // Forgot and Reset Password
    Route::prefix('auth')->controller(CompteController::class)->group(function () {
        Route::post('/forgot-password','forgotPassword');
        Route::get('/reset-password/{token}','resetPassword')->name('reset_password');
        Route::post('/reset-password','resetNewPassword');
    });

    //Authentification
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register',  'register');
        Route::post('login',  'login');
        Route::get('/email/verify/{token}','verifyEmail')->name('verificationEmailForRegistration');
    });

    //Image
    Route::prefix('images')->controller(ImageController::class)->group(function(){
        Route::post('/create-many', 'createManyImages');
        Route::post('/create', 'createOneImage');
        Route::get('/delete/{image}', 'deleteImage');
    });

    Route::prefix('accounts')->controller(CompteController::class)->group(function(){
        Route::get('/{id}', 'show');
    });

    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::get('', 'index');
        Route::get('/{id}', 'show');
    });

    // Route::get('/send-sms', [BeveurageController::class, 'sms']);

    Route::middleware('auth:api')->group(function(){
        Route::post('dashboard', [\App\Http\Controllers\API\DashboardController::class,'index']);
        Route::get('habilitations/{role}', [\App\Http\Controllers\API\DashboardController::class,'getMenuAndPermissions']);
        Route::prefix('auth')->group(function () {
            Route::delete('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
        });

        //route to handle Role
        Route::resource('roles', RoleController::class)->except(['edit', 'create']);

        //route to handle permissions
        Route::resource('permissions', \App\Http\Controllers\API\PermissionController::class)->except(['edit', 'create']);

        //route to handle category
        Route::prefix('categories')->controller(CategoryController::class)->group(function () {
            Route::post('', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        //route to handle Statut
        Route::resource('statuts', StatusController::class)->except(['edit', 'create']);

        //route to handle compte
        Route::prefix('accounts')->controller(CompteController::class)->group(function(){
            Route::get('', 'index');
            Route::post('', 'create');
            Route::delete('/{id}', 'destroy');
        });

        //route to handle beveurages
        Route::prefix('beveurages')->controller(BeveurageController::class)->group(function(){
            Route::get('/{id}', 'show');
            Route::get('', 'index');
            Route::post('', 'store');
            Route::patch('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

        });

        //route to handle beveurages
        Route::prefix('meals')->controller(MealController::class)->group(function(){
            Route::get('/{id}', 'show');
            Route::get('', 'index');
            Route::post('', 'store');
            Route::patch('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

        });

        //handling comments
        Route::prefix('comments')->controller(\App\Http\Controllers\API\CommentController::class)->group(function (){
            Route::post('/', 'make');
            Route::put('/{comment}', 'update');
            Route::delete('/{comment}', 'destroy');
        });
    });

});
