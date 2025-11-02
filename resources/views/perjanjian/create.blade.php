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

      <!-- Template area: description + indikator table -->
      <div style="margin-top:12px;">
        <label>Deskripsi (Template)</label>
        <textarea name="deskripsi" id="deskripsiField" class="form-control" rows="6"></textarea>
      </div>

      <div style="margin-top:12px;">
        <label>Daftar Indikator</label>
        <div style="overflow:auto; border:1px solid #eee; padding:8px; border-radius:8px; background:#fff;">
          <table id="indikatorTable" style="width:100%; border-collapse:collapse;">
            <thead>
              <tr style="background:#f6f6f6;">
                <th style="padding:6px; border:1px solid #eee;">Indikator</th>
                <th style="padding:6px; border:1px solid #eee;">Satuan</th>
                <th style="padding:6px; border:1px solid #eee;">Target</th>
                <th style="padding:6px; border:1px solid #eee; width:120px;">Bobot (%)</th>
                <th style="padding:6px; border:1px solid #eee; width:120px;">Keterangan</th>
                <th style="padding:6px; border:1px solid #eee; width:60px;"><button type="button" id="addIndikatorBtn" class="btn">+</button></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-name" placeholder="Contoh: Angka Kematian Ibu"></td>
                <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-satuan" placeholder="% / pasien / kasus"></td>
                <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-target" placeholder="Contoh: 5"></td>
                <td style="padding:6px; border:1px solid #eee;"><input type="number" step="0.01" class="form-control indikator-bobot" placeholder="10"></td>
                <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-ket" placeholder="Catatan"></td>
                <td style="padding:6px; border:1px solid #eee; text-align:center;"><button type="button" class="btn removeRowBtn">-</button></td>
              </tr>
            </tbody>
          </table>
        </div>
        <input type="hidden" name="indikator" id="indikatorJsonInput">
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

  document.getElementById('savePerjanjianBtn').addEventListener('click', (e) => {
    // save signatures
    s1.save(); s2.save();
    // collect indikator table rows into hidden input as JSON
    collectIndikatorRows();
    // allow form to submit
  });

  // indikator table helpers
  const addBtn = document.getElementById('addIndikatorBtn');
  addBtn.addEventListener('click', () => addIndikatorRow());

  function addIndikatorRow(data = {}) {
    const tbody = document.querySelector('#indikatorTable tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-name" value="${escapeHtml(data.name||'')}" placeholder="Contoh: ..."></td>
      <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-satuan" value="${escapeHtml(data.satuan||'')}" placeholder="% / pasien / kasus"></td>
      <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-target" value="${escapeHtml(data.target||'')}" placeholder="Contoh: 5"></td>
      <td style="padding:6px; border:1px solid #eee;"><input type="number" step="0.01" class="form-control indikator-bobot" value="${escapeHtml(data.bobot||'')}"></td>
      <td style="padding:6px; border:1px solid #eee;"><input type="text" class="form-control indikator-ket" value="${escapeHtml(data.ket||'')}"></td>
      <td style="padding:6px; border:1px solid #eee; text-align:center;"><button type="button" class="btn removeRowBtn">-</button></td>
    `;
    tbody.appendChild(tr);
    tr.querySelector('.removeRowBtn').addEventListener('click', () => tr.remove());
  }

  function collectIndikatorRows() {
    const rows = [];
    document.querySelectorAll('#indikatorTable tbody tr').forEach(r => {
      const name = r.querySelector('.indikator-name')?.value || '';
      const satuan = r.querySelector('.indikator-satuan')?.value || '';
      const target = r.querySelector('.indikator-target')?.value || '';
      const bobot = r.querySelector('.indikator-bobot')?.value || '';
      const ket = r.querySelector('.indikator-ket')?.value || '';
      if (name.trim() !== '') {
        rows.push({ indikator: name.trim(), satuan: satuan.trim(), target: target.trim(), bobot: bobot === '' ? null : parseFloat(bobot), keterangan: ket.trim() });
      }
    });
    document.getElementById('indikatorJsonInput').value = JSON.stringify(rows);
  }

  function escapeHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

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
