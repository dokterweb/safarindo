<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->get(); // Eager load 'roles'
        $roles = Role::all();
        return view('users.index', compact('users','roles'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6|confirmed',
            'role'          => 'required|exists:roles,name',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Avatar max 2MB
        ]);

        try {
            // Upload avatar jika ada
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            } else {
                $avatarPath = null; // Jika tidak ada avatar, set null
            }

            // Buat user baru
            $user = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => Hash::make($request->password),
                'avatar'                => $avatarPath,
            ]);

            // Assign role ke user
            $user->assignRole($request->role);

            // Redirect dengan pesan sukses
            return redirect()->route('users')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $userview = User::with('roles')->get();
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles', 'userview'));
    }

    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'name'          => 'required|string|max:255|unique:users,name,' . $user->id, 
            // Ignore unique check for the current user
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id, 
            // Ignore unique check for the current user
            'password'      => 'nullable|string|min:6|confirmed', 
            // Password is optional, only required if changing
            'role'          => 'required|exists:roles,name', // Ensure role exists in roles table
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Avatar max 2MB
        ]);

        try {
            // Cek jika ada file avatar yang diupload
            if ($request->hasFile('avatar')) {
                // Hapus avatar lama jika ada
                if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                    Storage::delete('public/' . $user->avatar);
                }

                // Upload avatar baru
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            } else {
                // Jika tidak ada perubahan avatar, biarkan avatar lama
                $avatarPath = $user->avatar;
            }

            // Update data user
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password ? Hash::make($request->password) : $user->password, // Hanya update password jika ada perubahan
                'avatar'    => $avatarPath,
            ]);

            // Assign role ke user
            $user->syncRoles($request->role); // Menggunakan syncRoles untuk memastikan hanya 1 role yang ada

            // Redirect dengan pesan sukses
            return redirect()->route('users')->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            // Hapus avatar jika ada
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Hapus user dan relasi role
            $user->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('users')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
