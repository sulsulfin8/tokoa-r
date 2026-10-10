<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko A&R - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        
        /* Modern Sidebar Micro-Interactions & Hover Animations */
        .sidebar-menu-link {
            position: relative;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }
        
        .sidebar-menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 4px;
            height: 65%;
            background: linear-gradient(180deg, #fbcfe8 0%, #f472b6 100%);
            border-radius: 0 4px 4px 0;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-menu-link:hover {
            transform: translateX(6px);
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.05) 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu-link:hover::before {
            transform: translateY(-50%) scaleY(1);
        }

        .sidebar-menu-link svg {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.25s ease;
        }

        .sidebar-menu-link:hover svg {
            transform: scale(1.18) rotate(3deg);
            color: #fbcfe8 !important;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.25));
        }

        /* Active State */
        .sidebar-active { 
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%) !important; 
            border-left: 4px solid #fbcfe8 !important; 
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.25) !important;
        }

        .sidebar-active svg {
            color: #fbcfe8 !important;
            transform: scale(1.08);
        }

        /* Tombol Logout Hover Effect */
        .btn-logout-effect {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-logout-effect:hover {
            transform: translateY(-2px);
            background: rgba(239, 68, 68, 0.25);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        .btn-logout-effect svg {
            transition: transform 0.3s ease;
        }
        .btn-logout-effect:hover svg {
            transform: translateX(4px);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="text-gray-800 antialiased selection:bg-pink-300 selection:text-pink-900 flex h-screen overflow-hidden bg-pink-50">

    <!-- Sidebar -->
    <aside class="w-[280px] bg-gradient-to-b from-pink-800 to-pink-950 text-white flex flex-col justify-between hidden md:flex transition-all duration-300 shadow-[4px_0_24px_rgba(131,24,67,0.15)] z-20">
        <div class="flex-1 overflow-y-auto no-scrollbar pb-8">
            <div class="h-24 flex items-center px-8 border-b border-white/10 mb-4 sticky top-0 bg-pink-800 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full overflow-hidden flex items-center justify-center shadow-lg shadow-pink-900/40 bg-white/10 border-2 border-pink-200/30">
                        <img src="{{ asset('storage/Logo.jpeg') }}" alt="A&R Logo" class="w-full h-full object-cover scale-[1.35]">
                    </div>
                    <h1 class="text-xl font-extrabold tracking-widest text-white drop-shadow-md">TOKO A&R</h1>
                </div>
            </div>
            
            <nav class="mt-2">
                <ul class="space-y-1.5 px-4 text-[13px] tracking-wide font-medium">
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'pemilik']))
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <li>
                        <a href="{{ route('bahan-baku.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('bahan-baku.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Bahan Baku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pemasok.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('pemasok.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Pemasok
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'reseller']))
                    <li>
                        <a href="{{ route('produk.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('produk.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Produk (Kue)
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <li>
                        <a href="{{ route('reseller.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('reseller.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Reseller
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stok-masuk.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('stok-masuk.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Stok Masuk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('penggunaan-bahan.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('penggunaan-bahan.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Penggunaan Bahan Baku
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'reseller']))
                    <li>
                        <a href="{{ route('penjualan.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('penjualan.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Penjualan
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'pemilik']))
                    <li>
                        <a href="{{ route('eoq.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('eoq.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Kalkulator EOQ
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && auth()->user()->role == 'admin')
                    <li>
                        <a href="{{ route('sma.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('sma.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            Prediksi SMA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perencanaan.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('perencanaan.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Perencanaan Produksi
                        </a>
                    </li>
                    @endif
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'pemilik']))
                    <li>
                        <a href="{{ route('laporan.index') }}" class="sidebar-menu-link flex items-center px-4 py-3 rounded-xl {{ request()->routeIs('laporan.*') ? 'sidebar-active text-pink-100 font-bold' : 'text-pink-100/70 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        
        <div class="p-6 border-t border-white/10 bg-pink-950">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout-effect flex items-center justify-center text-pink-200 hover:text-white transition w-full px-5 py-2.5 rounded-xl font-bold tracking-wide">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    LOG OUT
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Decoration blobs to give that elegant pink dominance without clashing -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-pink-300 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <!-- Topbar -->
        <header class="h-20 flex items-center justify-between px-8 bg-white/40 backdrop-blur-md sticky top-0 z-40 border-b border-pink-100/60 shadow-sm">
            <div class="flex-1 flex items-center">
                <!-- Sisi Kiri Header (Tombol Pencarian dihapus) -->
            </div>
            <div class="flex items-center gap-4">
                <!-- Ikon Notifikasi Stok Menipis -->
                <div class="relative z-50" id="notif-dropdown-wrapper">
                    <button type="button" onclick="toggleNotifDropdown()" id="notif-btn" class="relative p-2.5 text-pink-600 hover:text-pink-800 hover:bg-pink-100 transition bg-white/80 rounded-full shadow-[0_2px_8px_rgba(251,207,232,0.35)] border border-pink-100 focus:outline-none" title="Notifikasi Stok Menipis">
                        @if(isset($totalNotifStok) && $totalNotifStok > 0)
                            <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-gradient-to-r from-red-500 to-rose-600 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center border-2 border-white shadow-sm animate-pulse">
                                {{ $totalNotifStok }}
                            </span>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>

                    <!-- Dropdown Menu Notifikasi -->
                    <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-[0_20px_50px_rgba(131,24,67,0.25)] border border-pink-100 z-50 overflow-hidden transform transition-all duration-200 origin-top-right">
                        <!-- Header Dropdown -->
                        <div class="px-5 py-4 bg-gradient-to-r from-pink-800 to-pink-950 text-white flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                <h4 class="font-extrabold text-sm tracking-wide">Notifikasi Stok</h4>
                            </div>
                            @if(isset($totalNotifStok) && $totalNotifStok > 0)
                                <span class="bg-rose-500 text-white text-xs px-2.5 py-0.5 rounded-full font-bold shadow-xs">
                                    {{ $totalNotifStok }} Menipis
                                </span>
                            @else
                                <span class="bg-green-500 text-white text-xs px-2.5 py-0.5 rounded-full font-bold shadow-xs">
                                    Aman
                                </span>
                            @endif
                        </div>

                        <!-- Content List Notifikasi -->
                        <div class="max-h-[380px] overflow-y-auto no-scrollbar divide-y divide-pink-50">
                            @if(isset($totalNotifStok) && $totalNotifStok > 0)
                                <!-- Kategori Bahan Baku (< 50) -->
                                @if(isset($notifBahanMenipis) && $notifBahanMenipis->count() > 0)
                                <div class="p-3 bg-pink-50/40">
                                    <div class="px-2 py-1 text-xs font-bold text-pink-900 uppercase tracking-wider">
                                        <span>Bahan Baku (&lt; 50)</span>
                                    </div>
                                    <div class="mt-1.5 space-y-1.5">
                                        @foreach($notifBahanMenipis as $item)
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-pink-100 hover:border-pink-300 transition shadow-xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-xs font-bold text-gray-800 truncate">{{ $item->nama_bahan }}</p>
                                                    <p class="text-[11px] text-rose-500 font-medium">Stok bahan menipis</p>
                                                </div>
                                            </div>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-red-100 text-red-600 border border-red-200 shrink-0">
                                                {{ $item->stok }} {{ $item->satuan }}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Kategori Produk (< 10) -->
                                @if(isset($notifProdukMenipis) && $notifProdukMenipis->count() > 0)
                                <div class="p-3 bg-pink-50/20">
                                    <div class="px-2 py-1 text-xs font-bold text-pink-900 uppercase tracking-wider">
                                        <span>Produk Kue (&lt; 10)</span>
                                    </div>
                                    <div class="mt-1.5 space-y-1.5">
                                        @foreach($notifProdukMenipis as $item)
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-pink-100 hover:border-pink-300 transition shadow-xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-xs font-bold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                                                    <p class="text-[11px] text-amber-600 font-medium">Stok siap jual menipis</p>
                                                </div>
                                            </div>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-200 shrink-0">
                                                {{ $item->stok }} pcs
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="py-10 px-4 text-center">
                                    <div class="w-12 h-12 mx-auto mb-2.5 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Semua Stok Aman</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Tidak ada bahan baku atau produk yang menipis.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="button" onclick="openProfileDrawer()" class="flex items-center gap-3 pl-4 border-l border-pink-200 hover:bg-white/60 py-1.5 px-2.5 rounded-2xl transition-all duration-200 group text-left cursor-pointer focus:outline-none" title="Klik untuk edit profil">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&background=fbcfe8&color=831843" alt="User" class="w-10 h-10 rounded-full shadow-md border-2 border-white group-hover:scale-105 transition-transform duration-200">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-bold text-gray-800 group-hover:text-pink-600 transition">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-pink-600 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                        <p class="text-xs text-pink-600 font-bold tracking-wide">{{ ucfirst(auth()->user()->role ?? '') }}</p>
                    </div>
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto px-8 pt-6 pb-12 no-scrollbar relative z-10">
            <!-- Toast Notifications (Modern & Professional) -->
            <div class="fixed top-6 right-6 z-[60] flex flex-col gap-4 pointer-events-none" id="toast-container">
                @if(session('success'))
                <div id="toast-success" class="pointer-events-auto w-96 transform transition-all duration-500 translate-x-full opacity-0 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-green-100 overflow-hidden" role="alert">
                    <div class="p-4 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-900 font-bold text-sm">Berhasil!</h4>
                            <p class="text-gray-600 text-sm mt-0.5 leading-relaxed">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="closeToast('toast-success')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="h-1 bg-gray-100 w-full relative">
                        <div class="absolute top-0 left-0 h-full bg-green-500 w-full animate-toast-progress"></div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div id="toast-error" class="pointer-events-auto w-96 transform transition-all duration-500 translate-x-full opacity-0 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-red-100 overflow-hidden" role="alert">
                    <div class="p-4 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-900 font-bold text-sm">Terjadi Kesalahan!</h4>
                            <p class="text-gray-600 text-sm mt-0.5 leading-relaxed">{{ session('error') }}</p>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="closeToast('toast-error')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="h-1 bg-gray-100 w-full relative">
                        <div class="absolute top-0 left-0 h-full bg-red-500 w-full animate-toast-progress"></div>
                    </div>
                </div>
                @endif
            </div>

            <style>
                @keyframes toast-progress {
                    0% { width: 100%; }
                    100% { width: 0%; }
                }
                .animate-toast-progress {
                    animation: toast-progress 4s linear forwards;
                }
            </style>

            <script>
                // Slide in animation on load
                window.addEventListener('DOMContentLoaded', () => {
                    setTimeout(() => {
                        const success = document.getElementById('toast-success');
                        const error = document.getElementById('toast-error');
                        if (success) { success.classList.remove('translate-x-full', 'opacity-0'); }
                        if (error) { error.classList.remove('translate-x-full', 'opacity-0'); }
                    }, 100);

                    // Auto hide after 4 seconds
                    setTimeout(() => closeToast('toast-success'), 4000);
                    setTimeout(() => closeToast('toast-error'), 4000);
                });

                function closeToast(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        el.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => el.remove(), 500);
                    }
                }
            </script>

            @yield('content')
        </div>
    </main>

    <!-- Slide-over Drawer Edit Profil (Pojok Kanan) -->
    <div id="profile-drawer-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeProfileDrawer()"></div>
    
    <div id="profile-drawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-[-12px_0_40px_rgba(0,0,0,0.2)] z-50 transform translate-x-full transition-transform duration-300 ease-out flex flex-col overflow-hidden">
        <!-- Header Drawer -->
        <div class="p-6 bg-gradient-to-r from-pink-800 to-pink-950 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-pink-100 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold tracking-tight">Edit Profil Saya</h3>
                    <p class="text-xs text-pink-200">Perbarui data diri & kata sandi akun</p>
                </div>
            </div>
            <button type="button" onclick="closeProfileDrawer()" class="p-2 rounded-xl text-pink-200 hover:text-white hover:bg-white/10 transition" title="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body Drawer / Form Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 no-scrollbar">
            <!-- Profil Avatar Card -->
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-pink-50/70 border border-pink-100">
                <div class="relative flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&background=831843&color=ffffff&size=128" alt="Avatar" class="w-14 h-14 rounded-full shadow-md border-2 border-white object-cover">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-gray-800 text-base leading-snug truncate">{{ auth()->user()->name ?? 'Guest' }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Username: <span class="font-semibold text-gray-700">{{ auth()->user()->username ?? '-' }}</span></p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-pink-600 text-white shadow-sm">
                        {{ auth()->user()->role ?? 'User' }}
                    </span>
                </div>
            </div>

            @if(isset($errors) && $errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-600 space-y-1">
                <p class="font-bold flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Terdapat kesalahan input:
                </p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username ?? '') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="contoh@email.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium">
                    <p class="text-[11px] text-gray-400 mt-1">*Opsional, bisa diisi untuk notifikasi</p>
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800">
                    <p class="text-[11px] text-gray-400 mt-1">*Minimal 6 karakter jika ingin mengganti</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800">
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" onclick="closeProfileDrawer()" class="flex-1 py-2.5 px-4 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition text-sm text-center">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl bg-pink-600 hover:bg-pink-700 text-white font-bold transition shadow-sm hover:shadow-md text-sm text-center">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProfileDrawer() {
            const backdrop = document.getElementById('profile-drawer-backdrop');
            const drawer = document.getElementById('profile-drawer');
            if (backdrop && drawer) {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
                drawer.classList.remove('translate-x-full');
                drawer.classList.add('translate-x-0');
            }
        }

        function closeProfileDrawer() {
            const backdrop = document.getElementById('profile-drawer-backdrop');
            const drawer = document.getElementById('profile-drawer');
            if (backdrop && drawer) {
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.remove('translate-x-0');
                drawer.classList.add('translate-x-full');
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfileDrawer();
            }
        });

        // Dropdown Notifikasi Stok
        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notif-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-dropdown-wrapper');
            const dropdown = document.getElementById('notif-dropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        @if(isset($errors) && $errors->any())
        window.addEventListener('DOMContentLoaded', function() {
            openProfileDrawer();
        });
        @endif
    </script>
</body>
</html>
