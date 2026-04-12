@extends('layouts.sidebar-kasir')

@section('title', 'Riwayat Transaksi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-shopping-cart"></i> Riwayat Transaksi</h2>
    <a href="{{ route('kasir.transactions.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Transaksi Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c); color: white;">
                        <th class="ps-3">No</th>
                        <th>Kode Transaksi</th>
                        <th>Customer</th>
                        <th>Total Harga</th>
                        <th>Uang Bayar</th>
                        <th>Uang Kembali</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $trx)
                    <tr>
                        <td class="ps-3">{{ $index + 1 }}</td>
                        <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                        <td>{{ $trx->customer->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->uang_bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($trx->uang_kembali, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('kasir.transactions.show', $trx->id_transaksi) }}"
                               class="btn btn-info btn-sm text-white">
                                <i class="fas fa-eye"></i> 
                            </a>
                            <a href="{{ route('kasir.transactions.print', $trx->id_transaksi) }}"
                               class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fas fa-print"></i> 
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection