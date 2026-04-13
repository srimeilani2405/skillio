@extends('layouts.sidebar-owner')

@section('title', 'Activity Log')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-1">
    <div>
        <h2 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Aktivitas User</h2>
        <small class="text-muted">Dashboard / Aktivitas User</small>
    </div>
    <div class="fw-bold text-primary">
        <i class="fas fa-calendar me-1"></i> {{ now()->translatedFormat('d M Y') }}
    </div>
</div>

{{-- STATISTIK --}}
<div class="d-flex flex-wrap gap-3 my-4">
    @php
        $stats = [
            ['label' => 'Total',      'value' => $totalAktivitas,     'color' => '#2196f3', 'bg' => '#e8f4fd'],
            ['label' => 'Hari Ini',   'value' => $aktivitasHariIni,   'color' => '#6f42c1', 'bg' => '#f0ebfa'],
            ['label' => 'Minggu Ini', 'value' => $aktivitasMingguIni, 'color' => '#1abc9c', 'bg' => '#e8faf4'],
        ];
    @endphp
    @foreach($stats as $s)
    <div style="min-width:100px; border-radius:16px; background:{{ $s['bg'] }}; padding:14px 20px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
        <div style="font-size:1.8rem; font-weight:800; color:{{ $s['color'] }}; line-height:1;">{{ $s['value'] }}</div>
        <div style="font-size:12px; color:#888; margin-top:4px;">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- FILTER --}}
<form method="GET" action="" class="mb-4">
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
            <input type="date" name="dari_tanggal" class="form-control form-control-sm" value="{{ request('dari_tanggal') }}">
        </div>
        <div class="col-auto">
            <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai_tanggal" class="form-control form-control-sm" value="{{ request('sampai_tanggal') }}">
        </div>
        <div class="col-auto">
            <label class="form-label small fw-semibold mb-1">User</label>
            <select name="user_id" class="form-select form-select-sm" style="min-width:140px;">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small fw-semibold mb-1">Tipe</label>
            <select name="tipe" class="form-select form-select-sm" style="min-width:130px;">
                <option value="">Semua Tipe</option>
                <option value="Login"     {{ request('tipe') == 'Login'     ? 'selected' : '' }}>Login</option>
                <option value="Logout"    {{ request('tipe') == 'Logout'    ? 'selected' : '' }}>Logout</option>
                <option value="Tambah"    {{ request('tipe') == 'Tambah'    ? 'selected' : '' }}>Tambah</option>
                <option value="Edit"      {{ request('tipe') == 'Edit'      ? 'selected' : '' }}>Edit</option>
                <option value="Hapus"     {{ request('tipe') == 'Hapus'     ? 'selected' : '' }}>Hapus</option>
                <option value="Transaksi" {{ request('tipe') == 'Transaksi' ? 'selected' : '' }}>Transaksi</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success btn-sm px-3">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
        <div class="col-auto">
            <a href="{{ url('/owner/activity-logs') }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="fas fa-times me-1"></i> Reset
            </a>
        </div>
    </div>
</form>

{{-- TABEL --}}
<div style="background:#fff; border-radius:8px; box-shadow:0 1px 6px rgba(0,0,0,0.07); overflow:hidden;">

    {{-- Header info --}}
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid #eee;">
        <div>
            <strong>Riwayat Aktivitas</strong>
            @if($logs->total() > 0)
            <div class="small text-muted">
                Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
            </div>
            @else
            <div class="small text-muted">Tidak ada data</div>
            @endif
        </div>
        @if(isset($firstLog) && isset($lastLog) && $firstLog && $lastLog)
        <div class="small text-muted">
            <i class="fas fa-clock me-1"></i>
            {{ $firstLog->created_at->format('d/m/Y') }} &ndash; {{ $lastLog->created_at->format('d/m/Y') }}
        </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c);">
                    <th class="ps-4 text-white border-0" style="font-weight:600;">WAKTU</th>
                    <th class="text-white border-0" style="font-weight:600;">USER</th>
                    <th class="text-white border-0" style="font-weight:600;">ROLE</th>
                    <th class="text-white border-0" style="font-weight:600;">AKTIVITAS</th>
                    <th class="text-white border-0" style="font-weight:600;">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle bg-secondary d-inline-block flex-shrink-0"
                                  style="width:8px;height:8px;"></span>
                            <div>
                                <div class="small fw-semibold">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user-circle text-secondary"></i>
                            <span>{{ optional($log->user)->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        @php $role = optional($log->user)->role ?? '-'; @endphp
                        @if($role === 'admin')
                            <span class="badge rounded-pill" style="background:#e8f4fd; color:#2E75B6;">
                                <i class="fas fa-shield-alt me-1"></i>Admin
                            </span>
                        @elseif($role === 'kasir')
                            <span class="badge rounded-pill" style="background:#e8faf4; color:#1abc9c;">
                                <i class="fas fa-cash-register me-1"></i>Kasir
                            </span>
                        @elseif($role === 'owner')
                            <span class="badge rounded-pill" style="background:#fef9ec; color:#f6c23e;">
                                <i class="fas fa-crown me-1"></i>Owner
                            </span>
                        @else
                            <span class="badge rounded-pill bg-secondary text-white">{{ $role }}</span>
                        @endif
                    </td>
                    <td>
                        @php $tipe = $log->tipe_aktivitas; @endphp
                        @if($tipe === 'Login')
                            <span class="badge rounded-pill" style="background:#e8faf4; color:#1cc88a;">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </span>
                        @elseif($tipe === 'Logout')
                            <span class="badge rounded-pill bg-secondary text-white">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </span>
                        @elseif($tipe === 'Tambah')
                            <span class="badge rounded-pill" style="background:#eef0fb; color:#4e73df;">
                                <i class="fas fa-plus me-1"></i>Tambah
                            </span>
                        @elseif($tipe === 'Edit')
                            <span class="badge rounded-pill" style="background:#fef9ec; color:#f6c23e;">
                                <i class="fas fa-edit me-1"></i>Edit
                            </span>
                        @elseif($tipe === 'Hapus')
                            <span class="badge rounded-pill" style="background:#fdf0ef; color:#e74a3b;">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </span>
                        @elseif($tipe === 'Transaksi')
                            <span class="badge rounded-pill" style="background:#e3f2fd; color:#1565c0;">
                                <i class="fas fa-receipt me-1"></i>Transaksi
                            </span>
                        @else
                            <span class="badge rounded-pill bg-info text-white">{{ $tipe }}</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $log->aktivitas }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Tidak ada data activity log</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CUSTOM PAGINATION --}}
    @if($logs->lastPage() > 1)
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #eee;">

        {{-- Info halaman --}}
        <div class="small text-muted">
            Halaman <strong>{{ $logs->currentPage() }}</strong> dari <strong>{{ $logs->lastPage() }}</strong>
        </div>

        {{-- Tombol navigasi --}}
        <div class="d-flex align-items-center gap-1">

            {{-- Pertama --}}
            @if($logs->onFirstPage())
                <button class="btn btn-sm btn-light border" disabled style="width:34px;height:34px;padding:0;">
                    <i class="fas fa-angle-double-left" style="font-size:11px;"></i>
                </button>
            @else
                <a href="{{ $logs->withQueryString()->url(1) }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;">
                    <i class="fas fa-angle-double-left" style="font-size:11px;"></i>
                </a>
            @endif

            {{-- Prev --}}
            @if($logs->onFirstPage())
                <button class="btn btn-sm btn-light border" disabled style="width:34px;height:34px;padding:0;">
                    <i class="fas fa-angle-left" style="font-size:11px;"></i>
                </button>
            @else
                <a href="{{ $logs->withQueryString()->previousPageUrl() }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;">
                    <i class="fas fa-angle-left" style="font-size:11px;"></i>
                </a>
            @endif

            {{-- Nomor halaman (window ±2) --}}
            @php
                $current   = $logs->currentPage();
                $last      = $logs->lastPage();
                $start     = max(1, $current - 2);
                $end       = min($last, $current + 2);
            @endphp

            @if($start > 1)
                <a href="{{ $logs->withQueryString()->url(1) }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;font-size:13px;">1</a>
                @if($start > 2)
                    <span class="px-1 text-muted" style="line-height:34px;">…</span>
                @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page === $current)
                    <button class="btn btn-sm border"
                            style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;font-size:13px;font-weight:700;background:linear-gradient(135deg,#2c3e6b,#1abc9c);color:#fff;border-color:transparent;"
                            disabled>
                        {{ $page }}
                    </button>
                @else
                    <a href="{{ $logs->withQueryString()->url($page) }}"
                       class="btn btn-sm btn-light border"
                       style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;font-size:13px;">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($end < $last)
                @if($end < $last - 1)
                    <span class="px-1 text-muted" style="line-height:34px;">…</span>
                @endif
                <a href="{{ $logs->withQueryString()->url($last) }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;font-size:13px;">{{ $last }}</a>
            @endif

            {{-- Next --}}
            @if($logs->hasMorePages())
                <a href="{{ $logs->withQueryString()->nextPageUrl() }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;">
                    <i class="fas fa-angle-right" style="font-size:11px;"></i>
                </a>
            @else
                <button class="btn btn-sm btn-light border" disabled style="width:34px;height:34px;padding:0;">
                    <i class="fas fa-angle-right" style="font-size:11px;"></i>
                </button>
            @endif

            {{-- Terakhir --}}
            @if($logs->hasMorePages())
                <a href="{{ $logs->withQueryString()->url($logs->lastPage()) }}"
                   class="btn btn-sm btn-light border" style="width:34px;height:34px;padding:0;line-height:32px;text-align:center;">
                    <i class="fas fa-angle-double-right" style="font-size:11px;"></i>
                </a>
            @else
                <button class="btn btn-sm btn-light border" disabled style="width:34px;height:34px;padding:0;">
                    <i class="fas fa-angle-double-right" style="font-size:11px;"></i>
                </button>
            @endif

        </div>
    </div>
    @endif
    {{-- END PAGINATION --}}

</div>

@endsection