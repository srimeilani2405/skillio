{{-- resources/views/kasir/transactions/create.blade.php --}}
@extends('layouts.sidebar-kasir')

@section('title', 'Tambah Transaksi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus-circle"></i> Tambah Transaksi</h2>
    <a href="{{ route('kasir.transactions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('kasir.transactions.store') }}" method="POST" id="formTransaksi">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Paket Kursus <span class="text-danger">*</span></label>
                        <select id="id_paket" class="form-control">
                            <option value="">-- Pilih Paket Kursus --</option>
                            @foreach($courses as $course)
                                @php 
                                    $sisaKuota = $course->sisaKuota();
                                    $hariArray = is_array($course->hari) ? $course->hari : json_decode($course->hari, true);
                                    $hariString = is_array($hariArray) ? implode(', ', $hariArray) : ($course->hari ?? '-');
                                    $jamMulai = $course->jam_mulai ? date('H:i', strtotime($course->jam_mulai)) : '-';
                                    $jamSelesai = $course->jam_selesai ? date('H:i', strtotime($course->jam_selesai)) : '-';
                                    $isKuotaHabis = $sisaKuota <= 0; // Cek apakah kuota habis
                                @endphp
                                <option value="{{ $course->id_paket }}" 
                                        data-id="{{ $course->id_paket }}"
                                        data-nama="{{ $course->nama }}"
                                        data-jenjang="{{ $course->jenjang->nama_category ?? '-' }}"
                                        data-mapel="{{ $course->mapel->nama_mapel ?? $course->mata_pelajaran ?? '-' }}"
                                        data-pengajar="{{ $course->instructor->nama ?? '-' }}"
                                        data-hari="{{ $hariString }}"
                                        data-jam_mulai="{{ $jamMulai }}"
                                        data-jam_selesai="{{ $jamSelesai }}"
                                        data-harga_kursus="{{ $course->harga }}"
                                        data-biaya_sertifikat="{{ $course->biaya_sertifikat ?? 0 }}"
                                        data-masa_aktif="{{ $course->masa_aktif_hari ?? '-' }}"
                                        data-durasi="{{ $course->durasi_per_sesi ?? '-' }}"
                                        data-kuota="{{ $course->kuota_peserta }}"
                                        data-sisa="{{ $sisaKuota }}"
                                        data-kuota-habis="{{ $isKuotaHabis ? 'true' : 'false' }}"
                                        {{ $isKuotaHabis ? 'disabled' : '' }}
                                        style="{{ $isKuotaHabis ? 'background-color: #f8d7da; color: #721c24;' : '' }}">
                                    {{ $course->nama }} - Rp {{ number_format($course->harga + ($course->biaya_sertifikat ?? 0), 0, ',', '.') }} 
                                    @if($isKuotaHabis)
                                        (HABIS)
                                    @else
                                        (Sisa: {{ $sisaKuota }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Customer <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_customer" id="id_customer" class="form-control" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id_customer }}">
                                        {{ $customer->nama }} - {{ $customer->email }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCustomerBaru">
                                <i class="fas fa-plus"></i> Customer Baru
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Uang Bayar <span class="text-danger">*</span></label>
                        <input type="number" id="uang_bayar" class="form-control" required min="0" placeholder="Masukkan jumlah uang">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Uang Kembali</label>
                        <h4 id="uangKembaliDisplay" class="text-info">Rp 0</h4>
                    </div>
                </div>
            </div>

            <!-- TABEL DETAIL TRANSAKSI -->
            <div class="mt-4">
                <h5 class="fw-bold"><i class="fas fa-shopping-cart"></i> Detail Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="detailTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Nama Kursus</th>
                                <th style="width: 8%">Jenjang</th>
                                <th style="width: 10%">Mata Pelajaran</th>
                                <th style="width: 12%">Pengajar</th>
                                <th style="width: 10%">Hari</th>
                                <th style="width: 8%">Jam Mulai</th>
                                <th style="width: 8%">Jam Selesai</th>
                                <th style="width: 10%">Harga Kursus</th>
                                <th style="width: 10%">Biaya Sertifikat</th>
                                <th style="width: 10%">Subtotal</th>
                                <th style="width: 8%">Masa Aktif</th>
                                <th style="width: 8%">Durasi</th>
                                <th style="width: 8%">Sisa Kuota</th>
                                <th style="width: 5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="detailTransaksiBody">
                            <tr id="emptyRow">
                                <td colspan="15" class="text-center text-muted">Belum ada paket yang dipilih</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="10" class="text-end fw-bold fs-5">TOTAL:</td>
                                <td colspan="5" class="fw-bold text-success fs-5" id="totalDisplay">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <input type="hidden" id="total_harga" value="0">
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success" id="btnSubmit">
                    <i class="fas fa-save"></i> Proses Transaksi
                </button>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- MODAL CUSTOMER BARU --}}
<div class="modal fade" id="modalCustomerBaru" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Customer Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCustomerBaru">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Customer <span class="text-danger">*</span></label>
                        <input type="text" name="nama_customer" id="nama_customer" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>No. Telepon <span class="text-danger">*</span></label>
                        <input type="tel" name="no_telp" id="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanCustomer">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ========== VARIABEL GLOBAL ==========
let selectedPackages = [];

// ========== FUNGSI UNTUK MEMASTIKAN JAM VALID ==========
function formatJamDisplay(jamValue) {
    if (!jamValue || jamValue === '-' || jamValue === 'null' || jamValue === '' || jamValue === undefined) {
        return 'Tidak tersedia';
    }
    return jamValue;
}

// ========== RENDER TABEL DETAIL ==========
function renderDetailTable() {
    let tbody = document.getElementById('detailTransaksiBody');
    let total = 0;
    
    if (selectedPackages.length === 0) {
        tbody.innerHTML = '<tr id="emptyRow"><td colspan="15" class="text-center text-muted">Belum ada paket yang dipilih</td></tr>';
        document.getElementById('totalDisplay').innerHTML = 'Rp 0';
        document.getElementById('total_harga').value = 0;
        document.getElementById('uangKembaliDisplay').innerHTML = 'Rp 0';
        return;
    }
    
    let html = '';
    selectedPackages.forEach((item, index) => {
        let subtotal = item.harga_kursus + (item.biaya_sertifikat || 0);
        total += subtotal;
        
        let jamMulaiDisplay = formatJamDisplay(item.jam_mulai);
        let jamSelesaiDisplay = formatJamDisplay(item.jam_selesai);
        
        let jamMulaiClass = (jamMulaiDisplay !== 'Tidak tersedia') ? 'bg-success' : 'bg-secondary';
        let jamSelesaiClass = (jamSelesaiDisplay !== 'Tidak tersedia') ? 'bg-success' : 'bg-secondary';
        
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>
                    <strong>${item.nama}</strong>
                    <input type="hidden" name="paket[${index}][id_paket]" value="${item.id_paket}">
                </td>
                <td>${item.jenjang}</td>
                <td>${item.mapel}</td>
                <td>${item.pengajar}</td>
                <td><span class="badge bg-primary">${item.hari}</span></td>
                <td class="text-center"><span class="badge ${jamMulaiClass}">${jamMulaiDisplay}</span></td>
                <td class="text-center"><span class="badge ${jamSelesaiClass}">${jamSelesaiDisplay}</span></td>
                <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_kursus)}</td>
                <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(item.biaya_sertifikat || 0)}</td>
                <td class="text-end fw-bold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                <td class="text-center">${item.masa_aktif} hari</td>
                <td class="text-center">${item.durasi} menit</td>
                <td class="text-center ${item.sisa <= 0 ? 'text-danger fw-bold' : ''}">${item.sisa}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removePackage(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
    document.getElementById('totalDisplay').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    document.getElementById('total_harga').value = total;
    hitungKembalian();
}

// ========== HAPUS PAKET ==========
function removePackage(index) {
    selectedPackages.splice(index, 1);
    renderDetailTable();
}

// ========== CEK APAKAH PAKET VALID (SISA KUOTA > 0) ==========
function isPaketValid(selectedOption) {
    let sisaKuota = parseInt(selectedOption.dataset.sisa) || 0;
    let isHabis = selectedOption.dataset.kuotaHabis === 'true';
    
    if (sisaKuota <= 0 || isHabis) {
        Swal.fire({
            icon: 'error',
            title: 'Kuota Habis!',
            text: `Maaf, paket "${selectedOption.dataset.nama}" sudah habis kuota (Sisa: ${sisaKuota}). Tidak bisa melakukan transaksi untuk paket ini.`,
            confirmButtonColor: '#e74a3b'
        });
        return false;
    }
    return true;
}

// ========== TAMBAH PAKET ==========
document.getElementById('id_paket').addEventListener('change', function() {
    let selectedOption = this.options[this.selectedIndex];
    
    // Validasi: cek apakah option disabled atau kuota habis
    if (selectedOption.disabled) {
        Swal.fire({
            icon: 'error',
            title: 'Kuota Habis!',
            text: 'Paket kursus ini sudah habis kuota dan tidak dapat ditambahkan ke transaksi.',
            confirmButtonColor: '#e74a3b'
        });
        this.value = '';
        return;
    }
    
    // Validasi tambahan via dataset
    if (!isPaketValid(selectedOption)) {
        this.value = '';
        return;
    }
    
    if (this.value && selectedOption.dataset.harga_kursus) {
        
        let rawJamMulai = selectedOption.dataset.jam_mulai;
        let rawJamSelesai = selectedOption.dataset.jam_selesai;
        
        let jamMulaiFinal = '-';
        if (rawJamMulai && rawJamMulai !== '' && rawJamMulai !== 'null' && rawJamMulai !== '-') {
            jamMulaiFinal = rawJamMulai;
        }
        
        let jamSelesaiFinal = '-';
        if (rawJamSelesai && rawJamSelesai !== '' && rawJamSelesai !== 'null' && rawJamSelesai !== '-') {
            jamSelesaiFinal = rawJamSelesai;
        }
        
        let paket = {
            id_paket: parseInt(this.value),
            nama: selectedOption.dataset.nama || '-',
            jenjang: selectedOption.dataset.jenjang || '-',
            mapel: selectedOption.dataset.mapel || '-',
            pengajar: selectedOption.dataset.pengajar || '-',
            hari: selectedOption.dataset.hari || '-',
            jam_mulai: jamMulaiFinal,
            jam_selesai: jamSelesaiFinal,
            harga_kursus: parseInt(selectedOption.dataset.harga_kursus) || 0,
            biaya_sertifikat: parseInt(selectedOption.dataset.biaya_sertifikat || 0),
            masa_aktif: selectedOption.dataset.masa_aktif || '-',
            durasi: selectedOption.dataset.durasi || '-',
            kuota: parseInt(selectedOption.dataset.kuota) || 0,
            sisa: parseInt(selectedOption.dataset.sisa) || 0
        };
        
        // Cek duplikat
        let exists = selectedPackages.some(p => p.id_paket === paket.id_paket);
        if (exists) {
            Swal.fire({ 
                icon: 'warning', 
                title: 'Perhatian!', 
                text: 'Paket sudah ditambahkan!',
                confirmButtonColor: '#f6c23e'
            });
        } else {
            selectedPackages.push(paket);
            renderDetailTable();
        }
        
        // Reset select
        this.value = '';
    }
});

// Tampilkan warning jika ada paket yang habis
document.querySelectorAll('#id_paket option').forEach(option => {
    if (option.disabled) {
        document.getElementById('kuotaWarning').style.display = 'inline-block';
    }
});

// ========== HITUNG KEMBALIAN ==========
function hitungKembalian() {
    let totalHarga = parseInt(document.getElementById('total_harga').value) || 0;
    let uangBayar = parseInt(document.getElementById('uang_bayar').value) || 0;
    let kembalian = uangBayar - totalHarga;
    let displayElement = document.getElementById('uangKembaliDisplay');
    
    if (kembalian >= 0) {
        displayElement.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
        displayElement.style.color = '#1cc88a';
    } else {
        displayElement.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(kembalian)) + ' (Kurang)';
        displayElement.style.color = '#e74a3b';
    }
}

document.getElementById('uang_bayar').addEventListener('input', hitungKembalian);

// ========== SUBMIT TRANSAKSI ==========
document.getElementById('formTransaksi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (selectedPackages.length === 0) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Gagal!', 
            text: 'Pilih minimal 1 paket kursus!',
            confirmButtonColor: '#e74a3b'
        });
        return;
    }
    
    // Validasi ulang kuota sebelum submit (antisipasi perubahan data)
    let hasQuotaIssue = false;
    for (let paket of selectedPackages) {
        if (paket.sisa <= 0) {
            Swal.fire({ 
                icon: 'error', 
                title: 'Kuota Habis!', 
                text: `Paket "${paket.nama}" sudah habis kuota. Transaksi tidak dapat dilanjutkan. Silakan hapus paket tersebut.`,
                confirmButtonColor: '#e74a3b'
            });
            hasQuotaIssue = true;
            break;
        }
    }
    
    if (hasQuotaIssue) {
        return;
    }
    
    let totalHarga = parseInt(document.getElementById('total_harga').value);
    let uangBayar = parseInt(document.getElementById('uang_bayar').value);
    let idCustomer = document.getElementById('id_customer').value;
    
    if (!idCustomer) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Gagal!', 
            text: 'Pilih customer terlebih dahulu!',
            confirmButtonColor: '#e74a3b'
        });
        return;
    }
    
    if (uangBayar < totalHarga) {
        Swal.fire({ 
            icon: 'error', 
            title: 'Gagal!', 
            text: 'Uang bayar kurang dari total harga!',
            confirmButtonColor: '#e74a3b'
        });
        return;
    }
    
    let paketData = selectedPackages.map(p => ({ 
        id_paket: p.id_paket, 
        jumlah: 1 
    }));
    
    let submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    
    fetch('{{ route("kasir.transactions.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            paket: paketData,
            id_customer: idCustomer,
            uang_bayar: uangBayar
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'BERHASIL!',
                html: '<p>' + data.message + '</p>' +
                      '<p><strong>Kode Transaksi:</strong> ' + data.data.kode_transaksi + '</p>' +
                      '<p><strong>Kembalian:</strong> Rp ' + new Intl.NumberFormat('id-ID').format(data.data.uang_kembali) + '</p>',
                confirmButtonColor: '#1cc88a'
            }).then(() => {
                window.location.href = '{{ route("kasir.transactions.index") }}';
            });
        } else {
            Swal.fire({ 
                icon: 'error', 
                title: 'GAGAL!', 
                text: data.message,
                confirmButtonColor: '#e74a3b'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Proses Transaksi';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ 
            icon: 'error', 
            title: 'ERROR!', 
            text: error.message,
            confirmButtonColor: '#e74a3b'
        });
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Proses Transaksi';
    });
});

// ========== SIMPAN CUSTOMER BARU ==========
document.getElementById('formCustomerBaru').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let btn = document.getElementById('btnSimpanCustomer');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    fetch('{{ route("kasir.transactions.store-customer") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nama_customer: document.getElementById('nama_customer').value,
            email: document.getElementById('email').value,
            no_telp: document.getElementById('no_telp').value,
            alamat: document.getElementById('alamat').value,
            jenis_kelamin: document.getElementById('jenis_kelamin').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let select = document.getElementById('id_customer');
            let option = document.createElement('option');
            option.value = data.customer_id;
            option.text = data.customer_nama + ' - ' + data.customer_email;
            select.appendChild(option);
            select.value = data.customer_id;
            
            bootstrap.Modal.getInstance(document.getElementById('modalCustomerBaru')).hide();
            document.getElementById('formCustomerBaru').reset();
            
            Swal.fire({ 
                icon: 'success', 
                title: 'Berhasil!', 
                text: data.message, 
                timer: 2000, 
                showConfirmButton: false 
            });
        } else {
            Swal.fire({ 
                icon: 'error', 
                title: 'Gagal!', 
                text: data.message,
                confirmButtonColor: '#e74a3b'
            });
        }
        btn.disabled = false;
        btn.innerHTML = 'Simpan';
    })
    .catch(error => {
        Swal.fire({ 
            icon: 'error', 
            title: 'Error!', 
            text: error.message,
            confirmButtonColor: '#e74a3b'
        });
        btn.disabled = false;
        btn.innerHTML = 'Simpan';
    });
});
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85rem;
        padding: 5px 10px;
    }
    select option:disabled {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

@endsection