@extends('layouts.sidebar-owner')

@section('title', 'Data Paket Kursus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book"></i> Data Paket Kursus</h2>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->kategori_id }}" {{ request('kategori') == $category->kategori_id ? 'selected' : '' }}>
                            {{ $category->nama_kategori ?? $category->nama_category ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama kursus..." value="{{ request('search') }}">
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ url('/owner/products') }}" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Kursus --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0;">
                    <tr>
                        <th style="width: 5%; padding: 12px 8px; text-align: center;">No</th>
                        <th style="width: 20%; padding: 12px 8px;">Nama Kursus</th>
                        <th style="width: 10%; padding: 12px 8px;">Jenjang</th>
                        <th style="width: 15%; padding: 12px 8px;">Mata Pelajaran</th>
                        <th style="width: 15%; padding: 12px 8px;">Pengajar</th>
                        <th style="width: 15%; padding: 12px 8px;">Harga</th>
                        <th style="width: 10%; padding: 12px 8px; text-align: center;">Status</th>
                        <th style="width: 10%; padding: 12px 8px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                    <tr style="border-bottom: 1px solid #e3e6f0;">
                        <td style="padding: 12px 8px; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 12px 8px;">
                            <strong>{{ $course->nama }}</strong>
                        </td>
                        <td style="padding: 12px 8px;">
                            {{ $course->jenjang->nama_kategori ?? $course->jenjang->nama_category ?? '-' }}
                        </td>
                        <td style="padding: 12px 8px;">
                            {{ $course->mapel->nama_mapel ?? '-' }}
                        </td>
                        <td style="padding: 12px 8px;">
                            {{ $course->instructor->nama ?? '-' }}
                        </td>
                        <td style="padding: 12px 8px;">
                            Rp {{ number_format($course->harga, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            @if($course->status == 'aktif')
                                <span class="badge bg-success px-3 py-1 rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding: 12px 8px; text-align: center;">
                            <a href="{{ route('owner.products.show', $course->id_paket) }}"
                               class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada data kursus</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Info tambahan --}}
@if($courses->count() > 0)
<div class="mt-3 text-muted small">
    <i class="fas fa-info-circle"></i> Menampilkan {{ $courses->count() }} data kursus
</div>
@endif

@endsection