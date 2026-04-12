@extends('layouts.sidebar-owner')

@section('title', 'Detail Paket Kursus')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book"></i> Detail Paket Kursus</h2>
    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th width="35%">Nama Kursus</th><td>: <strong>{{ $course->nama }}</strong></td></tr>
                    <tr><th>Jenjang</th><td>: {{ $course->jenjang->nama_kategori ?? $course->jenjang->nama_category ?? '-' }}</td></tr>
                    <tr><th>Mata Pelajaran</th><td>: {{ $course->mapel->nama_mapel ?? '-' }}</td></tr>
                    <tr><th>Pengajar</th><td>: {{ $course->instructor->nama ?? '-' }}</td></tr>
                    <tr><th>Harga Kursus</th><td>: Rp {{ number_format($course->harga, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Sertifikat</th><td>: Rp {{ number_format($course->biaya_sertifikat, 0, ',', '.') }}</td></tr>
                    <tr><th>Total Harga</th><td>: <strong class="text-success">Rp {{ number_format($course->harga + $course->biaya_sertifikat, 0, ',', '.') }}</strong></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th width="35%">Masa Aktif</th><td>: {{ $course->masa_aktif_hari }} hari</td></tr>
                    <tr><th>Durasi per Sesi</th><td>: {{ $course->durasi_per_sesi }} jam</td></tr>
                    <tr><th>Hari</th><td>: {{ is_array($course->hari) ? implode(', ', $course->hari) : $course->hari }}</td></tr>
                    <tr><th>Kuota Peserta</th><td>: {{ $course->kuota_peserta }} orang</td></tr>
                    <tr><th>Terdaftar</th><td>: <span class="badge bg-info">{{ $course->terdaftar }} orang</span></td></tr>
                    <tr><th>Sisa Kuota</th><td>: <span class="badge {{ $course->sisaKuota() > 0 ? 'bg-success' : 'bg-danger' }}">{{ $course->sisaKuota() }} orang</span></td></tr>
                    <tr><th>Status</th><td>: @if($course->status == 'aktif') <span class="badge bg-success">Aktif</span> @else <span class="badge bg-secondary">Nonaktif</span> @endif</td></tr>
                </table>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <hr>
                <h6><i class="fas fa-align-left"></i> Deskripsi</h6>
                <p>{{ $course->deskripsi ?? '-' }}</p>
            </div>
        </div>

        {{-- Statistik Penjualan --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6><i class="fas fa-chart-line"></i> Total Terjual</h6>
                        <h3 class="mb-0">{{ $totalTerjual }} Peserta</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6><i class="fas fa-money-bill"></i> Total Pendapatan</h6>
                        <h3 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection