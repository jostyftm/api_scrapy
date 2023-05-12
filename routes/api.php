<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ResourceTypeController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SearchResourceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::resource('resourceTypes', ResourceTypeController::class);

Route::resource('websites', WebsiteController::class);
Route::get('websites/{website}/configuration', [WebsiteController::class, 'getConfig']);
Route::put('websites/{websiteConfiguration}/configuration', [WebsiteController::class, 'updateConfig']);
Route::post('websites/{websiteConfiguration}/test', [WebsiteController::class, 'testConfig']);

Route::resource('searches', SearchController::class);
Route::get('resources/search', [ResourceController::class, 'search']);
Route::resource('resources', ResourceController::class);
Route::resource('users', Usercontroller::class);

Route::get('search', [SearchResourceController::class, 'index']);