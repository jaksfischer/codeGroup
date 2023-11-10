<?php

use App\Http\Controllers\PlayersController;
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
    return view('welcome');
});
Route::resource('players', PlayersController::class);
Route::group(['prefix' => 'teams', 'as', 'teams.'], function () {
    Route::get('teams', [PlayersController::class, 'teams'])->name('teams');
    Route::post('sort', [PlayersController::class, 'sort'])->name('sort');
});
