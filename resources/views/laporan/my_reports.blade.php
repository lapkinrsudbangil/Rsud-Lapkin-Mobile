@extends('layouts.app')

@section('content')
  <div class="card">
    <h2>Daftar Laporan Saya</h2>
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr style="text-align:left; border-bottom:1px solid #eee;">
          <th>Tanggal</th>
          <th>Perjanjian</th>
          <th>Uraian</th>
          <th>Target</th>
          <th>Realisasi</th>
          <th>%</th>
        </tr>
      </thead>
      <tbody>
        @foreach($laporans as $l)
          <tr style="border-bottom:1px solid #f6f6f6;">
            <td>{{ $l->created_at->format('Y-m-d') }}</td>
            <td>{{ optional($l->perjanjian)->judul ?? '-' }}</td>
            <td style="max-width:300px; overflow:hidden; text-overflow:ellipsis">{{ Str::limit($l->uraian_kegiatan, 120) }}</td>
            <td>{{ $l->target }}</td>
            <td>{{ $l->realisasi }}</td>
            <td>{{ $l->persentase }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
