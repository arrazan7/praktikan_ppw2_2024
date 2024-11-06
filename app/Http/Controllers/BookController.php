<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Book;
use App\Models\Publisher;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batas = 20;
        // Mengambil data buku dengan paginasi
        $data_buku = Book::select('id', 'title', 'writer', 'picture')->paginate($batas);
        $jumlah_buku = Book::count(); // Menghitung jumlah total buku di database
        $total_harga_buku = Book::sum('price'); // Menjumlahkan harga semua buku di database
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('books.index', compact('data_buku', 'jumlah_buku', 'total_harga_buku', 'no'));
    }

    public function search(Request $request)
    {
        $batas = 2000;
        $search = $request->input('search');
        // Mengambil data buku dengan paginasi
        $data_buku = Book::where('title', 'like', '%' . $search . '%')->orwhere('writer', 'like', '%' . request('search') . '%')->paginate($batas);
        $jumlah_buku = Book::count(); // Menghitung jumlah total buku di database
        $total_harga_buku = Book::sum('price'); // Menjumlahkan harga semua buku di database
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('books.search', compact('data_buku', 'jumlah_buku', 'total_harga_buku', 'no', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Jika ada relasi dengan Publisher, ambil data Publisher
        $publishers = Publisher::all();
        return view('books.create', compact('publishers'));
    }

    /**
     * Store a newly created resource in storage.
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
            $filenameDatabase = $filenameWithoutExt . '_' . time() . $extension;

            // Save image
            $request->file('picture')->storeAs('images/books', $filenameDatabase);

            // Ubah nilai request picture
            $validatedData['picture'] = $filenameDatabase;
        } else {
            $validatedData['picture'] = null;
        }

        try {
            // Simpan buku baru
            Book::create($validatedData);

            // Flash message success jika berhasil
            return redirect()->route('books.index')->with('success', 'Book created successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to create book: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Ambil buku berdasarkan ID
            $buku = Book::findOrFail($id);
            $penerbit = Publisher::findOrFail($buku->publisher_id);

            // Tampilkan view show dengan data buku
            return view('books.show', compact('buku', 'penerbit'));
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to show book: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data buku berdasarkan id
        $buku = Book::findOrFail($id);

        // Ambil semua data publisher untuk dropdown
        $publishers = Publisher::all();

        // Tampilkan view edit dengan data buku dan publisher
        return view('books.edit', compact('buku', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
            $filenameDatabase = $filenameWithoutExt . '_' . time() . $extension;

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

            // Flash message sukses jika berhasil
            return redirect()->route('books.index')->with('success', 'Book updated successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to update book: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Ambil buku berdasarkan ID
            $buku = Book::findOrFail($id);

            $pictureBuku = $buku->picture;

            // Delete data buku
            $hapusBuku = $buku->delete();

            if ($hapusBuku) {
                File::delete(public_path() . '/storage/images/books/' . $pictureBuku);
            }

            // Flash message sukses jika berhasil
            return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to delete book: ' . $e->getMessage());
        }
    }
}
