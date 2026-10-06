<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Employee\CustomerController as EmployeeCustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;

// ======================================================
// หน้าแรก / Dashboard ร้านค้า
// ======================================================
Route::get('/', function () {
    $products = Product::all();

    return view('welcome', compact('products'));
})->name('home');

// ======================================================
// Authentication - ผู้ที่ยังไม่ได้ Login
// ======================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // สมัครสมาชิกด้วยตัวเองได้เฉพาะ Customer
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ======================================================
// ผู้ที่ Login แล้ว - ฟังก์ชันทั่วไป
// ======================================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::put('/profile/{user}', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/{user}/password', [UserController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/delete', [UserController::class, 'destroySelf'])->name('profile.destroy');

});


// ======================================================
// โปรไฟล์และรายงาน - Staff / Manager / Admin
// ======================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{order}', [ReportController::class, 'show'])->name('reports.show');
});

// ======================================================
// POS - Employee / Manager / Admin
// ======================================================
Route::middleware(['auth', 'role:employee,manager,admin'])
    ->prefix('pos')
    ->name('pos.')
    ->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::get('/member', [POSController::class, 'findMember'])->name('member');

        // พนักงาน/Manager/Admin สมัครสมาชิกให้ลูกค้า
        Route::get('/customers/create', [EmployeeCustomerController::class, 'create'])
            ->name('customers.create');

        Route::post('/customers', [EmployeeCustomerController::class, 'store'])
            ->name('customers.store');

        Route::post('/checkout', [POSController::class, 'checkout'])
            ->name('checkout');
    });

// ======================================================
// Admin เท่านั้น
// ======================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // จัดการพนักงาน
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::patch('/employees/{user}/role', [EmployeeController::class, 'changeRole'])
            ->name('employees.changeRole');
        Route::delete('/employees/{user}', [EmployeeController::class, 'destroy'])
            ->name('employees.destroy');

        // จัดการสินค้า
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

