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

    {{-- ACTIVITY LOG KHUSUS ADMIN --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2"></i> Activity Log (Admin)</span>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-link text-decoration-none">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentLogs->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentLogs as $log)
                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <div class="flex-shrink-0">
                                    @if($log->action == 'Tambah')
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; background: #d4edda;">
                                            <i class="fas fa-plus-circle text-success fa-lg"></i>
                                        </div>
                                    @elseif($log->action == 'Edit')
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; background: #fff3cd;">
                                            <i class="fas fa-edit text-warning fa-lg"></i>
                                        </div>
                                    @elseif($log->action == 'Hapus')
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; background: #f8d7da;">
                                            <i class="fas fa-trash-alt text-danger fa-lg"></i>
                                        </div>
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px; background: #e2e3e5;">
                                            <i class="fas fa-info-circle text-secondary fa-lg"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="mb-0">{{ $log->user->name ?? 'System' }}</strong>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge
                                            @if($log->action == 'Tambah') bg-success
                                            @elseif($log->action == 'Edit') bg-warning text-dark
                                            @elseif($log->action == 'Hapus') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ $log->action }}
                                        </span>
                                        <span class="text-muted small">{{ Str::limit($log->description, 50) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas</p>
                    </div>
                @endif
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