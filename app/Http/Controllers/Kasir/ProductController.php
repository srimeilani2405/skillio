<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $courses = Course::with(['jenjang', 'mapel'])
                    ->where('status', 'aktif')
                    ->get();

        $categories = Category::all();

        return view('kasir.products.index', compact('courses', 'categories'));
    }
}