<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['jenjang', 'mapel', 'instructor']);

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $courses = $query->get();
        $categories = Category::all();

        return view('owner.products.index', compact('courses', 'categories'));
    }

    public function show($id)
    {
        $course = Course::with(['jenjang', 'mapel', 'instructor', 'detailTransaksis.transaction.customer'])
                ->findOrFail($id);

        $totalTerjual = $course->detailTransaksis->sum('jumlah');
        $totalPendapatan = $course->detailTransaksis->sum('subtotal');

        return view('owner.products.show', compact('course', 'totalTerjual', 'totalPendapatan'));
    }
}