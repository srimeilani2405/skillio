<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\LogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Admin hanya bisa lihat user kasir
    public function index()
    {
        $data = User::where('role', 'kasir')->latest()->get();
        return view('admin.users.index', compact('data'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'status'   => 'required|boolean',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'kasir',
            'status'   => $request->status,
        ]);

        LogActivity::log('Admin menambah user kasir: ' . $user->name, 'Tambah');

        return redirect('/admin/users')->with('success', 'User Kasir berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'kasir') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda hanya bisa mengedit user dengan role Kasir!');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'kasir') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda hanya bisa mengupdate user dengan role Kasir!');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'status'   => 'required|boolean',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'status'   => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        LogActivity::log('Admin mengupdate user kasir: ' . $user->name, 'Edit');

        return redirect()->route('admin.users.index')
            ->with('success', 'User Kasir berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'kasir') {
            return back()->with('error', 'Anda hanya bisa menghapus user dengan role Kasir!');
        }

        $nama = $user->name;

        if ($user->transactions()->count() > 0) {
            return back()->with('error', 'User Kasir tidak bisa dihapus karena masih memiliki transaksi!');
        }

        $user->delete();

        LogActivity::log('Admin menghapus user kasir: ' . $nama, 'Hapus');

        return back()->with('success', 'User Kasir berhasil dihapus');
    }
}