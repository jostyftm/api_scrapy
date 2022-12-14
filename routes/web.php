<?php

use App\Models\Search;
use App\Models\Spider;
use App\Models\WebsiteConfiguration;
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

Route::get('/', function () {

    // $search = Search::with('website.webConfiguration')->where('id', '=', 3)->first();

    // $scrapy = new Spider($search);
    // $scrapy->run();

    // $config = WebsiteConfiguration::find(2);
    // $search = Search::where('website_id', '=', $config->website_id)->first();

    // $scrapy = new Spider($search);
    // $reults = $scrapy->runTest("socket");

    // dd($reults);
    return view('welcome');
});
