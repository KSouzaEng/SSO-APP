<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSO\ServiceController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/sso/login",[ServiceController::class,"getLogin"])->name("sso.login");
Route::get("/callback",[ServiceController::class,"getCallBack"])->name("sso.callback");
Route::get("/sso/connect",[ServiceController::class,"connectUser"])->name("sso.connect");




Auth::routes(['register' => false,'reset' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
