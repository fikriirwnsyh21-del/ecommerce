<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 overflow-x-hidden text-slate-850">
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
<body class="min-h-screen font-sans antialiased text-slate-800 flex flex-col justify-between pb-16 md:pb-0 overflow-x-hidden"
      x-data="{
          mobileMenuOpen: false,
          categoryDropdownOpen: false,
          userDropdownOpen: false,
          searchQuery: '{{ request('q') }}',
          selectedCategory: '{{ request('category') }}',
          autocompleteOpen: false,
          suggestions: [
              'ASUS ROG Zephyrus G16',
              'GeForce RTX 4090 Rog Strix',
              'AMD Ryzen 7 7800X3D',
              'Wooting 60HE+ Rapid Trigger',
              'Logitech G PRO X Superlight 2',
              'PlayStation 5 Pro Digital',
              'Steam Deck OLED 512GB',
              'Samsung Odyssey OLED G8 240Hz',
              'Moondrop Blessing 3 IEM',
              'Elgato Stream Deck MK.2',
              'Secretlab TITAN Evo',
              'Logitech G923 TrueForce Racing',
              'Corsair Dominator Titanium DDR5',
              'Kingston KC3000 PCIe 4.0 SSD',
              'PC Rakitan RTX 4080 Super',
              'Lian Li O11 Dynamic EVO'
          ]
      }">

    <!-- Top Notice Bar (Sleek Dark Slate with Emerald & Amber Highlights) -->
    <div class="bg-slate-950 text-slate-300 text-[11px] py-1.5 px-4 hidden md:block border-b border-slate-800/80">
        <div class="max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-emerald-400 font-extrabold tracking-tight">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Kebutuhan Gaming Gen Z Official Store
                </span>
                <span class="text-slate-700">•</span>
                <span class="text-slate-300 font-medium">100% Produk Original & Bergaransi Distributor Resmi</span>
                <span class="text-slate-700">•</span>
                <span class="text-amber-400 font-semibold flex items-center gap-1">
                    <span>⚡ Bebas Ongkir & Packing Kayu Aman</span>
                </span>
            </div>
            <div class="flex items-center gap-3.5">
                <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-rose-400 text-rose-300 font-semibold transition flex items-center gap-1">
                    <span>🔥 Flash Sale</span>
                </a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('products.index', ['q' => 'PC Rakitan']) }}" class="hover:text-emerald-400 transition flex items-center gap-1">
                    <span>🛠️ Rakit PC</span>
                </a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('products.index', ['q' => 'Voucher']) }}" class="hover:text-amber-300 text-amber-300 font-medium transition flex items-center gap-1">
                    <span>🎟️ Voucher 150K</span>
                </a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('products.index') }}" class="hover:text-emerald-400 transition">Semua Gear</a>
                <span class="text-slate-700">•</span>
                <a href="#" class="hover:text-emerald-400 transition">Pusat Garansi & FAQ</a>
                @auth
                    @if(auth()->user()->isSeller())
                        <span class="text-slate-700">•</span>
                        <a href="{{ route('seller.dashboard') }}" class="text-emerald-400 font-bold hover:underline">Seller Center</a>
                    @elseif(auth()->user()->isCustomer())
                        <span class="text-slate-700">•</span>
                        <a href="{{ route('register') }}?seller=1" class="text-emerald-400 font-semibold hover:underline">Buka Toko Official</a>
                    @endif
                @else
                    <span class="text-slate-700">•</span>
                    <a href="{{ route('register') }}?seller=1" class="text-emerald-400 font-semibold hover:underline">Buka Toko Gratis</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Sticky Main Header (Clean Modern Glassmorphism & High Contrast) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-2xs">
        <div class="max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            
            <!-- Row 1: Logo, Search, Actions -->
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-6">
                
                <!-- Left: Mobile Hamburger Button -->
                <button @click="mobileMenuOpen = true"
                        type="button"
                        class="md:hidden p-2 text-slate-700 hover:text-emerald-600 rounded-xl hover:bg-slate-100 transition"
                        title="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white font-black text-xl shadow-sm group-hover:scale-105 transition-transform">
                        🎮
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-lg sm:text-xl tracking-tight text-slate-900 leading-none">
                                Kebutuhan<span class="text-emerald-500">Gaming</span>
                            </span>
                            <span class="px-1.5 py-0.5 bg-emerald-500 text-white font-black text-[9px] rounded-md tracking-wider uppercase shadow-2xs">Gen Z</span>
                        </div>
                        <span class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">Gaming Gear & Tech Specialist</span>
                    </div>
                </a>

                <!-- Category Button & Expanded 4-Column Mega Menu -->
                <div class="relative hidden lg:block" @click.outside="categoryDropdownOpen = false">
                    <button @click="categoryDropdownOpen = !categoryDropdownOpen"
                            type="button"
                            class="flex items-center gap-2 px-3.5 py-2.5 text-xs font-bold text-slate-700 hover:text-emerald-600 rounded-xl hover:bg-emerald-50/60 border border-slate-200/90 transition shadow-2xs">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span>Semua Kategori</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 text-slate-400" :class="{'rotate-180': categoryDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Clean 5-Column Mega Menu Dropdown -->
                    <div x-show="categoryDropdownOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-98"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-full left-0 mt-2 w-[1040px] xl:w-[1140px] 2xl:w-[1240px] max-w-[calc(100vw-2rem)] bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 z-50">
                        <div class="grid grid-cols-5 gap-4 xl:gap-5">
                            
                            <!-- Col 1: Sistem, Laptop & PC -->
                            <div class="border-r border-slate-100 pr-3">
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <span>💻 Sistem & PC Rakitan</span>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Laptop Gaming RTX
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        PC Rakitan Gaming Ready
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Komponen PC & Motherboard
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'GeForce RTX']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Kartu Grafis RTX 40 Series
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'Ryzen 7']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Processor AMD & Intel
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="block p-2 rounded-xl bg-emerald-50/80 border border-emerald-100 text-[11px] font-bold text-emerald-800 hover:bg-emerald-100/70 transition mt-2">
                                        ⚡ Simulasi Rakit PC Gratis
                                    </a>
                                </div>
                            </div>

                            <!-- Col 2: Storage & Power Cooling -->
                            <div class="border-r border-slate-100 pr-3">
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <span>⚡ Storage & Hardware</span>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('products.index', ['category' => 'ram-ssd-storage']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        RAM DDR5 & SSD NVMe
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'cooling-power-supply']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        AIO Cooler & PSU 80+
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'KC3000']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        SSD PCIe 4.0 Super Cepat
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'Dominator']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        RAM DDR5 6000MHz RGB
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'Kraken']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Liquid AIO dengan LCD
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'ram-ssd-storage']) }}" class="block p-2 rounded-xl bg-teal-50/80 border border-teal-100 text-[11px] font-bold text-teal-800 hover:bg-teal-100/70 transition mt-2">
                                        🚀 Upgrade Speed NVMe SSD
                                    </a>
                                </div>
                            </div>

                            <!-- Col 3: Peripheral & Esports Pro -->
                            <div class="border-r border-slate-100 pr-3">
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <span>⌨️ Periferal Esports</span>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Keyboard Rapid Trigger & Custom
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Mouse Ultralight 4K/8K Hz
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Monitor 240Hz & OLED 0.03ms
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Headset 7.1 & IEM Audiophile
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'aksesoris-gaming']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Mousepad, Switch & Modding
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'Wooting']) }}" class="block p-2 rounded-xl bg-indigo-50/80 border border-indigo-100 text-[11px] font-bold text-indigo-800 hover:bg-indigo-100/70 transition mt-2">
                                        🏆 Gear Standar Turnamen
                                    </a>
                                </div>
                            </div>

                            <!-- Col 4: Konsol, Handheld & Setup -->
                            <div class="border-r border-slate-100 pr-3">
                                <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <span>🎮 Konsol & Room Setup</span>
                                </div>
                                <div class="space-y-1">
                                    <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        PlayStation 5 & PC Handheld
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'smartphone-gaming']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        HP Gaming ROG & Cooler
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Elgato Stream Deck & Mic XLR
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'racing-sim-vr']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Racing Sim Wheel & VR Headset
                                    </a>
                                    <a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="block px-2 py-1.5 rounded-lg text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                        Kursi Gaming & Meja Elektrik
                                    </a>
                                    <a href="{{ route('products.index', ['q' => 'G923']) }}" class="block p-2 rounded-xl bg-amber-50/80 border border-amber-100 text-[11px] font-bold text-amber-800 hover:bg-amber-100/70 transition mt-2">
                                        🏎️ Sim Racing Kokpit Imersif
                                    </a>
                                </div>
                            </div>

                            <!-- Col 5: Brand Partner & Voucher Promo -->
                            <div class="flex flex-col justify-between">
                                <div>
                                    <div class="text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2.5">
                                        Brand Partner Resmi
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 text-[9px] font-bold text-slate-600 mb-3">
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">ASUS ROG</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">Razer</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">GeForce</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">PS5 Pro</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">Logitech</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">Corsair</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">Steam Deck</span>
                                        <span class="p-1 bg-slate-50 border border-slate-100 rounded text-center">Elgato</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl text-white shadow-md">
                                    <span class="text-[9px] font-black uppercase tracking-wider bg-white/20 px-1.5 py-0.5 rounded">VOUCHER GEN Z</span>
                                    <p class="text-xs font-black mt-1.5">Klaim Diskon 150K</p>
                                    <p class="text-[10px] text-emerald-100 mt-0.5">Kode: <strong class="font-mono text-white underline">GAMERSEJATI</strong></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modern Clean Search Bar with Autocomplete & Quick Tags -->
                <div class="flex-1 max-w-3xl xl:max-w-4xl 2xl:max-w-5xl relative" @click.outside="autocompleteOpen = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full flex items-center">
                            <input type="text"
                                   name="q"
                                   x-model="searchQuery"
                                   @focus="autocompleteOpen = true"
                                   placeholder="Cari laptop gaming RTX, keyboard mechanical, monitor 240Hz, PS5..."
                                   class="w-full pl-4 pr-12 py-2.5 sm:py-2.5 bg-slate-50 hover:bg-slate-100/60 focus:bg-white text-xs sm:text-sm text-slate-900 rounded-xl border border-slate-250 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 transition outline-hidden shadow-2xs">
                            
                            <button type="submit"
                                    class="absolute right-1.5 top-1.5 bottom-1.5 px-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg flex items-center justify-center transition shadow-2xs"
                                    title="Cari">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Autocomplete Suggestions Dropdown -->
                    <div x-show="autocompleteOpen && searchQuery.length > 0" x-cloak
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50">
                        <div class="px-4 py-1.5 text-xs text-slate-400 font-bold uppercase tracking-wider">
                            Rekomendasi Pencarian Gear
                        </div>
                        <template x-for="item in suggestions.filter(s => s.toLowerCase().includes(searchQuery.toLowerCase()))" :key="item">
                            <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(item)"
                                class="flex items-center gap-3 px-4 py-2.5 text-xs sm:text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span x-text="item"></span>
                            </a>
                        </template>
                    </div>

                    <!-- Trending Search Tags Under Search (Desktop) -->
                    <div class="hidden lg:flex items-center gap-1.5 mt-1.5 text-[11px] text-slate-400 overflow-hidden whitespace-nowrap">
                        <span class="font-bold text-slate-500 shrink-0">Trending:</span>
                        <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="text-rose-600 font-bold hover:underline shrink-0">🔥 Flash Sale</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'RTX 4090']) }}" class="hover:text-emerald-600 transition shrink-0">RTX 4090 Strix</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Ryzen 7 7800X3D']) }}" class="hover:text-emerald-600 transition shrink-0">Ryzen 7 7800X3D</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Wooting 60HE']) }}" class="hover:text-emerald-600 transition shrink-0">Wooting 60HE+</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'PS5 Pro']) }}" class="hover:text-emerald-600 transition shrink-0">PS5 Pro Digital</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Steam Deck OLED']) }}" class="hover:text-emerald-600 transition shrink-0">Steam Deck OLED</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Superlight 2']) }}" class="hover:text-emerald-600 transition shrink-0">Superlight 2</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'Moondrop Blessing 3']) }}" class="hover:text-emerald-600 transition shrink-0">Moondrop Blessing 3</a>
                        <span>•</span>
                        <a href="{{ route('products.index', ['q' => 'G923']) }}" class="hover:text-emerald-600 transition shrink-0">Sim Racing G923</a>
                    </div>
                </div>

                <!-- Actions: Wishlist, Cart & Profile -->
                <div class="flex items-center gap-2 sm:gap-3.5 shrink-0">
                    
                    @auth
                        <!-- Wishlist Icon -->
                        @php
                            $wishlistCount = auth()->user()->wishlists()->count();
                        @endphp
                        <a href="{{ route('wishlist.index') }}"
                           class="relative p-2 text-slate-600 hover:text-rose-500 rounded-xl hover:bg-slate-100 transition hidden sm:flex items-center justify-center"
                           title="Wishlist Saya">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @if($wishlistCount > 0)
                                <span class="absolute top-0.5 right-0.5 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center shadow-xs">
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart Icon -->
                        @php
                            $cartCount = auth()->user()->cart?->total_count ?? 0;
                        @endphp
                        <a href="{{ route('cart.index') }}"
                           class="relative p-2 text-slate-600 hover:text-emerald-600 rounded-xl hover:bg-slate-100 transition flex items-center justify-center"
                           title="Keranjang Belanja">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @if($cartCount > 0)
                                <span class="absolute top-0.5 right-0.5 w-4 h-4 rounded-full bg-emerald-500 text-white text-[9px] font-black flex items-center justify-center shadow-xs">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative" @click.outside="userDropdownOpen = false">
                            <button @click="userDropdownOpen = !userDropdownOpen"
                                    type="button"
                                    class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-hidden">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center border border-emerald-300">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-bold text-slate-800 hidden md:block max-w-[100px] truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Profile Menu -->
                            <div x-show="userDropdownOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-100 py-3 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    <div class="mt-1.5">
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                            {{ auth()->user()->role->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="py-1">
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        Wishlist
                                    </a>
                                    <a href="{{ route('customer.addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Daftar Alamat
                                    </a>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <div class="py-1 border-t border-slate-100">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-emerald-600 font-bold hover:bg-emerald-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Admin Dashboard
                                        </a>
                                    </div>
                                @elseif(auth()->user()->isSeller())
                                    <div class="py-1 border-t border-slate-100">
                                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-emerald-600 font-bold hover:bg-emerald-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            Dashboard Toko
                                        </a>
                                    </div>
                                @endif

                                <div class="pt-1 border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition">
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
                               class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl border border-emerald-500 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-bold text-white bg-emerald-500 hover:bg-emerald-600 rounded-xl shadow-xs transition hidden sm:inline-block">
                                Daftar
                            </a>
                        </div>
                    @endauth

                </div>
            </div>

            <!-- Row 2: Comprehensive 15 Sub-Header Navigation Menu Bar (Desktop) -->
            <nav class="hidden md:flex items-center justify-between py-2 border-t border-slate-100 text-xs font-semibold text-slate-700">
                <div class="flex items-center gap-2 lg:gap-2.5 overflow-x-auto no-scrollbar py-0.5">
                    <!-- Flash Sale -->
                    <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="flex items-center gap-1.5 text-rose-600 hover:text-rose-700 font-black transition shrink-0 bg-rose-50/80 px-2.5 py-1 rounded-lg border border-rose-200/60">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-600"></span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        <span>Flash Sale</span>
                        <span class="px-1.5 py-0.2 bg-rose-500 text-white text-[9px] rounded font-black">HOT</span>
                    </a>

                    <!-- 1. Laptop Gaming -->
                    <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>💻 Laptop RTX</span>
                    </a>

                    <!-- 2. PC Rakitan -->
                    <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🖥️ PC Rakitan</span>
                    </a>

                    <!-- 3. PC Komponen -->
                    <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>⚙️ Komponen PC</span>
                    </a>

                    <!-- 4. RAM & SSD Storage -->
                    <a href="{{ route('products.index', ['category' => 'ram-ssd-storage']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>⚡ RAM & SSD</span>
                    </a>

                    <!-- 5. Cooling & Power Supply -->
                    <a href="{{ route('products.index', ['category' => 'cooling-power-supply']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>❄️ Cooler & PSU</span>
                    </a>

                    <!-- 6. Keyboard Mechanical -->
                    <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>⌨️ Keyboard</span>
                    </a>

                    <!-- 7. Mouse Gaming -->
                    <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🖱️ Mouse</span>
                    </a>

                    <!-- 8. Audio & Headset -->
                    <a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🎧 Audio & IEM</span>
                    </a>

                    <!-- 9. Monitor Gaming -->
                    <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>📺 Monitor 240Hz</span>
                    </a>

                    <!-- 10. Konsol & Handheld -->
                    <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🎮 PS5 & Handheld</span>
                    </a>

                    <!-- 11. Smartphone Gaming -->
                    <a href="{{ route('products.index', ['category' => 'smartphone-gaming']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>📱 HP Gaming</span>
                    </a>

                    <!-- 12. Streaming Gear -->
                    <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🎥 Stream Gear</span>
                    </a>

                    <!-- 13. Racing Sim & VR -->
                    <a href="{{ route('products.index', ['category' => 'racing-sim-vr']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🏎️ Sim Racing</span>
                    </a>

                    <!-- 14. Meja & Kursi Setup -->
                    <a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🪑 Meja & Kursi</span>
                    </a>

                    <!-- 15. Aksesoris Gaming -->
                    <a href="{{ route('products.index', ['category' => 'aksesoris-gaming']) }}" class="hover:text-emerald-600 hover:bg-slate-100/60 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🔌 Aksesoris</span>
                    </a>

                    <!-- Simulasi Rakit PC -->
                    <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="text-emerald-700 font-bold bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/70 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🛠️ Rakit PC</span>
                    </a>

                    <!-- Kupon Diskon -->
                    <a href="{{ route('products.index', ['q' => 'Voucher']) }}" class="text-amber-800 font-bold bg-amber-50 hover:bg-amber-100/80 border border-amber-200/70 px-2 py-1 rounded-lg transition shrink-0 flex items-center gap-1">
                        <span>🎟️ Kupon</span>
                    </a>
                </div>

                <div class="flex items-center gap-3 shrink-0 pl-4 border-l border-slate-200">
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-emerald-600 hover:text-emerald-700 font-bold flex items-center gap-1 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Toko Resmi</span>
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('products.index') }}" class="text-slate-500 hover:text-slate-900 transition text-xs">
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
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs"
             @click="mobileMenuOpen = false"></div>

        <div class="fixed inset-0 flex">
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative mr-14 flex w-full max-w-sm flex-1">
                
                <div class="flex flex-col overflow-y-auto bg-white px-5 pb-6 pt-5 shadow-2xl w-full">
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 via-teal-500 to-cyan-400 flex items-center justify-center text-white text-base shadow-sm ring-2 ring-emerald-500/20">
                                🎮
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-black text-sm tracking-tight text-slate-900 leading-none">Kebutuhan<span class="text-emerald-600">Gaming</span></span>
                                    <span class="px-1 py-0.2 bg-emerald-500 text-white font-black text-[8px] rounded uppercase">Gen Z</span>
                                </div>
                                <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Tech & Gaming Gear Store</span>
                            </div>
                        </a>
                        <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Quick Highlights -->
                    <div class="py-3 border-b border-slate-100">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="flex items-center gap-2 p-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100">
                                <span class="text-sm">🔥</span>
                                <div class="truncate">
                                    <p class="text-[11px] leading-tight">Flash Sale</p>
                                    <p class="text-[9px] text-rose-500 font-normal">Diskon s/d 70%</p>
                                </div>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="flex items-center gap-2 p-2 rounded-xl bg-emerald-50 text-emerald-800 font-bold border border-emerald-100">
                                <span class="text-sm">🛠️</span>
                                <div class="truncate">
                                    <p class="text-[11px] leading-tight">Rakit PC</p>
                                    <p class="text-[9px] text-emerald-600 font-normal">Gratis Perakitan</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Menus -->
                    <div class="py-4 space-y-1 border-b border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">15 Kategori Gear Gaming</p>
                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span class="text-sm">🏠</span> Beranda
                        </a>
                        <a href="{{ route('products.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="flex items-center gap-3"><span class="text-sm">📦</span> Semua Katalog Gear</span>
                            <span class="text-[10px] px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded font-bold">Lengkap</span>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">💻</span> Laptop Gaming RTX Series
                        </a>
                        <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🖥️</span> PC Rakitan Gaming Ready
                        </a>
                        <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">⚙️</span> Komponen PC & Hardware
                        </a>
                        <a href="{{ route('products.index', ['category' => 'ram-ssd-storage']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">⚡</span> RAM DDR5 & SSD NVMe
                        </a>
                        <a href="{{ route('products.index', ['category' => 'cooling-power-supply']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">❄️</span> Cooler AIO & Power Supply
                        </a>
                        <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">⌨️</span> Keyboard Mechanical & Switch
                        </a>
                        <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🖱️</span> Mouse Gaming Ultralight
                        </a>
                        <a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🎧</span> Headset 7.1, IEM & DAC
                        </a>
                        <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">📺</span> Monitor Gaming 240Hz OLED
                        </a>
                        <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🎮</span> PlayStation 5 & Handheld
                        </a>
                        <a href="{{ route('products.index', ['category' => 'smartphone-gaming']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">📱</span> Smartphone Gaming
                        </a>
                        <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🎥</span> Streaming Gear & Capture Card
                        </a>
                        <a href="{{ route('products.index', ['category' => 'racing-sim-vr']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🏎️</span> Racing Simulator & VR
                        </a>
                        <a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🪑</span> Kursi Gaming & Standing Desk
                        </a>
                        <a href="{{ route('products.index', ['category' => 'aksesoris-gaming']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            <span class="text-sm">🔌</span> Aksesoris & Modding Gear
                        </a>
                    </div>

                    <!-- Services & Guarantees -->
                    <div class="py-3 border-b border-slate-100 text-xs space-y-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Layanan & Jaminan</p>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>100% Produk Original & Bergaransi</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Gratis Packing Kayu & Bubble Wrap</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Cicilan 0% & Bayar Instan QRIS</span>
                        </div>
                    </div>

                    <!-- Bottom Info & Promo -->
                    <div class="mt-auto pt-4 space-y-3 text-xs">
                        <div class="p-3 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 rounded-2xl border border-emerald-200/80 shadow-2xs">
                            <p class="font-bold text-emerald-950 mb-0.5 flex items-center gap-1.5">
                                <span>🎟️</span> Voucher Diskon Gamers
                            </p>
                            <p class="text-[11px] text-emerald-800 font-mono font-bold">KODE: GAMERSEJATI (-Rp 150.000)</p>
                        </div>
                        @guest
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('login') }}" class="py-2.5 text-center bg-slate-100 hover:bg-slate-200 font-bold rounded-xl text-slate-800 transition">Masuk</a>
                                <a href="{{ route('register') }}" class="py-2.5 text-center bg-emerald-600 hover:bg-emerald-700 font-bold text-white rounded-xl shadow-xs transition">Daftar</a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Slot -->
    <main class="flex-1 max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-6 sm:py-8">
        @yield('content')
    </main>

    <!-- Marketplace Modern Clean Footer -->
    <footer class="bg-white border-t border-slate-200/80 mt-16 pt-14 pb-10 text-sm text-slate-600">
        <div class="max-w-[1720px] w-full mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-10 mb-12">
                <!-- Col 1: Brand & Profile -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 via-teal-500 to-cyan-400 flex items-center justify-center text-white font-black text-xl shadow-sm ring-2 ring-emerald-500/20">
                            🎮
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-xl tracking-tight text-slate-900 leading-none">
                                    Kebutuhan<span class="text-emerald-600">Gaming</span>
                                </span>
                                <span class="px-1.5 py-0.5 bg-emerald-600 text-white font-black text-[9px] rounded-md tracking-wider uppercase shadow-2xs">Gen Z</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">Official Tech & Gaming Gear Marketplace</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-md mb-5">
                        Platform e-commerce spesialis perangkat elektronik dan perlengkapan gaming terlengkap untuk generasi Gen Z di Indonesia. Menghadirkan jajaran laptop gaming RTX, komponen PC rakitan custom, monitor 240Hz OLED, mechanical keyboard, konsol next-gen, dan perlengkapan streaming 100% original bergaransi distributor resmi.
                    </p>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-lg border border-emerald-200/80">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            100% Original Tech
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-800 font-bold rounded-lg border border-teal-200/80">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Garansi Distributor Resmi
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-50 text-cyan-800 font-bold rounded-lg border border-cyan-200/80">
                            <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Free Packing Kayu
                        </span>
                    </div>
                </div>

                <!-- Col 2: Hardware & PC -->
                <div>
                    <h3 class="font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Hardware & Komponen</h3>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-emerald-600 transition">Laptop Gaming RTX 40 & 50</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="hover:text-emerald-600 transition">Komponen PC & Motherboard</a></li>
                        <li><a href="{{ route('products.index', ['q' => 'PC Rakitan']) }}" class="hover:text-emerald-600 transition font-medium text-emerald-700">Simulasi Rakit PC Gaming</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="hover:text-emerald-600 transition">Monitor Fast IPS & OLED 240Hz</a></li>
                        <li><a href="{{ route('products.index', ['q' => 'RAM SSD']) }}" class="hover:text-emerald-600 transition">RAM DDR5 & SSD NVMe Gen4</a></li>
                        <li><a href="{{ route('products.index', ['q' => 'RTX 4070']) }}" class="hover:text-emerald-600 transition">Kartu Grafis GPU Nvidia & Radeon</a></li>
                    </ul>
                </div>

                <!-- Col 3: Peripheral & Setup -->
                <div>
                    <h3 class="font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Peripheral & Setup Room</h3>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="hover:text-emerald-600 transition">Keyboard Mechanical & Custom</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="hover:text-emerald-600 transition">Mouse Gaming Ultralight & Wireless</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="hover:text-emerald-600 transition">Headset Spatial 7.1 & Audio DAC</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="hover:text-emerald-600 transition">Streaming Mic & Capture Card</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="hover:text-emerald-600 transition">Ergonomic Gaming Chair & Desk</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="hover:text-emerald-600 transition">PS5 Slim, Pro & Steam Deck</a></li>
                    </ul>
                </div>

                <!-- Col 4: Layanan & Seller -->
                <div>
                    <h3 class="font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Layanan & Mitra</h3>
                    <ul class="space-y-2 text-xs text-slate-500">
                        <li><a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-rose-600 transition text-rose-600 font-bold">Super Flash Sale Harian</a></li>
                        <li><a href="{{ route('products.index', ['sort' => 'popular']) }}" class="hover:text-emerald-600 transition">Daftar Toko Resmi Official</a></li>
                        <li><a href="{{ route('register') }}?seller=1" class="hover:text-emerald-600 transition text-emerald-700 font-semibold">Buka Toko Seller Gratis</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-600 transition">Pusat Seller Center</a></li>
                        <li><a href="{{ route('products.index', ['q' => 'Garansi']) }}" class="hover:text-emerald-600 transition">Cek Garansi & Serial Number</a></li>
                        <li><a href="{{ route('products.index', ['q' => 'Voucher']) }}" class="hover:text-emerald-600 transition">Pusat Voucher Promo</a></li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods & Logistics Badges -->
            <div class="pt-8 border-t border-slate-100 flex flex-col lg:flex-row items-center justify-between gap-6 text-xs text-slate-500">
                <div class="space-y-3 text-center lg:text-left">
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                        <span class="text-[11px] font-bold text-slate-700 mr-2">Metode Pembayaran Resmi:</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">QRIS Real-Time</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">BCA Virtual Account</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">Mandiri Bill</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">BNI / BRI VA</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">GoPay</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">OVO</span>
                        <span class="px-2.5 py-1 bg-slate-100 border border-slate-200/80 rounded-lg text-slate-800 font-bold text-[11px]">Cash on Delivery (COD)</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                        <span class="text-[11px] font-bold text-slate-700 mr-2">Logistik Pengiriman Aman:</span>
                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-slate-600 text-[10px]">JNE Express</span>
                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-slate-600 text-[10px]">SiCepat REG & Cargo</span>
                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-slate-600 text-[10px]">J&T Gaming Cargo</span>
                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-slate-600 text-[10px]">GoSend Instan 2 Jam</span>
                        <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-slate-600 text-[10px]">GrabExpress Someday</span>
                    </div>
                </div>

                <div class="flex flex-col items-center lg:items-end gap-1 text-center lg:text-right shrink-0">
                    <p class="font-bold text-slate-700 text-xs">&copy; {{ date('Y') }} Kebutuhan Gaming Gen Z. All rights reserved.</p>
                    <p class="text-[11px] text-slate-400">Tempat Belanja Elektronik & Gaming Gear Terpercaya di Indonesia.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/80 md:hidden flex items-center justify-around py-2 px-1 shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('home') ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('products.*') ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Katalog</span>
        </a>
        <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('cart.*') ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Keranjang</span>
            @auth
                @if(($cartCount ?? 0) > 0)
                    <span class="absolute -top-1 right-3 w-4 h-4 bg-emerald-600 text-white rounded-full text-[9px] font-bold flex items-center justify-center shadow-xs">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            @endauth
        </a>
        <a href="{{ auth()->check() ? route('customer.orders.index') : route('login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('customer.orders.*') ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Pesanan</span>
        </a>
        <a href="{{ auth()->check() ? route('customer.profile') : route('login') }}" class="flex flex-col items-center gap-0.5 text-[11px] {{ request()->routeIs('customer.profile*') ? 'text-emerald-600 font-bold' : 'text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Akun</span>
        </a>
    </nav>

    <!-- Global Toast Component -->
    <x-toast />

    @stack('scripts')
</body>
</html>
