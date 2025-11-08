@extends('layouts.app')

@section('content')
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
    <h1>Buat Perjanjian Kinerja (SAKIP)</h1>
  </div>

  <div class="card">
    <form method="POST" action="{{ isset($editing) ? route('perjanjian.update', $editing->id) : route('perjanjian.store') }}">
      @csrf
      @if(isset($editing))
        @method('PUT')
      @endif
      <div style="margin-bottom:12px;">
        <label>Pilih Perjanjian yang ada (opsional)</label>
        <select id="existingPerjanjian" name="existing_perjanjian_id" class="form-control">
          <option value="">-- Baru / Tidak memilih --</option>
          @foreach(($perjanjians ?? []) as $ep)
            <option value="{{ $ep->id }}"
              data-jabatan="{{ e($ep->jabatan) }}"
              data-tahun="{{ e($ep->tahun) }}"
              data-pihak1_name="{{ e($ep->pihak1_name) }}"
              data-pihak1_jabatan="{{ e($ep->pihak1_jabatan) }}"
              data-pihak1_nip="{{ e($ep->pihak1_nip) }}"
              data-pihak1_pangkat="{{ e($ep->pihak1_pangkat) }}"
              data-pihak2_name="{{ e($ep->pihak2_name) }}"
              data-pihak2_jabatan="{{ e($ep->pihak2_jabatan) }}"
              data-pihak2_nip="{{ e($ep->pihak2_nip) }}"
              data-pihak2_pangkat="{{ e($ep->pihak2_pangkat) }}"
            >{{ $ep->jabatan }} — {{ $ep->tahun }} @if($ep->judul) ({{ $ep->judul }}) @endif</option>
          @endforeach
        </select>
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label>Jabatan</label>
          <input name="jabatan" class="form-control" required value="{{ old('jabatan', $editing->jabatan ?? ($user->jabatan ?? '')) }}">
        </div>
        <div>
          <label>Tahun</label>
          <input name="tahun" class="form-control" value="{{ old('tahun', $editing->tahun ?? date('Y')) }}">
        </div>
      </div>

      <div style="margin-top:12px; display:flex; gap:12px; align-items:center;">
        <div style="flex:1;">
          <label>Jenis Perjanjian</label>
          <select name="jenis" id="jenisPerjanjian" class="form-control">
            <option value="normal" {{ old('jenis', $editing->jenis ?? '') === 'normal' ? 'selected' : '' }}>Perjanjian Kinerja</option>
            <option value="perubahan" {{ old('jenis', $editing->jenis ?? '') === 'perubahan' ? 'selected' : '' }}>Perjanjian Kinerja Perubahan</option>
          </select>
        </div>
        <div style="width:200px;">
          <label>Tanggal Pembuatan</label>
          <input type="date" name="tanggal_pembuatan" class="form-control" value="{{ old('tanggal_pembuatan', isset($editing) && $editing->tanggal_pembuatan ? $editing->tanggal_pembuatan : (date('Y-m-d'))) }}">
        </div>
        <div style="width:220px;">
          <label>Mode Perubahan</label>
          <select name="change_mode" id="changeMode" class="form-control">
            <option value="ubah_target" {{ old('change_mode', $editing->change_mode ?? '') === 'ubah_target' ? 'selected' : '' }}>Ubah Target Saja</option>
            <option value="ubah_perjanjian" {{ old('change_mode', $editing->change_mode ?? '') === 'ubah_perjanjian' ? 'selected' : '' }}>Ubah Seluruh Perjanjian</option>
          </select>
        </div>
      </div>

      <div style="margin-top:12px;">
        <label>Judul</label>
        <input name="judul" class="form-control" required value="{{ old('judul', $editing->judul ?? '') }}">
      </div>

      <!-- Template area: description + indikator table -->
      <div style="margin-top:12px;">
        <label>Deskripsi (Template)</label>
        <textarea name="deskripsi" id="deskripsiField" class="form-control" rows="6">{{ old('deskripsi', $editing->deskripsi ?? '') }}</textarea>
      </div>

      {{-- Indikator disabled for now — focus on first page print output --}}

  <hr style="margin:18px 0; border:none; border-top:1px solid #eee;">

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; align-items:start;">
        <div>
          <label>Pihak Pertama (Anda)</label>
          <div style="background:#f9f9f9; padding:10px; border-radius:8px;">
            <div><strong>Nama:</strong> {{ $user->nama ?? $user->name ?? '-' }}</div>
            <div><strong>Jabatan:</strong> {{ $user->jabatan ?? '-' }}</div>
          </div>
          {{-- Hidden inputs untuk pihak1 agar disimpan saat submit (ambil dari profil) --}}
          <input type="hidden" name="pihak1_name" value="{{ old('pihak1_name', $editing->pihak1_name ?? $user->nama ?? $user->name ?? '') }}">
          <input type="hidden" name="pihak1_jabatan" value="{{ old('pihak1_jabatan', $editing->pihak1_jabatan ?? $user->jabatan ?? '') }}">
          <input type="hidden" name="pihak1_pangkat" value="{{ old('pihak1_pangkat', $editing->pihak1_pangkat ?? $user->pangkat ?? '') }}">
          <input type="hidden" name="pihak1_golongan" value="{{ old('pihak1_golongan', $editing->pihak1_golongan ?? $user->golongan ?? '') }}">
          <input type="hidden" name="pihak1_nip" value="{{ old('pihak1_nip', $editing->pihak1_nip ?? $user->nip ?? '') }}">
          {{-- tampilkan tanda tangan pihak pertama dari profil jika ada --}}
          <div style="margin-top:8px;">Tanda Tangan Pihak 1</div>
          @if(!empty($user->tanda_tangan))
            @php $tt = $user->tanda_tangan; @endphp
            <div style="margin-top:6px;">
              <img src="{{ (str_starts_with($tt, 'http') || str_starts_with($tt, 'https')) ? $tt : asset('storage/' . $tt) }}" alt="TT Pihak 1" style="max-width:100%; max-height:120px; border:1px solid #ddd; border-radius:6px; background:#fff;">
            </div>
            <input type="hidden" name="pihak1_signature" value="{{ $user->tanda_tangan }}">
          @else
            <div style="margin-top:6px;">(Belum ada tanda tangan di profil)</div>
            <input type="hidden" name="pihak1_signature" id="pihak1_signature">
          @endif
        </div>

        <div>
          <label>Nama Pihak 2</label>
          <input name="pihak2_name" class="form-control" value="{{ old('pihak2_name', $editing->pihak2_name ?? '') }}">
          <div style="margin-top:8px; display:flex; gap:8px;">
            <div style="flex:1;">
              <label>Jabatan Pihak 2</label>
              <input name="pihak2_jabatan" class="form-control" value="{{ old('pihak2_jabatan', $editing->pihak2_jabatan ?? '') }}">
            </div>
            <div style="width:180px;">
              <label>Pangkat</label>
              <input name="pihak2_pangkat" class="form-control" value="{{ old('pihak2_pangkat', $editing->pihak2_pangkat ?? '') }}">
            </div>
            <div style="width:200px;">
              <label>NIP Pihak 2</label>
              <input name="pihak2_nip" class="form-control" value="{{ old('pihak2_nip', $editing->pihak2_nip ?? '') }}">
            </div>
          </div>
          <div style="margin-top:8px;">Tanda Tangan Pihak 2</div>
          <canvas id="sigPihak2" style="width:100%; height:120px; border:1px solid #ddd; border-radius:8px; background:#fff;"></canvas>
          <input type="hidden" name="pihak2_signature" id="pihak2_signature" value="{{ old('pihak2_signature', $editing->pihak2_signature ?? '') }}">
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

  // Only initialize signature pad for pihak2 (pihak1 signature comes from profile)
  // Only pihak2 signature is collected here; pihak1 is taken from profile
  const s2 = initSig('sigPihak2', 'pihak2_signature');

  document.getElementById('savePerjanjianBtn').addEventListener('click', (e) => {
    // save pihak2 signature
    s2.save();
    // allow form to submit
  });
  // indikator functionality removed for now to focus on first-page print

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

  // If user selects an existing perjanjian, prefill jabatan/tahun
  (function(){
    const existingSel = document.getElementById('existingPerjanjian');
    if (!existingSel) return;
    existingSel.addEventListener('change', function() {
      const opt = this.options[this.selectedIndex];
      const jab = opt.getAttribute('data-jabatan') || '';
      const tahun = opt.getAttribute('data-tahun') || '';
      const p1 = opt.getAttribute('data-pihak1_name') || '';
      const p1jab = opt.getAttribute('data-pihak1_jabatan') || '';
      const p1nip = opt.getAttribute('data-pihak1_nip') || '';
      const p1pangkat = opt.getAttribute('data-pihak1_pangkat') || '';
      const p2 = opt.getAttribute('data-pihak2_name') || '';
      const p2jab = opt.getAttribute('data-pihak2_jabatan') || '';
      const p2nip = opt.getAttribute('data-pihak2_nip') || '';
      const p2pangkat = opt.getAttribute('data-pihak2_pangkat') || '';

      if (jab) document.querySelector('input[name="jabatan"]').value = jab;
      if (tahun) document.querySelector('input[name="tahun"]').value = tahun;
      if (p1) document.querySelector('input[name="pihak1_name"]').value = p1;
      if (p1jab) document.querySelector('input[name="pihak1_jabatan"]').value = p1jab;
      if (p1nip) document.querySelector('input[name="pihak1_nip"]').value = p1nip;
      if (p1pangkat) document.querySelector('input[name="pihak1_pangkat"]').value = p1pangkat;
      if (p2) document.querySelector('input[name="pihak2_name"]').value = p2;
      if (p2jab) document.querySelector('input[name="pihak2_jabatan"]').value = p2jab;
      if (p2nip) document.querySelector('input[name="pihak2_nip"]').value = p2nip;
      if (p2pangkat) document.querySelector('input[name="pihak2_pangkat"]').value = p2pangkat;
    });
  })();
</script>
@endpush
