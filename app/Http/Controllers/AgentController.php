<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AgentRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AgentEditRequest;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::all();
        return view('agents.index',compact('agents'));
    }

    public function create()
    {
        return view('agents.create');
    }

    public function store(AgentRequest $request)
    {
        try {
            // 1. Upload avatar jika ada
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            } else {
                $avatarPath = null; // Jika tidak ada avatar
            }

            // 2. Membuat user baru
            $user = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => Hash::make($request->password),
                'avatar'                => $avatarPath,
            ]);

            // 3. Assign role 'agen' ke user
            $user->assignRole('agen'); // Role 'agen' dari tabel roles

            // 4. Membuat agent terkait dengan user
            $agent = Agent::create([
                'user_id'              => $user->id,
                'nik'                  => $request->nik,
                'no_hp'                => $request->no_hp,
                'kota'                 => $request->kota,
                'kelamin'              => $request->kelamin,
                'tempat_lahir'         => $request->tempat_lahir,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'status'               => $request->status,
                'foto_agent'           => $request->foto_agent,
                'alamat'               => $request->alamat,
                'catatan'              => $request->catatan,
            ]);

            // 5. Redirect dengan pesan sukses
            return redirect()->route('agents')->with('success', 'Agent berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Agent $agent)
    {
        $roles = Role::all();
        return view('agents.edit', compact('agent', 'roles'));
    }

    public function update(AgentEditRequest $request, Agent $agent)
    {
        try {
            // 1. Upload avatar jika ada
            if ($request->hasFile('avatar')) {
                // Hapus avatar lama jika ada
                if ($agent->user->avatar && Storage::exists('public/' . $agent->user->avatar)) {
                    Storage::delete('public/' . $agent->user->avatar);
                }

                // Upload avatar baru
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            } else {
                // Jika tidak ada perubahan avatar, biarkan avatar lama
                $avatarPath = $agent->user->avatar;
            }

            // 2. Update data user
            $agent->user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password ? Hash::make($request->password) : $agent->user->password, // Hanya update password jika ada perubahan
                'avatar'    => $avatarPath,
            ]);

            // 3. Update data agent
            $agent->update([
                'nik'                  => $request->nik,
                'no_hp'                => $request->no_hp,
                'kota'                 => $request->kota,
                'kelamin'              => $request->kelamin,
                'tempat_lahir'         => $request->tempat_lahir,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'status'               => $request->status,
                'alamat'               => $request->alamat,
                'catatan'              => $request->catatan,
            ]);

            // 4. Assign role jika diperlukan (misalnya role 'agen' tetap berlaku)
            $agent->user->syncRoles('agen'); // Menggunakan syncRoles untuk memastikan hanya 1 role yang ada

            // 5. Redirect dengan pesan sukses
            return redirect()->route('agents')->with('success', 'Agent berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Agent $agent)
    {
        try {
            $user = $agent->user;
    
            // 1. Hapus avatar user jika ada
            if ($user && $user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
    
            // 2. Hapus agent (soft delete)
            $agent->delete();
    
            // 3. Hapus user (soft delete)
            if ($user) {$user->delete();}
    
            return redirect()->route('agents.index')->with('success', 'Agent berhasil dihapus.');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
