@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>Buat Laporan Kinerja - Berdasarkan Perjanjian</h1>

    <div class="card">
      <div class="card-body">
        <h3>Perjanjian: {{ $perjanjian->judul }} ({{ $perjanjian->tahun }})</h3>
        <form method="POST" action="{{ route('laporan.store') }}">
          @csrf
          <input type="hidden" name="perjanjian_id" value="{{ $perjanjian->id }}">

          <div class="form-group">
            <label>Periode Awal</label>
            <input name="periode_awal" class="form-control" placeholder="YYYY-MM-DD">
          </div>

          <div class="form-group">
            <label>Periode Akhir</label>
            <input name="periode_akhir" class="form-control" placeholder="YYYY-MM-DD">
          </div>

          <div class="form-group">
            <label>Uraian Kegiatan (Sesuai SAKIP)</label>
            <textarea name="uraian_kegiatan" class="form-control" required></textarea>
          </div>

          <div class="form-group">
            <label>Indikator (JSON) - jika ada</label>
            <textarea name="indikator" class="form-control">{{ json_encode($perjanjian->indikator) }}</textarea>
          </div>

          <div class="form-row">
            <div class="form-group" style="display:inline-block; width:30%; margin-right:10px;">
              <label>Target</label>
              <input name="target" class="form-control">
            </div>
            <div class="form-group" style="display:inline-block; width:30%; margin-right:10px;">
              <label>Realisasi</label>
              <input name="realisasi" class="form-control">
            </div>
            <div class="form-group" style="display:inline-block; width:30%;">
              <label>Satuan</label>
              <input name="satuan" class="form-control">
            </div>
          </div>

          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
          </div>

          <div style="margin-top:12px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label>Sasaran</label>
              <input name="sasaran" class="form-control" value="{{ $perjanjian->sasaran }}">
            </div>
            <div>
              <label>Bobot (%)</label>
              <input name="bobot" class="form-control" value="{{ $perjanjian->bobot }}">
            </div>
          </div>

          <hr style="margin:18px 0; border:none; border-top:1px solid #eee;">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label>Nama Pihak 1</label>
              <input name="pihak1_name" class="form-control">
              <div style="margin-top:8px;">Tanda Tangan Pihak 1</div>
              <canvas id="sigPihak1" style="width:100%; height:120px; border:1px solid #ddd; border-radius:8px; background:#fff;"></canvas>
              <input type="hidden" name="pihak1_signature" id="pihak1_signature">
            </div>

            <div>
              <label>Nama Pihak 2</label>
              <input name="pihak2_name" class="form-control">
              <div style="margin-top:8px;">Tanda Tangan Pihak 2</div>
              <canvas id="sigPihak2" style="width:100%; height:120px; border:1px solid #ddd; border-radius:8px; background:#fff;"></canvas>
              <input type="hidden" name="pihak2_signature" id="pihak2_signature">
            </div>
          </div>

          <div style="margin-top:12px; text-align:right;">
            <button class="btn btn-primary" id="saveReportBtn" type="submit">Simpan Laporan</button>
          </div>

          <button class="btn btn-primary" type="submit">Simpan Laporan</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  function initSig(canvasId, inputId) {
    const canvas = document.getElementById(canvasId);
    const sigPad = new SignaturePad(canvas, { backgroundColor: 'rgba(255,255,255,0)', penColor: 'black' });
    function resize() {
      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext('2d').scale(ratio, ratio);
      sigPad.clear();
    }
    window.addEventListener('resize', resize);
    resize();
    return { pad: sigPad, save: () => { if (!sigPad.isEmpty()) document.getElementById(inputId).value = sigPad.toDataURL(); }};
  }

  const s1 = initSig('sigPihak1', 'pihak1_signature');
  const s2 = initSig('sigPihak2', 'pihak2_signature');

  document.getElementById('saveReportBtn').addEventListener('click', () => {
    s1.save(); s2.save();
  });
</script>
@endpush
