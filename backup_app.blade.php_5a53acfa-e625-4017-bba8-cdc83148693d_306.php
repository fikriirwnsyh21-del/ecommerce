<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PasarKeren') - Marketplace Belanja Online Modern & Aman</title>
    <meta name="description" content="@yield('meta_description', 'Belanja online berbagai produk elektronik, fashion, gadget, otomotif dan kebutuhan rumah tangga dengan harga terbaik, promo flash sale, dan garansi resmi di PasarKeren.')">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'PasarKeren Marketplace')">
    <meta property="og:description" content="@yield('meta_description', 'Marketplace Belanja Online Modern & Aman')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[#212121] flex flex-col justify-between pb-16 md:pb-0"
      x-data="{
          mobileMenuOpen: false,
          categoryDropdownOpen: false,
          userDropdownOpen: false,
          searchQuery: '{{ request('q') }}',
          selectedCategory: '{{ request('category') }}',
          autocompleteOpen: false,
          suggestions: [
              'Smartphone 5G',
              'Headphones ANC',
              'Laptop ZenBook',
              'Mechanical Keyboard',
              'Sneakers Running',
              'Smart TV 4K',
              'Air Purifier HEPA'
          ]
      }">

    <!-- Top Notice Bar -->
    <div class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 py-1.5 px-4 hidden md:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span>Download Aplikasi PasarKeren</span>
                <span>•</span>
                <span>Mitra Seller Resmi</span>
                <span>•</span>
                <span class="text-emerald-600 font-semibold">Gratis Ongkir Se-Indonesia S&K Berlaku</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('products.index') }}" class="hover:text-emerald-600">Jelajahi Produk</a>
                <span>•</span>
                <a href="#" class="hover:text-emerald-600">Bantuan & FAQ</a>
                @auth
                    @if(auth()->user()->isSeller())
                        <span>•</span>
                        <a href="{{ route('seller.dashboard') }}" class="text-emerald-600 font-bold hover:underline">Dashboard Toko</a>
                    @elseif(auth()->user()->isCustomer())
                        <span>•</span>
                        <a href="{{ route('register') }}?seller=1" class="text-emerald-600 font-semibold hover:underline">Buka Toko Gratis</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Sticky Main Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-6">
                
                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black text-2xl shadow-md group-hover:scale-105 transition-transform">
                        P
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-xl sm:text-2xl tracking-tight text-emerald-500 leading-none">Pasar<span class="text-[#212121]">Keren</span></span>
                        <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase mt-0.5">Marketplace</span>
                    </div>
                </a>

                <!-- Category Button & Mega Menu Dropdown -->
                <div class="relative hidden lg:block" @click.outside="categoryDropdownOpen = false">
                    <button @click="categoryDropdownOpen = !categoryDropdownOpen"
                            type="button"
                            class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-gray-700 hover:text-emerald-600 rounded-xl hover:bg-emerald-50/50 transition">
                        <span>Kategori</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': categoryDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="categoryDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Pilihan Kategori
                        </div>
                        <div class="max-h-96 overflow-y-auto py-1">
                            @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                   class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition">
                                    <span>{{ $cat->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modern Search Bar with Autocomplete -->
                <div class="flex-1 max-w-2xl relative" @click.outside="autocompleteOpen = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full flex items-center">
                            <input type="text"
                                   name="q"
                                   x-model="searchQuery"
                                   @focus="autocompleteOpen = true"
                                   placeholder="Cari produk impian, merek, atau toko di sini..."
                                   class="w-full pl-4 pr-12 py-2.5 sm:py-3 bg-gray-50 hover:bg-gray-100/70 focus:bg-white text-sm text-gray-900 rounded-xl border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition outline-hidden">
                            
                            <button type="submit"
                                    class="absolute right-1.5 sm:right-2 top-1.5 sm:top-2 bottom-1.5 sm:bottom-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg flex items-center justify-center transition shadow-xs"
                                    title="Cari">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Autocomplete Suggestions Dropdown -->
                    <div x-show="autocompleteOpen && searchQuery.length > 0" x-cloak
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">
                        <div class="px-4 py-1.5 text-xs text-gray-400 font-bold uppercase tracking-wider">
                            Rekomendasi Pencarian
                        </div>
                        <template x-for="item in suggestions.filter(s => s.toLowerCase().includes(searchQuery.toLowerCase()))" :key="item">
                            <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(item)"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span x-text="item"></span>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Actions: Wishlist, Cart & Profile -->
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                    
                    @auth
                        <!-- Wishlist Icon -->
                        @php
                            $wishlistCount = auth()->user()->wishlists()->count();
                        @endphp
                        <a href="{{ route('wishlist.index') }}"
                           class="relative p-2 text-gray-600 hover:text-rose-500 rounded-xl hover:bg-gray-100 transition hidden sm:flex items-center justify-center"
                           title="Wishlist Saya">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @if($wishlistCount > 0)
                                <span class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart Icon -->
                        @php
                            $cartCount = auth()->user()->cart?->total_count ?? 0;
                        @endphp
                        <a href="{{ route('cart.index') }}"
                           class="relative p-2 text-gray-600 hover:text-emerald-600 rounded-xl hover:bg-gray-100 transition flex items-center justify-center"
                           title="Keranjang Belanja">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @if($cartCount > 0)
                                <span class="absolute top-1 right-1 w-5 h-5 rounded-full bg-emerald-500 text-white text-[10px] font-extrabold flex items-center justify-center shadow-xs">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative" @click.outside="userDropdownOpen = false">
                            <button @click="userDropdownOpen = !userDropdownOpen"
                                    type="button"
                                    class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition focus:outline-hidden">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center border border-emerald-300">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-semibold text-gray-800 hidden md:block max-w-[100px] truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Profile Menu -->
                            <div x-show="userDropdownOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    <div class="mt-1.5">
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                            {{ auth()->user()->role->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        Wishlist
                                    </a>
                                    <a href="{{ route('customer.addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Daftar Alamat
                                    </a>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <div class="py-1 border-t border-gray-100">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-emerald-600 font-bold hover:bg-emerald-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Admin Dashboard
                                        </a>
                                    </div>
                                @elseif(auth()->user()->isSeller())
                                    <div class="py-1 border-t border-gray-100">
                                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-emerald-600 font-bold hover:bg-emerald-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            Dashboard Toko
                                        </a>
                                    </div>
                                @endif

                                <div class="pt-1 border-t border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-semibold transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                               class="px-3 sm:px-4 py-2 text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl border border-emerald-500 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                               class="px-3 sm:px-4 py-2 text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl shadow-xs transition hidden sm:inline-block">
                                Daftar
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Marketplace Modern Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16 pt-12 pb-8 text-sm text-gray-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 mb-12">
                <!-- Col 1 -->
                <div class="col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black text-xl shadow-md">
                            P
                        </div>
                        <span class="font-black text-2xl tracking-tight text-emerald-500">Pasar<span class="text-[#212121]">Keren</span></span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-sm mb-4">
                        Platform e-commerce marketplace modern Indonesia yang menyediakan ribuan pilihan produk original, bergaransi resmi, dan transaksi aman terlindungi.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200">100% Original</span>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200">Garansi Uang Kembali</span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Belanja</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="{{ route('products.index') }}" class="hover:text-emerald-600">Katalog Lengkap</a></li>
                        <li><a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-emerald-600">Flash Sale Spesial</a></li>
                        <li><a href="{{ route('products.index', ['sort' => 'popular']) }}" class="hover:text-emerald-600">Produk Terlaris</a></li>
                        <li><a href="{{ route('products.index', ['sort' => 'newest']) }}" class="hover:text-emerald-600">Produk Terbaru</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Bantuan & Panduan</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="#" class="hover:text-emerald-600">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Panduan Pembelian</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Panduan Pengembalian</a></li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Mitra Penjual</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="{{ route('register') }}?seller=1" class="hover:text-emerald-600">Buka Toko Gratis</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-600">Pusat Edukasi Seller</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Mitra Logistik Resmi</a></li>
                    </ul>
                </div>
            </div>

            <!-- Payment & Security Icons -->
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} PasarKeren Marketplace. All rights reserved. Dikembangkan dengan Laravel 13.</p>
                <div class="flex items-center gap-3">
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">BCA</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">Mandiri</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">QRIS</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">GoPay</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">COD</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 md:hidden flex items-center justify-around py-2 px-1 shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('home') ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('products.*') ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Kategori</span>
        </a>
        <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('cart.*') ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Keranjang</span>
            @auth
                @if(($cartCount ?? 0) > 0)
                    <span class="absolute -top-1 right-3 w-4 h-4 bg-emerald-500 text-white rounded-full text-[9px] font-bold flex items-center justify-center">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            @endauth
        </a>
        <a href="{{ auth()->check() ? route('customer.orders.index') : route('login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('customer.orders.*') ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Pesanan</span>
        </a>
        <a href="{{ auth()->check() ? route('customer.profile') : route('login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('customer.profile*') ? 'text-emerald-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Akun</span>
        </a>
    </nav>

    <!-- Global Toast Component -->
    <x-toast />

    @stack('scripts')
</body>
</html>
