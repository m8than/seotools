<?php

use Facades\App\Helpers\Authentication;
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

Route::get('/trk/imgs/{category}/{user}.png', 'TrackingController@imageTrack');
Route::get('/trk/imgs/{categorySlug}.png', 'TrackingController@imageTrackByCategory');

Route::get('/', function() {
    return redirect('/login');
});

Route::get('/logout', function() {
    Authentication::logout();
    return redirect()->action('ClientController@login')->with('success', 'Successfully logged out!');
});

Route::middleware(['auth.guest'])->group(function () {
    Route::get('/login', 'ClientController@login');
    Route::post('/login', 'ClientController@loginAction');
});

Route::middleware(['auth.all'])->group(function () {
    Route::get('/dashboard', 'ClientController@dashboard');
    
    Route::get('/links', 'ClientController@links')->name('links.index');
    Route::get('/links/{page}', 'ClientController@links')->name('links.page');

    Route::get('/indexer', 'ClientController@indexer');
    Route::post('/indexer', 'ClientController@indexerAction');
});