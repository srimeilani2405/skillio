@extends('layouts.sidebar-admin')

@section('title', 'Detail Paket Kursus')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book-open"></i> Detail Paket Kursus</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.courses.edit', $course->id_paket) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    {{-- KOLOM KIRI - Info Utama --}}
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Kursus</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-semibold text-muted" width="45%">Nama Kursus</td>
                        <td>: <strong>{{ $course->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Jenjang</td>
                        <td>:
                            <span class="badge bg-info text-dark">
                                {{ $course->jenjang->nama_category ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Mata Pelajaran</td>
                        <td>:
                            <span class="badge bg-warning text-dark">
                                {{ $course->mapel->nama_mapel ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Pengajar</td>
                        <td>:
                            <span class="badge bg-primary">
                                <i class="fas fa-chalkboard-user"></i> 
                                {{ $course->instructor->nama ?? '-' }}
                            </span>
                            @if($course->instructor)
                                <br><small class="text-muted ms-3">{{ $course->instructor->spesialisasi ?? '' }}</small>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Harga Kursus</td>
                        <td>: <strong class="text-success">Rp {{ number_format($course->harga, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Biaya Sertifikat</td>
                        <td>:
                            @if($course->biaya_sertifikat > 0)
                                <strong class="text-primary">Rp {{ number_format($course->biaya_sertifikat, 0, ',', '.') }}</strong>
                            @else
                                <span class="badge bg-success">Gratis</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Total Tagihan</td>
                        <td>: <strong class="text-danger">
                            Rp {{ number_format($course->harga + $course->biaya_sertifikat, 0, ',', '.') }}
                        </strong>
                        <small class="text-muted">(kursus + sertifikat)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Status</td>
                        <td>:
                            @if($course->status == 'aktif')
                                <span class="badge bg-success">Aktif / Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN - Jadwal & Teknis --}}
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Jadwal & Detail Teknis</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="fw-semibold text-muted" width="50%">Hari Kursus</td>
                        <td>:
                            @php
                                $hariArr = is_array($course->hari)
                                    ? $course->hari
                                    : (json_decode($course->hari, true) ?? []);
                            @endphp
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @forelse($hariArr as $hari)
                                    <span class="badge bg-primary">{{ trim($hari) }}</span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Masa Aktif</td>
                        <td>: {{ $course->masa_aktif_hari }} hari</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Durasi Per Sesi</td>
                        <td>: {{ $course->durasi_per_sesi }} menit</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Kuota Peserta</td>
                        <td>: {{ $course->kuota_peserta }} orang</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Terdaftar</td>
                        <td>: {{ $course->terdaftar }} orang</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Sisa Kuota</td>
                        <td>: 
                            <span class="badge {{ $course->sisaKuota() > 0 ? 'bg-info' : 'bg-danger' }}">
                                {{ $course->sisaKuota() }} orang
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- DESKRIPSI --}}
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="fas fa-align-left"></i> Deskripsi Kursus</h5>
    </div>
    <div class="card-body">
        @if($course->deskripsi)
            <p class="mb-0" style="white-space: pre-line;">{{ $course->deskripsi }}</p>
        @else
            <p class="text-muted mb-0"><em>Tidak ada deskripsi</em></p>
        @endif
    </div>
</div>

@endsection