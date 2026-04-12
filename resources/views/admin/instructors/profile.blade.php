@extends('layouts.sidebar-admin')

@section('title', 'Profil Pengajar - ' . $instructor->nama)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-tie me-2"></i> Profil Pengajar</h2>
    <a href="{{ route('admin.instructors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    {{-- KOLOM KIRI: PROFIL PENGAJAR --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                         style="width: 120px; height: 120px; font-size: 48px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <h4 class="mb-1">{{ $instructor->nama }}</h4>
                <p class="text-muted mb-2">
                    <i class="fas fa-envelope me-1"></i> {{ $instructor->email }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-phone me-1"></i> {{ $instructor->no_telp ?? '-' }}
                </p>
                <p class="mb-2">
                    <i class="fas fa-tag me-1"></i> 
                    <span class="badge bg-info">{{ $instructor->spesialisasi ?? 'Umum' }}</span>
                </p>
                <p>
                    @if($instructor->status == 'aktif')
                        <span class="badge bg-success px-3 py-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">Nonaktif</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: DAFTAR SISWA --}}
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i> Daftar Siswa yang Terdaftar</h5>
            </div>
            <div class="card-body">
                @if($students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #2c3e6b, #1abc9c); color: white;">
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Kursus yang Diambil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $student->nama }}</strong></td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->no_telp ?? '-' }}</td>
                                    <td>
                                        @php
                                            $studentCourses = [];
                                            foreach($student->transactions as $transaction) {
                                                foreach($transaction->detailTransaksis as $detail) {
                                                    if(in_array($detail->id_paket, $courseIds ?? [])) {
                                                        $studentCourses[] = $detail->course->nama ?? 'Kursus';
                                                    }
                                                }
                                            }
                                            $studentCourses = array_unique($studentCourses);
                                        @endphp
                                        @foreach($studentCourses as $courseName)
                                            <span class="badge bg-primary me-1 mb-1">{{ $courseName }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-graduate fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Belum ada siswa yang terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection