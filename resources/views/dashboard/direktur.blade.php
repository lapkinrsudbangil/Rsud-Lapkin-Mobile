<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Direktur - RSUD Bangil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #E3F8F6;
        }
        
        header {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 40px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        
        .header-title {
            font-weight: 600;
            font-size: 18px;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .header-right a {
            text-decoration: none;
            color: #333;
        }
        
        .header-right i {
            font-size: 20px;
            cursor: pointer;
        }
        
        main {
            padding: 40px;
        }
        
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 30px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .dashboard-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .dashboard-card h2 {
            font-size: 18px;
            color: #009970;
            margin-top: 0;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin: 15px 0;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        form {
            display: inline;
        }
        
        .logout-button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #333;
        }
        
        .logout-button i {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">Dashboard Direktur</div>
        <div class="header-right">
            <a href="{{ route('profil') }}">
                <i class="fas fa-user"></i>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <main>
        <h1>Selamat datang, {{ Auth::user()->nama }}</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h2>Total Laporan</h2>
                <div class="stat-number">0</div>
                <div class="stat-label">Laporan bulan ini</div>
            </div>
            
            <div class="dashboard-card">
                <h2>Menunggu Review</h2>
                <div class="stat-number">0</div>
                <div class="stat-label">Laporan perlu ditinjau</div>
            </div>
            
            <div class="dashboard-card">
                <h2>Kinerja Pegawai</h2>
                <div class="stat-number">0%</div>
                <div class="stat-label">Rata-rata pencapaian</div>
            </div>
        </div>
    </main>
</body>
</html>