<?php

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

use App\Http\Controllers\MainController;

Route::get('/', [MainController::class, "home"]);
Route::get('/home', [MainController::class, "home"]);
Route::get('/contacts', [MainController::class, "contacts"])->name('contacts');
Route::get('/new/contact', [MainController::class, "newContact"]);
Route::post('/new/contact', [MainController::class, "createContact"]);
Route::get('/contacts/delete/{id}', [MainController::class, "deleteContact"]);