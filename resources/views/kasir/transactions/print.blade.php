<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran - {{ $transaksi->kode_transaksi ?? '-' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #fff;
            padding: 20px;
        }
        
        #kuitansi {
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
            gap: 2px 20px;
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
        }
        
        #kuitansi table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        #kuitansi table th,
        #kuitansi table td {
            border: 1px solid #333;
            padding: 6px 10px;
            text-align: left;
        }
        
        #kuitansi table th {
            text-align: center;
            font-weight: bold;
            background: #fff;
        }
        
        #kuitansi .metode-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        #kuitansi .ttd-section {
            text-align: right;
            margin-top: 16px;
            font-size: 12px;
        }
        
        #kuitansi .ttd-section .ttd-box {
            display: inline-block;
            text-align: center;
            margin-right: 10px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            #kuitansi {
                border: 2px solid #333 !important;
                padding: 30px 36px !important;
            }
            .no-print {
                display: none !important;
            }
        }
        
        .btn-print {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            background: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
        }
        
        .btn-print:hover {
            background: #2e59d9;
        }
        
        .btn-back {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }
        
        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak / Print
        </button>
        <a href="{{ route('kasir.transactions.show', $transaksi->id_transaksi) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div id="kuitansi">
        
        {{-- HEADER --}}
        <div class="header-title">
            <h3>Skillio</h3>
            <h2>Kuitansi Pembayaran</h2>
        </div>
        
        <hr style="border-top: 2px solid #333; margin: 10px 0 14px;">
        
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
                <span class="info-label">NAMA PAKET KURSUS</span>
                <span>: {{ $transaksi->details->first()->course->nama ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">NOMOR TELEPON</span>
                <span>: {{ $transaksi->customer->no_telp ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">DURASI KURSUS</span>
                <span>: {{ $transaksi->details->first()->course->masa_aktif_hari ?? '-' }} Hari</span>
            </div>
        </div>
        
        <hr style="border-top: 1px solid #333; margin: 10px 0 12px;">
        
        {{-- TABEL RINCIAN --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 60%;">NAMA PAKET KURSUS</th>
                    <th style="width: 40%; text-align:right;">BIAYA KURSUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $detail)
                <tr>
                    <td>{{ $detail->course->nama ?? '-' }}</td>
                    <td style="text-align:right;">Rp. {{ number_format($detail->course->harga ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if(isset($detail->course->biaya_sertifikat) && $detail->course->biaya_sertifikat > 0)
                <tr>
                    <td>Sertifikasi Kompetensi</td>
                    <td style="text-align:right;">Rp. {{ number_format($detail->course->biaya_sertifikat, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
                <tr style="border-top: 2px solid #333;">
                    <td style="text-align:right; font-weight:bold;">TOTAL</td>
                    <td style="text-align:right; font-weight:bold;">Rp. {{ number_format($transaksi->total_harga ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        {{-- METODE & TOTAL --}}
        <div class="metode-total">
            <div>
                <strong>METODE PEMBAYARAN : {{ strtoupper($transaksi->metode_pembayaran ?? 'TUNAI') }}</strong>
            </div>
            <div>
                <strong>UANG BAYAR : Rp. {{ number_format($transaksi->uang_bayar ?? 0, 0, ',', '.') }}</strong>
            </div>
        </div>
        <div class="metode-total">
            <div></div>
            <div>
                <strong>UANG KEMBALI : Rp. {{ number_format($transaksi->uang_kembali ?? 0, 0, ',', '.') }}</strong>
            </div>
        </div>
        
        {{-- TERBILANG --}}
        <div style="margin-top: 15px; font-size: 11px;">
            <strong>Terbilang :</strong> {{ terbilang($transaksi->total_harga ?? 0) }} rupiah
        </div>
        
        {{-- TANGGAL KOTA --}}
        <div style="text-align:right; font-size:12px; margin-top: 20px;">
            Subang, {{ isset($transaksi->tanggal_transaksi) ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') : '-' }}
        </div>
        
        {{-- TANDA TANGAN --}}
        <div class="ttd-section" style="margin-top: 50px;">
            <div class="ttd-box">
                <div style="margin-bottom: 50px;">( {{ $transaksi->customer->nama ?? 'Peserta' }} )</div>
                <div>Peserta</div>
            </div>
            <div class="ttd-box">
                <div style="margin-bottom: 50px;">( {{ $transaksi->user->name ?? 'Kasir' }} )</div>
                <div>Hormat Kami</div>
            </div>
        </div>
        
    </div>
    
</body>
</html>

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