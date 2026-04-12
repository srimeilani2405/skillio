@extends('layouts.sidebar-owner')

@section('title','Kelola Semua User')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog me-2"></i> Kelola Semua User</h2>
    <a href="{{ route('owner.users.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i> Tambah User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background: linear-gradient(135deg, #1F2A6C, #1CA7A6, #8BC34A); color: white;">
                        <th class="ps-3" style="width: 60px;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data as $u)
                <tr>
                    <td class="ps-3">{{ $loop->iteration }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->username }}</td>
                    <td>
                        @if($u->role == 'admin')
                            <span class="badge px-3 py-2" style="background: #1F2A6C; font-size: 0.8rem;">
                                <i class="fas fa-user-shield me-1"></i> Admin
                            </span>
                        @else
                            <span class="badge px-3 py-2" style="background: #1CA7A6; font-size: 0.8rem;">
                                <i class="fas fa-cash-register me-1"></i> Kasir
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($u->status == 1)
                            <span class="badge px-3 py-2" style="background: #28a745; font-size: 0.8rem;">
                                <i class="fas fa-check-circle me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge px-3 py-2" style="background: #dc3545; font-size: 0.8rem;">
                                <i class="fas fa-times-circle me-1"></i> Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('owner.users.edit', $u->id) }}"
                           class="btn btn-warning btn-sm text-white me-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('owner.users.destroy', $u->id) }}"
                              method="POST" class="d-inline form-hapus">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-hapus"
                                    data-nama="{{ $u->name }}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Belum ada data user (Admin/Kasir)</p>
                        <a href="{{ route('owner.users.create') }}" class="btn btn-sm btn-primary mt-3">
                            <i class="fas fa-plus me-1"></i> Tambah User Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-success {
        background: linear-gradient(45deg, #1CA7A6, #8BC34A);
        border: none;
    }
    .btn-success:hover {
        background: linear-gradient(45deg, #178f8e, #7ab33e);
        transform: scale(0.98);
    }
    .btn-warning {
        background: #f39c12;
        border: none;
    }
    .btn-warning:hover {
        background: #e67e22;
    }
    .btn-danger {
        background: #e74a3b;
        border: none;
    }
    .btn-danger:hover {
        background: #c0392b;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(28, 167, 166, 0.05);
    }
    .rounded-4 {
        border-radius: 1rem !important;
    }
    .btn-sm {
        padding: 6px 12px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var nama = this.dataset.nama;
        var form = this.closest('.form-hapus');

        Swal.fire({
            icon: 'warning',
            title: 'Hapus User?',
            html: 'User <strong>' + nama + '</strong> akan dihapus permanen!',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#6c757d'
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

@endsection