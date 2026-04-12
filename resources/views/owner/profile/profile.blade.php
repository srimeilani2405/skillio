@extends('layouts.sidebar-owner')

@section('title', 'Profil Owner')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-circle"></i> Profil Saya</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <i class="fas fa-user-edit me-2"></i> Edit Profil
            </div>
            <div class="card-body">
                <form action="{{ route('owner.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" 
                               value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                        <small class="text-muted">Role tidak dapat diubah</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white py-3">
                <i class="fas fa-key me-2"></i> Ganti Password
            </div>
            <div class="card-body">
                <form action="{{ route('owner.profile.password') }}" method="POST" id="passwordForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                        <small class="text-muted">Masukkan password Anda saat ini</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" id="new_password" required>
                        <small class="text-muted">Minimal 6 karakter</small>
                        <div class="invalid-feedback" id="passwordFeedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" id="confirm_password" required>
                        <div class="invalid-feedback" id="confirmFeedback"></div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 text-white">
                        <i class="fas fa-lock me-2"></i> Ganti Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Penting -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-info">
            <div class="card-body">
                <h6 class="text-info mb-2">
                    <i class="fas fa-info-circle me-2"></i>Informasi Penting:
                </h6>
                <small>
                    • Pastikan password baru minimal 6 karakter.<br>
                    • Jangan beri tahu password Anda kepada siapapun.<br>
                    • Password yang kuat sebaiknya menggunakan kombinasi huruf dan angka.
                </small>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side password validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    let newPass = document.getElementById('new_password').value;
    let confirmPass = document.getElementById('confirm_password').value;
    let isValid = true;

    if (newPass.length < 6) {
        document.getElementById('passwordFeedback').innerText = 'Password minimal 6 karakter';
        document.getElementById('new_password').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('new_password').classList.remove('is-invalid');
    }

    if (newPass !== confirmPass) {
        document.getElementById('confirmFeedback').innerText = 'Password tidak cocok';
        document.getElementById('confirm_password').classList.add('is-invalid');
        isValid = false;
    } else {
        document.getElementById('confirm_password').classList.remove('is-invalid');
    }

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<style>
    .card-header {
        font-weight: bold;
        font-size: 1.1rem;
    }
    .btn-primary {
        background: linear-gradient(45deg, #1CA7A6, #8BC34A);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #178f8e, #7ab33e);
    }
    .form-control:focus {
        border-color: #1CA7A6;
        box-shadow: 0 0 0 0.2rem rgba(28, 167, 166, 0.25);
    }
</style>

@endsection