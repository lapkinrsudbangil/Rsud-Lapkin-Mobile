<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'RSUD Bangil')</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; margin:0; background:#E3F8F6; }
    header { background:#fff; padding:12px 30px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
    main { padding:30px; }
    .container { max-width:1000px; margin: 0 auto; }
    .card { background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
    .btn { background:#009970; color:#fff; padding:8px 14px; border-radius:8px; border:none; cursor:pointer; }
    .form-control { width:100%; padding:8px 10px; border:1px solid #ddd; border-radius:8px; }
    textarea.form-control { min-height:120px; }
  </style>
</head>
<body>
  <header>
    <div style="display:flex; align-items:center; gap:12px;">
      <img src="{{ asset('images/logo_pemda.png') }}" style="height:44px;" alt="logo">
      <div style="font-weight:700;">RSUD Bangil</div>
    </div>
    <div>
      <a href="{{ route('home') }}" style="text-decoration:none; color:#000; font-weight:600;">Beranda</a>
    </div>
  </header>

  <main>
    <div class="container">
      @if(session('success'))
        <div style="margin-bottom:12px; padding:10px; background:#e6ffef; border:1px solid #b8f0d1; border-radius:8px;">{{ session('success') }}</div>
      @endif
      @yield('content')
    </div>
  </main>

  <footer style="background:#fff; padding:12px 30px; text-align:center; border-top:1px solid #eee;">
    © 2025 RSUD Bangil
  </footer>
</body>
</html>
