@extends('layouts.sidebar-owner')

@section('title', 'Activity Log')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h2 class="mb-0"><i class="fas fa-history"></i> Aktivitas User</h2>
        <small class="text-muted">Dashboard / Aktivitas User</small>
    </div>
    <div class="text-end">
        <div class="fw-bold text-primary"><i class="fas fa-calendar"></i> {{ now()->format('d M Y') }}</div>
    </div>
</div>

{{-- STATISTIK PER TIPE --}}
<div class="d-flex flex-wrap gap-3 mb-4 mt-3">
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#e8f4fd;">
        <div class="fw-bold fs-3 text-primary">{{ $totalAktivitas }}</div>
        <div class="small text-muted">Total</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#e8faf4;">
        <div class="fw-bold fs-3 text-success">{{ $statLogin }}</div>
        <div class="small text-muted">Login</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#fdf0ef;">
        <div class="fw-bold fs-3 text-danger">{{ $statLogout }}</div>
        <div class="small text-muted">Logout</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#eef0fb;">
        <div class="fw-bold fs-3 text-primary">{{ $statTambah }}</div>
        <div class="small text-muted">Tambah</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#fef9ec;">
        <div class="fw-bold fs-3 text-warning">{{ $statEdit }}</div>
        <div class="small text-muted">Edit</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#fdf0ef;">
        <div class="fw-bold fs-3 text-danger">{{ $statHapus }}</div>
        <div class="small text-muted">Hapus</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#f0ebfa;">
        <div class="fw-bold fs-3 text-purple" style="color:#6f42c1;">{{ $aktivitasHariIni }}</div>
        <div class="small text-muted">Hari Ini</div>
    </div>
    <div class="card border-0 shadow-sm text-center px-4 py-3" style="min-width:110px; border-radius:16px; background:#e8faf4;">
        <div class="fw-bold fs-3" style="color:#1abc9c;">{{ $aktivitasMingguIni }}</div>
        <div class="small text-muted">Minggu Ini</div>
    </div>
</div>

{{-- FILTER --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="form-control" value="{{ request('sampai_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">User</label>
                <select name="user_id" class="form-select">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Tipe</label>
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="Login"  {{ request('tipe') == 'Login'  ? 'selected' : '' }}>Login</option>
                    <option value="Logout" {{ request('tipe') == 'Logout' ? 'selected' : '' }}>Logout</option>
                    <option value="Tambah" {{ request('tipe') == 'Tambah' ? 'selected' : '' }}>Tambah</option>
                    <option value="Edit"   {{ request('tipe') == 'Edit'   ? 'selected' : '' }}>Edit</option>
                    <option value="Hapus"  {{ request('tipe') == 'Hapus'  ? 'selected' : '' }}>Hapus</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ url('/owner/activity-logs') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        {{-- Header info --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <div>
                <strong>Riwayat Aktivitas</strong>
                <div class="small text-muted">
                    Menampilkan {{ $logs->firstItem() }}-{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
                </div>
            </div>
            @if($firstLog && $lastLog)
            <div class="small text-muted">
                <i class="fas fa-clock"></i>
                {{ $firstLog->created_at->format('d/m/Y') }} &ndash; {{ $lastLog->created_at->format('d/m/Y') }}
            </div>
            @endif
        </div>

        <style>
            .tbl-log thead tr th {
                background: linear-gradient(135deg, #2c3e6b, #1abc9c) !important;
                color: #ffffff !important;
                font-weight: 600;
                border: none;
            }
        </style>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 tbl-log">
                <thead>
                    <tr>
                        <th class="ps-4">WAKTU</th>
                        <th>USER</th>
                        <th>ROLE</th>
                        <th>AKTIVITAS</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle bg-secondary d-inline-block" style="width:8px;height:8px;"></span>
                                <div>
                                    <div class="small fw-semibold">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-circle text-secondary"></i>
                                <span>{{ $log->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @php $role = $log->user->role ?? '-'; @endphp
                            @if($role == 'admin')
                                <span class="badge rounded-pill" style="background:#e8f4fd; color:#2E75B6;">
                                    <i class="fas fa-shield-alt me-1"></i> Admin
                                </span>
                            @elseif($role == 'kasir')
                                <span class="badge rounded-pill" style="background:#e8faf4; color:#1abc9c;">
                                    <i class="fas fa-cash-register me-1"></i> Kasir
                                </span>
                            @elseif($role == 'owner')
                                <span class="badge rounded-pill" style="background:#fef9ec; color:#f6c23e;">
                                    <i class="fas fa-crown me-1"></i> Owner
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary">{{ $role }}</span>
                            @endif
                        </td>
                        <td>
                            @if($log->tipe_aktivitas == 'Login')
                                <span class="badge rounded-pill" style="background:#e8faf4; color:#1cc88a;">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </span>
                            @elseif($log->tipe_aktivitas == 'Logout')
                                <span class="badge rounded-pill bg-secondary">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </span>
                            @elseif($log->tipe_aktivitas == 'Tambah')
                                <span class="badge rounded-pill" style="background:#eef0fb; color:#4e73df;">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </span>
                            @elseif($log->tipe_aktivitas == 'Edit')
                                <span class="badge rounded-pill" style="background:#fef9ec; color:#f6c23e;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </span>
                            @elseif($log->tipe_aktivitas == 'Hapus')
                                <span class="badge rounded-pill" style="background:#fdf0ef; color:#e74a3b;">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </span>
                            @else
                                <span class="badge rounded-pill bg-info">{{ $log->tipe_aktivitas }}</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $log->aktivitas }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Tidak ada data activity log</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end p-3">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection