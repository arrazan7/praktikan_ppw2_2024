<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\UserController;

use App\Http\Middleware\CustomAuthRedirect;
use App\Http\Middleware\CheckAge;
use App\Http\Middleware\Admin;

Route::get('/', function () {
    return view('welcome');
});


// Middleware guest memastikan bahwa 'HANYA PENGGUNA YANG BELUM LOGIN' yang dapat mengakses rute atau aksi tertentu.
Route::controller(LoginRegisterController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
});

// Dibuat dengan php artisan make:middleware CustomAuthRedirect
// Alasan memakai middleware custom karena page login tidak menerima session message di percobaan saya
// Middleware CustomAuthRedirect memastikan bahwa 'HANYA PENGGUNA YANG SUDAH LOGIN' yang dapat mengakses rute atau aksi tertentu.
Route::middleware([CustomAuthRedirect::class, Admin::class])->group(function () {
    Route::get('/dashboard', [LoginRegisterController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [LoginRegisterController::class, 'logout'])->name('logout');

    Route::get('/publishers', [PublisherController::class, 'index'])->name('publishers.index');
    Route::get('/publishers/create', [PublisherController::class, 'create'])->name('publishers.create');
    Route::post('/publishers', [PublisherController::class, 'store'])->name('publishers.store');
    Route::get('/publishers/edit/{id}', [PublisherController::class, 'edit'])->name('publishers.edit');
    Route::put('/publishers/{id}', [PublisherController::class, 'update'])->name('publishers.update');
    Route::delete('/publishers/{id}', [PublisherController::class, 'destroy'])->name('publishers.destroy');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/edit/{id}', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/search', [MemberController::class, 'search'])->name('members.search');

    Route::get('/borrowers', [BorrowerController::class, 'index'])->name('borrowers.index');
    Route::get('/borrowers/create', [BorrowerController::class, 'create'])->name('borrowers.create');
    Route::post('/borrowers', [BorrowerController::class, 'store'])->name('borrowers.store');
    Route::get('/borrowers/edit/{id}', [BorrowerController::class, 'edit'])->name('borrowers.edit');
    Route::put('/borrowers/{id}', [BorrowerController::class, 'update'])->name('borrowers.update');
    Route::delete('/borrowers/{id}', [BorrowerController::class, 'destroy'])->name('borrowers.destroy');
    Route::get('/borrowers/search', [BorrowerController::class, 'search'])->name('borrowers.search');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
});

Route::get('/send-email', [SendEmailController::class, 'index'])->name('send.email');
Route::post('/post-email', [SendEmailController::class, 'store'])->name('post.email');

Route::get('restricted', function () {
    return redirect()->route('dashboard')->withSuccess('Anda berusia lebih dari 18 tahun!');
})->middleware(CheckAge::class);
