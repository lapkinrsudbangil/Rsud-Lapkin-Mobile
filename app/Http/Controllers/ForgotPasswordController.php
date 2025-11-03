<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
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

        // Kirim email kode verifikasi (tangani kegagalan pengiriman)
        try {
            Mail::raw("Kode verifikasi Anda: $code", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode Verifikasi Reset Password');
            });
            session()->flash('status', 'Kode verifikasi telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            // Log error dan beri tahu pengguna (jangan tampilkan detail error pada UI)
            Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage());
            // Fallback: simpan kode di session untuk pengujian/dev, tapi tetap simpan di DB
            session()->flash('status', "Gagal mengirim email. Jika ini lingkungan pengujian, kode adalah: $code");
        }

        return redirect()->route('verify.form')->with('email', $user->email);
    }

    // 3. Tampilkan form verifikasi kode
    public function showVerifyForm()
    {
        // Jika tidak ada email di session, kembalikan ke form lupa password
        if (!session('email')) {
            return redirect()->route('forgot.form')->with('error', 'Silakan masukkan email/ID terlebih dahulu.');
        }

        return view('auth.verify-code');
    }

    // 4. Verifikasi kode
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|array',
            'email' => 'nullable|email',
        ]);

        $code = implode('', $request->code);
        $email = $request->input('email', session('email'));

        $record = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$record) {
            return back()->with('error', 'Kode verifikasi salah atau tidak ditemukan!');
        }

        // Periksa usia token: batasi 60 menit
        $created = Carbon::parse($record->created_at);
        if ($created->addMinutes(60)->isPast()) {
            return redirect()->route('forgot.form')->with('error', 'Kode telah kadaluarsa. Silakan minta kode baru.');
        }

        return redirect()->route('reset.form')->with('email', $email);
    }

    // 5. Form reset password
    public function showResetForm()
    {
        if (!session('email')) {
            return redirect()->route('forgot.form')->with('error', 'Sesi kedaluwarsa. Silakan minta kode lagi.');
        }

        return view('auth.reset-password');
    }

    // 6. Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = $request->input('email', session('email'));

        // Pastikan ada token yang masih valid
        $record = DB::table('password_resets')->where('email', $email)->first();
        if (!$record) {
            return redirect()->route('forgot.form')->with('error', 'Token reset tidak ditemukan. Silakan minta kode lagi.');
        }

        $created = Carbon::parse($record->created_at);
        if ($created->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $email)->delete();
            return redirect()->route('forgot.form')->with('error', 'Token telah kadaluarsa. Silakan minta kode baru.');
        }

        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')->where('email', $email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset!');
    }
}
