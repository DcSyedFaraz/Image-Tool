<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/new', function () {
    return view('newfile');
});
Route::post('endpoint', [HomeController::class,'store'])->name('endpoint');
Route::get('/create', [HomeController::class, 'create'])->name('create');
// Route::post('/upload', [HomeController::class, 'upload'])->name('upload');
// Route::post('/mark', [HomeController::class, 'mark'])->name('mark');
