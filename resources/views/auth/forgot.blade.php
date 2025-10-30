<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #E3F8F6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background: #fff;
            width: 360px;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        h3 {
            color: #006C52;
            font-weight: 600;
            margin: 10px 0 5px;
        }

        p {
            color: #555;
            font-size: 13px;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #00966B;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        button:hover {
            background-color: #007a57;
        }

        .back {
            margin-top: 15px;
        }

        .back a {
            text-decoration: none;
            font-size: 13px;
            color: #00966B;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO -->
        <div class="logo">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
        </div>

        <!-- JUDUL -->
        <h3>Lupa Password</h3>
        <p>Masukkan ID atau email untuk menerima kode verifikasi.</p>

        <!-- FORM -->
        <form method="POST" action="{{ route('forgot.post') }}">
            @csrf
            <input type="text" name="email_or_id" placeholder="ID/Email" required>
            <button type="submit">KIRIM KODE</button>
        </form>

        <!-- LINK -->
        <div class="back">
            <a href="/login">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>