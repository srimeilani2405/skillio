{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.sidebar-admin')

@section('title', 'Tambah Jenjang')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Tambah Jenjang</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Jenjang</label>
                <input type="text"
                       name="nama_category"
                       value="{{ old('nama_category') }}"
                       class="form-control @error('nama_category') is-invalid @enderror"
                       placeholder="Contoh: SD, SMP, SMA"
                       required>
                @error('nama_category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi"
                          class="form-control"
                          placeholder="Deskripsi tentang jenjang ini">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
</div>

@endsection