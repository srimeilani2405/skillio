@extends('layouts.sidebar-owner')

@section('title', 'Profil Owner')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="fas fa-user-circle me-2"></i> Profil Saya</h2>
    </div>
    {{-- BUTTON KEMBALI KE HALAMAN SEBELUMNYA --}}
    <a href="javascript:history.back()" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- TAMBAHKAN BLOK ERROR INI --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- FORM EDIT PROFIL --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-user-edit fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Edit Profil</h4>
                        <p class="text-muted mb-0">Ubah data diri Anda di sini</p>
                    </div>
                </div>

                <form action="{{ route('owner.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user text-primary me-1"></i> Nama Lengkap
                        </label>
                        <input type="text" name="name"
                            class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Masukkan nama lengkap"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-envelope text-primary me-1"></i> Username
                        </label>
                        <input type="text" name="username"
                            class="form-control form-control-lg rounded-3 @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}"
                            placeholder="Masukkan username"
                            required>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-shield-alt text-primary me-1"></i> Role
                        </label>
                        <div class="bg-light rounded-3 p-3">
                            <span class="badge px-3 py-2" style="background: #e74a3b; font-size: 0.9rem;">
                                <i class="fas fa-crown me-1"></i> {{ strtoupper($user->role) }}
                            </span>
                            <small class="text-muted ms-2">Role tidak dapat diubah</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3"
                        style="background: linear-gradient(135deg, #1CA7A6, #8BC34A); border: none;">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- FORM GANTI PASSWORD --}}
    <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-key fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">Ganti Password</h4>
                        <p class="text-muted mb-0">Perbarui password Anda</p>
                    </div>
                </div>

                <form action="{{ route('owner.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-lock text-warning me-1"></i> Password Saat Ini
                        </label>
                        <input type="password" name="current_password"
                            class="form-control form-control-lg rounded-3 @error('current_password') is-invalid @enderror"
                            placeholder="Masukkan password saat ini"
                            value="{{ old('current_password') }}"
                            required>
                        @error('current_password')
                        <div class="invalid-feedback d-block mt-1 text-danger">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-key text-warning me-1"></i> Password Baru
                        </label>
                        <input type="password" name="password"
                            class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                            placeholder="Masukkan password baru (min. 6 karakter)"
                            required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-check-circle text-warning me-1"></i> Konfirmasi Password Baru
                        </label>
                        <input type="password" name="password_confirmation"
                            class="form-control form-control-lg rounded-3"
                            placeholder="Ulangi password baru"
                            required>
                    </div>

                    <button type="submit" class="btn btn-warning btn-lg w-100 rounded-3 text-white fw-semibold"
                        style="background: linear-gradient(135deg, #e67e22, #f39c12); border: none;">
                        <i class="fas fa-sync-alt me-2"></i> Ganti Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 {
        border-radius: 1rem !important;
    }

    .rounded-3 {
        border-radius: 0.75rem !important;
    }

    .form-control-lg {
        padding: 12px 16px;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #1CA7A6;
        box-shadow: 0 0 0 0.2rem rgba(28, 167, 166, 0.25);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-primary:hover,
    .btn-warning:hover {
        opacity: 0.9;
        transform: scale(0.98);
        transition: all 0.2s ease;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>

<script>
    // Hapus error validation setelah 3 detik
    setTimeout(function() {
        // 1. Hilangkan teks pesan error
        let textErrors = document.querySelectorAll('.invalid-feedback');
        textErrors.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        });

        // 2. Hilangkan border merah pada input box
        let invalidInputs = document.querySelectorAll('.is-invalid');
        invalidInputs.forEach(function(input) {
            input.classList.remove('is-invalid');
        });

        // (Opsional) Hilangkan juga alert success/error agar bersih
        let sessionAlerts = document.querySelectorAll('.alert');
        sessionAlerts.forEach(function(alertElement) {
            alertElement.style.transition = 'opacity 0.5s ease';
            alertElement.style.opacity = '0';
            setTimeout(function() {
                alertElement.style.display = 'none';
            }, 500);
        });
    }, 3000); // 3000 ms = 3 detik
</script>

@endsection