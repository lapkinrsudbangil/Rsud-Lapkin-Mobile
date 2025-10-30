<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e8f8f6; 
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .register-container {
            background-color: white;
            width: 450px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 70px;
            height: auto;
        }

        .register-title {
            text-align: center;
            font-weight: 700;
            color:  #006b46;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            background-color: #f9f9f9;
        }

        .btn-register {
            background-color: #007e59;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: none;
            transition: 0.3s;
        }

        .btn-register:hover {
            background-color: #007e59;
        }

        .text-login {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .text-login a {
            color:#009668;
            text-decoration: none;
        }

        .text-login a:hover {
            text-decoration: underline;
        }

        select.form-control {
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            background-image: none;
            padding-right: 15px; 
            background-position: right 15px center; 
        }
    </style>
</head>
<body>
    <div class="register-container">

        <!-- Logo di atas -->
        <div class="logo-container">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo rsud">
        </div>

        <h4 class="register-title">Daftar Akun</h4>
        <p class="subtitle">Isi data berikut untuk membuat akun pegawai baru.</p>

        @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
                <input type="text" name="id_pegawai" class="form-control" placeholder="ID Pegawai" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                @error('nama')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nip" class="form-control" placeholder="NIP" required>
            </div>
            <div class="mb-3">
                <select name="jabatan" class="form-control" required>
                    <option value="" disabled selected>Pilih Jabatan</option>
                    <option>Direktur</option>
                    <option>Wadir Umum dan Keuangan</option>
                    <option>Wadir Pelayanan</option>
                    <option>Kabid Pelayanan</option>
                    <option>Kabid Pelayanan Keperawatan</option>
                    <option>Kabid Pelayanan Penunjang</option>
                    <option>Kabag SDM dan Pengembangan</option>
                    <option>Kabag Umum</option>
                    <option>Kabag Keuangan</option>
                    <option>Ketua Tim Kerja</option>
                    <option>Admin/Staff</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="text" name="pangkat" class="form-control" placeholder="Pangkat" required>
            </div>
            <div class="mb-3">
                <input type="text" name="divisi" class="form-control" placeholder="Divisi" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <div class="password-strength mt-2" style="display: none;">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="strength-text mt-1 d-block"></small>
                </div>
                <small class="text-muted">
                    Password harus memiliki minimal 8 karakter, huruf besar, huruf kecil, angka, dan simbol
                </small>
            </div>
            <div class="mb-3">
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="form-control" placeholder="Konfirmasi Password" required>
                <div class="password-match mt-1" style="display: none;">
                    <small class="text-danger">Password tidak cocok</small>
                </div>
            </div>

            <button type="submit" class="btn-register" id="submitBtn" disabled>DAFTAR</button>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');
                const submitBtn = document.getElementById('submitBtn');
                const strengthMeter = document.querySelector('.password-strength');
                const progressBar = document.querySelector('.progress-bar');
                const strengthText = document.querySelector('.strength-text');
                const matchText = document.querySelector('.password-match');

                function checkPasswordStrength(password) {
                    let strength = 0;
                    const patterns = {
                        length: password.length >= 8,
                        lowercase: /[a-z]/.test(password),
                        uppercase: /[A-Z]/.test(password),
                        numbers: /\d/.test(password),
                        symbols: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                    };

                    // Calculate strength
                    strength = Object.values(patterns).filter(Boolean).length;

                    // Update UI
                    strengthMeter.style.display = 'block';
                    let percentage = (strength / 5) * 100;
                    progressBar.style.width = percentage + '%';

                    // Update colors and text
                    if (strength <= 2) {
                        progressBar.style.backgroundColor = '#dc3545';
                        strengthText.style.color = '#dc3545';
                        strengthText.textContent = 'Password lemah';
                    } else if (strength <= 3) {
                        progressBar.style.backgroundColor = '#ffc107';
                        strengthText.style.color = '#ffc107';
                        strengthText.textContent = 'Password sedang';
                    } else {
                        progressBar.style.backgroundColor = '#28a745';
                        strengthText.style.color = '#28a745';
                        strengthText.textContent = 'Password kuat';
                    }

                    return strength >= 4;
                }

                function checkPasswordMatch() {
                    const password = passwordInput.value;
                    const confirm = confirmInput.value;
                    
                    if (confirm) {
                        matchText.style.display = 'block';
                        if (password === confirm) {
                            matchText.innerHTML = '<small class="text-success">Password cocok</small>';
                            return true;
                        } else {
                            matchText.innerHTML = '<small class="text-danger">Password tidak cocok</small>';
                            return false;
                        }
                    }
                    return false;
                }

                function updateSubmitButton() {
                    const isStrong = checkPasswordStrength(passwordInput.value);
                    const isMatch = checkPasswordMatch();
                    submitBtn.disabled = !(isStrong && isMatch);
                }

                passwordInput.addEventListener('input', updateSubmitButton);
                confirmInput.addEventListener('input', updateSubmitButton);
            });
            </script>
        </form>

        <div class="text-login">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </div>
</body>
</html>
