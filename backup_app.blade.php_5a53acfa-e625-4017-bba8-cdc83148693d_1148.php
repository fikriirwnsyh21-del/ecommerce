<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kebutuhan Gaming Gen Z') - Tempatnya Gear Gaming & Tech Original</title>
    <meta name="description" content="@yield('meta_description', 'Pusat belanja online spesialis gear gaming, laptop gaming RTX, komponen PC rakitan, monitor 240Hz, keyboard mechanical, konsol PS5, dan aksesoris elektronik terbaik di Kebutuhan Gaming Gen Z.')">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Kebutuhan Gaming Gen Z')">
    <meta property="og:description" content="@yield('meta_description', 'Pusat Belanja Online Gear Gaming & Tech Spesialis Gen Z Terpercaya')">
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
              'ASUS ROG Zephyrus G16',
              'GeForce RTX 4080 Super',
              'Ryzen 7 7800X3D',
              'PlayStation 5 Slim',
              'Mechanical Keyboard 75%',
              'Logitech G PRO X Superlight',
              'Monitor OLED 240Hz',
              'Steam Deck OLED'
          ]
      }">

    <!-- Top Notice Bar -->
    <div class="bg-gray-900 text-gray-300 text-xs py-1.5 px-4 hidden md:block border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-emerald-400 font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Kebutuhan Gaming Gen Z Official
                </span>
                <span class="text-gray-700">•</span>
                <span>100% Produk Original & Bergaransi Resmi</span>
                <span class="text-gray-700">•</span>
                <span class="text-amber-400 font-semibold">Gratis Ongkir & Proteksi Packing Kayu S&K Berlaku</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-rose-400 text-rose-300 font-semibold transition flex items-center gap-1">
                    <span>🔥 Flash Sale</span>
                </a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('products.index') }}" class="hover:text-emerald-400 transition">Jelajahi Gear</a>
                <span class="text-gray-700">•</span>
                <a href="#" class="hover:text-emerald-400 transition">Pusat Garansi & FAQ</a>
                @auth
                    @if(auth()->user()->isSeller())
                        <span class="text-gray-700">•</span>
                        <a href="{{ route('seller.dashboard') }}" class="text-emerald-400 font-bold hover:underline">Seller Center</a>
                    @elseif(auth()->user()->isCustomer())
                        <span class="text-gray-700">•</span>
                        <a href="{{ route('register') }}?seller=1" class="text-emerald-400 font-semibold hover:underline">Buka Toko Official</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Sticky Main Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Row 1: Logo, Search, Actions -->
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-6">
                
                <!-- Left: Mobile Hamburger Button -->
                <button @click="mobileMenuOpen = true"
                        type="button"
                        class="md:hidden p-2 text-gray-700 hover:text-emerald-600 rounded-xl hover:bg-gray-100 transition"
                        title="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                        🎮
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-lg sm:text-xl tracking-tight text-gray-900 leading-none">
                                Kebutuhan<span class="text-emerald-500">Gaming</span>
                            </span>
                            <span class="px-1.5 py-0.5 bg-emerald-500 text-white font-black text-[9px] rounded-md tracking-wider uppercase shadow-2xs">Gen Z</span>
                        </div>
                        <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase mt-0.5">Gaming Gear & Tech Specialist</span>
                    </div>
                </a>

                <!-- Category Button & Mega Menu Dropdown -->
                <div class="relative hidden lg:block" @click.outside="categoryDropdownOpen = false">
                    <button @click="categoryDropdownOpen = !categoryDropdownOpen"
                            type="button"
                            class="flex items-center gap-1.5 px-3.5 py-2 text-sm font-bold text-gray-700 hover:text-emerald-600 rounded-xl hover:bg-emerald-50/70 border border-gray-200 transition shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span>Kategori</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-gray-400" :class="{'rotate-180': categoryDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Mega Menu Dropdown Content -->
                    <div x-show="categoryDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-full left-0 mt-2 w-[760px] bg-white rounded-3xl shadow-2xl border border-gray-100 p-6 z-50">
                        <div class="grid grid-cols-12 gap-6">
                            
                            <!-- Col 1: Kategori Utama -->
                            <div class="col-span-5 border-r border-gray-100 pr-4">
                                <div class="text-xs font-black text-gray-400 uppercase tracking-wider mb-3 flex items-center justify-between">
                                    <span>Kategori Gear Gaming</span>
                                    <span class="text-[10px] text-emerald-600 font-bold">10 Kategori</span>
                                </div>
                                <div class="space-y-0.5 max-h-80 overflow-y-auto pr-1">
                                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $cat)
                                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                            <span>{{ $cat->name }}</span>
                                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Col 2: Setup Battlestation Gen Z -->
                            <div class="col-span-4 border-r border-gray-100 pr-4">
                                <div class="text-xs font-black text-gray-400 uppercase tracking-wider mb-3">
                                    Setup Battlestation
                                </div>
                                <div class="space-y-2">
                                    <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="block p-2.5 rounded-2xl bg-gray-50 hover:bg-emerald-50 transition border border-gray-100">
                                        <p class="text-xs font-bold text-gray-800">⚡ Setup Esports Ready</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">High refresh 240Hz, Mouse 63g & Rapid Trigger</p>
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="block p-2.5 rounded-2xl bg-gray-50 hover:bg-emerald-50 transition border border-gray-100">
                                        <p class="text-xs font-bold text-gray-800">🎥 Streamer & Creator</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">Elgato Stream Deck, Mic Studio & Dual Cam</p>
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="block p-2.5 rounded-2xl bg-gray-50 hover:bg-emerald-50 transition border border-gray-100">
                                        <p class="text-xs font-bold text-gray-800">👑 Sultan 4K Ultimate</p>
                                        <p class="text-[11px] text-gray-500 mt-0.5">Zephyrus G16, RTX 4080 Super & OLED 49"</p>
                                    </a>
                                </div>
                            </div>

                            <!-- Col 3: Brand Partner & Promo -->
                            <div class="col-span-3 flex flex-col justify-between">
                                <div>
                                    <div class="text-xs font-black text-gray-400 uppercase tracking-wider mb-3">
                                        Brand Partner Resmi
                                    </div>
                                    <div class="grid grid-cols-2 gap-1.5 text-[11px] font-bold text-gray-600">
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">ASUS ROG</span>
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">Razer</span>
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">GeForce</span>
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">PlayStation</span>
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">Corsair</span>
                                        <span class="p-1.5 bg-gray-100 rounded-lg text-center">SteelSeries</span>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white shadow-md">
                                    <span class="text-[10px] font-black uppercase tracking-wider bg-white/20 px-1.5 py-0.5 rounded">VOUCHER</span>
                                    <p class="text-xs font-extrabold mt-1">Diskon Gamers 150K</p>
                                    <p class="text-[10px] text-emerald-100 mt-0.5">Klaim kode <strong class="font-mono text-white underline">GAMERSEJATI</strong></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modern Search Bar with Autocomplete & Quick Tags -->
                <div class="flex-1 max-w-2xl relative" @click.outside="autocompleteOpen = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full flex items-center">
                            <input type="text"
                                   name="q"
                                   x-model="searchQuery"
                                   @focus="autocompleteOpen = true"
                                   placeholder="Cari laptop gaming, VGA RTX 4080, keyboard mechanical, monitor..."
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

                    <!-- Trending Search Tags Under Search (Desktop) -->
                    <div class="hidden lg:flex items-center gap-1.5 mt-1.5 text-[11px] text-gray-400 overflow-hidden whitespace-nowrap">
                        <span class="font-bold text-gray-600 shrink-0">Trending:</span>
                        <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="text-rose-600 font-bold hover:underline shrink-0">🔥 Flash Sale</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'RTX 4080']) }}" class="hover:text-emerald-600 transition shrink-0">RTX 4080 Super</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'ROG Zephyrus']) }}" class="hover:text-emerald-600 transition shrink-0">ROG Zephyrus</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Ryzen 7']) }}" class="hover:text-emerald-600 transition shrink-0">Ryzen 7 7800X3D</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'PlayStation 5']) }}" class="hover:text-emerald-600 transition shrink-0">PS5 Slim</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Mechanical Keyboard']) }}" class="hover:text-emerald-600 transition shrink-0">Keyboard 75%</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Steam Deck']) }}" class="hover:text-emerald-600 transition shrink-0">Steam Deck OLED</a>
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

            <!-- Row 2: Comprehensive Sub-Header Navigation Menu Bar (Desktop) -->
            <nav class="hidden md:flex items-center justify-between py-2.5 border-t border-gray-100 text-xs font-semibold text-gray-700">
                <div class="flex items-center gap-4 lg:gap-5 overflow-x-auto no-scrollbar">
                    <!-- Menu 1: Flash Sale -->
                    <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="flex items-center gap-1.5 text-rose-600 hover:text-rose-700 font-black transition shrink-0">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        <span>Super Flash Sale</span>
                        <span class="px-1.5 py-0.2 bg-rose-100 text-rose-700 text-[9px] rounded font-black">HOT</span>
                    </a>

                    <!-- Menu 2: Laptop Gaming -->
                    <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>💻 Laptop Gaming</span>
                    </a>

                    <!-- Menu 3: PC & Komponen -->
                    <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🖥️ PC & Komponen</span>
                    </a>

                    <!-- Menu 4: Monitor Gaming -->
                    <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>📺 Monitor 240Hz</span>
                    </a>

                    <!-- Menu 5: Konsol & Handheld -->
                    <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🎮 Konsol & Handheld</span>
                    </a>

                    <!-- Menu 6: Keyboard Mechanical -->
                    <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>⌨️ Keyboard Mechanical</span>
                    </a>

                    <!-- Menu 7: Mouse Gaming -->
                    <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🖱️ Mouse Gaming</span>
                    </a>

                    <!-- Menu 8: Audio & Headset -->
                    <a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🎧 Audio & Headset</span>
                    </a>

                    <!-- Menu 9: Streaming Gear -->
                    <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🎥 Streaming Gear</span>
                    </a>

                    <!-- Menu 10: Kursi & Setup Meja -->
                    <a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="hover:text-emerald-600 transition shrink-0 flex items-center gap-1">
                        <span>🪑 Setup Meja</span>
                    </a>
                </div>

                <div class="flex items-center gap-3 shrink-0 pl-4 border-l border-gray-200">
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-emerald-600 hover:text-emerald-700 font-bold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Official Stores</span>
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-900 transition">
                        Semua Katalog
                    </a>
                </div>
            </nav>

        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="relative z-50 md:hidden" role="dialog" aria-modal="true">
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-xs"
             @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 flex">
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative mr-16 flex w-full max-w-xs flex-1">
                
                <div class="flex flex-col overflow-y-auto bg-white px-5 pb-6 pt-5 shadow-2xl w-full">
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white text-base shadow-xs">
                                🎮
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-sm tracking-tight text-gray-900 leading-none">Kebutuhan<span class="text-emerald-500">Gaming</span></span>
                                <span class="text-[9px] text-emerald-600 font-bold uppercase tracking-wider">Gen Z Official</span>
                            </div>
                        </a>
                        <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-gray-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Quick Menus -->
                    <div class="py-4 space-y-1 border-b border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Menu Navigasi</p>
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span>🏠 Beranda</span>
                        </a>
                        <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50">
                            <span>🔥 Super Flash Sale</span>
                            <span class="px-1.5 py-0.2 bg-rose-500 text-white text-[9px] font-black rounded">HOT</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50">
                            <span>📦 Semua Katalog Gear</span>
                        </a>
                        <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold text-emerald-600 hover:bg-emerald-50">
                            <span>⭐ Toko Mitra Resmi</span>
                        </a>
                    </div>

                    <!-- Categories List -->
                    <div class="py-4 space-y-1 border-b border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Kategori Gear Gaming</p>
                        @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->get() as $cat)
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                <span>{{ $cat->name }}</span>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>

                    <!-- Bottom Info & Promo -->
                    <div class="mt-auto pt-4 space-y-3 text-xs">
                        <div class="p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100">
                            <p class="font-bold text-emerald-900 mb-0.5">🎟️ Voucher Diskon Gamers</p>
                            <p class="text-[11px] text-emerald-700 font-mono font-bold">GAMERSEJATI (Potongan 150K)</p>
                        </div>
                        @guest
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('login') }}" class="py-2.5 text-center bg-gray-100 font-bold rounded-xl text-gray-800">Masuk</a>
                                <a href="{{ route('register') }}" class="py-2.5 text-center bg-emerald-500 font-bold text-white rounded-xl">Daftar</a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white font-black text-xl shadow-md">
                            🎮
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                                <span class="font-black text-xl tracking-tight text-gray-900 leading-none">
                                    Kebutuhan<span class="text-emerald-500">Gaming</span>
                                </span>
                                <span class="px-1.5 py-0.5 bg-emerald-500 text-white font-black text-[9px] rounded-md tracking-wider uppercase shadow-2xs">Gen Z</span>
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase mt-0.5">Gaming Gear & Tech Specialist</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed max-w-sm mb-4">
                        Marketplace spesialis perangkat elektronik dan gear gaming terlengkap untuk generasi Gen Z di Indonesia. Menghadirkan laptop gaming, komponen PC rakitan, monitor 240Hz, keyboard mechanical, konsol, dan peripheral 100% original bergaransi resmi.
                    </p>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200">100% Original Tech</span>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg border border-emerald-200">Garansi Resmi Brand</span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Belanja Gear</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="{{ route('products.index') }}" class="hover:text-emerald-600">Semua Katalog Produk</a></li>
                        <li><a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-emerald-600 text-rose-600 font-semibold">Super Flash Sale</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-emerald-600">Laptop Gaming RTX</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="hover:text-emerald-600">Komponen PC Rakitan</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="hover:text-emerald-600">PlayStation 5 & Handheld</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Bantuan & Garansi</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="#" class="hover:text-emerald-600">Klaim Garansi Resmi</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Panduan Pembayaran QRIS & VA</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Kebijakan Pengembalian Produk</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Syarat & Ketentuan Garansi</a></li>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-sm tracking-wide">Mitra Penjual</h3>
                    <ul class="space-y-2.5 text-xs text-gray-500">
                        <li><a href="{{ route('register') }}?seller=1" class="hover:text-emerald-600 font-semibold text-emerald-600">Buka Toko Official Gratis</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-600">Pusat Seller Center</a></li>
                        <li><a href="#" class="hover:text-emerald-600">Mitra Logistik Packing Kayu</a></li>
                    </ul>
                </div>
            </div>

            <!-- Payment & Security Icons -->
            <div class="pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} Kebutuhan Gaming Gen Z. All rights reserved. Tempatnya Gear Gaming & Elektronik Terpercaya.</p>
                <div class="flex items-center gap-3">
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">QRIS</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">BCA VA</span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-semibold">Mandiri</span>
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
