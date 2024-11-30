<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *   title="Book Management API",
 *   version="1.0.0",
 *   description="API untuk mengelola buku, termasuk CRUD dan pencarian",
 *   @OA\Contact(
 *       email="admin@yourdomain.com"
 *   )
 * )
 */

class BookControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Get list of books with pagination",
     *     description="Mengambil daftar buku dengan paginasi, total jumlah buku, dan total harga buku",
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     required=true,
     *     description="Paginasi halaman",
     *     @OA\Schema(type="integer", example="1")
     * ),    
     * @OA\Response(
     *         response=200,
     *         description="Sukses mendapatkan data buku",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="The Great Gatsby"),
     *                             @OA\Property(property="writer", type="string", example="F. Scott Fitzgerald"),
     *                             @OA\Property(property="picture", type="string", example="image_url.jpg")
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(property="total_books", type="integer", example=100),
     *                 @OA\Property(property="total_price", type="number", format="float", example=500000),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="per_page", type="integer", example=20),
     *                         @OA\Property(property="total_pages", type="integer", example=5),
     *                         @OA\Property(property="total_items", type="integer", example=100)
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // $batas = 20;

        // // Mengambil data buku dengan paginasi
        // $data_buku = Book::select('id', 'title', 'writer', 'picture')->paginate($batas);

        // // Menghitung jumlah total buku di database
        // $jumlah_buku = Book::count();

        // // Menjumlahkan harga semua buku di database
        // $total_harga_buku = Book::sum('price');

        // // Menentukan nomor urut berdasarkan halaman saat ini
        // $no = $batas * ($data_buku->currentPage() - 1);

        // // Mereturn respons dalam format JSON
        // return response()->json([
        //     'data' => $data_buku,
        //     'total_books' => $jumlah_buku,
        //     'total_price' => $total_harga_buku,
        //     'pagination' => [
        //         'current_page' => $data_buku->currentPage(),
        //         'per_page' => $batas,
        //         'total_pages' => $data_buku->lastPage(),
        //         'total_items' => $data_buku->total(),
        //     ]
        // ], 200);
        $batas = 20;

        // Mengambil data buku dengan paginasi
        $data_buku = Book::select('id', 'title', 'writer', 'picture')->paginate($batas);

        // Mereturn respons dalam format JSON
        return response()->json([
            'data' => $data_buku,
            'total_books' => Book::count(),
            'total_price' => Book::sum('price'),
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/books/all",
     *     tags={"Books"},
     *     summary="Get list of books",
     *     description="Mengambil daftar buku, total jumlah buku, dan total harga buku",   
     * @OA\Response(
     *         response=200,
     *         description="Sukses mendapatkan data buku",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="The Great Gatsby"),
     *                             @OA\Property(property="writer", type="string", example="F. Scott Fitzgerald"),
     *                             @OA\Property(property="picture", type="string", example="image_url.jpg")
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(property="total_books", type="integer", example=100),
     *                 @OA\Property(property="total_price", type="number", format="float", example=500000),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function all()
    {
        $data_buku = Book::select('id', 'title', 'writer', 'picture')->get();

        // Mereturn respons dalam format JSON
        return response()->json([
            'status' => true,
            'message' => "Berhasil mendapatkan semua buku",
            'data' => $data_buku,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/books/search",
     *     tags={"Books"},
     *     summary="Search books by title or writer",
     *     description="Mencari buku berdasarkan judul atau penulis, dengan paginasi dan informasi terkait jumlah buku dan total harga",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=true,
     *         description="Kata kunci pencarian untuk mencari buku berdasarkan judul atau penulis",
     *         @OA\Schema(type="string", example="The Great Gatsby")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sukses mendapatkan hasil pencarian buku",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="The Great Gatsby"),
     *                             @OA\Property(property="writer", type="string", example="F. Scott Fitzgerald"),
     *                             @OA\Property(property="picture", type="string", example="image_url.jpg")
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(property="total_books", type="integer", example=100),
     *                 @OA\Property(property="total_price", type="number", format="float", example=500000),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="per_page", type="integer", example=20),
     *                         @OA\Property(property="total_pages", type="integer", example=5),
     *                         @OA\Property(property="total_items", type="integer", example=100)
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        $batas = 2000;
        $search = $request->input('search');

        // Mengambil data buku dengan paginasi
        $data_buku = Book::where('title', 'like', '%' . $search . '%')
            ->orWhere('writer', 'like', '%' . $search . '%')
            ->paginate($batas);

        $jumlah_buku = Book::count(); // Menghitung jumlah total buku di database
        $total_harga_buku = Book::sum('price'); // Menjumlahkan harga semua buku di database
        $no = $batas * ($data_buku->currentPage() - 1);

        // Mereturn response JSON
        return response()->json([
            'data' => $data_buku->items(),
            'total_books' => $jumlah_buku,
            'total_price' => $total_harga_buku,
            'pagination' => [
                'current_page' => $data_buku->currentPage(),
                'per_page' => $data_buku->perPage(),
                'total_pages' => $data_buku->lastPage(),
                'total_items' => $data_buku->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     description="Create a new book with title, writer, and other details",
     *     operationId="storeBook",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data untuk membuat buku baru",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     description="Judul buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="writer",
     *                     description="Penulis buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="publisher_id",
     *                     description="ID penerbit",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="publication_year",
     *                     description="Tahun publikasi buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="number_of_pages",
     *                     description="Jumlah halaman buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Harga buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Deskripsi buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="picture",
     *                     description="File gambar buku",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Buku berhasil dibuat",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Buku berhasil dibuat!"),
     *                 @OA\Property(property="book", type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Contoh Buku"),
     *                         @OA\Property(property="writer", type="string", example="Nama Penulis"),
     *                         @OA\Property(property="publisher_id", type="integer", example=123),
     *                         @OA\Property(property="publication_year", type="integer", example=2020),
     *                         @OA\Property(property="number_of_pages", type="integer", example=300),
     *                         @OA\Property(property="price", type="integer", example=50000),
     *                         @OA\Property(property="description", type="string", example="Ini adalah buku contoh."),
     *                         @OA\Property(property="picture", type="string", example="image_123.jpg")
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Request tidak valid",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Request tidak valid"),
     *                 @OA\Property(property="error", type="string", example="Kesalahan dalam data yang dikirim")
     *             }
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'writer' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id', // Relasi dengan Publisher
            'publication_year' => 'required|integer|digits:4',
            'number_of_pages' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'required|string',
            'picture' => 'image|nullable|max:10000', // batas ukuran image 10MB
        ]);

        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameDatabase = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // Save image
            $request->file('picture')->storeAs('images/books', $filenameDatabase);

            // Ubah nilai request picture
            $validatedData['picture'] = $filenameDatabase;
        } else {
            $validatedData['picture'] = null;
        }

        try {
            // Simpan buku baru
            $book = Book::create($validatedData);

            // Mengembalikan response JSON sukses
            return response()->json([
                'message' => 'Book created successfully!',
                'book' => $book
            ], 201); // HTTP Status 201 Created
        } catch (\Exception $e) {
            // Mengembalikan response JSON error
            return response()->json([
                'message' => 'Failed to create book',
                'error' => $e->getMessage()
            ], 500); // HTTP Status 500 Internal Server Error
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Get details of a specific book",
     *     description="Retrieve a book by its ID along with its publisher information",
     *     operationId="showBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details of the requested book",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="book", type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Contoh Buku"),
     *                         @OA\Property(property="writer", type="string", example="Nama Penulis"),
     *                         @OA\Property(property="publisher_id", type="integer", example=123),
     *                         @OA\Property(property="publication_year", type="integer", example=2020),
     *                         @OA\Property(property="number_of_pages", type="integer", example=300),
     *                         @OA\Property(property="price", type="integer", example=50000),
     *                         @OA\Property(property="description", type="string", example="Ini adalah buku contoh."),
     *                         @OA\Property(property="picture", type="string", example="image_123.jpg")
     *                     }
     *                 ),
     *                 @OA\Property(property="publisher", type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=123),
     *                         @OA\Property(property="name", type="string", example="Penerbit XYZ"),
     *                         @OA\Property(property="address", type="string", example="Jl. Penerbit No. 10")
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Buku tidak ditemukan")
     *             }
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            // Ambil buku berdasarkan ID
            $buku = Book::findOrFail($id);
            $penerbit = Publisher::findOrFail($buku->publisher_id);

            // Mengembalikan response JSON dengan data buku dan penerbit
            return response()->json([
                'book' => $buku,
                'publisher' => $penerbit
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan response JSON error jika buku tidak ditemukan
            return response()->json([
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update a specific book",
     *     description="Update the details of a book by its ID, including optional image upload",
     *     operationId="updateBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to update",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data to update the book including image upload (optional)",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     description="Judul buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="writer",
     *                     description="Penulis buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="publisher_id",
     *                     description="ID penerbit",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="publication_year",
     *                     description="Tahun publikasi buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="number_of_pages",
     *                     description="Jumlah halaman buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Harga buku",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Deskripsi buku",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="picture",
     *                     description="File gambar buku",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book successfully updated",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Book updated successfully!")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Buku tidak ditemukan")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data provided",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Invalid input data")
     *             }
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        Log::info($request->all());
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'writer' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id', // Relasi dengan Publisher
            'publication_year' => 'required|integer|digits:4',
            'number_of_pages' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'required|string',
            'picture' => 'image|nullable|max:10000|mimes:jpg,jpeg,png', // batas ukuran image 10MB
        ]);

        // Ambil buku berdasarkan ID
        $buku = Book::findOrFail($id);

        if ($request->hasFile('picture')) {
            // Hapus image lama
            if ($buku->picture != null) {
                File::delete(public_path() . '/storage/images/books/' . $buku->picture);
            }

            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameDatabase = $filenameWithoutExt . '_' . time() . '.' . $extension;

            // Save image
            $request->file('picture')->storeAs('images/books', $filenameDatabase);

            // Ubah nilai request picture
            $validatedData['picture'] = $filenameDatabase;
        } else {
            if ($buku->picture != null) {
                $validatedData['picture'] = $buku->picture;
            } else {
                $validatedData['picture'] = null;
            }
        }

        try {
            // Update data buku dengan data yang baru
            $buku->update($validatedData);

            // Mengembalikan response JSON sukses jika berhasil
            return response()->json([
                'message' => 'Book updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan response JSON error jika gagal
            return response()->json([
                'message' => 'Failed to update book: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete a specific book",
     *     description="Delete a book by its ID, along with its image file if exists",
     *     operationId="deleteBook",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to delete",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book successfully deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Book deleted successfully!")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to delete book",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="message", type="string", example="Failed to delete book: error message")
     *             }
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            // Ambil buku berdasarkan ID
            $buku = Book::findOrFail($id);

            $pictureBuku = $buku->picture;

            // Hapus data buku
            $hapusBuku = $buku->delete();

            if ($hapusBuku) {
                // Hapus file gambar
                File::delete(public_path() . '/storage/images/books/' . $pictureBuku);
            }

            // Kembalikan respons sukses dalam format JSON
            return response()->json([
                'message' => 'Book deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Kembalikan respons error dalam format JSON
            return response()->json([
                'message' => 'Failed to delete book: ' . $e->getMessage()
            ], 400);
        }
    }
}
