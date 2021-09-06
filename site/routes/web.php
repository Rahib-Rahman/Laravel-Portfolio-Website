<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\ContactController;
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

Route::get('/', [HomeController::class,'HomeIndex']);
Route::post('/contactSend', [HomeController::class,'ContactSend']);

Route::get('/Courses', [CoursesController::class,'CoursePage']);
Route::get('/Policy', [PolicyController::class,'PolicyPage']);
Route::get('/Projects', [ProjectsController::class,'ProjectPage']);
Route::get('/Terms', [TermsController::class,'TermPage']);
Route::get('/Contact', [ContactController::class,'ContactPage']);
