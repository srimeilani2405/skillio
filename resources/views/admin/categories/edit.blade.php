{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.sidebar-admin')

@section('title', 'Edit Jenjang')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Edit Jenjang</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->kategori_id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Jenjang</label>
                <input type="text"
                       name="nama_category"
                       value="{{ old('nama_category', $category->nama_category) }}"
                       class="form-control @error('nama_category') is-invalid @enderror"
                       required>
                @error('nama_category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi"
                          class="form-control">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="aktif" {{ $category->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $category->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
</div>

@endsection