<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Menampilkan form lupa password
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    // 2. Mengirim kode verifikasi ke email
    public function sendCode(Request $request)
    {
        $request->validate([
            'email_or_id' => 'required',
        ]);

        // Cek user berdasarkan email
        $user = User::where('email', $request->email_or_id)
                    ->orWhere('id_pegawai', $request->email_or_id)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Email atau ID tidak ditemukan!');
        }

        // Generate kode 6 digit
        $code = rand(100000, 999999);

        // Simpan ke tabel verifikasi
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $code, 'created_at' => now()]
        );

        // Kirim email kode verifikasi
        Mail::raw("Kode verifikasi Anda: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode Verifikasi Reset Password');
        });

        return redirect()->route('verify.form')->with('email', $user->email);
    }

    // 3. Tampilkan form verifikasi kode
    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    // 4. Verifikasi kode
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|array']);

        $code = implode('', $request->code);
        $email = session('email');

        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$record) {
            return back()->with('error', 'Kode verifikasi salah!');
        }

        return redirect()->route('reset.form')->with('email', $email);
    }

    // 5. Form reset password
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // 6. Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = session('email');

        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')->where('email', $email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset!');
    }
}
