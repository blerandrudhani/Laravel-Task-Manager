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



// Route::get('/', [App\Http\Controllers\PagesController::class, 'index']);
// Route::get('/login', [App\Http\Controllers\PagesController::class, 'login']);
// Route::get('/signup', [App\Http\Controllers\PagesController::class, 'signup']);
// Route::get('/main', [App\Http\Controllers\PagesController::class, 'main']);


/*
Route::any('/', function () {
    return view('home');
});
Route::any('/login', function () {
    return view('login');
});
Route::get('/signup', function () {
    return view('signup');
});
*/

Auth::routes();

Route::any('/', [App\Http\Controllers\ToDoControllers::class, 'getAllTasks'])->name('home');


Route::post('/save',[App\Http\Controllers\ToDoControllers::class, 'Insert'])->name('save');
Route::get('/getTasksData',[App\Http\Controllers\ToDoControllers::class, 'getDataById'])->name('getTasksData');
Route::delete('/deleteTask',[App\Http\Controllers\ToDoControllers::class, 'deleteTask'])->name('deleteTask');
Route::post('/update',[App\Http\Controllers\ToDoControllers::class, 'updateTask'])->name('update');
Route::get('/filterTasks',[App\Http\Controllers\ToDoControllers::class, 'filterTasks'])->name('filterTasks');


Route::get('/logout', function(){
    Auth::logout();
    return view('login');
});
