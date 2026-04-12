@extends('layouts.sidebar-kasir')

@section('title', 'Dashboard Kasir')

@section('content')

{{-- HEADER --}}
<div class="mb-4">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard Kasir</h2>
    <p class="text-muted">Selamat datang, {{ Auth::user()->name }}! 👋</p>
</div>

{{-- CARD STATISTIK --}}
<div class="row g-3 mb-4">

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:#eef0fb;">
                    <i class="fas fa-book fa-lg" style="color:#4e73df;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Paket Kursus</div>
                    <div class="fw-bold fs-4">{{ $totalKursus }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #e74a3b !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:#fdf0ef;">
                    <i class="fas fa-shopping-cart fa-lg" style="color:#e74a3b;"></i>
                </div>
                <div>
                    <div class="text-muted small">Transaksi Hari Ini</div>
                    <div class="fw-bold fs-4">{{ $totalTransaksiHari }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6f42c1 !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:#f0ebfa;">
                    <i class="fas fa-money-bill-wave fa-lg" style="color:#6f42c1;"></i>
                </div>
                <div>
                    <div class="text-muted small">Pendapatan Hari Ini</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalPendapatanHari, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:#fef9ec;">
                    <i class="fas fa-calendar-alt fa-lg" style="color:#f6c23e;"></i>
                </div>
                <div>
                    <div class="text-muted small">Pendapatan Bulan Ini</div>
                    <div class="fw-bold fs-5">Rp {{ number_format($totalPendapatanBulan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:55px;height:55px;background:#e8faf4;">
                    <i class="fas fa-receipt fa-lg" style="color:#1cc88a;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Semua Transaksi</div>
                    <div class="fw-bold fs-4">{{ $totalTransaksi }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- TABEL PAKET KURSUS --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="fas fa-book"></i> Paket Kursus Tersedia</h5>
</div>

{{-- FILTER JENJANG --}}
<div class="mb-3 d-flex flex-wrap gap-2">
    <button class="btn btn-success btn-sm btn-filter active" data-filter="semua">Semua</button>
    @foreach($jenjangList as $jenjang)
        <button class="btn btn-outline-secondary btn-sm btn-filter" data-filter="jenjang-{{ $jenjang->kategori_id }}">
            {{ $jenjang->nama_category }}
        </button>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c); color: white;">
                        <th class="ps-3">No</th>
                        <th>Nama Kursus</th>
                        <th>Jenjang</th>
                        <th>Mata Pelajaran</th>
                        <th>Pengajar</th>
                        <th>Hari</th>
                        <th>Harga Kursus</th>
                        <th>Biaya Sertifikat</th>
                        <th>Total</th>
                        <th>Masa Aktif</th>
                        <th>Durasi</th>
                        <th>Kuota</th>
                        <th>Sisa Kuota</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                    @php
                        $hariArr = is_array($course->hari)
                            ? $course->hari
                            : (json_decode($course->hari, true) ?? []);
                        $totalHarga = $course->harga + $course->biaya_sertifikat;
                        $sisaKuota = $course->sisaKuota();
                        $isQuotaAvailable = ($course->status == 'aktif' && $sisaKuota > 0);
                    @endphp
                    <tr class="row-course" data-jenjang="jenjang-{{ $course->id_kategori }}">
                        <td class="ps-3">{{ $index + 1 }}</td>
                        <td><strong>{{ $course->nama }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $course->jenjang->nama_category ?? '-' }}</span></td>
                        <td><span class="badge bg-warning text-dark">{{ $course->mapel->nama_mapel ?? '-' }}</span></td>
                        <td>
                            <span class="badge bg-primary">
                                <i class="fas fa-chalkboard-user"></i> 
                                {{ $course->instructor->nama ?? '-' }}
                            </span>
                            @if($course->instructor)
                                <br><small class="text-muted">{{ $course->instructor->spesialisasi ?? '' }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($hariArr as $hari)
                                    <span class="badge bg-secondary">{{ trim($hari) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>Rp {{ number_format($course->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($course->biaya_sertifikat > 0)
                                Rp {{ number_format($course->biaya_sertifikat, 0, ',', '.') }}
                            @else
                                <span class="badge bg-success">Gratis</span>
                            @endif
                        </td>
                        <td><strong class="text-success">Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                        <td>{{ $course->masa_aktif_hari }} hari</td>
                        <td>{{ $course->durasi_per_sesi }} menit</td>
                        <td>{{ $course->kuota_peserta }}</td>
                        <td>
                            @if($sisaKuota > 0)
                                <span class="badge bg-info">{{ $sisaKuota }} tersisa</span>
                            @else
                                <span class="badge bg-danger">0 (HABIS)</span>
                            @endif
                        </td>
                        <td>
                            @if($course->status == 'aktif' && $sisaKuota > 0)
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($course->status == 'aktif' && $sisaKuota <= 0)
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Belum ada paket kursus tersedia</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ========== FILTER JENJANG ==========
document.querySelectorAll('.btn-filter').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-filter').forEach(b => {
            b.classList.remove('active', 'btn-success');
            b.classList.add('btn-outline-secondary');
        });
        this.classList.add('active', 'btn-success');
        this.classList.remove('btn-outline-secondary');

        var filter = this.dataset.filter;
        document.querySelectorAll('.row-course').forEach(function(row) {
            row.style.display = (filter === 'semua' || row.dataset.jenjang === filter) ? '' : 'none';
        });
    });
});

// ========== MODAL CUSTOMER ==========
document.querySelectorAll('.btn-beli').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var nama = this.dataset.nama;
        var harga = this.dataset.harga;
        var sisa = this.dataset.sisa;
        
        // VALIDASI ULANG: cek kuota sebelum lanjut
        if (parseInt(sisa) <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Kuota Habis!',
                text: 'Maaf, paket kursus ini sudah habis kuota. Tidak dapat melakukan transaksi.',
                confirmButtonColor: '#e74a3b'
            });
            return;
        }
        
        document.getElementById('paket_id').value = id;
        document.getElementById('paket_nama').innerText = nama;
        document.getElementById('total_harga').value = harga;
        document.getElementById('sisa_kuota').value = sisa;
        document.getElementById('paket_harga_display').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        
        document.getElementById('formCustomer').action = '{{ route("kasir.transactions.store-customer") }}';
        
        var modal = new bootstrap.Modal(document.getElementById('modalCustomer'));
        modal.show();
    });
});

// ========== SUBMIT FORM CUSTOMER ==========
document.getElementById('formCustomer').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var form = this;
    var submitBtn = document.getElementById('btnLanjutBayar');
    var originalText = submitBtn.innerHTML;
    
    // VALIDASI SISA KUOTA SEBELUM SUBMIT
    var sisaKuota = parseInt(form.sisa_kuota.value) || 0;
    if (sisaKuota <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Kuota Habis!',
            text: 'Maaf, paket kursus ini sudah habis kuota. Transaksi tidak dapat dilanjutkan.',
            confirmButtonColor: '#e74a3b'
        });
        return;
    }
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_paket: form.id_paket.value,
            nama_customer: form.nama_customer.value,
            email: form.email.value,
            no_telp: form.no_telp.value,
            alamat: form.alamat.value,
            total_harga: form.total_harga.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tutup modal customer
            bootstrap.Modal.getInstance(document.getElementById('modalCustomer')).hide();
            
            // Reset form customer
            form.reset();
            
            // Set data untuk modal pembayaran
            document.getElementById('pay_paket_id').value = data.paket_id;
            document.getElementById('pay_paket_nama').innerText = data.paket_nama;
            document.getElementById('pay_customer_nama').innerText = data.customer_nama;
            document.getElementById('pay_total_harga').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_harga);
            document.getElementById('id_customer').value = data.customer_id;
            document.getElementById('sisa_kuota_check').value = data.sisa_kuota;
            
            document.getElementById('formPembayaran').action = '{{ route("kasir.transactions.store") }}';
            
            // Buka modal pembayaran
            var modalPay = new bootstrap.Modal(document.getElementById('modalPembayaran'));
            modalPay.show();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonColor: '#e74a3b'
            });
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan pada server',
            confirmButtonColor: '#e74a3b'
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// ========== HITUNG KEMBALIAN ==========
document.getElementById('uang_bayar').addEventListener('input', function() {
    var totalHarga = parseInt(document.getElementById('pay_total_harga').innerHTML.replace(/[^0-9]/g, ''));
    var uangBayar = parseInt(this.value) || 0;
    var kembalian = uangBayar - totalHarga;
    var infoDiv = document.getElementById('kembalianInfo');
    
    if (uangBayar >= totalHarga) {
        infoDiv.innerHTML = '<div class="alert alert-success py-2 mb-0">✅ Kembalian: Rp ' + new Intl.NumberFormat('id-ID').format(kembalian) + '</div>';
        document.getElementById('btnProsesBayar').disabled = false;
    } else {
        if (uangBayar > 0) {
            infoDiv.innerHTML = '<div class="alert alert-danger py-2 mb-0">⚠️ Uang kurang: Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga - uangBayar) + '</div>';
        } else {
            infoDiv.innerHTML = '';
        }
        document.getElementById('btnProsesBayar').disabled = true;
    }
});

// ========== SUBMIT FORM PEMBAYARAN ==========
document.getElementById('formPembayaran').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var form = this;
    var submitBtn = document.getElementById('btnProsesBayar');
    var originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_paket: form.id_paket.value,
            id_customer: form.id_customer.value,
            uang_bayar: form.uang_bayar.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#1cc88a'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                confirmButtonColor: '#e74a3b'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan pada server',
            confirmButtonColor: '#e74a3b'
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// ========== FLASH MESSAGE ==========
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#1cc88a',
    timer: 2500
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    confirmButtonColor: '#e74a3b'
});
@endif
</script>

@endsection