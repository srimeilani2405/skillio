@extends('layouts.sidebar-owner')

@section('title', 'Dashboard Owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard Owner</h2>
    <div>
        <span class="text-muted">{{ now()->format('d F Y') }}</span>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Kursus</h6>
                        <h3 class="mb-0">{{ $totalKursus }}</h3>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Kategori</h6>
                        <h3 class="mb-0">{{ $totalKategori }}</h3>
                    </div>
                    <i class="fas fa-tags fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pengajar</h6>
                        <h3 class="mb-0">{{ $totalPengajar }}</h3>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Transaksi</h6>
                        <h3 class="mb-0">{{ $totalTransaksi }}</h3>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Pendapatan 6 Bulan Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="pendapatanChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Kursus Terlaris</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kursus</th>
                                <th>Total Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kursusTerlaris as $index => $kursus)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kursus->nama }}</td>
                                <td>{{ $kursus->total_terjual }} paket</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data penjualan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Ringkasan Keuangan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="border p-3 rounded">
                    <h6>Total Pendapatan</h6>
                    <h3 class="text-success">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border p-3 rounded">
                    <h6>Rata-rata Transaksi</h6>
                    <h3 class="text-primary">Rp {{ number_format($totalTransaksi > 0 ? $pendapatanTotal / $totalTransaksi : 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    
    const bulanLabels = [];
    const dataPendapatan = [];
    
    @foreach($pendapatanBulanan as $data)
        bulanLabels.push('{{ $data->bulan }}/{{ $data->tahun }}');
        dataPendapatan.push({{ $data->total }});
    @endforeach
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataPendapatan,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection