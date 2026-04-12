@extends('layouts.sidebar-owner')

@section('title','Edit User')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-edit"></i> Edit User</h2>
    <a href="{{ route('owner.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($user->role == 'owner')
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> 
                <strong>Perhatian!</strong> User ini adalah Owner. Untuk mengedit data Owner, silahkan gunakan menu 
                <strong>Profil Saya</strong> di pojok kanan atas.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('owner.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required
                           {{ $user->role == 'owner' ? 'disabled' : '' }}>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                           value="{{ old('username', $user->username) }}" required
                           {{ $user->role == 'owner' ? 'disabled' : '' }}>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           {{ $user->role == 'owner' ? 'disabled' : '' }}>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           {{ $user->role == 'owner' ? 'disabled' : '' }}>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" 
                            required {{ $user->role == 'owner' ? 'disabled' : '' }}>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        {{-- ❌ TIDAK ADA OPSI OWNER --}}
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" 
                            required {{ $user->role == 'owner' ? 'disabled' : '' }}>
                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <div class="text-end">
                @if($user->role != 'owner')
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                @else
                    <a href="{{ route('owner.profile') }}" class="btn btn-primary">
                        <i class="fas fa-user-circle me-2"></i> Edit di Profil Saya
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<style>
    .btn-primary {
        background: linear-gradient(45deg, #1CA7A6, #8BC34A);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(45deg, #178f8e, #7ab33e);
    }
</style>

@endsection