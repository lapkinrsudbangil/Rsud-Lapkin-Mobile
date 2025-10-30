<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/[a-z]/',      // harus memiliki huruf kecil
                    'regex:/[A-Z]/',      // harus memiliki huruf besar
                    'regex:/[0-9]/',      // harus memiliki angka
                    'regex:/[@$!%*#?&]/', // harus memiliki simbol
                ],
                'id_pegawai' => 'required|string|max:50|unique:users',
                'nip' => 'required|string|max:50|unique:users',
                'jabatan' => 'required|string|max:100',
                'pangkat' => 'required|string|max:100',
                'divisi' => 'required|string|max:100',
            ], [
                'nama.required' => 'Nama lengkap wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'id_pegawai.required' => 'ID Pegawai wajib diisi',
                'id_pegawai.unique' => 'ID Pegawai sudah terdaftar',
                'nip.required' => 'NIP wajib diisi',
                'nip.unique' => 'NIP sudah terdaftar',
                'jabatan.required' => 'Jabatan wajib diisi',
                'pangkat.required' => 'Pangkat wajib diisi',
                'divisi.required' => 'Divisi wajib diisi',
            ]);

            // Hash password
            $validated['password'] = Hash::make($validated['password']);

            // Create user in local database
            $user = User::create($validated);

            // Log successful registration
            Log::info('User registered successfully', [
                'id_pegawai' => $validated['id_pegawai'],
                'email' => $validated['email']
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran berhasil! Silahkan login dengan ID Pegawai dan password Anda.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'data' => $request->except(['password', 'password_confirmation'])
            ]);

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Terjadi kesalahan saat membuat akun. Silakan coba lagi atau hubungi admin.']);
        }
    }
}
