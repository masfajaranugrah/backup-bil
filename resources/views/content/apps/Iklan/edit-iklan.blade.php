@extends('layouts/layoutMaster')

@section('title', 'Edit Notifikasi')

@section('content')
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="page-header">
    <div class="page-title-wrap">
        <span class="page-title-icon"><i class="ri-edit-line"></i></span>
        <div>
            <h4 class="page-title">Edit Notifikasi</h4>
            <p class="page-subtitle">Perubahan hanya memperbarui data notifikasi, tidak mengirim ulang push ke pelanggan.</p>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-xl-8 col-lg-9">
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <h5 class="alert-heading mb-2">
                <i class="ri-error-warning-line me-2"></i>Terjadi Kesalahan!
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('iklan.update', $iklan->id) }}" method="POST" enctype="multipart/form-data" id="iklanForm">
            @csrf
            @method('PUT')

            <div class="card form-modern border-0 shadow-sm">
                <div class="card-header-custom">
                    <h5 class="mb-0 fw-bold">
                        <i class="ri-article-line me-2"></i>Form Edit Notifikasi
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="form-section">
                        <h6 class="form-section-title">
                            <i class="ri-list-settings-line"></i>Jenis Notifikasi
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="type-card">
                                    <input type="radio" name="type" value="informasi" id="type-informasi" {{ old('type', $iklan->type) === 'informasi' ? 'checked' : '' }} required>
                                    <label for="type-informasi" class="type-label">
                                        <div class="type-icon"><i class="ri-information-line"></i></div>
                                        <div class="type-title">Informasi</div>
                                        <small class="text-muted">Info umum ke pelanggan</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="type-card">
                                    <input type="radio" name="type" value="maintenance" id="type-maintenance" {{ old('type', $iklan->type) === 'maintenance' ? 'checked' : '' }}>
                                    <label for="type-maintenance" class="type-label">
                                        <div class="type-icon"><i class="ri-tools-line"></i></div>
                                        <div class="type-title">Maintenance</div>
                                        <small class="text-muted">Pemberitahuan maintenance</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="type-card">
                                    <input type="radio" name="type" value="iklan" id="type-iklan" {{ old('type', $iklan->type) === 'iklan' ? 'checked' : '' }}>
                                    <label for="type-iklan" class="type-label">
                                        <div class="type-icon"><i class="ri-megaphone-line"></i></div>
                                        <div class="type-title">Iklan/Promosi</div>
                                        <small class="text-muted">Promosi & penawaran</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <small class="text-danger d-block mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-section">
                        <h6 class="form-section-title">
                            <i class="ri-edit-2-line"></i>Konten Notifikasi
                        </h6>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Judul Notifikasi <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <div class="input-icon"><i class="ri-text"></i></div>
                                <input type="text"
                                       class="@error('title') is-invalid @enderror"
                                       name="title"
                                       value="{{ old('title', $iklan->title) }}"
                                       required
                                       maxlength="255"
                                       placeholder="Contoh: Promo Spesial Akhir Tahun!">
                            </div>
                            @error('title')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pesan Notifikasi <span class="text-danger">*</span></label>
                            <div class="input-with-icon input-textarea">
                                <div class="input-icon textarea-icon"><i class="ri-chat-1-line"></i></div>
                                <textarea class="@error('message') is-invalid @enderror py-2"
                                          name="message"
                                          rows="6"
                                          required
                                          minlength="10"
                                          maxlength="1000"
                                          placeholder="Tulis pesan notifikasi Anda di sini...">{{ old('message', $iklan->message) }}</textarea>
                            </div>
                            @error('message')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Minimal 10 karakter</small>
                                <small class="text-muted"><span id="charCount">0</span>/1000 karakter</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Gambar Baru (Opsional)</label>
                            <div class="input-with-icon">
                                <div class="input-icon"><i class="ri-image-add-line"></i></div>
                                <input type="file"
                                       class="@error('image') is-invalid @enderror"
                                       name="image"
                                       accept="image/*"
                                       id="imageInput">
                            </div>
                            @error('image')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti gambar. Format: JPG, PNG, GIF (Max 2MB)</small>

                            <div class="image-preview-wrap mt-3">
                                @if($iklan->image)
                                    <div class="current-image" id="currentImage">
                                        <small class="text-muted d-block mb-2">Gambar saat ini</small>
                                        <img src="{{ asset('storage/' . $iklan->image) }}" alt="Gambar saat ini">
                                    </div>
                                @endif

                                <div id="imagePreview" class="position-relative" style="display: none;">
                                    <small class="text-muted d-block mb-2">Preview gambar baru</small>
                                    <img id="preview" src="" alt="Preview">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" id="removeImage">
                                        <i class="ri-close-line text-white"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('iklan.index') }}" class="btn btn-secondary btn-cancel">
                            <i class="ri-close-line me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                            <i class="ri-save-line me-1 text-white"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-xl-4 col-lg-3">
        <div class="side-panel sticky-lg-top">
            <div class="side-panel-icon"><i class="ri-shield-check-line"></i></div>
            <h5 class="fw-bold mb-2">Edit Aman</h5>
            <p class="text-muted small mb-3">Menyimpan perubahan di halaman ini tidak akan mengirim push notification ulang ke pelanggan.</p>

            <div class="delivery-step">
                <span>1</span>
                <div>
                    <strong>Update Data</strong>
                    <small>Judul, pesan, tipe, atau gambar diperbarui.</small>
                </div>
            </div>
            <div class="delivery-step">
                <span>2</span>
                <div>
                    <strong>Tidak Masuk Queue</strong>
                    <small>Job pengiriman notifikasi tidak dijalankan saat edit.</small>
                </div>
            </div>
            <div class="delivery-step mb-0">
                <span>3</span>
                <div>
                    <strong>Kirim Manual Jika Perlu</strong>
                    <small>Gunakan tombol kirim di list hanya jika ingin broadcast ulang.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-style')
<style>
:root {
  --primary-color: #18181b;
  --primary-hover: #27272a;
  --gray-border: #e4e4e7;
  --text-primary: #18181b;
  --text-secondary: #71717a;
  --border-radius: 8px;
}

.page-header {
  background: linear-gradient(180deg, #ffffff 0%, #fbfbfc 100%);
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  border: 1px solid var(--gray-border);
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.page-title-wrap {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.page-title-icon {
  width: 46px;
  height: 46px;
  border-radius: 13px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #18181b;
  color: #ffffff;
  font-size: 1.25rem;
  flex: 0 0 auto;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.25rem;
  letter-spacing: -0.02em;
}

.page-subtitle {
  color: var(--text-secondary);
  font-size: 0.875rem;
  margin-bottom: 0;
}

.form-modern,
.side-panel {
  border-radius: 16px;
  border: 1px solid var(--gray-border);
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  background: #ffffff;
}

.card-header-custom {
  color: var(--text-primary);
  border-radius: 16px 16px 0 0 !important;
  padding: 1.5rem;
  border-bottom: 1px solid var(--gray-border);
  background: #ffffff;
}

.form-section {
  background: #ffffff;
  border: 1px solid var(--gray-border);
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.form-section-title {
  color: var(--text-primary);
  font-weight: 600;
  margin-bottom: 1.25rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--gray-border);
}

.form-section-title i {
  margin-right: 0.5rem;
  font-size: 1.1rem;
}

.input-with-icon {
  position: relative;
  display: flex;
  align-items: stretch;
  width: 100%;
  border: 1px solid var(--gray-border);
  border-radius: var(--border-radius);
  transition: all 0.2s;
  overflow: hidden;
  background: white;
  height: 46px;
}

.input-with-icon:hover,
.input-with-icon:focus-within {
  border-color: var(--primary-color);
}

.input-with-icon:focus-within {
  box-shadow: 0 0 0 2px rgba(24, 24, 27, 0.1);
}

.input-textarea {
  height: auto;
}

.input-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 45px;
  background: #18181b;
  color: #ffffff;
  font-size: 1.1rem;
  flex-shrink: 0;
  border-right: 1px solid #18181b;
}

.textarea-icon {
  align-self: flex-start;
  height: auto;
  min-height: 46px;
  padding-top: 10px;
  padding-bottom: 10px;
}

.input-with-icon input,
.input-with-icon textarea {
  flex: 1;
  border: none;
  outline: none;
  padding: 0 1rem;
  font-size: 0.875rem;
  background: transparent;
  color: var(--text-primary);
  height: 100%;
}

.input-with-icon input[type="file"] {
  padding-top: 10px;
}

.input-with-icon textarea {
  width: 100%;
  resize: vertical;
  min-height: 140px;
}

.type-card {
  position: relative;
  cursor: pointer;
  transition: all 0.3s;
  height: 100%;
}

.type-card input[type="radio"] {
  position: absolute;
  opacity: 0;
}

.type-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem 1rem;
  border: 2px solid #e4e4e7;
  border-radius: 12px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  height: 100%;
  background: #ffffff;
}

.type-card:hover .type-label {
  border-color: #18181b;
  background: #fafafa;
  transform: translateY(-2px);
}

.type-card input[type="radio"]:checked + .type-label {
  border-color: #18181b;
  background: #f4f4f5;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.type-title {
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 0.25rem;
  color: #18181b;
}

.type-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
  font-size: 1.5rem;
  background: #18181b;
  color: white;
}

.side-panel {
  top: 1rem;
  padding: 1.25rem;
}

.side-panel-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: #f4f4f5;
  color: #18181b;
  border: 1px solid var(--gray-border);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
  font-size: 1.2rem;
}

.delivery-step {
  display: flex;
  gap: 0.75rem;
  padding: 0.875rem 0;
  border-bottom: 1px solid var(--gray-border);
}

.delivery-step span {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #18181b;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 800;
  flex: 0 0 auto;
}

.delivery-step strong {
  display: block;
  color: #18181b;
  font-size: 0.9rem;
}

.delivery-step small {
  display: block;
  color: var(--text-secondary);
  line-height: 1.45;
}

.current-image img,
#imagePreview img {
  max-width: 100%;
  max-height: 300px;
  border-radius: 12px;
  border: 1px solid var(--gray-border);
  object-fit: cover;
}

.btn-primary {
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fff !important;
}

.btn-primary:hover {
  background-color: #27272a !important;
  border-color: #27272a !important;
}

.btn-cancel {
  border: 1px solid var(--gray-border);
  background: white;
  color: var(--text-primary);
}

.btn-cancel:hover {
  background: #f4f4f5;
  color: var(--text-primary);
}

.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(2px);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.spinner-border-custom {
  color: var(--primary-color);
  width: 3rem;
  height: 3rem;
}

@media (max-width: 991.98px) {
  .side-panel {
    position: static !important;
  }
}

@media (max-width: 575.98px) {
  .page-header,
  .card-body {
    padding: 1rem !important;
  }

  .page-title-wrap {
    align-items: flex-start;
  }

  .d-flex.justify-content-end.gap-3 {
    flex-direction: column-reverse;
  }

  .d-flex.justify-content-end.gap-3 .btn {
    width: 100%;
  }
}
</style>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.querySelector('[name="message"]');
    const charCount = document.getElementById('charCount');

    if (messageTextarea && charCount) {
        const updateCount = () => {
            charCount.textContent = messageTextarea.value.length;
            charCount.classList.toggle('text-danger', messageTextarea.value.length > 900);
        };

        messageTextarea.addEventListener('input', updateCount);
        updateCount();
    }

    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const currentImage = document.getElementById('currentImage');
    const preview = document.getElementById('preview');
    const removeImageBtn = document.getElementById('removeImage');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                imagePreview.style.display = 'block';
                if (currentImage) currentImage.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.style.display = 'none';
            preview.src = '';
            if (currentImage) currentImage.style.display = 'block';
        });
    }

    const form = document.getElementById('iklanForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            const title = form.querySelector('[name="title"]').value.trim();
            const message = form.querySelector('[name="message"]').value.trim();
            const type = form.querySelector('[name="type"]:checked');
            const errors = [];

            if (!type) errors.push('Pilih tipe notifikasi!');
            if (!title) errors.push('Judul wajib diisi!');
            if (!message || message.length < 10) errors.push('Pesan minimal 10 karakter!');

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
                return false;
            }

            if (submitBtn) {
                document.querySelector('.loading-overlay').style.display = 'flex';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            }
        });
    }
});
</script>
@endsection
