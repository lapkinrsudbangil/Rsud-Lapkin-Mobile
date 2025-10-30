<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyCodeController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.verify-code'); 
    }

    public function verifyCode(Request $request)
    {
        // nanti diisi logika untuk memverifikasi kode
        return "Kode berhasil diverifikasi (sementara)";
    }
}
