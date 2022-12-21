<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => 'isAuthorized'], function () {
    Route::get('/api/employees', [EmployeeController::class, 'index']);
    Route::post('/api/employees', [EmployeeController::class, 'store'])->name('store-employee');
    //Route::get('/employees/list', EmployeeController::class)->name('employeeList');
    Route::post('/api/employees/delete/{id}', [EmployeeController::class, 'delete']);
    Route::get('/api/employees/edit/{id}', [EmployeeController::class, 'edit']);

    Route::get('employees/{vue?}', EmployeeController::class)->where('vue', '.*?');     // This line can resolve 404 error when click reload browser at http://localhost:8000/employees/create
    //Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
    Route::get('api/usersWithRoles', [UserController::class, 'listUsersWithRoles']);
    Route::post('/api/users/delete/{id}', [UserController::class, 'userDelete']);
    Route::post('/api/users/edit', [UserController::class, 'userEdit']);
});

Route::get('users/{vue?}', UserController::class)->where('vue', '.*?');
/*Route::get('users/{vue?}', function () {
    return view('users.index');
})->where('vue', '.*?');*/

//Route::get('/api/login', [UserController::class, 'index']);
Route::get('/api/user/{id}', [UserController::class, 'retrieveUser']);
Route::post('/api/login', [UserController::class, 'signin']);
Route::get('/api/logout', [UserController::class, 'logout']);
//Route::post('/login', [UserController::class, 'signin'])->name('login-submit');
Route::post('/api/signup', [UserController::class, 'signup']);