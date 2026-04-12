@extends('layouts.sidebar-owner')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-chart-line"></i> Laporan Transaksi</h2>
    <div>
        <a href="{{ route('owner.transactions.export.pdf') }}?{{ http_build_query(request()->all()) }}" 
           class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- Filter Form --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control" 
                       value="{{ request('dari_tanggal', now()->startOfMonth()->toDateString()) }}">
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" class="form-control" 
                       value="{{ request('sampai_tanggal', now()->toDateString()) }}">
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Nama Customer</label>
                <input type="text" name="customer" class="form-control" placeholder="Cari customer..." 
                       value="{{ request('customer') }}">
            </div>
            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Total Pendapatan --}}
<div class="alert alert-info">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Total Pendapatan (Berdasarkan Filter)</h5>
        <h4 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c); color: white;">
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Kasir</th>
                        <th>Total Harga</th>
                        <th>Uang Bayar</th>
                        <th>Kembali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $trx)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                        <td>
                            @if($trx->tanggal_transaksi)
                                {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $trx->customer->nama ?? '-' }}</td>
                        <td>{{ $trx->user->name ?? '-' }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->uang_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->uang_kembali, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ url('/owner/transactions/' . $trx->id_transaksi) }}" 
                               class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i> 
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Tidak ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(method_exists($transactions, 'links'))
<div class="mt-3">
    {{ $transactions->links() }}
</div>
@endif

@endsection