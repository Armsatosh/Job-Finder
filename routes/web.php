<?php declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Home Page
Route::get('/', [\App\Http\Controllers\AuthController::class, 'home']);

// Login and Logout
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'getLogin']);
    Route::post('/login', [AuthController::class, 'postLogin']);
});
Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth']);

// Registration and User Profile
Route::resource('user', UserController::class, ['except' => ['index', 'show', 'destroy']]);

// Job Resources
Route::resource('job', JobController::class)->middleware('auth');

// Favorite Jobs
//Route::group(['middleware' => 'auth'], function () {
//    Route::get('favorite', [JobController::class, 'index'])->name('favorite.index');
//});
