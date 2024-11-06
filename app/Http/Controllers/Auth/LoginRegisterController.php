<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;
use App\Jobs\SendEmailJob;

class LoginRegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' => 'image|nullable|max:10000', // batas ukuran image 10MB
        ]);

        if ($request->hasFile('photo')) {
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
            $filenameModified = null;
            $extension = null;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'admin', // bisa diubah sesuai kebutuhan. admin atau public,
            'photo' => $filenameModified,
            'photo_ext' => $extension,
        ]);

        // Proses login otomatis setelah melakukan register
        $credentials = $request->only('email', 'password'); // Baris ini mengambil hanya email dan password dari request untuk digunakan sebagai kredensial saat mencoba untuk login. Fungsi only memastikan bahwa hanya data yang diperlukan yang diambil, yang membantu mencegah kebocoran data sensitif.
        Auth::attempt($credentials); // Fungsi ini mencoba untuk melakukan login dengan menggunakan kredensial yang telah disiapkan. Jika login berhasil, sesi pengguna akan dimulai, dan pengguna akan dianggap terautentikasi.
        $request->session()->regenerate(); // Setelah berhasil login, sesi pengguna diatur ulang. Ini penting untuk mencegah serangan "session fixation," di mana penyerang mencoba untuk menggunakan sesi yang sudah ada untuk mendapatkan akses. Dengan mengatur ulang sesi, Laravel membuat sesi baru untuk pengguna yang baru saja login.

        // Kirim email konfirmasi register
        $registerEmail = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => 'Registration Successful',
        ];

        dispatch(new SendEmailJob($registerEmail)); // Mengirim email tanda berhasil register

        return redirect()->route('dashboard')->withSuccess('You have successfully registered & logged in!');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors(['email' => 'Your provided credentials do not match in our records.',])->onlyInput('email');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('auth.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->withSuccess('You have logged out successfully!');
    }
}
