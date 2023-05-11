<?php

use App\Helper;
use App\Models\Resource;
use App\Models\Search;
use App\Models\Spider;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider whereHasin a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $search = Search::whereHas('website.webConfiguration',function($q){
        $q->where('state', '=', true);
    })->get();

    return response()->json($search);
});
