<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->has('is_active'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Cegah lockout jika mengubah peran/status administrator terakhir
        if ($user->isAdmin() && ($validated['role'] !== 'admin' || !$request->has('is_active'))) {
            $otherAdmins = User::where('role', 'admin')->where('is_active', true)->where('id', '!=', $user->id)->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Perubahan ditolak: Sistem harus memiliki minimal satu akun Administrator yang aktif.');
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->has('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        if ($user->isAdmin() && $user->is_active) {
            $otherAdmins = User::where('role', 'admin')->where('is_active', true)->where('id', '!=', $user->id)->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Tidak dapat menonaktifkan: Ini adalah satu-satunya akun Administrator yang aktif.');
            }
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun pengguna berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->isAdmin()) {
            $otherAdmins = User::where('role', 'admin')->where('is_active', true)->where('id', '!=', $user->id)->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Tidak dapat menghapus: Ini adalah satu-satunya akun Administrator yang aktif.');
            }
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
