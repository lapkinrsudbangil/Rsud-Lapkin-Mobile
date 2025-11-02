@extends('layouts.app')

@section('content')
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h1>Laporan Kinerja</h1>
    <div>
      <a href="{{ route('laporan.my') }}" class="btn">Laporan Saya</a>
    </div>
  </div>

  <div class="card">
    @if($perjanjians->isEmpty())
      <p>Tidak ada perjanjian untuk jabatan Anda.</p>
    @else
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:12px;">
        @foreach($perjanjians as $p)
          <div style="padding:12px; border-radius:10px; background:#fff8f3;">
            <div style="font-weight:700; margin-bottom:6px;">{{ $p->judul }}</div>
            <div style="font-size:13px; color:#666; margin-bottom:8px;">{{ $p->jabatan }} • {{ $p->tahun }}</div>
            <div style="display:flex; gap:8px;">
              <a href="{{ route('laporan.create_from_perjanjian', $p->id) }}" class="btn">Buat Laporan</a>
              <a href="#" class="btn" style="background:#007bff;">Lihat Detail</a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
