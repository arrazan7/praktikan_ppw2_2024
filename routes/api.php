<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetController;
use App\Http\Controllers\BookControllerAPI;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/greet', [GreetController::class, 'greet'])->name('greet');

Route::prefix('books')->group(function () {
    // Menampilkan daftar buku
    Route::get('/', [BookControllerAPI::class, 'index'])->name('books.index');

    // Mencari buku berdasarkan judul atau penulis
    Route::get('/search', [BookControllerAPI::class, 'search'])->name('books.search');

    // Menyimpan buku baru
    Route::post('/', [BookControllerAPI::class, 'store'])->name('books.store');

    // Menampilkan detail buku
    Route::get('/{id}', [BookControllerAPI::class, 'show'])->name('books.show');

    // Memperbarui data buku
    Route::put('/{id}', [BookControllerAPI::class, 'update'])->name('books.update');

    // Menghapus buku
    Route::delete('/{id}', [BookControllerAPI::class, 'destroy'])->name('books.destroy');
});