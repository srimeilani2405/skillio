<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 11px; 
            color: #333;
            padding: 20px;
        }

        /* HEADER */
        .header { 
            text-align: center; 
            margin-bottom: 15px; 
        }
        .header h1 { 
            font-size: 18px; 
            color: #2c3e6b; 
            margin-bottom: 3px;
        }
        .header p { 
            font-size: 10px; 
            color: #777; 
        }

        /* INFO PERIODE */
        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 15px; 
            font-size: 10px; 
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        /* TABEL */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
        }
        thead tr { 
            background: #2c3e6b; 
            color: white; 
        }
        thead th { 
            padding: 8px 6px; 
            text-align: left; 
            font-size: 10px; 
            font-weight: normal;
        }
        tbody tr { 
            border-bottom: 1px solid #eee; 
        }
        tbody td { 
            padding: 6px; 
            font-size: 10px; 
        }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
        }
        .badge-kembali { 
            background: #e8faf4; 
            color: #1abc9c; 
        }

        /* TOTAL PENDAPATAN - DI BAWAH TABEL SEBELAH KANAN */
        .summary-box {
            text-align: right;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .summary-content {
            display: inline-block;
            text-align: left;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 4px;
            min-width: 180px;
        }
        .summary-content .label { 
            font-size: 10px; 
            color: #666; 
            margin-bottom: 3px;
        }
        .summary-content .amount { 
            font-size: 16px; 
            font-weight: bold; 
            color: #2c3e6b; 
        }
        .summary-content .total-transaksi { 
            font-size: 9px; 
            color: #888; 
            margin-top: 3px;
        }

        /* FOOTER */
        .footer { 
            margin-top: 20px; 
            text-align: right; 
            font-size: 9px; 
            color: #aaa; 
            border-top: 1px solid #eee; 
            padding-top: 8px; 
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Skillio</h1>
        <p>Laporan Transaksi Pembayaran Kursus</p>
    </div>

    <div class="info-row">
        <span>Periode: <strong>{{ \Carbon\Carbon::parse($dari_tanggal)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($sampai_tanggal)->format('d/m/Y') }}</strong></span>
        <span>Dicetak: <strong>{{ now()->format('d/m/Y H:i') }}</strong></span>
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Kasir</th>
                <th>Total Harga</th>
                <th>Uang Bayar</th>
                <th>Kembali</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $trx->kode_transaksi }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $trx->customer->nama ?? '-' }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($trx->uang_bayar, 0, ',', '.') }}</td>
                <td>
                    @php $kembali = $trx->uang_kembali ?? 0; @endphp
                    <span class="badge badge-kembali">
                        Rp {{ number_format($kembali, 0, ',', '.') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:15px; color:#999;">
                    Tidak ada data transaksi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TOTAL PENDAPATAN DI BAWAH TABEL (SEBELAH KANAN) -->
    <div class="summary-box">
        <div class="summary-content">
            <div class="label">Total Pendapatan</div>
            <div class="amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="total-transaksi">{{ $transactions->count() }} transaksi</div>
        </div>
    </div>

    <div class="footer">
        Skillio — Laporan digenerate otomatis pada {{ now()->format('d M Y, H:i') }}
    </div>

</body>
</html>