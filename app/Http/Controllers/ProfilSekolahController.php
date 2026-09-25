<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilSekolahRequest;
use App\Models\ProfilSekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilSekolahController extends Controller
{
    /**
     * Menampilkan form pengaturan profil sekolah dan pengesahan.
     */
    public function edit(): View
    {
        $profil = ProfilSekolah::getProfil();

        return view('profil-sekolah.edit', compact('profil'));
    }

    /**
     * Menyimpan/memperbarui satu-satunya baris data profil sekolah (Single Row Config).
     */
    public function update(UpdateProfilSekolahRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        ProfilSekolah::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()
            ->route('profil-sekolah.edit')
            ->with('success', 'Profil Sekolah dan data Pengesahan berhasil disimpan!');
    }
}
