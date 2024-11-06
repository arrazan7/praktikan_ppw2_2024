<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_pengguna = User::select('name', 'level', 'photo', 'photo_ext')->get();

        return view('users.index', compact('data_pengguna'));
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
        if (Auth::user()->id == $id) {
            // Ambil data pengguna berdasarkan id
            $pengguna = User::findOrFail($id);

            // Tampilkan view edit dengan data pengguna
            return view('users.edit', compact('pengguna'));
        } else {
            return redirect()->route('dashboard')->withError('Anda tidak bisa mengedit akun pengguna lain!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,' . $id,
            'photo' => 'image|nullable|max:10000|mimes:jpg,jpeg,png', // batas ukuran image 10MB
        ]);

        // Ambil data pengguna berdasarkan ID
        $pengguna = User::findOrFail($id);

        if ($request->hasFile('photo')) {
            // Hapus image lama
            if ($pengguna->photo != null) {
                File::delete(public_path() . '/storage/images/users/original/' . $pengguna->photo . '_Original.' . $pengguna->photo_ext);
                File::delete(public_path() . '/storage/images/users/square/' . $pengguna->photo . '_Square.' . $pengguna->photo_ext);
                File::delete(public_path() . '/storage/images/users/thumbnail/' . $pengguna->photo . '_Thumbnail.' . $pengguna->photo_ext);
            }

            $manager = new ImageManager(new Driver());

            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameModified = $filenameWithoutExt . '_' . time();

            // Save original image
            $filenameOriginal = $filenameModified . '_Original.' . $extension;
            $request->file('photo')->storeAs('images/users/original', $filenameOriginal);

            // Create and save square image
            $squareImage = $manager->read($request->file('photo')->path());
            $smallestDimension = min($squareImage->width(), $squareImage->height());
            $squareImage->crop($smallestDimension, $smallestDimension, ($squareImage->width() - $smallestDimension) / 2, ($squareImage->height() - $smallestDimension) / 2);
            $squareImage->save(storage_path('app/public/images/users/square/' . $filenameModified . '_Square.' . $extension));

            // Create and save thumbnail
            $thumbnailImage = $squareImage->scale(width: 100)->toJpeg();
            $thumbnailImage->save(storage_path('app/public/images/users/thumbnail/' . $filenameModified . '_Thumbnail.' . $extension));
        } else {
            if ($pengguna->photo != null) {
                $filenameModified = $pengguna->photo;
                $extension = $pengguna->photo_ext;
            } else {
                $filenameModified = null;
                $extension = null;
            }
        }

        try {
            // Update data pengguna dengan data yang baru
            $pengguna->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'photo' => $filenameModified,
                'photo_ext' => $extension,
            ]);

            // Flash message sukses jika berhasil
            return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Ambil pengguna berdasarkan ID
            $pengguna = User::findOrFail($id);

            $photoPengguna = $pengguna->photo;
            $extensionPengguna = $pengguna->photo_ext;

            // Delete data pengguna
            $hapusPengguna = $pengguna->delete();

            if ($hapusPengguna) {
                File::delete(public_path() . '/storage/images/users/original/' . $photoPengguna . '_Original.' . $extensionPengguna);
                File::delete(public_path() . '/storage/images/users/square/' . $photoPengguna . '_Square.' . $extensionPengguna);
                File::delete(public_path() . '/storage/images/users/thumbnail/' . $photoPengguna . '_Thumbnail.' . $extensionPengguna);
            }

            // Flash message sukses jika berhasil
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            // Flash message error jika gagal
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
