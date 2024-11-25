<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'id' => "posts",
            'menu' => 'Gallery',
            'galleries' => Book::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(20),
        );
        return view('gallery.index')->with($data);
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/gallery",
     *     tags={"Gallery"},
     *     summary="Get list of books pictured",
     *     description="Mengambil daftar buku yang ada gambar",   
     * @OA\Response(
     *         response=200,
     *         description="Sukses mendapatkan data buku",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="status", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Berhasil mendapatkan semua buku"),
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
    public function indexAPI()
    {
        $data_buku_bergambar = Book::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->get();

        // Mereturn respons dalam format JSON
        return response()->json([
            'status' => true,
            'message' => "Berhasil mendapatkan semua buku",
            'data' => $data_buku_bergambar,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
