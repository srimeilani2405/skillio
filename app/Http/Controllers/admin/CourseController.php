<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\MataPelajaran;
use App\Models\Instructor;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['jenjang', 'mapel', 'instructor'])->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $jenjangIds = [1, 2, 3, 4, 5, 6, 7, 8];
        $jenjangList = Category::whereIn('kategori_id', $jenjangIds)->get();
        $mapelList = MataPelajaran::all();
        $instructorList = Instructor::where('status', 'aktif')->get();

        return view('admin.courses.create', compact('jenjangList', 'mapelList', 'instructorList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:255',
            'id_mapel' => 'required|exists:matapelajarans,id',
            'id_kategori' => 'required|exists:categories,kategori_id',
            'id_instructors' => 'required|exists:instructors,id_instructors',
            'harga' => 'required|numeric|min:0',
            'biaya_sertifikat' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'hari' => 'required|array|min:1|max:7',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            // Validasi jam sesuai nama input di Blade
            'jam_mulai_full' => 'required',
            'jam_selesai_full' => 'required',
            'masa_aktif_hari' => 'required|integer|min:1',
            'durasi_per_sesi' => 'required|integer|min:1',
            'kuota_peserta' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Ambil nama mapel agar kolom 'mata_pelajaran' tidak NULL
        $mapel = MataPelajaran::findOrFail($request->id_mapel);

        // Konversi format jam AM/PM ke format 24 jam untuk database
        $jamMulai = date('H:i:s', strtotime($request->jam_mulai_full));
        $jamSelesai = date('H:i:s', strtotime($request->jam_selesai_full));

        Course::create([
            'nama' => $request->nama_kursus,
            'id_mapel' => $request->id_mapel,
            'mata_pelajaran' => $mapel->nama_mapel,
            'id_kategori' => $request->id_kategori,
            'id_instructors' => $request->id_instructors,
            'harga' => $request->harga,
            'biaya_sertifikat' => $request->biaya_sertifikat ?? 0,
            'deskripsi' => $request->deskripsi,
            'hari' => $request->hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'masa_aktif_hari' => $request->masa_aktif_hari,
            'durasi_per_sesi' => $request->durasi_per_sesi,
            'kuota_peserta' => $request->kuota_peserta,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kursus berhasil ditambahkan!');
    }

    public function show($id)
    {
        $course = Course::with(['jenjang', 'mapel', 'instructor'])->findOrFail($id);
        return view('admin.courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $jenjangIds = [1, 2, 3, 4, 5, 6, 7, 8];
        $jenjangList = Category::whereIn('kategori_id', $jenjangIds)->get();
        $mapelList = MataPelajaran::all();
        $instructorList = Instructor::where('status', 'aktif')->get();

        return view('admin.courses.edit', compact('course', 'jenjangList', 'mapelList', 'instructorList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:255',
            'id_mapel' => 'required|exists:matapelajarans,id',
            'id_kategori' => 'required|exists:categories,kategori_id',
            'id_instructors' => 'required|exists:instructors,id_instructors',
            'harga' => 'required|numeric|min:0',
            'biaya_sertifikat' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'hari' => 'required|array|min:1|max:7',
            'hari.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai_full' => 'required',
            'jam_selesai_full' => 'required',
            'masa_aktif_hari' => 'required|integer|min:1',
            'durasi_per_sesi' => 'required|integer|min:1',
            'kuota_peserta' => 'required|integer|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $course = Course::findOrFail($id);
        $mapel = MataPelajaran::findOrFail($request->id_mapel);

        // Konversi format jam AM/PM ke format 24 jam untuk database
        $jamMulai = date('H:i:s', strtotime($request->jam_mulai_full));
        $jamSelesai = date('H:i:s', strtotime($request->jam_selesai_full));

        $course->update([
            'nama' => $request->nama_kursus,
            'id_mapel' => $request->id_mapel,
            'mata_pelajaran' => $mapel->nama_mapel,
            'id_kategori' => $request->id_kategori,
            'id_instructors' => $request->id_instructors,
            'harga' => $request->harga,
            'biaya_sertifikat' => $request->biaya_sertifikat ?? 0,
            'deskripsi' => $request->deskripsi,
            'hari' => $request->hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'masa_aktif_hari' => $request->masa_aktif_hari,
            'durasi_per_sesi' => $request->durasi_per_sesi,
            'kuota_peserta' => $request->kuota_peserta,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kursus berhasil diupdate!');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->route('admin.courses.index')
            ->with('success', 'Kursus berhasil dihapus!');
    }

    public function storeMapel(Request $request)
    {
        try {
            $request->validate([
                'nama_mapel' => 'required|string|max:255',
            ]);

            $mapel = MataPelajaran::create([
                'nama_mapel' => $request->nama_mapel,
            ]);

            return response()->json([
                'success' => true,
                'id' => $mapel->id,
                'nama' => $mapel->nama_mapel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}