@extends('layouts.sidebar-owner')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-receipt"></i> Detail Transaksi</h2>
    <a href="{{ url('/owner/transactions') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Kode Transaksi</th>
                        <td>: <strong>{{ $transaction->kode_transaksi }}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td>: {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td>: {{ $transaction->customer->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>: {{ $transaction->customer->no_telp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kasir</th>
                        <td>: {{ $transaction->user->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Pembayaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Total Harga</th>
                        <td>: <strong>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Uang Bayar</th>
                        <td>: Rp {{ number_format($transaction->uang_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Uang Kembali</th>
                        <td>: Rp {{ number_format($transaction->uang_kembali, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:
                            <span class="badge bg-success">LUNAS</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Kursus</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kursus</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->course->nama ?? '-' }}</td>
                        <td>{{ $detail->course->jenjang->nama_category ?? '-' }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah ?? 1 }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada detail kursus</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">TOTAL</th>
                        <th>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="mt-3 text-end">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Cetak
    </button>
</div>
@endsection