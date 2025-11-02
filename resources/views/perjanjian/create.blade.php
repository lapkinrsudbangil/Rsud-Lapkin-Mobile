@extends('layouts.app')

@section('content')
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <h1>Buat Perjanjian Kinerja (SAKIP)</h1>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('perjanjian.store') }}">
      @csrf
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Jabatan</label>
          <input name="jabatan" class="form-control" required>
        </div>
        <div>
          <label>Tahun</label>
          <input name="tahun" class="form-control">
        </div>
      </div>

      <div style="margin-top:12px; display:flex; gap:12px; align-items:center;">
        <div style="flex:1;">
          <label>Jenis Perjanjian</label>
          <select name="jenis" id="jenisPerjanjian" class="form-control">
            <option value="normal">Perjanjian Kinerja</option>
            <option value="perubahan">Perjanjian Kinerja Perubahan</option>
          </select>
        </div>
        <div style="width:200px;">
          <label>Tanggal Pembuatan</label>
          <input type="date" name="tanggal_pembuatan" class="form-control">
        </div>
        <div style="width:220px;">
          <label>Mode Perubahan</label>
          <select name="change_mode" id="changeMode" class="form-control">
            <option value="ubah_target">Ubah Target Saja</option>
            <option value="ubah_perjanjian">Ubah Seluruh Perjanjian</option>
          </select>
        </div>
      </div>

      <div style="margin-top:12px;">
        <label>Judul</label>
        <input name="judul" class="form-control" required>
      </div>

      <div style="margin-top:12px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-control"></textarea>
        </div>
        <div>
          <label>Indikator (JSON)</label>
          <textarea name="indikator" class="form-control" placeholder='{"indikator":"contoh"}'></textarea>
        </div>
      </div>

  <hr style="margin:18px 0; border:none; border-top:1px solid #eee;">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; align-items:start;">
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
        <button class="btn" type="submit" id="savePerjanjianBtn">Simpan Perjanjian</button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  function initSig(canvasId, inputId) {
    const canvas = document.getElementById(canvasId);
    const sigPad = new SignaturePad(canvas, { backgroundColor: 'rgba(255,255,255,0)', penColor: 'black' });
    // resize to device pixel ratio
    function resize() {
      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext('2d').scale(ratio, ratio);
      sigPad.clear();
    }
    window.addEventListener('resize', resize);
    resize();

    return { pad: sigPad, save: () => {
      if (!sigPad.isEmpty()) {
        document.getElementById(inputId).value = sigPad.toDataURL();
      }
    }};
  }

  const s1 = initSig('sigPihak1', 'pihak1_signature');
  const s2 = initSig('sigPihak2', 'pihak2_signature');

  document.getElementById('savePerjanjianBtn').addEventListener('click', () => {
    s1.save(); s2.save();
  });

  // Auto-populate description template based on jenis & change mode
  (function(){
    const jenisEl = document.getElementById('jenisPerjanjian');
    const modeEl = document.getElementById('changeMode');
    const deskripsiEl = document.querySelector('textarea[name="deskripsi"]');
    const jabatanEl = document.querySelector('input[name="jabatan"]');
    const judulEl = document.querySelector('input[name="judul"]');
    const tahunEl = document.querySelector('input[name="tahun"]');
    const tanggalEl = document.querySelector('input[name="tanggal_pembuatan"]');

    function todayISO() {
      const d = new Date();
      const mm = String(d.getMonth()+1).padStart(2,'0');
      const dd = String(d.getDate()).padStart(2,'0');
      return `${d.getFullYear()}-${mm}-${dd}`;
    }

    if (tanggalEl && !tanggalEl.value) {
      tanggalEl.value = todayISO();
    }

    function buildTemplate() {
      const jenis = jenisEl ? jenisEl.value : 'normal';
      const mode = modeEl ? modeEl.value : 'ubah_target';
      const jabatan = (jabatanEl && jabatanEl.value) ? jabatanEl.value : '[Jabatan]';
      const judul = (judulEl && judulEl.value) ? judulEl.value : '[Judul]';
      const tahun = (tahunEl && tahunEl.value) ? tahunEl.value : '[Tahun]';
      const tanggal = (tanggalEl && tanggalEl.value) ? tanggalEl.value : '[Tanggal]';

      if (jenis === 'normal') {
        return `Perjanjian Kinerja ${jabatan} Tahun ${tahun}\n\nJudul: ${judul}\n\nDeskripsi: \n- Sasaran: ...\n- Indikator: ...\n- Bobot: ...\n\nTanggal Pembuatan: ${tanggal}`;
      }

      // perubahan
      if (mode === 'ubah_target') {
        return `Perubahan Perjanjian Kinerja (Ubah Target Saja)\n\nPerubahan untuk ${jabatan}, Tahun ${tahun}.\n\nJudul: ${judul}\n\nDeskripsi Perubahan: \n- Target lama: ...\n- Target baru: ...\n\nTanggal Pembuatan: ${tanggal}`;
      }

      return `Perubahan Perjanjian Kinerja (Ubah Seluruh Perjanjian)\n\nPerubahan menyeluruh untuk ${jabatan}, Tahun ${tahun}.\n\nJudul: ${judul}\n\nDeskripsi Perubahan (uraikan perubahan pada ruang lingkup, indikator, bobot, dst):\n- ...\n\nTanggal Pembuatan: ${tanggal}`;
    }

    function tryPopulate() {
      if (!deskripsiEl) return;
      // only auto-fill when description is empty to avoid overwriting user edits
      if (deskripsiEl.value.trim() === '') {
        deskripsiEl.value = buildTemplate();
      }
    }

    if (jenisEl) jenisEl.addEventListener('change', tryPopulate);
    if (modeEl) modeEl.addEventListener('change', tryPopulate);
    if (jabatanEl) jabatanEl.addEventListener('blur', tryPopulate);
    if (judulEl) judulEl.addEventListener('blur', tryPopulate);
    if (tahunEl) tahunEl.addEventListener('blur', tryPopulate);

    // initial populate on load
    tryPopulate();
  })();
</script>
@endpush
