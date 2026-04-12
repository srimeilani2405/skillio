<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Customer;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $data = Instructor::all();
        return view('admin.instructors.index', compact('data'));
    }
    
    public function create()
    {
        return view('admin.instructors.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:instructors',
            'no_telp' => 'required',
            'spesialisasi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif'
        ]);
        
        Instructor::create($request->all());
        
        return redirect()->route('admin.instructors.index')
            ->with('success', 'Pengajar berhasil ditambahkan!');
    }
    
    public function edit($id)
    {
        $instructor = Instructor::findOrFail($id);
        return view('admin.instructors.edit', compact('instructor'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:instructors,email,' . $id,
            'no_telp' => 'required',
            'spesialisasi' => 'nullable',
            'status' => 'required|in:aktif,nonaktif'
        ]);
        
        $instructor = Instructor::findOrFail($id);
        $instructor->update($request->all());
        
        return redirect()->route('admin.instructors.index')
            ->with('success', 'Pengajar berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        try {
            $instructor = Instructor::findOrFail($id);
            $instructor->delete();
            
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Pengajar berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'Gagal menghapus pengajar!');
        }
    }

    // METHOD PROFILE - Menampilkan profil pengajar beserta kursus dan siswa
    public function profile($id)
    {
        $instructor = Instructor::findOrFail($id);
        
        // Ambil semua kursus yang diajar oleh instructor ini
        $courses = Course::where('id_instructors', $id)->get();
        
        // Ambil ID kursus (id_paket) untuk query siswa
        $courseIds = $courses->pluck('id_paket')->toArray();
        
        // Ambil semua siswa (customer) yang terdaftar di kursus instructor ini
        $students = Customer::whereHas('transactions.detailTransaksis', function($query) use ($courseIds) {
            $query->whereIn('id_paket', $courseIds);
        })->with(['transactions.detailTransaksis.course'])->get();
        
        return view('admin.instructors.profile', compact('instructor', 'courses', 'students', 'courseIds'));
    }
}