@extends('layouts.sidebar-kasir')

@section('title', 'Detail Transaksi')

@push('styles')
<style>
    .section-header {
        padding: 12px 18px;
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        border-radius: 4px 4px 0 0;
    }

    .section-header.blue {
        background: #2196f3;
    }

    .section-header.green {
        background: #43a047;
    }

    .section-header.cyan {
        background: linear-gradient(90deg, #26c6da 0%, #00acc1 100%);
    }

    .section-box {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        overflow: hidden;
        height: 100%;
    }

    .section-body {
        padding: 20px;
        background: #fff;
    }

    .info-row {
        display: flex;
        padding: 8px 0;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #444;
        min-width: 160px;
    }

    .info-value {
        color: #333;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-lunas {
        background: #43a047;
        color: #fff;
    }

    .table-detail thead th {
        background: #37474f;
        color: #fff;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 600;
        border: none;
    }

    .table-detail tbody td {
        padding: 10px 12px;
        font-size: 13px;
        color: #444;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }

    .table-detail tfoot td {
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 700;
        background: #f5f5f5;
        border-top: 2px solid #dee2e6;
    }

    /* Sembunyikan kuitansi dari layar tapi tetap ter-render */
    #print-kuitansi {
        position: absolute;
        left: -9999px;
        top: 0;
        width: 680px;
    }

    /* STYLE KHUSUS UNTUK PRINT */
    @media print {
        body * {
            visibility: hidden;
        }

        #print-kuitansi,
        #print-kuitansi * {
            visibility: visible;
        }

        #print-kuitansi {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            background: white;
            padding: 20px;
            font-family: 'Times New Roman', Times, serif;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<style media="print">
    #print-kuitansi {
        max-width: 680px;
        margin: 0 auto;
        background: #fff;
        border: 2px solid #333;
        padding: 30px 36px;
        font-size: 13px;
        font-family: 'Times New Roman', Times, serif;
    }

    #print-kuitansi .header-title {
        text-align: center;
        margin-bottom: 18px;
    }

    #print-kuitansi .header-title h3 {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        letter-spacing: 1px;
    }

    #print-kuitansi .header-title h2 {
        font-size: 22px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 4px 0 0;
    }

    #print-kuitansi .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 20px;
        margin-bottom: 14px;
        font-size: 12px;
    }

    #print-kuitansi .info-item {
        display: flex;
        gap: 4px;
    }

    #print-kuitansi .info-label {
        font-weight: bold;
        white-space: nowrap;
        min-width: 130px;
    }

    #print-kuitansi table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 12px;
    }

    #print-kuitansi table th,
    #print-kuitansi table td {
        border: 1px solid #333;
        padding: 8px 10px;
        text-align: left;
    }

    #print-kuitansi table th {
        text-align: center;
        font-weight: bold;
    }

    #print-kuitansi table td:last-child {
        text-align: right;
    }

    #print-kuitansi .jadwal-box {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        padding: 12px;
        margin: 15px 0;
        font-size: 12px;
        border-radius: 4px;
    }

    #print-kuitansi .payment-info {
        margin: 15px 0;
        font-size: 12px;
    }

    #print-kuitansi .payment-info p {
        margin: 5px 0;
    }

    #print-kuitansi .terbilang {
        margin: 15px 0;
        font-size: 11px;
        font-style: italic;
    }

    #print-kuitansi .ttd-section {
        text-align: right;
        margin-top: 40px;
        font-size: 12px;
    }

    #print-kuitansi .ttd-section .ttd-box {
        display: inline-block;
        text-align: center;
        margin-left: 30px;
    }

    #print-kuitansi .ttd-section .ttd-box:first-child {
        margin-right: 30px;
        margin-left: 0;
    }

    hr {
        margin: 12px 0;
        border: none;
        border-top: 1px solid #333;
    }
</style>
@endpush

@section('content')

{{-- TOMBOL AKSI --}}
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Transaksi</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-1"></i> Cetak
        </button>
        <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

{{-- BARIS 1: INFORMASI TRANSAKSI + PEMBAYARAN SIDE BY SIDE --}}
<div class="row mb-4 no-print">
    {{-- INFORMASI TRANSAKSI --}}
    <div class="col-md-7 mb-3 mb-md-0">
        <div class="section-box">
            <div class="section-header blue">
                <i class="fas fa-info-circle me-2"></i>Informasi Transaksi
            </div>
            <div class="section-body">
                <div class="info-row">
                    <span class="info-label">Kode Transaksi</span>
                    <span class="info-value">: <strong>{{ $transaksi->kode_transaksi ?? '-' }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Transaksi</span>
                    <span class="info-value">: {{ isset($transaksi->tanggal_transaksi) ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer</span>
                    <span class="info-value">: {{ $transaksi->customer->nama ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon</span>
                    <span class="info-value">: {{ $transaksi->customer->no_telp ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kasir</span>
                    <span class="info-value">: {{ $transaksi->user->name ?? 'Kasir Skillio' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- PEMBAYARAN --}}
    <div class="col-md-5">
        <div class="section-box">
            <div class="section-header green">
                <i class="fas fa-money-bill-wave me-2"></i>Pembayaran
            </div>
            <div class="section-body">
                <div class="info-row">
                    <span class="info-label">Total Harga</span>
                    <span class="info-value">: <strong>Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Uang Bayar</span>
                    <span class="info-value">: Rp {{ number_format($transaksi->uang_bayar ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Uang Kembali</span>
                    <span class="info-value">: Rp {{ number_format(($transaksi->uang_bayar ?? 0) - ($transaksi->total_harga ?? 0), 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">:
                        <span class="status-badge status-lunas">LUNAS</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BARIS 2: DETAIL KURSUS --}}
<div class="no-print">
    <div class="section-box">
        <div class="section-header cyan">
            <i class="fas fa-book-open me-2"></i>Detail Kursus
        </div>
        <div class="p-0">
            <table class="table table-detail mb-0 w-100">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 35%">Nama Kursus</th>
                        <th style="width: 15%">Kategori</th>
                        <th style="width: 20%">Harga Satuan</th>
                        <th style="width: 10%">Qty</th>
                        <th style="width: 15%">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $detail->course->nama ?? '-' }}</strong></td>
                        <td>
                            @php
                                $kategori = '';
                                if($detail->course) {
                                    switch($detail->course->tingkatan) {
                                        case 'SD': $kategori = 'SD'; break;
                                        case 'SMP': $kategori = 'SMP'; break;
                                        case 'SMA': $kategori = 'SMA'; break;
                                        default: $kategori = 'Umum';
                                    }
                                }
                            @endphp
                            {{ $kategori }}
                        </td>
                        <td>Rp {{ number_format($detail->harga_satuan ?? $detail->course->harga ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah ?? 1 }}</td>
                        <td>Rp {{ number_format($detail->subtotal ?? ($detail->course->harga ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right;">TOTAL</td>
                        <td>Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- KONTEN UNTUK PRINT (KUITANSI FORMAL) - selalu ada di DOM, disembunyikan pakai CSS --}}
<div id="print-kuitansi">
    <div style="max-width: 680px; margin: 0 auto;">
        <div class="header-title">
            <h3>Skillio</h3>
            <h2>KUITANSI PEMBAYARAN</h2>
        </div>

        <hr>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">NOMOR KUITANSI</span>
                <span>: {{ $transaksi->kode_transaksi ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">TANGGAL PEMBAYARAN</span>
                <span>: {{ isset($transaksi->tanggal_transaksi) ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NAMA PESERTA</span>
                <span>: {{ $transaksi->customer->nama ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">EMAIL PESERTA</span>
                <span>: {{ $transaksi->customer->email ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NOMOR TELEPON</span>
                <span>: {{ $transaksi->customer->no_telp ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NAMA GURU / PENGARAH</span>
                <span>:
                    @php
                        $guruList = [];
                        foreach($transaksi->details as $detail) {
                            if($detail->course && $detail->course->instructor) {
                                $guruList[] = $detail->course->instructor->nama;
                            }
                        }
                        echo implode(', ', array_unique($guruList)) ?: '-';
                    @endphp
                </span>
            </div>
        </div>

        <hr>

        @php
            $firstDetail = $transaksi->details->first();
            $course = $firstDetail->course ?? null;
            if($course) {
                $hariArray = is_array($course->hari) ? $course->hari : json_decode($course->hari, true);
                $hariString = is_array($hariArray) ? implode(' & ', $hariArray) : ($course->hari ?? '-');
                $jamMulai = $course->jam_mulai ? date('H:i', strtotime($course->jam_mulai)) : '-';
                $jamSelesai = $course->jam_selesai ? date('H:i', strtotime($course->jam_selesai)) : '-';
                $masaAktif = $course->masa_aktif_hari ?? 0;
                $tanggalMulai = \Carbon\Carbon::parse($transaksi->tanggal_transaksi);
                $tanggalSelesai = $tanggalMulai->copy()->addDays($masaAktif);
            }
        @endphp

        @if($course)
        <div class="jadwal-box">
            <strong>Jadwal Kursus:</strong><br>
            <div style="margin-top: 5px;">
                📅 Hari: {{ $hariString }}<br>
                🕐 Jam: {{ $jamMulai }} - {{ $jamSelesai }} WIB<br>
                📆 Periode Kursus: {{ $tanggalMulai->translatedFormat('d F Y') }} s/d {{ $tanggalSelesai->translatedFormat('d F Y') }}<br>
                ⏱️ Durasi per Sesi: {{ $course->durasi_per_sesi ?? '-' }} menit
            </div>
        </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th style="width: 65%;">NAMA PAKET KURSUS</th>
                    <th style="width: 35%;">BIAYA KURSUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $detail)
                <tr>
                    <td><strong>{{ $detail->course->nama ?? '-' }}</strong></td>
                    <td>Rp {{ number_format($detail->course->harga ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if(isset($detail->course->biaya_sertifikat) && $detail->course->biaya_sertifikat > 0)
                <tr>
                    <td style="padding-left: 20px;">&nbsp;&nbsp;&nbsp;→ Biaya Sertifikat Kompetensi</td>
                    <td>Rp {{ number_format($detail->course->biaya_sertifikat, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                <tr style="border-top: 2px solid #333;">
                    <td style="text-align:right; font-weight:bold;">TOTAL PEMBAYARAN:</td>
                    <td style="font-weight:bold;">Rp {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="payment-info">
            <p><strong>METODE PEMBAYARAN : {{ strtoupper($transaksi->metode_pembayaran ?? 'TUNAI') }}</strong></p>
            <p><strong>UANG BAYAR : Rp {{ number_format($transaksi->uang_bayar ?? 0, 0, ',', '.') }}</strong></p>
            <p><strong>UANG KEMBALI : Rp {{ number_format(($transaksi->uang_bayar ?? 0) - ($transaksi->total_harga ?? 0), 0, ',', '.') }}</strong></p>
        </div>

        <div class="terbilang">
            <strong>Terbilang:</strong> {{ terbilang($transaksi->total_harga ?? 0) }} rupiah
        </div>

        <div style="text-align:right; margin-top: 20px;">
            Subang, {{ isset($transaksi->tanggal_transaksi) ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') : '-' }}
        </div>

        <div class="ttd-section">
            <div class="ttd-box">
                <div style="margin-bottom: 50px;">( {{ $transaksi->customer->nama ?? 'Peserta' }} )</div>
                <div>Peserta</div>
            </div>
            <div class="ttd-box">
                <div style="margin-bottom: 50px;">( {{ $transaksi->user->name ?? 'Kasir' }} )</div>
                <div>Kasir Skillio</div>
            </div>
        </div>
    </div>
</div>

@endsection

@php
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

    if ($angka < 12) {
        return $baca[$angka];
    } elseif ($angka < 20) {
        return terbilang($angka - 10) . ' belas';
    } elseif ($angka < 100) {
        return terbilang(floor($angka / 10)) . ' puluh ' . terbilang($angka % 10);
    } elseif ($angka < 200) {
        return 'seratus ' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        return terbilang(floor($angka / 100)) . ' ratus ' . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        return 'seribu ' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        return terbilang(floor($angka / 1000)) . ' ribu ' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        return terbilang(floor($angka / 1000000)) . ' juta ' . terbilang($angka % 1000000);
    }
    return $angka;
}
@endphp