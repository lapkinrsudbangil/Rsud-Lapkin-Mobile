<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - RSUD Bangil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #E9F8F8;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 380px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            height: 60px;
            margin: 0 10px;
        }

        h2 {
            color: #006633;
            text-align: center;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        button {
            width: 100%;
            background-color: #009970;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #007a59;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .back-to-login a {
            color: #009970;
            text-decoration: none;
            font-weight: 500;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
        </div>

        <h2>Lupa Password?</h2>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('forgot.post') }}">
            @csrf
            <div class="form-group">
                <label>ID / Email</label>
                <input type="text" name="email_or_id" value="{{ old('email_or_id') }}" required 
                       placeholder="Masukkan ID atau email yang terdaftar">
            </div>

            <button type="submit">Kirim Kode Verifikasi</button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">Kembali ke halaman login</a>
        </div>
    </div>
</body>
</html>