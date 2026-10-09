<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $__env->yieldContent('title', 'Buku Inventaris Barang (BIB) - SMK Negeri 1 Beringin'); ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('storage/assets/foto.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('storage/assets/foto.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('storage/assets/foto.png')); ?>">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            -webkit-tap-highlight-color: transparent;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
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
        .line-clamp-1 {
            display: -webkit-box !important;
            -webkit-line-clamp: 1 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
        .line-clamp-2 {
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-[1600px] mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">

                <!-- Brand / Logo -->
                <div class="flex items-center space-x-2.5 sm:space-x-3 min-w-0">
                    <a href="<?php echo e(route('inventaris.index')); ?>" class="shrink-0 flex items-center">
                        <img src="<?php echo e(asset('storage/assets/foto.png')); ?>" 
                             alt="Logo SIM-SARPRAS" 
                             class="w-8 h-8 sm:w-10 sm:h-10 object-contain rounded-xl p-0.5 bg-white border border-slate-200/80 shadow-xs">
                    </a>
                    <div class="truncate flex items-center">
                        <div>
                            <a href="<?php echo e(route('inventaris.index')); ?>" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-1.5 sm:space-x-2">
                                <span>SIM-SARPRAS</span>
                                <!-- Mobile Kolaborasi Logo -->
                                <img src="<?php echo e(asset('assets/logo-kolaborasi.png')); ?>" alt="Logo Kolaborasi" class="h-4 sm:h-5 object-contain md:hidden ml-1">
                            </a>
                            <p class="hidden sm:block text-xs text-slate-500 font-medium">Buku Inventaris Barang Sekolah</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="<?php echo e(route('inventaris.index')); ?>" 
                       class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors duration-150 <?php echo e(request()->routeIs('inventaris.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'); ?>">
                        <i class="fa-solid fa-table-list mr-1.5 text-blue-600"></i> Daftar Inventaris
                    </a>

                    <?php if(auth()->check()): ?>
                        <a href="<?php echo e(route('inventaris.create')); ?>" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors duration-150 <?php echo e(request()->routeIs('inventaris.create') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'); ?>">
                            <i class="fa-solid fa-plus-circle mr-1.5 text-emerald-600"></i> Tambah Barang
                        </a>
                    <?php endif; ?>

                    <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('profil-sekolah.edit')); ?>" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors duration-150 <?php echo e(request()->routeIs('profil-sekolah.edit') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'); ?>">
                            <i class="fa-solid fa-stamp mr-1.5 text-amber-600"></i> Lembar Pengesahan
                        </a>
                        <a href="<?php echo e(route('berita-acara.index')); ?>" 
                           class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-colors duration-150 <?php echo e(request()->routeIs('berita-acara.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'); ?>">
                            <i class="fa-solid fa-file-contract mr-1.5 text-indigo-600"></i> Berita Acara
                        </a>
                    <?php endif; ?>
                    
                    <div class="w-px h-6 bg-slate-200 mx-2"></div>
                    <img src="<?php echo e(asset('assets/logo-kolaborasi.png')); ?>" alt="Logo Kolaborasi" class="h-7 object-contain drop-shadow-sm">
                </nav>

                <!-- User Profile & Action Menu -->
                <div class="flex items-center space-x-2">
                    <?php if(auth()->guard()->check()): ?>
                        <!-- User Role Badge (Desktop) -->
                        <div class="hidden sm:flex items-center space-x-2.5 px-3 py-1.5 bg-slate-100/80 rounded-xl border border-slate-200">
                            <div class="w-6 h-6 rounded-lg <?php echo e(auth()->user()->isAdmin() ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white'); ?> flex items-center justify-center text-[10px] font-bold">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>
                            <div class="text-left text-xs leading-tight">
                                <span class="font-bold text-slate-800 block truncate max-w-[120px]"><?php echo e(auth()->user()->name); ?></span>
                                <span class="text-[10px] font-semibold <?php echo e(auth()->user()->isAdmin() ? 'text-blue-600' : 'text-emerald-600'); ?> uppercase">
                                    <?php echo e(auth()->user()->isAdmin() ? 'Administrator' : 'Staf / User'); ?>

                                </span>
                            </div>
                        </div>

                        <!-- Logout Button (Desktop) -->
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="hidden sm:inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" 
                                    title="Keluar dari sistem" 
                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                            </button>
                        </form>

                        <!-- Mobile Role Pill (Compact) -->
                        <span class="sm:hidden px-2 py-0.5 rounded-full text-[10px] font-bold <?php echo e(auth()->user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'); ?> uppercase">
                            <?php echo e(auth()->user()->isAdmin() ? 'Admin' : 'User'); ?>

                        </span>

                        <!-- Mobile Hamburger Button -->
                        <button type="button" 
                                @click="mobileMenuOpen = !mobileMenuOpen" 
                                class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors"
                                aria-label="Toggle Navigation">
                            <i :class="mobileMenuOpen ? 'fa-solid fa-xmark text-lg' : 'fa-solid fa-bars text-lg'"></i>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-xs">
                            Masuk
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <?php if(auth()->guard()->check()): ?>
        <div x-show="mobileMenuOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-2 shadow-lg"
             @click.away="mobileMenuOpen = false">

            <!-- User Info Card in Drawer -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 flex items-center space-x-3 mb-2">
                <div class="w-9 h-9 rounded-xl <?php echo e(auth()->user()->isAdmin() ? 'bg-blue-600 text-white' : 'bg-emerald-600 text-white'); ?> flex items-center justify-center font-bold text-sm">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-800 truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-[11px] text-slate-500 truncate"><?php echo e(auth()->user()->email); ?></p>
                    <span class="inline-block mt-0.5 text-[9px] font-bold px-1.5 py-0.2 rounded <?php echo e(auth()->user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'); ?> uppercase">
                        <?php echo e(auth()->user()->isAdmin() ? 'Administrator (Akses Penuh)' : 'Staf / User (Lihat, Import & Ekspor)'); ?>

                    </span>
                </div>
            </div>

            <!-- Links -->
            <a href="<?php echo e(route('inventaris.index')); ?>" 
               class="flex items-center space-x-2.5 p-2.5 rounded-xl text-xs font-semibold <?php echo e(request()->routeIs('inventaris.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-100'); ?>">
                <i class="fa-solid fa-table-list text-blue-600 w-5 text-center"></i>
                <span>Daftar Buku Inventaris</span>
            </a>

            <a href="<?php echo e(route('inventaris.create')); ?>" 
               class="flex items-center space-x-2.5 p-2.5 rounded-xl text-xs font-semibold <?php echo e(request()->routeIs('inventaris.create') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-100'); ?>">
                <i class="fa-solid fa-circle-plus text-emerald-600 w-5 text-center"></i>
                <span>Catat / Tambah Barang Baru</span>
            </a>

            <?php if(auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('profil-sekolah.edit')); ?>" 
                   class="flex items-center space-x-2.5 p-2.5 rounded-xl text-xs font-semibold <?php echo e(request()->routeIs('profil-sekolah.edit') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-100'); ?>">
                    <i class="fa-solid fa-stamp text-amber-600 w-5 text-center"></i>
                    <span>Lembar Pengesahan Kepala Sekolah</span>
                </a>
                <a href="<?php echo e(route('berita-acara.index')); ?>" 
                   class="flex items-center space-x-2.5 p-2.5 rounded-xl text-xs font-semibold <?php echo e(request()->routeIs('berita-acara.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-100'); ?>">
                    <i class="fa-solid fa-file-contract text-indigo-600 w-5 text-center"></i>
                    <span>Buat Berita Acara (BAST)</span>
                </a>
            <?php endif; ?>

            <!-- Logout Link -->
            <div class="pt-2 border-t border-slate-100">
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                            class="w-full flex items-center space-x-2.5 p-2.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-5 text-center"></i>
                        <span>Keluar dari Akun (Logout)</span>
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-[1600px] mx-auto w-full px-3 sm:px-4 lg:px-6 py-4 sm:py-5 pb-20 md:pb-6">
        <!-- Flash Alert Messages -->
        <?php if(session('success')): ?>
            <div class="mb-4 sm:mb-6 flex items-center p-3.5 sm:p-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-xs transition-all text-xs sm:text-sm" role="alert">
                <i class="fa-solid fa-circle-check text-lg sm:text-xl mr-2.5 text-emerald-600 shrink-0"></i>
                <div class="font-medium flex-1">
                    <?php echo e(session('success')); ?>

                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-4 sm:mb-6 flex items-center p-3.5 sm:p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs transition-all text-xs sm:text-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation text-lg sm:text-xl mr-2.5 text-rose-600 shrink-0"></i>
                <div class="font-medium flex-1">
                    <?php echo e(session('error')); ?>

                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs text-xs sm:text-sm">
                <div class="flex items-center mb-1.5">
                    <i class="fa-solid fa-circle-xmark text-base mr-2 text-rose-600"></i>
                    <h3 class="font-bold text-rose-900">Terdapat kesalahan:</h3>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Mobile Bottom Navigation Bar (App-like UX) -->
    <?php if(auth()->guard()->check()): ?>
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-3 py-1.5 flex items-center justify-around shadow-lg">
        <a href="<?php echo e(route('inventaris.index')); ?>" 
           class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors <?php echo e(request()->routeIs('inventaris.index') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'); ?>">
            <i class="fa-solid fa-table-list text-base mb-0.5"></i>
            <span class="text-[10px]">Inventaris</span>
        </a>

        <a href="<?php echo e(route('inventaris.create')); ?>" 
           class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors <?php echo e(request()->routeIs('inventaris.create') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'); ?>">
            <i class="fa-solid fa-circle-plus text-base mb-0.5 text-emerald-600"></i>
            <span class="text-[10px]">Catat</span>
        </a>

        <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('profil-sekolah.edit')); ?>" 
               class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors <?php echo e(request()->routeIs('profil-sekolah.edit') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'); ?>">
                <i class="fa-solid fa-stamp text-base mb-0.5 text-amber-600"></i>
                <span class="text-[10px]">Pengesahan</span>
            </a>
            <a href="<?php echo e(route('berita-acara.index')); ?>" 
               class="flex flex-col items-center py-1 px-3 rounded-lg transition-colors <?php echo e(request()->routeIs('berita-acara.index') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'); ?>">
                <i class="fa-solid fa-file-contract text-base mb-0.5 text-indigo-600"></i>
                <span class="text-[10px]">BAST</span>
            </a>
        <?php endif; ?>

        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex flex-col items-center py-1 px-3 rounded-lg text-slate-500 hover:text-rose-600 transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket text-base mb-0.5"></i>
                <span class="text-[10px]">Keluar</span>
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 sm:py-6 text-center text-xs text-slate-500">
        <div class="max-w-[1600px] mx-auto px-4">
            <p>Sistem Informasi Buku Inventaris Barang (BIB) &bull; SMK Negeri 1 Beringin</p>
        </div>
    </footer>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /www/wwwroot/sarpras.tiksmkn1beringin.my.id/resources/views/layouts/app.blade.php ENDPATH**/ ?>