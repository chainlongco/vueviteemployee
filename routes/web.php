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
Route::get('/employees/list', EmployeeController::class)->name('employeeList');
Route::post('/api/employees/delete/{id}', [EmployeeController::class, 'delete']);
Route::get('/api/employees/edit/{id}', [EmployeeController::class, 'edit']);

Route::get('employees/{vue?}', EmployeeController::class)->where('vue', '.*?');     // This line can resolve 404 error when click reload browser at http://localhost:8000/employees/create
//Route::get('{view}', ApplicationController::class)->where('view', '(.*)');