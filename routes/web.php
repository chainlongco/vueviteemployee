<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EmployeeController;

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

//Route::get('/main', function() {
//    return view('layouts.header');
//});

Route::get('/api/employees', [EmployeeController::class, 'index']);
Route::post('/api/employees', [EmployeeController::class, 'store']);
Route::get('/employees/list', ApplicationController::class);

//Route::get('{view}', ApplicationController::class)->where('view', '(.*)');