<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda - RSUD Bangil</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #E3F8F6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* HEADER */
    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 40px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-container img {
      height: 60px;
    }

    nav {
      margin-left: 500px; 
    }

    nav a {
      text-decoration: none;
      color: #000;
      font-weight: 520;
      font-size: 20px;
      letter-spacing: 0.3px;
      margin: 0 10px;
      position: relative;
      transition: color 0.3s;
    }

    nav a::after {
      content: "";
      position: absolute;
      width: 0%;
      height: 2px;
      bottom: -3px;
      left: 0;
      background-color: #000;
      transition: width 0.3s;
    }

    nav a:hover::after {
      width: 100%;
    }

    nav a:hover {
      color: #009970;
    }

    .icons {
      display: flex;
      align-items: center;
      gap: 15px;
      position: relative;
    }

    .icons i {
      background-color: #E6F6F2;
      color: #009970;
      font-size: 20px;
      border-radius: 50%;
      padding: 8px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .icons i:hover {
      background-color: #CFF2E9;
    }

    /* DROPDOWN PROFIL */
    .profile-menu {
      position: absolute;
      top: 70px;
      right: 30px;
      background-color: #E6F6F2;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 160px;
      padding: 15px;
      display: none;
      flex-direction: column;
      gap: 15px;
      animation: fadeIn 0.3s ease;
    }

    .profile-item {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #000;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
    }

    .profile-item i {
      font-size: 18px;
      color: #000;
    }

    .profile-item:hover {
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* MAIN */
    main {
      text-align: center;
      padding: 70px 20px;
      flex: 1;
    }

    main h1 {
      color: #333;
      font-weight: 800;
      font-size: 28px;
      line-height: 1.6;
      margin-bottom: 15px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    }

    main p {
      font-size: 14px;
      color: #555;
      margin-bottom: 45px;
    }

    .menu-container {
      display: flex;
      justify-content: center;
      gap: 50px;
      flex-wrap: wrap;
    }

    .card {
      background: #fff;
      width: 210px;
      padding: 30px 20px;
      border-radius: 18px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .card img {
      width: 60px;
      margin-bottom: 10px;
    }

    .card h3 {
      color: #222;
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .card button {
      background-color: #009970;
      color: #fff;
      border: none;
      padding: 8px 25px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .card button:hover {
      background-color: #007b5e;
    }

    .card a {
      text-decoration: none;
      display: inline-block;
    }

    /* FOOTER */
    footer {
      background: #fff;
      text-align: center;
      font-size: 13px;
      color: #333;
      padding: 15px 0;
      border-top: 1px solid #ddd;
      font-weight: 600;
    }

    footer span {
      font-weight: 500;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
    </div>

    <nav>
      <a href="#">Panduan</a>
      <a href="#">Kontak</a>
      <a href="#">Tentang</a>
    </nav>

    <div class="icons">
      <i id="profileIcon" class="fa-solid fa-user"></i>
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
          <i class="fa-solid fa-right-from-bracket"></i>
        </button>
      </form>

      <!-- dropdown -->
      <div id="profileMenu" class="profile-menu">
        <a href="{{ route('profil') }}" class="profile-item">
        <i class="fa-solid fa-user"></i> Profil Saya</a>
        <div class="profile-item"><i class="fa-solid fa-gear"></i> Settings</div>
      </div>
    </div>
  </header>

  <main>
    <h1>Selamat datang di Sistem<br>Laporan Kinerja RSUD Bangil.</h1>
    <p>Pilih menu di bawah untuk melanjutkan</p>

    <div class="menu-container">
      <div class="card">
        <img src="{{ asset('images/icon_perjanjian.png') }}" alt="Perjanjian">
        <h3>Perjanjian</h3>
        <a href="{{ route('perjanjian.index') }}">
          <button>Buka</button>
        </a>
      </div>

      <div class="card">
        <img src="{{ asset('images/icon_kinerja.png') }}" alt="Laporan Kinerja">
        <h3>Laporan Kinerja</h3>
        <a href="{{ route('laporan.index') }}">
          <button>Buka</button>
        </a>
      </div>
    </div>
  </main>

  <footer>
    © 2025 RSUD Bangil | <span style="font-weight: 600;">Dikelola oleh Tim IT RSUD Bangil</span>
  </footer>

  <script>
    const profileIcon = document.getElementById('profileIcon');
    const profileMenu = document.getElementById('profileMenu');

    profileIcon.addEventListener('click', () => {
      profileMenu.style.display =
        profileMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    // Klik di luar menu untuk menutup
    document.addEventListener('click', (e) => {
      if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = 'none';
      }
    });
  </script>
</body>
</html>
