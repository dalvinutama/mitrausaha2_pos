<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengguna, diurutkan dari yang terbaru
        $users = User::orderBy('created_at', 'desc')->get();
        return view('pengguna', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        // CEK KEAMANAN: Admin tidak boleh membuat akun dengan role Owner
        if (Auth::user()->role === 'admin' && $request->role === 'owner') {
            return redirect()->back()->with('error', 'Akses Ditolak: Admin tidak diizinkan membuat akun Owner baru.');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Akun pengguna baru berhasil ditambahkan!');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // CEK KEAMANAN: Admin tidak boleh mengedit akun Owner
        if (Auth::user()->role === 'admin' && $user->role === 'owner') {
            return redirect()->back()->with('error', 'Akses Ditolak: Admin tidak diizinkan mengubah data milik Owner.');
        }

        // CEK KEAMANAN: Admin tidak boleh mengubah role orang lain menjadi Owner
        if (Auth::user()->role === 'admin' && $request->role === 'owner') {
            return redirect()->back()->with('error', 'Akses Ditolak: Admin tidak dapat mengangkat pengguna menjadi Owner.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->no_wa = $request->no_wa;
        $user->alamat = $request->alamat;

        // Jika password diisi, berarti mau ganti password. Jika kosong, biarkan password lama.
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah user menghapus akunnya sendiri yang sedang dipakai
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        // CEK KEAMANAN: Admin tidak boleh menghapus akun Owner
        if (Auth::user()->role === 'admin' && $user->role === 'owner') {
            return redirect()->back()->with('error', 'Akses Ditolak: Admin tidak diizinkan menghapus akun Owner.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus.');
    }
}