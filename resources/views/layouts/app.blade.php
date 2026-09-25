<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Buku Inventaris Barang (BIB) Dana BOS')</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </div>
                    <div>
                        <a href="{{ route('inventaris.index') }}" class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                            <span>SIM-SARPRAS</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 uppercase">Dana BOS</span>
                        </a>
                        <p class="text-xs text-slate-500 font-medium">Buku Inventaris Barang Sekolah</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('inventaris.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('inventaris.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-table-list mr-1.5 text-blue-600"></i> Daftar Inventaris
                    </a>
                    <a href="{{ route('inventaris.create') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('inventaris.create') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-plus-circle mr-1.5 text-emerald-600"></i> Tambah Barang
                    </a>
                    <a href="{{ route('profil-sekolah.edit') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('profil-sekolah.edit') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-stamp mr-1.5 text-amber-600"></i> Lembar Pengesahan
                    </a>
                </nav>

                <!-- Action Button -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('inventaris.create') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm hover:shadow transition-all duration-150">
                        <i class="fa-solid fa-plus mr-2 text-xs"></i> Catat Barang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="mb-6 flex items-center p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm transition-all" role="alert">
                <i class="fa-solid fa-circle-check text-xl mr-3 text-emerald-600"></i>
                <div class="text-sm font-medium flex-1">
                    {{ session('success') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm transition-all" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-xl mr-3 text-rose-600"></i>
                <div class="text-sm font-medium flex-1">
                    {{ session('error') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm">
                <div class="flex items-center mb-2">
                    <i class="fa-solid fa-circle-xmark text-lg mr-2 text-rose-600"></i>
                    <h3 class="text-sm font-bold text-rose-900">Terdapat kesalahan pengisian data:</h3>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>Sistem Informasi Buku Inventaris Barang (BIB) Pengadaan Dana BOS &bull; Versi 1.0</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
