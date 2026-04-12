@extends('layouts.sidebar-admin')

@section('title', 'Data Paket Kursus')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book"></i> Data Paket Kursus</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kursus
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c); color: white;">
                        <th class="ps-3">No</th>
                        <th>Nama Kursus</th>
                        <th>Jenjang</th>
                        <th>Mata Pelajaran</th>
                        <th>Pengajar</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                     </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                    <tr>
                        <td class="ps-3">{{ $index + 1 }}</td>
                        <td><strong>{{ $course->nama }}</strong></td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $course->jenjang->nama_category ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $course->mapel->nama_mapel ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary">
                                <i class="fas fa-chalkboard-user"></i> 
                                {{ $course->instructor->nama ?? '-' }}
                            </span>
                            @if($course->instructor)
                                <br><small class="text-muted">{{ $course->instructor->spesialisasi ?? '' }}</small>
                            @endif
                        </td>
                        <td>Rp {{ number_format($course->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($course->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.courses.show', $course->id_paket) }}"
                               class="btn btn-info btn-sm text-white" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.courses.edit', $course->id_paket) }}"
                               class="btn btn-warning btn-sm text-white" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course->id_paket) }}"
                                  method="POST" class="d-inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-hapus"
                                        data-nama="{{ $course->nama }}"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada data kursus</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@php
    $flashSuccess = session('success');
    $flashError   = session('error');
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var nama = this.dataset.nama;
        var form = this.closest('.form-hapus');

        Swal.fire({
            icon: 'warning',
            title: 'Hapus Kursus?',
            html: 'Kursus <strong>' + nama + '</strong> akan dihapus permanen!',
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

var flashSuccess = '{{ $flashSuccess }}';
var flashError   = '{{ $flashError }}';

if (flashSuccess) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: flashSuccess,
        confirmButtonColor: '#1cc88a',
        timer: 2500,
        timerProgressBar: true
    });
}

if (flashError) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: flashError,
        confirmButtonColor: '#e74a3b'
    });
}
</script>

@endsection 