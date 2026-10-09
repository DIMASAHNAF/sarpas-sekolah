<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - SIM-SARPRAS | SMKN 1 Beringin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/assets/foto.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('storage/assets/foto.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/assets/foto.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center p-4 antialiased text-slate-800"
      x-data="{ showPassword: false }">

    <div class="w-full max-w-md my-8">

        <!-- Logo & Branding -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center gap-3 px-4 py-2 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl shadow-black/20 ring-1 ring-white/50 mb-5">
                <img src="{{ asset('storage/assets/foto.png') }}" alt="Logo SMKN 1 Beringin" class="w-12 h-12 object-contain">
                <div class="w-px h-8 bg-slate-200"></div>
                <img src="{{ asset('assets/logo-kolaborasi.png') }}" alt="Logo Kolaborasi" class="h-8 w-auto object-contain">
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">
                SIM-SARPRAS
            </h1>
            <p class="text-xs text-slate-300 mt-1 font-medium">Sistem Informasi Sarana & Prasarana</p>
            <p class="text-xs text-blue-300 font-semibold mt-0.5">SMK Negeri 1 Beringin</p>
        </div>

        <!-- Card Form Login -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/40 border border-white/20 p-6 sm:p-8">
            <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Autentikasi Pengguna</h2>
                    <p class="text-xs text-slate-500">Silakan masuk untuk mengakses sistem</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>

            <!-- Feedback Alerts -->
            @if(session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-start space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0"></i>
                    <span class="leading-relaxed">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-start space-x-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm mt-0.5 shrink-0"></i>
                    <span class="leading-relaxed">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs">
                    <div class="flex items-center space-x-1.5 font-bold mb-1">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Gagal Masuk:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email / Username -->
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email atau Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        <input type="text" 
                               name="login" 
                               id="login" 
                               value="{{ old('login') }}"
                               required 
                               autofocus
                               placeholder="Masukkan email atau username"
                               autocomplete="username"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Kata Sandi (Password)
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               id="password" 
                               required 
                               placeholder="••••••••"
                               autocomplete="current-password"
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors"
                                title="Lihat / Sembunyikan Password">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1 text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Ingat sesi saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" 
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-200 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                    <span>Masuk ke SIM-SARPRAS</span>
                </button>
            </form>

        </div>

        <!-- Footer Notice -->
        <div class="text-center mt-6 text-xs text-slate-400 space-y-1">
            <p><i class="fa-solid fa-lock mr-1 text-[10px] text-emerald-400"></i> Sistem Dilindungi Autentikasi Internal Sekolah</p>
            <p class="text-[11px] text-slate-500">&copy; 2026 SMK Negeri 1 Beringin. Hak Cipta Dilindungi.</p>
        </div>

    </div>

</body>
</html>
