<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeraController;
use App\Http\Controllers\MskiController;
use App\Http\Controllers\PdController;
use App\Http\Controllers\BankController;

// ==================== HALAMAN AWAL ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== DASHBOARD REDIRECT (OTOMATIS BY ROLE) ====================
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'staff' => redirect()->route('staff.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => redirect('/'),
    };
})->name('dashboard');

// ==================== AUTENTIKASI ====================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ==================== AUTH SCAFFOLDING (BREEZE / JETSTREAM) ====================
require __DIR__ . '/auth.php';

// ==================== ROUTE TERPROTEKSI (LOGIN) ====================
Route::middleware('auth')->group(function () {

    // ---------- PROFILE ----------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== ADMIN ====================
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // CRUD Admin
        Route::resource('/admin/admins', AdminUserController::class)->names('admin.admins');

        // CRUD Staff
        Route::resource('/admin/staffs', AdminStaffController::class)->names('admin.staffs');

        // CRUD User (role: user)
        Route::get('/admin/users', [AdminUserController::class, 'indexUser'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminUserController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users/store', [AdminUserController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{id}/edit', [AdminUserController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [AdminUserController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroyUser'])->name('admin.users.destroy');
    });

    // ==================== STAFF ====================
    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    });

    // ==================== USER ====================
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

        // Layanan
        Route::get('/user/layanan-vera/create', [VeraController::class, 'create'])->name('vera.create');
        Route::post('/user/layanan-vera', [VeraController::class, 'store'])->name('vera.store');

        Route::get('/user/layanan-mski/create', [MskiController::class, 'create'])->name('mski.create');
        Route::post('/user/layanan-mski', [MskiController::class, 'store'])->name('mski.store');

        Route::get('/user/layanan-pd/create', [PdController::class, 'create'])->name('pd.create');
        Route::post('/user/layanan-pd', [PdController::class, 'store'])->name('pd.store');

        Route::get('/user/layanan-bank/create', [BankController::class, 'create'])->name('bank.create');
        Route::post('/user/layanan-bank', [BankController::class, 'store'])->name('bank.store');
    });

    // ==================== LOGOUT ====================
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
