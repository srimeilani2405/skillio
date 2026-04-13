@extends('layouts.sidebar-kasir')

@section('title', 'Detail Transaksi')

@push('styles')
<style>
    #kuitansi {
        font-family: 'Times New Roman', Times, serif;
        max-width: 680px;
        margin: 0 auto;
        background: #fff;
        border: 2px solid #333;
        padding: 30px 36px;
        font-size: 13px;
    }

    #kuitansi .header-title {
        text-align: center;
        margin-bottom: 18px;
    }

    #kuitansi .header-title h3 {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        letter-spacing: 1px;
    }

    #kuitansi .header-title h2 {
        font-size: 22px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 4px 0 0;
    }

    #kuitansi .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 20px;
        margin-bottom: 14px;
        font-size: 12px;
    }

    #kuitansi .info-grid .info-item {
        display: flex;
        gap: 4px;
    }

    #kuitansi .info-grid .info-label {
        font-weight: bold;
        white-space: nowrap;
        min-width: 130px;
    }

    #kuitansi table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 12px;
    }

    #kuitansi table th,
    #kuitansi table td {
        border: 1px solid #333;
        padding: 8px 10px;
        text-align: left;
    }

    #kuitansi table th {
        text-align: center;
        font-weight: bold;
        background: #fff;
    }

    #kuitansi table td:last-child {
        text-align: right;
    }

    #kuitansi .jadwal-box {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        padding: 12px;
        margin: 15px 0;
        font-size: 12px;
        border-radius: 4px;
    }

    #kuitansi .deskripsi-box {
        background: #f9f9f9;
        border: 1px solid #ddd;
        padding: 12px;
        margin: 15px 0;
        font-size: 12px;
        border-radius: 4px;
    }

    #kuitansi .payment-info {
        margin: 15px 0;
        font-size: 12px;
    }

    #kuitansi .payment-info p {
        margin: 5px 0;
    }

    #kuitansi .terbilang {
        margin: 15px 0;
        font-size: 11px;
        font-style: italic;
    }

    #kuitansi .ttd-section {
        text-align: right;
        margin-top: 40px;
        font-size: 12px;
    }

    #kuitansi .ttd-section .ttd-box {
        display: inline-block;
        text-align: center;
        margin-left: 30px;
    }

    #kuitansi .ttd-section .ttd-box:first-child {
        margin-right: 30px;
        margin-left: 0;
    }

    hr {
        margin: 12px 0;
        border: none;
        border-top: 1px solid #333;
    }

    hr.dashed {
        border-top: 1px dashed #999;
    }

    @media print {
        body * { visibility: hidden; }
        #kuitansi, #kuitansi * { visibility: visible; }
        #kuitansi {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            border: 2px solid #333 !important;
            padding: 30px 36px !important;
        }
        .no-print { display: none !important; }
        #kuitansi .jadwal-box,
        #kuitansi .deskripsi-box {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endpush

@section('content')

{{-- TOMBOL AKSI --}}
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Cetak Kuitansi</h4>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print"></i> Cetak
        </button>
        <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- KUITANSI --}}
<div id="kuitansi">

    {{-- HEADER --}}
    <div class="header-title">
        <h3>Skillio</h3>
        <h2>Kuitansi Pembayaran</h2>
    </div>

    <hr>

    {{-- INFO GRID --}}
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
                    $guruList = array_unique($guruList);
                    echo implode(', ', $guruList) ?: '-';
                @endphp
            </span>
        </div>
    </div>

    <hr>

    {{-- JADWAL KURSUS --}}
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

    {{-- TABEL RINCIAN --}}
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
                <td style="padding-left: 20px;">&nbsp;&nbsp;&nbsp;+ Biaya Sertifikat Kompetensi</td>
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

    {{-- DESKRIPSI KURSUS --}}
    @php
        $deskripsiList = [];
        foreach($transaksi->details as $detail) {
            if($detail->course && $detail->course->deskripsi) {
                $deskripsiList[] = [
                    'nama' => $detail->course->nama,
                    'deskripsi' => $detail->course->deskripsi
                ];
            }
        }
        $deskripsiList = array_unique($deskripsiList, SORT_REGULAR);
    @endphp

    @if(count($deskripsiList) > 0)
    <div class="deskripsi-box">
        <strong>DESKRIPSI KURSUS:</strong><br>
        @foreach($deskripsiList as $desc)
            <div style="margin-top: 5px;">
                <strong>{{ $desc['nama'] }}:</strong> {{ $desc['deskripsi'] }}
            </div>
        @endforeach
    </div>
    @endif

    {{-- METODE & TOTAL --}}
    <div class="payment-info">
        <p><strong>METODE PEMBAYARAN : {{ strtoupper($transaksi->metode_pembayaran ?? 'TUNAI') }}</strong></p>
        <p><strong>UANG BAYAR : Rp {{ number_format($transaksi->uang_bayar ?? 0, 0, ',', '.') }}</strong></p>
        <p><strong>UANG KEMBALI : Rp {{ number_format(($transaksi->uang_bayar ?? 0) - ($transaksi->total_harga ?? 0), 0, ',', '.') }}</strong></p>
    </div>

    {{-- TERBILANG --}}
    <div class="terbilang">
        <strong>Terbilang:</strong> {{ terbilang($transaksi->total_harga ?? 0) }} rupiah
    </div>

    {{-- TANGGAL --}}
    <div style="text-align:right; margin-top: 20px;">
        Subang, {{ isset($transaksi->tanggal_transaksi) ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') : '-' }}
    </div>

    {{-- TANDA TANGAN --}}
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