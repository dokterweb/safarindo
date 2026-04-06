<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJamaahRequest;
use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Paket;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\Request;

class JamaahController extends Controller
{
    public function createByPaket($id)
    {
        $paket = Paket::findOrFail($id);
        $agents = Agent::all();
        return view('jamaahs.create', compact('paket','agents'));
    }

    public function storeByPaket(StoreJamaahRequest $request, $id)
    {
        $paket = Paket::findOrFail($id);

        // 🔥 VALIDASI KUOTA
        if ($paket->jamaahs()->count() >= $paket->kuota) {
            return back()->with('error', 'Kuota paket sudah penuh');
        }

        // 🔥 HANDLE UPLOAD FILE
        $data = $request->validated();

        $uploadFields = [
            'foto_jamaah',
            'foto_ktp',
            'foto_kk',
            'foto_pasport1',
            'foto_pasport2'
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('jamaah', 'public');
            }
        }

        // 🔥 INSERT DATA
        Jamaah::create([
            'paket_id' => $paket->id,
            'agent_id' => $data['agent_id'], // sesuaikan jika relasi agent beda
            'nik' => $data['nik'],
            'nama_jamaah' => $data['nama_jamaah'],
            'no_hp' => $data['no_hp'],
            'kota' => $data['kota'],
            'kelamin' => $data['kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'catatan' => $data['catatan'],

            // Passport
            'nama_jamaah_pasport' => $data['nama_jamaah_pasport'] ?? null,
            'no_pasport' => $data['no_pasport'] ?? null,
            'penerbit' => $data['penerbit'] ?? null,
            'pasport_aktif' => $data['pasport_aktif'] ?? null,
            'pasport_expired' => $data['pasport_expired'] ?? null,

            // File
            'foto_jamaah' => $data['foto_jamaah'] ?? null,
            'foto_ktp' => $data['foto_ktp'] ?? null,
            'foto_kk' => $data['foto_kk'] ?? null,
            'foto_pasport1' => $data['foto_pasport1'] ?? null,
            'foto_pasport2' => $data['foto_pasport2'] ?? null,

            // Default
            'status' => 'aktif',
            'lunas' => '0'
        ]);

        return redirect()
            ->route('pakets.jamaah.detail', $paket->id)
            ->with('success', 'Jamaah berhasil ditambahkan');
    }

    public function show($id)
    {
        $jamaah = Jamaah::with('paket', 'agent.user')->findOrFail($id);
        return view('jamaahs.show', compact('jamaah'));
    }

    public function edit($id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $agents = Agent::with('user')->get();

        return view('jamaahs.edit', compact('jamaah', 'agents'));
    }

    public function update(StoreJamaahRequest $request, $id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $data = $request->validated();

        $uploadFields = [
            'foto_jamaah',
            'foto_ktp',
            'foto_kk',
            'foto_pasport1',
            'foto_pasport2'
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {

                // hapus file lama jika ada
                if ($jamaah->$field && Storage::disk('public')->exists($jamaah->$field)) {
                    Storage::disk('public')->delete($jamaah->$field);
                }

                $data[$field] = $request->file($field)->store('jamaah', 'public');
            }
        }

        $jamaah->update([
            'agent_id' => $data['agent_id'],
            'nik' => $data['nik'],
            'nama_jamaah' => $data['nama_jamaah'],
            'no_hp' => $data['no_hp'],
            'kota' => $data['kota'],
            'kelamin' => $data['kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'catatan' => $data['catatan'],

            // passport
            'nama_jamaah_pasport' => $data['nama_jamaah_pasport'] ?? null,
            'no_pasport' => $data['no_pasport'] ?? null,
            'penerbit' => $data['penerbit'] ?? null,
            'pasport_aktif' => $data['pasport_aktif'] ?? null,
            'pasport_expired' => $data['pasport_expired'] ?? null,

            // file
            'foto_jamaah' => $data['foto_jamaah'] ?? $jamaah->foto_jamaah,
            'foto_ktp' => $data['foto_ktp'] ?? $jamaah->foto_ktp,
            'foto_kk' => $data['foto_kk'] ?? $jamaah->foto_kk,
            'foto_pasport1' => $data['foto_pasport1'] ?? $jamaah->foto_pasport1,
            'foto_pasport2' => $data['foto_pasport2'] ?? $jamaah->foto_pasport2,
        ]);

        return redirect()
            ->route('jamaahs.show', $jamaah->id)
            ->with('success', 'Data jamaah berhasil diupdate');
    }
}
