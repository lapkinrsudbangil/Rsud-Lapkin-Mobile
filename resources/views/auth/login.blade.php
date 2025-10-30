<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
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

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 380px;
            text-align: center;
        }

        .login-container img {
            height: 60px;
            margin: 0 10px;
        }

        h2 {
            color: #006633;
            font-weight: 600;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #009970;
            border: none;
            color: white;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #007a59;
        }

        .links {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .links a {
            font-size: 13px;
            color: #009970;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logos">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo rsud">
        </div>
        <h2>Masuk ke Akun Anda</h2>

        @if(session('success'))
        <div style="
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="input-group">
        <label>USER</label>
        <input type="text" name="user_id" placeholder="Masukkan ID" required>
    </div>

    <div class="input-group">
        <label>PASSWORD</label>
        <input type="password" name="password" placeholder="Masukkan Password" required>
    </div>

    <button type="submit">MASUK</button>

    <div class="links">
        <a href="/register">Daftar Akun</a>
        <a href="/forgot-password">Lupa Password?</a>
            </div>
        </form>
    </div>
</body>
</html>
