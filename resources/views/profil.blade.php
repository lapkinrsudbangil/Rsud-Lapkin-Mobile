<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Include Croppie dan Signature Pad CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #D8F3F1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fff;
      padding: 15px 40px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .header-left i, .header-right i {
      font-size: 22px;
      cursor: pointer;
    }
    .header-title { font-weight: 600; font-size: 18px; }
    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 0;
    }
    .profile-container {
      display: flex;
      gap: 80px;
      align-items: flex-start;
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    form {
      display: grid;
      grid-template-columns: 150px 250px;
      row-gap: 15px;
      column-gap: 20px;
      align-items: center;
    }
    label { text-align: right; font-weight: 500; color: #333; font-size: 14px; }
    input[type="text"], input[type="email"] {
      width: 100%; padding: 10px 12px;
      border: 1px solid #ddd; border-radius: 8px;
      background-color: #fff;
      transition: all 0.3s ease;
    }
    input[readonly] {
      background-color: #f8f8f8;
      cursor: not-allowed;
    }
    input:focus {
      border-color: #009970;
      box-shadow: 0 0 0 3px rgba(0,153,112,0.1);
      outline: none;
    }
    .edit-btn, .upload-btn {
      background-color: #009970;
      color: white;
      border: none;
      padding: 10px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .edit-btn:hover, .upload-btn:hover {
      background-color: #008060;
      transform: translateY(-1px);
    }
    .edit-btn {
      grid-column: span 2;
      justify-self: center;
    }
    .right-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 24px;
    }
    .photo-container {
      position: relative;
      width: 150px;
      height: 150px;
    }
    .photo {
      width: 150px; 
      height: 150px;
      background-color: #fff;
      border-radius: 50%;
      display: flex; 
      align-items: center; 
      justify-content: center;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border: 3px solid #fff;
      position: relative;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .photo:hover {
      transform: scale(1.02);
    }
    .photo img {
      width: 100%; 
      height: 100%; 
      object-fit: cover;
    }
    .photo-edit-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0,0,0,0.6);
      color: white;
      padding: 8px;
      font-size: 12px;
      text-align: center;
      opacity: 0;
      transition: opacity 0.3s;
    }
    .photo:hover .photo-edit-overlay {
      opacity: 1;
    }
    .signature-box {
      width: 300px; 
      height: 150px;
      background-color: #fff;
      display: flex; 
      align-items: center; 
      justify-content: center;
      border-radius: 12px; 
      border: 2px dashed #ddd;
      font-size: 14px; 
      color: #666;
      cursor: pointer;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    .signature-box:hover {
      border-color: #009970;
      background-color: #f8f8f8;
    }
    .signature-box img {
      max-width: 100%;
      max-height: 100%;
    }
    footer {
      background: #fff;
      text-align: center;
      font-size: 13px;
      padding: 15px 0;
      border-top: 1px solid #ddd;
      font-weight: 600;
    }
    footer span { font-weight: 700; }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(5px);
    }
    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 90vw;
      max-height: 90vh;
      overflow-y: auto;
    }
    .modal-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
    }
    .signature-pad-container {
      margin: 20px 0;
      touch-action: none;
    }
    #signaturePad {
      border: 1px solid #ddd;
      border-radius: 12px;
      touch-action: none;
    }
    .button-group {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-top: 20px;
    }
    .button-group button {
      min-width: 100px;
    }
    .croppie-container {
      width: 300px;
      height: 300px;
    }
    .close-modal {
      position: absolute;
      top: 15px;
      right: 15px;
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #666;
    }
    .close-modal:hover {
      color: #333;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .profile-container {
        flex-direction: column-reverse;
        gap: 40px;
        padding: 20px;
        margin: 20px;
      }
      form {
        grid-template-columns: 1fr;
      }
      label {
        text-align: left;
      }
      .signature-box {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="header-left"><i class="fa-solid fa-arrow-left" onclick="window.history.back()"></i></div>
  <div class="header-title">Profil Saya</div>
  <div class="header-right"><i class="fa-solid fa-right-from-bracket"></i></div>
</header>

<main>
  <div class="profile-container">
    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
      @csrf

      <label>Nama Pegawai</label>
      <input type="text" name="nama" value="{{ $user->nama }}" readonly>

      <label>ID Pegawai</label>
      <input type="text" name="id_pegawai" value="{{ $user->id_pegawai }}" readonly>

      <label>NIP</label>
      <input type="text" name="nip" value="{{ $user->nip }}" readonly>

      <label>Email</label>
      <input type="email" name="email" value="{{ $user->email }}" readonly>

      <label>Jabatan</label>
      <input type="text" name="jabatan" value="{{ $user->jabatan }}" readonly>

      <label>Pangkat</label>
      <input type="text" name="pangkat" value="{{ $user->pangkat }}" readonly>

      <label>Divisi</label>
      <input type="text" name="divisi" value="{{ $user->divisi }}" readonly>

      <input type="hidden" name="signature_data" id="signatureData">

      <button type="button" class="edit-btn" id="editBtn">Edit Profil</button>
      <button type="submit" class="edit-btn" id="saveBtn" style="display:none;">Simpan</button>
    </form>

    <div class="right-section">
      <div class="photo" id="photoPreview">
        @if($user->foto_profil)
          <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil">
        @else
          FOTO PROFIL
        @endif
      </div>
      <input type="file" name="foto_profil" id="fotoInput" style="display:none;" accept="image/*">
      <button type="button" class="upload-btn" onclick="document.getElementById('fotoInput').click()">Upload Foto</button>

      <div class="signature-box" id="signatureBox">
        @if($user->tanda_tangan)
          <img src="{{ asset('storage/' . $user->tanda_tangan) }}" width="200" height="100">
        @else
          Klik untuk tanda tangan
        @endif
      </div>
    </div>
  </div>
</main>

<footer>
  © 2025 RSUD Bangil | <span>Dikelola oleh Tim IT RSUD Bangil</span>
</footer>

<!-- Modal Foto -->
<div class="modal" id="photoModal">
  <div class="modal-content">
    <button class="close-modal" onclick="closePhotoModal()">&times;</button>
    <div class="modal-title">Edit Foto Profil</div>
    <div class="button-group">
      <button class="upload-btn" id="rotateBtn">
        <i class="fas fa-rotate-right"></i> Putar
      </button>
      <button class="upload-btn" id="saveCroppedPhoto">
        <i class="fas fa-check"></i> Simpan
      </button>
    </div>
  </div>
</div>

<!-- Modal Tanda Tangan -->
<div class="modal" id="signatureModal">
  <div class="modal-content">
    <button class="close-modal" onclick="closeSignatureModal()">&times;</button>
    <div class="modal-title">Buat Tanda Tangan Digital</div>
    <div class="signature-pad-container">
      <canvas id="signaturePad" width="400" height="200"></canvas>
    </div>
    <div class="button-group">
      <button class="upload-btn" id="clearBtn">
        <i class="fas fa-eraser"></i> Hapus
      </button>
      <button class="upload-btn" id="saveSignature">
        <i class="fas fa-check"></i> Simpan
      </button>
    </div>
  </div>
</div>

<input type="hidden" id="croppedPhotoData" name="croppedPhotoData">
<input type="hidden" id="signatureData" name="signatureData">

<!-- Include Croppie dan SignaturePad JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
  let croppie = null;
  let signaturePad = null;
  
  // Form editing
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const inputs = document.querySelectorAll('input[type="text"], input[type="email"]');
  const fotoInput = document.getElementById('fotoInput');
  const photoPreview = document.getElementById('photoPreview');
  
  editBtn.addEventListener('click', () => {
    inputs.forEach(input => {
      if (!['id_pegawai', 'nip'].includes(input.name)) {
        input.removeAttribute('readonly');
      }
    });
    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
  });

  // Photo cropping setup
  function initCroppie() {
    if (croppie) {
      croppie.destroy();
    }
    
    const element = document.createElement('div');
    document.querySelector('.modal-content').appendChild(element);
    
    croppie = new Croppie(element, {
      viewport: { width: 150, height: 150, type: 'circle' },
      boundary: { width: 300, height: 300 },
      enableOrientation: true
    });
  }

  // Photo upload and cropping
  fotoInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) {
      const modal = document.getElementById('photoModal');
      modal.style.display = 'flex';
      
      initCroppie();
      
      const reader = new FileReader();
      reader.onload = e => {
        croppie.bind({ url: e.target.result }).then(() => {
          console.log('Croppie bind complete');
        });
      };
      reader.readAsDataURL(file);
    }
  });

  // Save cropped photo
  document.getElementById('saveCroppedPhoto').addEventListener('click', () => {
    croppie.result({
      type: 'base64',
      size: 'viewport',
      format: 'jpeg',
      quality: 1
    }).then(croppedImage => {
      photoPreview.innerHTML = `<img src="${croppedImage}" alt="Profile Photo">
        <div class="photo-edit-overlay">Klik untuk edit foto</div>`;
      document.getElementById('croppedPhotoData').value = croppedImage;
      closePhotoModal();
    });
  });

  // Signature Pad setup
  function initSignaturePad() {
    const canvas = document.getElementById('signaturePad');
    signaturePad = new SignaturePad(canvas, {
      penColor: 'blue',
      backgroundColor: 'white',
      minWidth: 1,
      maxWidth: 2.5,
      throttle: 16,
      velocityFilterWeight: 0.7
    });

    // Handle touch events
    function resizeCanvas() {
      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
      signaturePad.clear();
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
  }

  // Open signature modal
  document.getElementById('signatureBox').addEventListener('click', () => {
    const modal = document.getElementById('signatureModal');
    modal.style.display = 'flex';
    initSignaturePad();
  });

  // Clear signature
  document.getElementById('clearBtn').addEventListener('click', () => {
    signaturePad.clear();
  });

  // Save signature
  document.getElementById('saveSignature').addEventListener('click', () => {
    if (!signaturePad.isEmpty()) {
      const dataURL = signaturePad.toDataURL();
      document.getElementById('signatureData').value = dataURL;
      document.getElementById('signatureBox').innerHTML = `
        <img src="${dataURL}" alt="Signature">`;
      closeSignatureModal();
    } else {
      alert('Silakan buat tanda tangan terlebih dahulu');
    }
  });

  // Modal controls
  function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.style.display = 'none';
    if (croppie) {
      croppie.destroy();
      croppie = null;
    }
  }

  function closeSignatureModal() {
    const modal = document.getElementById('signatureModal');
    modal.style.display = 'none';
  }

  // Close modals when clicking outside
  window.addEventListener('click', e => {
    const photoModal = document.getElementById('photoModal');
    const signatureModal = document.getElementById('signatureModal');
    
    if (e.target === photoModal) {
      closePhotoModal();
    } else if (e.target === signatureModal) {
      closeSignatureModal();
    }
  });

  // Rotate photo
  document.getElementById('rotateBtn').addEventListener('click', () => {
    if (croppie) {
      croppie.rotate(90);
    }
  });
</script>
</script>

</body>
</html>
