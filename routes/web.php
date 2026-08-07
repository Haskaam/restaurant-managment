<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;

Route::get('/auth/login', [AuthController::class, 'view'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');


//Tylko zalogowani
Route::middleware('auth')->group(function () {
    Route::get('/account/password', [AccountController::class, 'editPassword'])->name('account.password.edit');

    Route::patch('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('/dashboard');
    });

});

//Wymuszenie zmiany hasła
Route::middleware(['auth', 'force.password.change'])->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});

//Tylko rola director
Route::middleware(['auth', 'force.password.change', 'role:director'])->group(function (){
    Route::get('/employees/{user}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::patch('/employees/{user}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::patch('/employees/{user}/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store']);
});

