@extends('layouts.sidebar-admin')

@section('title', 'Data Pengajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chalkboard-teacher"></i> Data Pengajar</h2>
    <a href="{{ route('admin.instructors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pengajar
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th>Spesialisasi</th>
                        <th>Status</th>
                        <th width="220px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $d->nama }}</strong></td>
                        <td>{{ $d->email ?? '-' }}</td>
                        <td>{{ $d->no_telp ?? '-' }}</td>
                        <td>
                            @if($d->spesialisasi)
                                <span class="badge bg-info text-white">{{ $d->spesialisasi }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($d->status == 'aktif')
                                <span class="badge bg-primary">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <!-- ✅ TOMBOL LIHAT PROFIL -->
                            <a href="{{ route('admin.instructors.profile', $d->id_instructors) }}" 
                               class="btn bg-success btn-sm text-white" title=Profil>
                                <i class="fas fa-eye    "></i> 
                            </a>
                            
                            <a href="{{ route('admin.instructors.edit', $d->id_instructors) }}" 
                               class="btn btn-warning btn-sm text-white" title=edit >
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.instructors.destroy', $d->id_instructors) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus {{ $d->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data pengajar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection