<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ReportController;

Route::get('/auth/login', [AuthController::class, 'view'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');


//Tylko zalogowani
Route::middleware('auth')->group(function () {
    Route::get('/account/password', [AccountController::class, 'editPassword'])->name('account.password.edit');

    Route::patch('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

});

//Wymuszenie zmiany hasła
Route::middleware(['auth', 'force.password.change'])->group(function (){

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

});





//Dostęp do listy zamówień

Route::middleware(['auth', 'force.password.change', 'role:waiter,director,manager'])->group(function () {

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

});



//Tworzenie zamówienia

Route::middleware(['auth', 'force.password.change', 'role:waiter,manager'])->group(function () {

    Route::get('/orders/create', [OrderController::class, 'create'])
    ->name('orders.create');

    Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store');

    Route::post('/orders/{order}/payment', [OrderController::class, 'addPayment'])
    ->name('orders.payments.store');

    Route::patch('/orders/{order}/close', [OrderController::class, 'close'])
    ->name('orders.close');

    Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])
    ->name('orders.payment');

    Route::post('/orders/{order}/payment', [OrderController::class, 'processPayment'])
    ->name('orders.payment.store');

    Route::patch('/orders/{order}/discount', [OrderController::class, 'applyDiscount'])
    ->name('orders.discount');
});


//Kuchnia

Route::middleware(['auth', 'force.password.change', 'role:kitchen_assistant,cook,manager'])->group(function () {
    Route::get('/kitchen', [KitchenController::class, 'index'])
    ->name('kitchen.index');

    Route::patch('/kitchen/orders/{order}/start', [KitchenController::class, 'startPreparation'])
    ->name('kitchen.orders.start');

    Route::patch('/kitchen/orders/{order}/ready', [KitchenController::class, 'markReady'])
    ->name('kitchen.orders.ready');
});


//Odbiór zamówienia

Route::middleware(['auth', 'force.password.change', 'role:waiter,manager'])->group(function () {
    Route::patch('/orders/{order}/collect', [OrderController::class, 'collect'])
    ->name('orders.collect');
});






//Tylko rola director i manager
Route::middleware(['auth', 'force.password.change', 'role:director,manager'])->group(function () {

    Route::get('/employees/{user}/edit', [EmployeeController::class, 'edit'])
    ->name('employees.edit');

    Route::patch('/employees/{user}', [EmployeeController::class, 'update'])
    ->name('employees.update');

    Route::patch('/employees/{user}/terminate', [EmployeeController::class, 'terminate'])
    ->name('employees.terminate');

    Route::get('/employees', [EmployeeController::class, 'index'])
    ->name('employees.index');

    Route::get('/employees/create', [EmployeeController::class, 'create'])
    ->name('employees.create');

    Route::post('/employees', [EmployeeController::class, 'store'])
    ->name('employees.store');


    //Widok dań restauracji

    Route::get('/dishes', [DishController::class, 'index'])
        ->name('dishes.index');

    Route::get('/dishes/create', [DishController::class, 'create'])
        ->name('dishes.create');

    Route::post('/dishes', [DishController::class, 'store'])
        ->name('dishes.store');

    Route::get('/dishes/{dish}/edit', [DishController::class, 'edit'])
        ->name('dishes.edit');

    Route::patch('/dishes/{dish}', [DishController::class, 'update'])
        ->name('dishes.update');

    Route::patch('/dishes/{dish}/availability', [DishController::class, 'toggleAvailability'])
        ->name('dishes.availability');

    //Raporty

    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');

});

