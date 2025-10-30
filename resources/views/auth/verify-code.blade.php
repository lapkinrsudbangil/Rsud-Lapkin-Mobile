<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode</title>
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

        .verify-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 380px;
            text-align: center;
        }

        .verify-container img {
            height: 60px;
            margin: 0 10px;
        }

        h2 {
            color: #006633;
            font-weight: 600;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 12px;
            margin-top:-5;
            margin-bottom: 25px;
        }

        .code-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .code-input {
            width: 45px;
            height: 45px;
            font-size: 18px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: all 0.2s ease;
        }

        .code-input:focus {
            border-color: #009970;
            outline: none;
            box-shadow: 0 0 5px #00b37e50;
            transform: scale(1.1);
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
            font-size: 13px;
        }

        .links a {
            color: #009970;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .code-input {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="verify-container">
        <div class="logos">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo rsud">
        </div>
        <h2>Verifikasi Kode</h2>
        <p>Kode 6 digit telah dikirim ke email Anda.</p>

        <form action="{{ route('verify.code') }}" method="POST">
            @csrf
            <div class="code-inputs">
                @for ($i = 1; $i <= 6; $i++)
                    <input type="text" maxlength="1" name="code[]" class="code-input" required>
                @endfor
            </div>

            <button type="submit">VERIFIKASI</button>
        </form>

        <div class="links">
            <a href="/login">Kembali ke Login</a>
        </div>
    </div>

    <script>
        // Auto focus pindah ke input berikutnya
        const inputs = document.querySelectorAll('.code-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>

</body>
</html>
