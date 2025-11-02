@extends('layouts.app')

@section('content')
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h1>Perjanjian Kinerja</h1>
    <div>
      <a href="{{ route('perjanjian.create') }}" class="btn">+ Buat Perjanjian</a>
    </div>
  </div>

  <div class="card">
    @if($perjanjians->isEmpty())
      <p>Tidak ada perjanjian. Silakan tambah perjanjian terlebih dahulu.</p>
    @else
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:12px;">
        @foreach($perjanjians as $p)
          <div style="padding:12px; border-radius:10px; background:#f8fff9;">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
              <div style="font-weight:700;">{{ $p->judul }}</div>
              <div style="font-size:12px; color:#444;">
                @if($p->jenis === 'perubahan')
                  <span style="background:#fff3cd; padding:4px 8px; border-radius:6px;">Perubahan</span>
                @endif
              </div>
            </div>
            <div style="font-size:13px; color:#666; margin-bottom:8px;">{{ $p->jabatan }} • {{ $p->tahun }} @if($p->tanggal_pembuatan) • {{ $p->tanggal_pembuatan }} @endif</div>
            <div style="display:flex; gap:8px;">
              <a href="{{ route('laporan.create_from_perjanjian', $p->id) }}" class="btn">Buat Laporan</a>
              <a href="#" class="btn" style="background:#007bff;">Export</a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
