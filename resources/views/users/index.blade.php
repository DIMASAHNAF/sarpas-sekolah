@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Staf Unit - SIM-SARPRAS')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-1">
                <span>SIM-SARPRAS</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-blue-600 font-bold">Administrator</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                Manajemen Pengguna & Staf Jurusan/Unit
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Atur akun administrator dan staf per jurusan (7 jurusan + UKS & Perpustakaan) dengan hak akses terisolasi.
            </p>
        </div>

        <div>
            <a href="{{ route('pengguna.create') }}" 
               class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all duration-150">
                <i class="fa-solid fa-user-plus mr-2 text-blue-200"></i> Tambah Akun Pengguna
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('pengguna.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Search Text -->
            <div class="relative sm:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, email, atau jurusan..." 
                       class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <!-- Filter Peran -->
            <div>
                <select name="role" class="w-full py-2 px-3 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="">Semua Peran</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="kaprodi" {{ request('role') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                    <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staf / Pengurus Unit</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'role', 'jurusan']))
                    <a href="{{ route('pengguna.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                Total {{ $users->total() }} Akun Pengguna
            </span>
            <span class="text-[11px] text-slate-400">Hal. {{ $users->currentPage() }}/{{ $users->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-3 w-[40px] text-center">No</th>
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Email Login</th>
                        <th class="py-3 px-3 text-center">Peran</th>
                        <th class="py-3 px-4">Jurusan / Unit</th>
                        <th class="py-3 px-3 text-center w-[100px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-blue-50/30 transition-colors {{ $u->id === auth()->id() ? 'bg-blue-50/40 font-semibold' : '' }}">
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800 flex items-center space-x-2">
                                    <span>{{ $u->name }}</span>
                                    @if($u->id === auth()->id())
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-100 text-blue-700">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $u->email }}
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                @if($u->isAdmin())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fa-solid fa-shield-halved mr-1"></i> Admin
                                    </span>
                                @elseif($u->role === 'kaprodi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        <i class="fa-solid fa-graduation-cap mr-1"></i> Kaprodi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fa-solid fa-user-tag mr-1"></i> Staf Unit
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($u->jurusan)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <i class="fa-solid fa-building-columns mr-1.5 text-indigo-500"></i> {{ $u->jurusan }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Seluruh Jurusan & Sekolah (Penuh)</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <a href="{{ route('pengguna.edit', $u) }}" 
                                       title="Ubah Akun" 
                                       class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('pengguna.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Akun" 
                                                    class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-users-slash text-2xl mb-1 block"></i>
                                <span>Tidak ada akun pengguna yang ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection

