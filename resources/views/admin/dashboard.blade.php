@extends('layouts.sidebar-admin')

@section('title', 'Dashboard')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h2>
    <p class="text-muted">Selamat datang, {{ Auth::user()->name }}! 👋</p>
</div>

{{-- CARD STATISTIK --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#eef0fb;">
                    <i class="fas fa-book fa-lg" style="color:#4e73df;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Kursus</div>
                    <div class="fw-bold fs-3">{{ $totalKursus }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#e8faf4;">
                    <i class="fas fa-tags fa-lg" style="color:#1cc88a;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Kategori</div>
                    <div class="fw-bold fs-3">{{ $totalKategori }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#e8f8fb;">
                    <i class="fas fa-chalkboard-teacher fa-lg" style="color:#36b9cc;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pengajar</div>
                    <div class="fw-bold fs-3">{{ $totalPengajar }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:#fef9ec;">
                    <i class="fas fa-shopping-cart fa-lg" style="color:#f6c23e;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fw-bold fs-3">{{ $totalTransaksi }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK + ACTIVITY LOG --}}
<div class="row g-3">

    {{-- GRAFIK PENDAPATAN PER BULAN --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 fw-semibold">
                <i class="fas fa-chart-bar me-2"></i> Pendapatan Per Bulan ({{ date('Y') }})
            </div>
            <div class="card-body">
                <canvas id="grafikTransaksi" height="120"></canvas>
            </div>
        </div>
    </div>

    
</div>

<style>
    .list-group-item {
        transition: all 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fc;
    }
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari controller
    var bulanLabel = <?php echo json_encode($bulanLabel); ?>;
    var grafikData = <?php echo json_encode($grafikData); ?>;

    var ctx = document.getElementById('grafikTransaksi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bulanLabel,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
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