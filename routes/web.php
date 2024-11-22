<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MoneyController;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\providers\ServiceProvider;

// Publicly accessible routes (no auth required)
Route::middleware('guest')->group(function () {

// Route for login page
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('loginuser', [RegisterController::class, 'loginuser'])->name('loginuser');

});

// Protected routes (auth required)
Route::middleware('auth')->group(function () {
// Route to load the view
Route::get('/', [MoneyController::class, 'showMonthlyRevenuePage'])->name('monthlyRevenue.page');

// Route for the home page
Route::get('/home', [RegisterController::class, 'usertable'])->name('home');



// Route for registration page
Route::get('/register', function () {
    return view('register');
})->name('register');

// // Route for Money Management page
// Route::get('/management', function () {
//     return view('money-management');
// })->name('management');

// // Route for Money Management page
Route::get('/salary-management', function () {
    return view('salary');
})->name('salary-management');

Route::get('/management', function () {
    return view('money-management');
})->name('management');

// Route::get('/daily-expense', function () {
//     return view('daily-expense');
// })->name('daily-expense');

Route::get('/expenses', function () {
    return view('expenses');
})->name('expenses');


// Order routes
Route::get('/add-order', [OrderController::class, 'create'])->name('addOrderForm');
Route::post('/add-order', [OrderController::class, 'addOrder'])->name('addOrder');

// // Route for all orders
// Route::get('/all-orders', function () {
//     return view('all-orders');
// })->name('all-orders');

// User registration and login
Route::post('/registeru', [RegisterController::class, 'registeruser'])->name('registeru');


// Handle logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login')->with('success', 'You have been logged out successfully.');
})->name('logout');

// User management routes
Route::get('/users', [RegisterController::class, 'index'])->name('user.index');
Route::put('/users/{id}', [RegisterController::class, 'updateUser'])->name('updateUser');
Route::delete('/user/delete/{id}', [RegisterController::class, 'deleteUser'])->name('deleteUser');
// Route to display all orders
Route::get('all-orders', [OrderController::class, 'orderprint'])->name('all-orders');
// Route::get('/order/show/{id}', [OrderController::class, 'show'])->name('order.show');


// // Route to update an order
// Route::put('order/{id}', [OrderController::class, 'update'])->name('order.update');

// Define the update route
Route::get('/show/{id}', [OrderController::class, 'userorder'])->name('ordershow');
Route::put('/show', [OrderController::class, 'update'])->name('update-order');
// Route::post('/add-order?{id}', [OrderController::class, 'addOrder'])->name('add-order');

// Route::post('/add-order{id}', [OrderController::class, 'uorder'])->name('add-order');


// Route to show the edit form for a specific order
Route::get('/orders/{id}/edit', [OrderController::class, 'editorders'])->name('edit-order');

// Route to update the order
Route::put('/orders/{id}', [OrderController::class, 'updateOrders'])->name('orders.update');

// Route to show a specific order
Route::get('/orders/{id}', [OrderController::class, 'show1'])->name('orders.show');

// Route to Money Managment
Route::get('/salary-management', [MoneyController::class, 'salary'])->name('salary-management');
Route::post('/salary-management', [MoneyController::class, 'saveMoney'])->name('saveMoneyManagement');

Route::get('/money-management', [MoneyController::class, 'showMoneyManagement'])->name('management');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('delete-order');

// Route::delete('/expenses', [MoneyController::class, 'expen'])->name('expenses');

// Route to display daily expenses (d-expense)
Route::get('/daily-expense', [MoneyController::class, 'showDailyExpenses'])->name('d-expenses');

// Route to add an expense (redirects to expense form)
Route::get('/expenses', [MoneyController::class, 'index'])->name('expenses.index');

// Route to store a new expense
Route::post('/expenses', [MoneyController::class, 'store'])->name('expenses.store');

// Route to delete an expense
Route::delete('/daily-expense/{id}', [MoneyController::class, 'destroy'])->name('expenses.destroy');



// Route to fetch JSON data
Route::get('/monthly-revenue-data/{year}', [MoneyController::class, 'calculateMonthlyRevenue'])->name('monthlyRevenue.data');

// web.php (routes file)
Route::get('/user-profile/{id}', [MoneyController::class, 'showProfile'])->name('user.profile');

Route::delete('/user/{id}/delete', [MoneyController::class, 'deletesalary'])->name('user.delete');

Route::get('/', [MoneyController::class, 'getMonthlyRevenuePage'])->name('monthlyRevenue.page');

Route::get('/test-notify', function () {
    $admin = User::where('type', 'admin')->first(); // Fetch an admin
    $admin->notify(new AdminNotification('Test Notification', ['message' => 'This is a test.'])); });

    Route::get('/all-clients', [RegisterController::class, 'showClients'])->name('clients.index');

    Route::get('/clients/{id}/profile', [RegisterController::class, 'showClientProfile'])->name('clients.profile');
    

});