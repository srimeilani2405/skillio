@extends('layouts.sidebar-admin')

@section('title', 'Edit Paket Kursus')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Paket Kursus</h4>
    </div>

    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.courses.update', $course->id_paket) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- KOLOM KIRI --}}
                <div class="col-md-6">

                    {{-- NAMA KURSUS --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Kursus <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="nama_kursus"
                               class="form-control @error('nama_kursus') is-invalid @enderror"
                               value="{{ old('nama_kursus', $course->nama) }}"
                               required>
                        @error('nama_kursus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- JENJANG --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jenjang <span class="text-danger">*</span>
                        </label>
                        <select name="id_kategori"
                                class="form-control @error('id_kategori') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach($jenjangList as $jenjang)
                                <option value="{{ $jenjang->kategori_id }}"
                                    {{ old('id_kategori', $course->id_kategori) == $jenjang->kategori_id ? 'selected' : '' }}>
                                    {{ $jenjang->nama_category }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MATA PELAJARAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <select name="id_mapel"
                                    id="id_mapel"
                                    class="form-control @error('id_mapel') is-invalid @enderror"
                                    required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ old('id_mapel', $course->id_mapel) == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                    class="btn btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambahMapel">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('id_mapel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PENGAJAR (GURU) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Pengajar <span class="text-danger">*</span>
                        </label>
                        <select name="id_instructors"
                                class="form-control @error('id_instructors') is-invalid @enderror"
                                required>
                            <option value="">-- Pilih Pengajar --</option>
                            @foreach($instructorList as $instructor)
                                <option value="{{ $instructor->id_instructors }}"
                                    {{ old('id_instructors', $course->id_instructors) == $instructor->id_instructors ? 'selected' : '' }}>
                                    {{ $instructor->nama }} - {{ $instructor->spesialisasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_instructors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> Hanya menampilkan pengajar dengan status Aktif.
                        </div>
                    </div>

                    {{-- HARGA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Harga Kursus <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga', $course->harga) }}"
                                   min="0"
                                   required>
                        </div>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BIAYA SERTIFIKAT --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Biaya Sertifikat
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   name="biaya_sertifikat"
                                   class="form-control @error('biaya_sertifikat') is-invalid @enderror"
                                   value="{{ old('biaya_sertifikat', $course->biaya_sertifikat) }}"
                                   min="0">
                        </div>
                        <div class="form-text">Isi 0 jika sertifikat gratis.</div>
                        @error('biaya_sertifikat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status"
                                class="form-control @error('status') is-invalid @enderror">
                            <option value="aktif"    {{ old('status', $course->status) == 'aktif'    ? 'selected' : '' }}>Tersedia</option>
                            <option value="nonaktif" {{ old('status', $course->status) == 'nonaktif' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="col-md-6">

                    {{-- HARI KURSUS --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Hari Kursus <span class="text-danger">*</span>
                            <small class="text-muted fw-normal">(pilih maks. 2 hari)</small>
                        </label>
                        @php
                            $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                            $oldHari  = old('hari', $course->hari ?? []);
                            if (is_string($oldHari)) {
                                $oldHari = json_decode($oldHari, true) ?? [];
                            }
                        @endphp
                        <div class="border rounded p-3 @error('hari') border-danger @enderror">
                            <div class="row g-2">
                                @foreach($hariList as $hari)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input hari-checkbox"
                                               type="checkbox"
                                               name="hari[]"
                                               value="{{ $hari }}"
                                               id="hari_{{ $hari }}"
                                               {{ in_array($hari, $oldHari) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hari_{{ $hari }}">
                                            {{ $hari }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div id="hariWarning" class="text-danger small mt-2" style="display:none;">
                                <i class="fas fa-exclamation-circle"></i> Maksimal pilih 2 hari!
                            </div>
                        </div>
                        @error('hari')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DURASI PER SESI --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Durasi Per Sesi (menit) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="durasi_per_sesi"
                               class="form-control @error('durasi_per_sesi') is-invalid @enderror"
                               value="{{ old('durasi_per_sesi', $course->durasi_per_sesi) }}"
                               min="1" required>
                        @error('durasi_per_sesi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MASA AKTIF HARI --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Masa Aktif (hari) <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="masa_aktif_hari"
                               class="form-control @error('masa_aktif_hari') is-invalid @enderror"
                               value="{{ old('masa_aktif_hari', $course->masa_aktif_hari) }}"
                               min="1" required>
                        @error('masa_aktif_hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KUOTA PESERTA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Kuota Peserta <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="kuota_peserta"
                               class="form-control @error('kuota_peserta') is-invalid @enderror"
                               value="{{ old('kuota_peserta', $course->kuota_peserta) }}"
                               min="1" required>
                        @error('kuota_peserta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TERDAFTAR & SISA KUOTA (Readonly) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Statistik Pendaftaran</label>
                        <div class="border rounded p-3 bg-light">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Terdaftar</small>
                                    <p class="mb-0 fw-bold">{{ $course->terdaftar }} orang</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Sisa Kuota</small>
                                    <p class="mb-0 fw-bold {{ $course->sisaKuota() > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $course->sisaKuota() }} orang
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="row mt-2">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Kursus</label>
                        <textarea name="deskripsi"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="4">{{ old('deskripsi', $course->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </form>
    </div>
</div>

{{-- MODAL TAMBAH MATA PELAJARAN --}}
<div class="modal fade" id="modalTambahMapel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle text-primary"></i> Tambah Mata Pelajaran Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Mata Pelajaran <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="input_nama_mapel"
                           placeholder="Contoh: Matematika, Bahasa Inggris, IPA">
                    <div id="error_mapel" class="text-danger mt-1" style="display:none;">
                        Nama mata pelajaran harus diisi!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanMapel">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.hari-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var checked = document.querySelectorAll('.hari-checkbox:checked');
        var warning = document.getElementById('hariWarning');
        if (checked.length > 2) {
            this.checked = false;
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
    });
});

document.getElementById('btnSimpanMapel').addEventListener('click', function() {
    var nama     = document.getElementById('input_nama_mapel').value.trim();
    var errorDiv = document.getElementById('error_mapel');

    if (!nama) {
        errorDiv.style.display = 'block';
        return;
    }
    errorDiv.style.display = 'none';

    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/admin/mapel/store-quick', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onload = function() {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.success) {
                var select = document.getElementById('id_mapel');
                var option = document.createElement('option');
                option.value = data.id;
                option.text  = data.nama;
                select.appendChild(option);
                select.value = data.id;

                bootstrap.Modal.getInstance(document.getElementById('modalTambahMapel')).hide();
                document.getElementById('input_nama_mapel').value = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.nama + ' berhasil ditambahkan!',
                    confirmButtonColor: '#1cc88a',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonColor: '#e74a3b'
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Server Error!',
                text: 'Terjadi kesalahan pada server (kode: ' + xhr.status + ')',
                confirmButtonColor: '#e74a3b'
            });
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    };

    xhr.onerror = function() {
        Swal.fire({
            icon: 'error',
            title: 'Koneksi Error!',
            text: 'Tidak dapat terhubung ke server. Coba lagi.',
            confirmButtonColor: '#e74a3b'
        });
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    };

    xhr.send(JSON.stringify({ nama_mapel: nama }));
});

document.getElementById('modalTambahMapel').addEventListener('hidden.bs.modal', function() {
    document.getElementById('input_nama_mapel').value = '';
    document.getElementById('error_mapel').style.display = 'none';
});
</script>

@endsection