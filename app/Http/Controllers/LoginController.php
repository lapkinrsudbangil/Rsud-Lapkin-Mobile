<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'user_id' => 'required|string',
                'password' => 'required|string'
            ], [
                'user_id.required' => 'ID Pegawai wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]);

            $user = User::where('id_pegawai', $credentials['user_id'])->first();

            if (!$user) {
                Log::warning('Login attempt with non-existent ID Pegawai', [
                    'id_pegawai' => $credentials['user_id'],
                    'ip' => $request->ip()
                ]);

                throw ValidationException::withMessages([
                    'login' => ['ID Pegawai tidak ditemukan']
                ]);
            }

            // Attempt to authenticate
            if (Auth::attempt(['id_pegawai' => $credentials['user_id'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                
                // Log successful login
                Log::info('User logged in successfully', [
                    'id_pegawai' => $user->id_pegawai,
                    'nama' => $user->nama,
                    'ip' => $request->ip()
                ]);
                
                // Redirect based on role
                if (in_array($user->jabatan, ['Direktur', 'Wakil Direktur Umum dan Keuangan', 'Wakil Direktur Pelayanan'])) {
                    return redirect()->route('dashboard.direktur')
                        ->with('success', 'Selamat datang, ' . $user->nama);
                }
                
                return redirect()->route('home')
                    ->with('success', 'Selamat datang, ' . $user->nama);
            }

            // Log failed login attempt
            Log::warning('Failed login attempt', [
                'id_pegawai' => $credentials['user_id'],
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'login' => ['Password yang Anda masukkan salah']
            ]);

        } catch (ValidationException $e) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'id_pegawai' => $request->user_id,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => 'Terjadi kesalahan saat login. Silakan coba lagi atau hubungi admin.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
