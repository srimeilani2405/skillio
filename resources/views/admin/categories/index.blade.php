{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.sidebar-admin')

@section('title', 'Data Jenjang')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-layer-group"></i> Data Jenjang</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jenjang
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Jenjang</th>
                        <th width="40%">Deskripsi</th>
                        <th width="15%">Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $category->nama_category }}</strong></td>
                        <td>{{ $category->deskripsi ?? '-' }}</td>
                        <td>
                            @if($category->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                       <td class="text-center">
    <a href="{{ route('admin.categories.edit', $category->kategori_id) }}"
       class="btn btn-warning btn-sm text-white" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('admin.categories.destroy', $category->kategori_id) }}"
          method="POST"
          class="d-inline form-hapus">
        @csrf
        @method('DELETE')
        <button type="button"
                class="btn btn-danger btn-sm btn-hapus"
                data-nama="{{ $category->nama_category }}"
                title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data jenjang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection