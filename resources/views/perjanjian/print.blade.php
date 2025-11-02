@extends('layouts.app')

@section('content')
<style>
.page { width: 800px; margin: 0 auto; background:#fff; padding:40px; box-shadow:none; }
.header { text-align:center; }
.page-break { page-break-after: always; }
.table { width:100%; border-collapse: collapse; }
.table th, .table td { border:1px solid #ddd; padding:8px; }
</style>

<div class="page">
  <div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="logo" style="height:80px;">
    @extends('layouts.app')

    @section('content')
    <style>
      body { font-family: serif; color:#111; }
      .page { width: 800px; margin: 16px auto; background:#fff; padding:36px 48px; box-shadow:none; }
      .header { text-align:center; }
      .title { font-weight:700; text-transform:uppercase; }
      .sub { font-weight:700; }
      .lead { text-align:justify; margin-top:18px; line-height:1.5; }
      .parties { margin-top:18px; }
      .party { margin-top:8px; }
      .meta { margin-top:28px; text-align:right; }
      .sign-blocks { display:flex; justify-content:space-between; margin-top:28px; }
      .sign { text-align:center; width:45%; }
      .sign img { max-height:90px; }
      .table { width:100%; border-collapse: collapse; margin-top:10px; }
      .table th, .table td { border:1px solid #000; padding:6px; }
      .page-break { page-break-after: always; }
    </style>

    <div class="page">
      <div class="header">
        @if(file_exists(public_path('images/logo.png')))
          <img src="{{ asset('images/logo.png') }}" alt="logo" style="height:90px; margin-bottom:6px;">
        @endif
        <div class="title">PEMERINTAH KABUPATEN PASURUAN</div>
        <div style="font-size:18px; margin-top:6px;">PERJANJIAN KINERJA PERUBAHAN TAHUN {{ $p->tahun ?? date('Y') }}</div>
        <div style="margin-top:6px;">UOBK RSUD BANGIL</div>
      </div>

      <div class="lead">
        Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :
      </div>

      <div class="parties">
        <div class="party">
          Nama	: <strong>{{ $p->pihak1_name ?? '-' }}</strong><br>
          Jabatan	: <strong>{{ $p->pihak1_jabatan ?? $p->jabatan ?? '-' }}</strong>
        </div>

        <div class="party" style="margin-top:12px;">
          Nama	: <strong>{{ $p->pihak2_name ?? '-' }}</strong><br>
          Jabatan	: <strong>{{ $p->pihak2_jabatan ?? '-' }}</strong>
        </div>
      </div>

      <div class="lead">{!! nl2br(e($p->deskripsi ?? '')) !!}</div>

      <div class="meta">Pasuruan, {{ 
        (isset($p->tanggal_pembuatan) && $p->tanggal_pembuatan) ? 
          \Carbon\Carbon::parse($p->tanggal_pembuatan)->format('d F Y') : 
          \Carbon\Carbon::now()->format('d F Y')
      }}</div>

      <div class="sign-blocks">
        <div class="sign">
          <div>PIHAK KEDUA</div>
          <div style="height:90px; margin-top:10px;">
            @if($p->pihak2_signature)
              <img src="{{ (str_starts_with($p->pihak2_signature,'http') ? $p->pihak2_signature : asset('storage/' . $p->pihak2_signature)) }}" alt="tanda tangan pihak2">
            @endif
          </div>
          <div style="margin-top:8px; font-weight:700;">{{ $p->pihak2_name }}</div>
          <div>NIP: {{ $p->pihak2_nip ?? '' }}</div>
        </div>

        <div class="sign">
          <div>PIHAK PERTAMA</div>
          <div style="height:90px; margin-top:10px;">
            @if($p->pihak1_signature)
              <img src="{{ (str_starts_with($p->pihak1_signature,'http') ? $p->pihak1_signature : asset('storage/' . $p->pihak1_signature)) }}" alt="tanda tangan pihak1">
            @endif
          </div>
          <div style="margin-top:8px; font-weight:700;">{{ $p->pihak1_name }}</div>
          <div>NIP: {{ $p->pihak1_nip ?? '' }}</div>
        </div>
      </div>
    </div>

    <div class="page page-break">
      <div class="header">
        <div style="font-weight:700;">LAMPIRAN: INDIKATOR & TARGET</div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th style="width:40px;">No</th>
            <th>Indikator</th>
            <th style="width:120px;">Satuan</th>
            <th style="width:120px;">Target</th>
            <th style="width:100px;">Bobot (%)</th>
            <th style="width:150px;">Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @php $indik = is_array($p->indikator) ? $p->indikator : json_decode($p->indikator ?? '[]', true); @endphp
          @foreach($indik ?? [] as $i => $row)
            <tr>
              <td style="text-align:center;">{{ $i+1 }}</td>
              <td>{{ $row['indikator'] ?? ($row['name'] ?? '-') }}</td>
              <td style="text-align:center;">{{ $row['satuan'] ?? '' }}</td>
              <td style="text-align:center;">{{ $row['target'] ?? '' }}</td>
              <td style="text-align:center;">{{ $row['bobot'] ?? '' }}</td>
              <td>{{ $row['keterangan'] ?? ($row['ket'] ?? '') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @endsection
