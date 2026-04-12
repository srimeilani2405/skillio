@extends('layouts.sidebar-admin')

@section('title', 'Tambah Paket Kursus')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-time { border: none !important; }
    .input-group-text { background-color: #f8f9fc; }
</style>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 text-primary"><i class="fas fa-plus-circle"></i> Tambah Paket Kursus</h4>
    </div>

    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- KOLOM KIRI --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kursus <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kursus" class="form-control @error('nama_kursus') is-invalid @enderror" 
                               value="{{ old('nama_kursus') }}" placeholder="Contoh: English Conversation for Kids" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenjang <span class="text-danger">*</span></label>
                        <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangList as $jenjang)
                                <option value="{{ $jenjang->kategori_id }}" {{ old('id_kategori') == $jenjang->kategori_id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mata Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_mapel" id="id_mapel" class="form-select @error('id_mapel') is-invalid @enderror" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}" {{ old('id_mapel') == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMapel">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pengajar <span class="text-danger">*</span></label>
                        <select name="id_instructors" class="form-select @error('id_instructors') is-invalid @enderror" required>
                            <option value="">-- Pilih Pengajar --</option>
                            @foreach($instructorList as $instructor)
                                <option value="{{ $instructor->id_instructors }}" {{ old('id_instructors') == $instructor->id_instructors ? 'selected' : '' }}>
                                    {{ $instructor->nama }} - {{ $instructor->spesialisasi }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small text-muted mt-1">
                            <i class="fas fa-info-circle text-info"></i> Hanya menampilkan pengajar aktif.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga Kursus <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" placeholder="0" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Biaya Sertifikat <small class="text-muted">(Opsional)</small></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="biaya_sertifikat" class="form-control" value="{{ old('biaya_sertifikat', 0) }}" min="0">
                        </div>
                        <div class="form-text small">Isi 0 jika sertifikat gratis.</div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Periode Aktif Kursus <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                                <small class="text-muted">Mulai</small>
                            </div>
                            <div class="col-6">
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                        <div id="hariOtomatis" class="text-info small mt-1" style="display:none;">
                            <i class="fas fa-calendar-alt"></i> <span id="jumlahHari"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hari Kursus <span class="text-danger">*</span> <small class="fw-normal text-muted">(Maks. 2 hari)</small></label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row g-2">
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input hari-checkbox" type="checkbox" name="hari[]" value="{{ $hari }}" id="hari_{{ $hari }}" 
                                               {{ is_array(old('hari')) && in_array($hari, old('hari')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hari_{{ $hari }}">{{ $hari }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="hariWarning" class="text-danger small mt-1" style="display:none;">Maksimal pilih 2 hari!</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jadwal Per Sesi <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6 position-relative">
                                <div class="input-group">
                                    <input type="text" name="jam_mulai_full" id="jam_mulai_full" class="form-control bg-white" placeholder="08:00 AM" readonly required>
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <small class="text-muted">Jam Mulai</small>
                            </div>
                            <div class="col-6 position-relative">
                                <div class="input-group">
                                    <input type="text" name="jam_selesai_full" id="jam_selesai_full" class="form-control bg-white" placeholder="10:00 AM" readonly required>
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <small class="text-muted">Jam Selesai</small>
                            </div>
                        </div>
                        <div id="durasiOtomatis" class="text-info small mt-1" style="display:none;">
                            <i class="fas fa-hourglass-half"></i> Durasi: <span id="durasiMenit"></span> menit
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kuota Peserta <span class="text-danger">*</span></label>
                        <input type="number" name="kuota_peserta" class="form-control" value="{{ old('kuota_peserta', 15) }}" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Tersedia (Aktif)</option>
                            <option value="nonaktif">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3 mt-2">
                <label class="form-label fw-bold">Deskripsi Kursus</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan detail kursus...">{{ old('deskripsi') }}</textarea>
            </div>

            <input type="hidden" name="durasi_per_sesi" id="durasi_per_sesi_hidden" value="">
            <input type="hidden" name="masa_aktif_hari" id="masa_aktif_hari_hidden" value="">
            <input type="hidden" name="jam_mulai_sesi" id="h_jam_m">
            <input type="hidden" name="menit_mulai_sesi" id="h_menit_m">
            <input type="hidden" name="jam_selesai_sesi" id="h_jam_s">
            <input type="hidden" name="menit_selesai_sesi" id="h_menit_s">

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-success px-4 shadow-sm">
                    <i class="fas fa-save me-1"></i> Simpan Paket Kursus
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-light border px-4">Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH MAPEL --}}
<div class="modal fade" id="modalTambahMapel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Mapel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" class="form-control" id="input_nama_mapel" placeholder="Contoh: Matematika">
                <div id="error_mapel" class="text-danger small mt-1" style="display:none;">Harus diisi!</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanMapel">Simpan Mapel</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Flatpickr untuk Jam (Tampilan Persis Gambar 1)
    const configTime = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // Format 12 Jam (AM/PM)
        defaultDate: "08:00",
        onChange: function(selectedDates, dateStr, instance) {
            updateDurasi();
        }
    };

    const jamMulaiPicker = flatpickr("#jam_mulai_full", configTime);
    const jamSelesaiPicker = flatpickr("#jam_selesai_full", {
        ...configTime,
        defaultDate: "10:00"
    });

    function updateDurasi() {
        const start = jamMulaiPicker.selectedDates[0];
        const end = jamSelesaiPicker.selectedDates[0];

        if (start && end) {
            let diff = (end.getTime() - start.getTime()) / 1000 / 60;
            if (diff < 0) diff += 1440; // Handle lewat tengah malam

            document.getElementById('durasiMenit').innerText = diff;
            document.getElementById('durasiOtomatis').style.display = 'block';
            document.getElementById('durasi_per_sesi_hidden').value = diff;

            // Isi hidden fields untuk controller kamu (jam & menit terpisah)
            document.getElementById('h_jam_m').value = start.getHours().toString().padStart(2, '0');
            document.getElementById('h_menit_m').value = start.getMinutes().toString().padStart(2, '0');
            document.getElementById('h_jam_s').value = end.getHours().toString().padStart(2, '0');
            document.getElementById('h_menit_s').value = end.getMinutes().toString().padStart(2, '0');
        }
    }

    // 2. Hitung Masa Aktif Hari
    function hitungHari() {
        let tglMulai = document.getElementById('tanggal_mulai').value;
        let tglSelesai = document.getElementById('tanggal_selesai').value;
        if (tglMulai && tglSelesai) {
            let m = new Date(tglMulai);
            let s = new Date(tglSelesai);
            let diff = Math.ceil((s - m) / (1000 * 60 * 60 * 24)) + 1;
            if (diff > 0) {
                document.getElementById('jumlahHari').innerText = diff + ' Hari masa aktif';
                document.getElementById('hariOtomatis').style.display = 'block';
                document.getElementById('masa_aktif_hari_hidden').value = diff;
            }
        }
    }

    document.getElementById('tanggal_mulai').addEventListener('change', hitungHari);
    document.getElementById('tanggal_selesai').addEventListener('change', hitungHari);

    // 3. Batasi Checklist Hari
    document.querySelectorAll('.hari-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            let checked = document.querySelectorAll('.hari-checkbox:checked');
            if (checked.length > 2) {
                this.checked = false;
                document.getElementById('hariWarning').style.display = 'block';
            } else {
                document.getElementById('hariWarning').style.display = 'none';
            }
        });
    });

    // Jalankan kalkulasi awal
    updateDurasi();
});

// 4. Ajax Simpan Mapel (Tetap pakai logika kamu)
document.getElementById('btnSimpanMapel').addEventListener('click', function() {
    var nama = document.getElementById('input_nama_mapel').value;
    if (!nama) { document.getElementById('error_mapel').style.display = 'block'; return; }
    
    this.disabled = true;
    fetch('/admin/mapel/store-quick', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ nama_mapel: nama })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            let sel = document.getElementById('id_mapel');
            sel.add(new Option(data.nama, data.id, true, true));
            bootstrap.Modal.getInstance(document.getElementById('modalTambahMapel')).hide();
            Swal.fire('Berhasil!', 'Mapel ditambahkan', 'success');
        }
        this.disabled = false;
    });
});
</script>
@endsection