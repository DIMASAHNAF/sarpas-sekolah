<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBarang;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna/staf SIM-SARPRAS.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($jurusan = $request->input('jurusan')) {
            $query->where('jurusan', $jurusan);
        }

        $users = $query->orderByRaw("FIELD(role, 'admin', 'kaprodi', 'staff')")->orderBy('name')->paginate(15)->withQueryString();
        $jurusanList = PengajuanBarang::getJurusanList();

        return view('users.index', compact('users', 'jurusanList'));
    }

    /**
     * Form tambah akun pengguna baru.
     */
    public function create(): View
    {
        $jurusanList = PengajuanBarang::getJurusanList();
        return view('users.create', compact('jurusanList'));
    }

    /**
     * Simpan akun pengguna baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'string', 'in:admin,kaprodi,staff'],
            'jurusan'  => ['nullable', 'string', 'max:100'],
        ], [
            'email.unique'      => 'Alamat email ini sudah terdaftar.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.in'           => 'Pilih peran yang valid: Admin, Kaprodi, atau Staf.',
        ]);

        // Jika peran admin, kosongkan jurusan
        if ($validated['role'] === 'admin') {
            $validated['jurusan'] = null;
        }

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('pengguna.index')
            ->with('success', "Akun '{$validated['name']}' berhasil dibuat.");
    }

    /**
     * Form edit akun pengguna.
     */
    public function edit(User $pengguna): View
    {
        $jurusanList = PengajuanBarang::getJurusanList();
        return view('users.edit', [
            'user'        => $pengguna,
            'jurusanList' => $jurusanList,
        ]);
    }

    /**
     * Perbarui data akun pengguna.
     */
    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $pengguna->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', 'string', 'in:admin,kaprodi,staff'],
            'jurusan'  => ['nullable', 'string', 'max:100'],
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar pada akun lain.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($validated['role'] === 'admin') {
            $validated['jurusan'] = null;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('pengguna.index')
            ->with('success', "Data akun '{$pengguna->name}' berhasil diperbarui.");
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $pengguna): RedirectResponse
    {
        if ($pengguna->id === auth()->id()) {
            return redirect()->route('pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $name = $pengguna->name;
        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', "Akun '{$name}' berhasil dihapus.");
    }
}

