<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GreetController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BookControllerAPI;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/greet', [GreetController::class, 'greet'])->name('greet');

Route::get('/gallery', [GalleryController::class, 'indexAPI']);

Route::prefix('books')->group(function () {
    // Menampilkan daftar buku dengan paginasi
    Route::get('/', [BookControllerAPI::class, 'index'])->name('api.books.index');

    // Menampilkan daftar semua buku
    Route::get('/all', [BookControllerAPI::class, 'all']);

    // Mencari buku berdasarkan judul atau penulis
    Route::get('/search', [BookControllerAPI::class, 'search']);

    // Menyimpan buku baru
    Route::post('/', [BookControllerAPI::class, 'store']);

    // Menampilkan detail buku
    Route::get('/{id}', [BookControllerAPI::class, 'show']);

    // Memperbarui data buku
    Route::put('/{id}', [BookControllerAPI::class, 'update']);

    // Menghapus buku
    Route::delete('/{id}', [BookControllerAPI::class, 'destroy']);
});
