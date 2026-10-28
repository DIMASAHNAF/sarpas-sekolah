@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna Baru - SIM-SARPRAS')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div>
        <a href="{{ route('pengguna.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors inline-flex items-center mb-1">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar Pengguna
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buat Akun Pengguna Baru</h1>
        <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun administrator atau staf/kaprodi per jurusan sekolah.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-sm" x-data="{ selectedRole: '{{ old('role', 'kaprodi') }}' }">
        <form action="{{ route('pengguna.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Pengguna / Pejabat <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Kaprodi Kuliner / Budi Santoso, S.Pd."
                       class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('name') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Login -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email Login <span class="text-rose-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@smkn1beringin.sch.id"
                       class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-mono transition">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Sandi (Password) <span class="text-rose-500">*</span></label>
                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                       class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilihan Role -->
            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 uppercase mb-1">Peran / Hak Akses <span class="text-rose-500">*</span></label>
                <select name="role" id="role" x-model="selectedRole" required
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('role') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-medium transition">
                    <option value="kaprodi">Kaprodi (Kepala Program Keahlian)</option>
                    <option value="staff">Staf Unit (UKS, Perpustakaan, dll)</option>
                    <option value="admin">Administrator (Akses Penuh Semua Jurusan)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilihan Jurusan / Unit (Hanya jika bukan Admin) -->
            <div x-show="selectedRole !== 'admin'" x-transition>
                <label for="jurusan" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Jurusan / Unit yang Ditugaskan <span class="text-rose-500">*</span>
                </label>
                <select name="jurusan" id="jurusan"
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-slate-50 border @error('jurusan') border-rose-500 @else border-slate-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white font-medium transition">
                    <option value="">-- Pilih Jurusan / Unit --</option>
                    @foreach($jurusanList as $j)
                        <option value="{{ $j }}" {{ old('jurusan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Pengguna ini hanya dapat melihat & mengelola inventaris dan pengajuan barang unit ini.</p>
                @error('jurusan')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('pengguna.index') }}" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

