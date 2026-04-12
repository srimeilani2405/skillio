<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // OWNER BISA LIHAT SEMUA USER KECUALI USER ROLE OWNER
    public function index()
    {
        // AMBIL SEMUA USER KECUALI ROLE 'owner'
        $data = User::where('role', '!=', 'owner')->latest()->get();
        return view('owner.users.index', compact('data'));
    }

    public function create()
    {
        return view('owner.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir', // HANYA ADMIN & KASIR
            'status' => 'required|boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        LogActivity::log('Owner menambah user: ' . $user->name . ' (' . $user->role . ')', 'Tambah');

        return redirect('/owner/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // CEK JIKA USER YANG DIEDIT ADALAH OWNER, TIDAK BOLEH
        if ($user->role === 'owner') {
            return redirect()->route('owner.users.index')
                ->with('error', 'Tidak dapat mengedit user Owner!');
        }
        
        return view('owner.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // CEK JIKA USER YANG DIUPDATE ADALAH OWNER, TIDAK BOLEH
        if ($user->role === 'owner') {
            return redirect()->route('owner.users.index')
                ->with('error', 'Tidak dapat mengupdate user Owner!');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,kasir',
            'status' => 'required|boolean'
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        LogActivity::log('Owner mengupdate user: ' . $user->name, 'Edit');

        return redirect()->route('owner.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $nama = $user->name;

        // CEK JANGAN SAMPAI HAPUS DIRI SENDIRI
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        
        // CEK JANGAN SAMPAI HAPUS USER OWNER
        if ($user->role === 'owner') {
            return back()->with('error', 'Tidak dapat menghapus user Owner!');
        }

        if ($user->transactions()->count() > 0) {
            return back()->with('error', 'User tidak bisa dihapus karena masih memiliki transaksi!');
        }

        $user->delete();

        LogActivity::log('Owner menghapus user: ' . $nama, 'Hapus');

        return back()->with('success', 'User berhasil dihapus');
    }
}