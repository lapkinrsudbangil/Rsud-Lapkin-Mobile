@extends('layouts.app')

@section('content')
<style>
  /* Print page set to Folio (215.9mm x 330.2mm) for more precise layout when printing/PDF */
  @page { size: 215.9mm 330.2mm; margin: 12mm; }
  body { font-family: 'Times New Roman', Times, serif; color:#111; }
  /* reduce padding to better fit physical folio paper */
  .page { width: 215.9mm; height: 330.2mm; margin: 0 auto; background:#fff; padding:12mm; box-shadow:none; }
  .header { text-align:center; }
  .org { font-weight:700; font-size:14px; }
  .org { font-weight:700; font-size:14px; }
  .doc-title { font-weight:700; font-size:18px; margin-top:4px; text-transform:uppercase; }
  .small { font-size:12px; }
  .uobk { font-weight:700; font-size:18px; margin-top:4px; text-transform:uppercase; }
  .lead { text-align:justify; margin-top:12px; line-height:1.6; }
  .parties { margin-top:12px; }
  .party { margin-top:8px; }
  .meta { margin-top:18px; text-align:right; }
  /* align signature baselines so both signatures sit on the same horizontal line */
  .sign-blocks { display:flex; justify-content:space-between; margin-top:16px; align-items:flex-end; }
  .party-col { width:45%; text-align:center; }
  .logo { height:80px; display:block; margin:0 auto 4px; }
  .meta-table { width:100%; border-collapse:collapse; }
  .label-col { width:140px; vertical-align:top; }
  .value-col { vertical-align:top; }
  .mt-18 { margin-top:12px; }
  .mb-12 { margin-bottom:10px; }
  .mt-8 { margin-top:6px; }
  .mt-12 { margin-top:8px; }
  .para { margin-top:12px; text-align:justify; line-height:1.6; }
  .party-title { font-weight:700; margin-bottom:6px; }
  .signature-box { min-height:80px; margin-bottom:6px; display:flex; align-items:flex-end; justify-content:center; }
  .sig-img { max-height:80px; display:block; }
  .name-underline { font-weight:700; text-decoration:underline; }
  .date-right { text-align:right; margin-bottom:6px; }
  .date-row { display:flex; justify-content:space-between; margin-top:6px; }
  .date-placeholder { width:45%; }
  .date-right-col { width:45%; text-align:right; margin-bottom:2px; font-size:12px; }
  .lampiran-title { font-weight:700; margin-bottom:8px; }
  .table { width:100%; border-collapse: collapse; margin-top:8px; }
  .table th, .table td { border:1px solid #000; padding:6px; font-size:12px; }
  .w40 { width:40px; text-align:center; }
  .w120 { width:120px; text-align:center; }
  .w100 { width:100px; text-align:center; }
  .w150 { width:150px; text-align:center; }
  .td-center { text-align:center; vertical-align:top; }
  .td-top { vertical-align:top; }
  .small { font-size:12px; }
  .page-break { page-break-after: always; }
</style>

<div class="page">
  <div class="header">
    @php
      // Try multiple common locations for the PEMDA logo and embed the first found as base64 so PDFs always include it
      $logoCandidates = [
        public_path('images/logo.png'),
        public_path('storage/images/logo.png'),
        storage_path('app/public/images/logo.png'),
        public_path('images/pemda_logo.png'),
      ];
      $logoUrl = null;
      foreach($logoCandidates as $c) {
        if($c && file_exists($c)) {
          try {
            $m = @mime_content_type($c) ?: 'image/png';
            $logoUrl = 'data:' . $m . ';base64,' . base64_encode(file_get_contents($c));
            break;
          } catch (\Exception $e) {
            // ignore and continue
          }
        }
      }
    @endphp
    @if($logoUrl)
      <img src="{{ $logoUrl }}" alt="logo" class="logo">
    @endif
    <div class="org">PEMERINTAH KABUPATEN PASURUAN</div>
    <div class="doc-title">PERJANJIAN KINERJA @if(($p->jenis ?? 'normal') === 'perubahan') PERUBAHAN @endif TAHUN {{ $p->tahun ?? date('Y') }}</div>
    <div class="uobk">UOBK RSUD BANGIL</div>
  </div>

  <div class="lead">
    Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini :
  </div>

  <div class="mt-18">
    <div class="mb-12">
      <table class="meta-table">
        <tr>
          <td class="label-col"><strong>Nama</strong></td>
          <td class="value-col">: {{ $p->pihak1_name ?? '-' }}</td>
        </tr>
        <tr>
          <td class="label-col"><strong>Jabatan</strong></td>
          <td class="value-col">: {{ $p->pihak1_jabatan ?? $p->jabatan ?? '-' }}</td>
        </tr>
      </table>
      <div class="mt-8">Selanjutnya disebut pihak pertama.</div>
    </div>

    <div class="mt-12 mb-12">
      <table class="meta-table">
        <tr>
          <td class="label-col"><strong>Nama</strong></td>
          <td class="value-col">: {{ $p->pihak2_name ?? '-' }}</td>
        </tr>
        <tr>
          <td class="label-col"><strong>Jabatan</strong></td>
          <td class="value-col">: {{ $p->pihak2_jabatan ?? '-' }}</td>
        </tr>
      </table>
      <div class="mt-8">Selaku atasan pihak pertama, selanjutnya disebut pihak kedua.</div>
    </div>

  <div class="para">
      Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab kami.
    </div>

    <div class="para">
      Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.
    </div>

  {{-- tanggal akan ditampilkan satu-saja, di atas kolom Pihak Pertama supaya posisinya presisi dengan tanda tangan kanan --}}
    <div class="date-row">
      <div class="date-placeholder"></div>
      <div class="date-right-col">Pasuruan, {{ (isset($p->tanggal_pembuatan) && $p->tanggal_pembuatan) ? \Carbon\Carbon::parse($p->tanggal_pembuatan)->format('d F Y') : \Carbon\now()->format('d F Y') }}</div>
    </div>

    <div class="sign-blocks">
      {{-- Left: Pihak Kedua --}}
      <div class="party-col">
        <div class="party-title">PIHAK KEDUA</div>
        <div class="signature-box">
          @if($p->pihak2_signature)
            @php
              $sig2 = $p->pihak2_signature;
              $sig2Url = '';
              $candidates2 = [
                public_path('storage/' . ltrim($sig2, '/')),
                storage_path('app/public/' . ltrim($sig2, '/')),
                public_path(ltrim($sig2, '/')),
              ];
              foreach($candidates2 as $c2) {
                if($c2 && file_exists($c2)) {
                  $m2 = @mime_content_type($c2) ?: 'image/png';
                  $data2 = base64_encode(file_get_contents($c2));
                  $sig2Url = 'data:' . $m2 . ';base64,' . $data2;
                  break;
                }
              }
              if(!$sig2Url) {
                if(str_starts_with($sig2, 'http')) {
                  $sig2Url = $sig2;
                } else {
                  $sig2Url = asset('storage/' . ltrim($sig2, '/'));
                }
              }
            @endphp
            <img src="{{ $sig2Url }}" alt="tanda tangan pihak2" class="sig-img">
          @endif
        </div>
        <div class="name-underline">{{ $p->pihak2_name }}</div>
        <div>{{ $p->pihak2_pangkat ?? '' }}</div>
        <div>NIP. {{ $p->pihak2_nip ?? '' }}</div>
      </div>

      {{-- Right: Pihak Pertama --}}
      <div class="party-col">
        <div class="party-title">PIHAK PERTAMA</div>
        <div class="signature-box">
          @if($p->pihak1_signature)
            @php
              $sig1 = $p->pihak1_signature;
              $sig1Url = '';
              $candidates1 = [
                public_path('storage/' . ltrim($sig1, '/')),
                storage_path('app/public/' . ltrim($sig1, '/')),
                public_path(ltrim($sig1, '/')),
              ];
              foreach($candidates1 as $c1) {
                if($c1 && file_exists($c1)) {
                  $m = @mime_content_type($c1) ?: 'image/png';
                  $data = base64_encode(file_get_contents($c1));
                  $sig1Url = 'data:' . $m . ';base64,' . $data;
                  break;
                }
              }
              if(!$sig1Url) {
                if(str_starts_with($sig1, 'http')) {
                  $sig1Url = $sig1;
                } else {
                  $sig1Url = asset('storage/' . ltrim($sig1, '/'));
                }
              }
            @endphp
            <img src="{{ $sig1Url }}" alt="tanda tangan pihak1" class="sig-img">
          @endif
        </div>
        <div class="name-underline">{{ $p->pihak1_name }}</div>
        <div>{{ $p->pihak1_pangkat ?? '' }}</div>
        <div>NIP. {{ $p->pihak1_nip ?? '' }}</div>
      </div>
    </div>
  </div>
</div>

{{-- lampiran indikator dihilangkan pada cetak untuk menghasilkan 1 halaman saja --}}

@endsection
