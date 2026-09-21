<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#5B3BF5] text-slate-850">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kebutuhan Gaming Gen Z') - Tempatnya Gear Gaming & Tech Original</title>
    <meta name="description" content="@yield('meta_description', 'Pusat belanja online spesialis gear gaming, laptop gaming RTX, komponen PC rakitan, monitor 240Hz, keyboard mechanical, konsol PS5, dan aksesoris elektronik terbaik.')">
    
    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Kebutuhan Gaming Gen Z')">
    <meta property="og:description" content="@yield('meta_description', 'Pusat Belanja Online Gear Gaming & Tech Spesialis Gen Z Terpercaya')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-[#4C1D95] via-[#5B3BF5] to-[#2E1065] md:p-4 lg:p-6 xl:p-8 font-sans antialiased text-slate-800 flex flex-col justify-between"
      x-data="{
          mobileMenuOpen: false,
          userDropdownOpen: false,
          searchQuery: '{{ request('q') }}',
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

    <!-- Floating Master Application Container Card (Matches Reference Aesthetic) -->
    <div class="max-w-[1640px] w-full mx-auto bg-white rounded-none md:rounded-[36px] lg:rounded-[44px] shadow-2xl shadow-indigo-950/40 min-h-[94vh] flex flex-col overflow-hidden border border-white/20">

        <!-- Top Announcement Pill Bar inside Floating Container -->
        <div class="bg-slate-900 text-slate-300 text-[11px] py-2 px-6 hidden md:flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5 text-indigo-400 font-extrabold tracking-tight">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    KGZ Official Marketplace
                </span>
                <span class="text-slate-700">•</span>
                <span class="text-slate-300 font-medium">100% Original Tech & Distributor Resmi</span>
                <span class="text-slate-700">•</span>
                <span class="text-amber-400 font-semibold flex items-center gap-1">
                    <span>⚡ Bebas Ongkir se-Indonesia</span>
                </span>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="text-rose-400 hover:text-rose-300 transition flex items-center gap-1">
                    <span>🔥 Flash Sale</span>
                </a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="text-slate-300 hover:text-indigo-400 transition">
                    🛠️ Rakit PC
                </a>
                <span class="text-slate-700">•</span>
                @auth
                    @if(auth()->user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}" class="text-indigo-400 font-bold hover:underline">Seller Center</a>
                    @elseif(auth()->user()->isCustomer())
                        <a href="{{ route('register') }}?seller=1" class="text-indigo-400 font-semibold hover:underline">Buka Toko Official</a>
                    @endif
                @else
                    <a href="{{ route('register') }}?seller=1" class="text-indigo-400 font-semibold hover:underline">Buka Toko Gratis</a>
                @endauth
            </div>
        </div>

        <!-- Modern Header (Logo, Pill Search, Orders, Wishlist, Cart, Profile) -->
        <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 px-4 sm:px-6 lg:px-8 py-3.5">
            <div class="flex items-center justify-between gap-3 sm:gap-6">
                
                <!-- Left: Modern Logo & Mobile Menu Toggle -->
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="p-2 -ml-2 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 md:hidden transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <!-- Brand Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#4A28E8] via-[#5B3BF5] to-[#7B5BF2] flex items-center justify-center text-white font-black text-sm tracking-wider shadow-md shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                            KGZ
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-lg tracking-tight text-slate-950 leading-none">
                                KGZ<span class="text-[#5B3BF5]">.STORE</span>
                            </span>
                            <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest mt-0.5">Gaming & Tech</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Sleek Rounded Pill Search Input -->
                <div class="flex-1 max-w-2xl relative" @click.outside="autocompleteOpen = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <input type="text"
                                   name="q"
                                   x-model="searchQuery"
                                   @focus="autocompleteOpen = true"
                                   placeholder="Cari laptop gaming RTX, monitor 240Hz, keyboard mechanical, PS5..."
                                   class="w-full pl-11 pr-24 py-2.5 bg-slate-100/80 hover:bg-slate-100 focus:bg-white text-xs sm:text-sm text-slate-900 rounded-full border border-slate-200/80 focus:border-[#5B3BF5] focus:ring-4 focus:ring-indigo-500/15 transition-all outline-hidden">
                            
                            <!-- Search Icon Left -->
                            <div class="absolute left-4 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Search Button Right Pill -->
                            <button type="submit"
                                    class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-[#5B3BF5] hover:bg-[#4A28E8] text-white rounded-full font-bold text-xs flex items-center gap-1.5 transition shadow-sm shadow-indigo-500/25">
                                <span>Cari</span>
                            </button>
                        </div>
                    </form>

                    <!-- Autocomplete Suggestions Dropdown -->
                    <div x-show="autocompleteOpen && searchQuery.length > 0" x-cloak
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50">
                        <div class="px-4 py-1.5 text-xs text-slate-400 font-bold uppercase tracking-wider">
                            Rekomendasi Gear Gaming
                        </div>
                        <template x-for="item in suggestions.filter(s => s.toLowerCase().includes(searchQuery.toLowerCase()))" :key="item">
                            <a :href="'{{ route('products.index') }}?q=' + encodeURIComponent(item)"
                               class="flex items-center gap-3 px-4 py-2 text-xs sm:text-sm text-slate-700 hover:bg-indigo-50 hover:text-[#5B3BF5] transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span x-text="item"></span>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Right Actions: Orders, Wishlist, Cart, Profile -->
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    
                    @auth
                        <!-- Orders Link -->
                        <a href="{{ route('customer.orders.index') }}"
                           class="hidden lg:flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-600 hover:text-[#5B3BF5] hover:bg-slate-50 rounded-full transition"
                           title="Pesanan Saya">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Pesanan</span>
                        </a>

                        <!-- Wishlist Button -->
                        @php
                            $wishlistCount = auth()->user()->wishlists()->count();
                        @endphp
                        <a href="{{ route('wishlist.index') }}"
                           class="relative p-2.5 text-slate-600 hover:text-rose-500 rounded-full hover:bg-slate-100 transition flex items-center justify-center"
                           title="Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            @if($wishlistCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center shadow-xs">
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Cart Button with Pill & Counter -->
                        @php
                            $cartCount = auth()->user()->cart?->total_count ?? 0;
                        @endphp
                        <a href="{{ route('cart.index') }}"
                           class="relative p-2.5 text-slate-600 hover:text-[#5B3BF5] rounded-full hover:bg-slate-100 transition flex items-center justify-center"
                           title="Keranjang Belanja">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#5B3BF5] text-white text-[9px] font-black flex items-center justify-center shadow-xs">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="relative ml-1" @click.outside="userDropdownOpen = false">
                            <button @click="userDropdownOpen = !userDropdownOpen"
                                    type="button"
                                    class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition focus:outline-hidden">
                                <div class="w-9 h-9 rounded-full bg-[#5B3BF5] text-white font-black text-xs flex items-center justify-center shadow-xs">
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
                                 class="absolute right-0 top-full mt-2 w-64 bg-white rounded-3xl shadow-2xl border border-slate-100 py-3 z-50">
                                <div class="px-5 py-3 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    <div class="mt-2">
                                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-extrabold rounded-full bg-indigo-50 text-[#5B3BF5] uppercase tracking-wider">
                                            {{ auth()->user()->role->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="py-1 text-xs">
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-3 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profil Saya
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Pesanan Saya
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        Wishlist
                                    </a>
                                    <a href="{{ route('customer.addresses.index') }}" class="flex items-center gap-3 px-5 py-2.5 font-semibold text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Daftar Alamat
                                    </a>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <div class="py-1 border-t border-slate-100">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-xs text-[#5B3BF5] font-bold hover:bg-indigo-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Admin Dashboard
                                        </a>
                                    </div>
                                @elseif(auth()->user()->isSeller())
                                    <div class="py-1 border-t border-slate-100">
                                        <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-xs text-[#5B3BF5] font-bold hover:bg-indigo-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            Dashboard Toko
                                        </a>
                                    </div>
                                @endif

                                <div class="pt-1 border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-bold transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Auth Action Buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 text-xs sm:text-sm font-bold text-slate-700 hover:text-[#5B3BF5] hover:bg-slate-100 rounded-full transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 text-xs sm:text-sm font-bold text-white bg-[#5B3BF5] hover:bg-[#4A28E8] rounded-full shadow-sm shadow-indigo-500/25 transition hidden sm:inline-block">
                                Daftar
                            </a>
                        </div>
                    @endauth

                </div>
            </div>

            <!-- Horizontal Category Pills Bar (Matches Reference Image) -->
            @php
                $currentCat = request('category');
                $isFlash = request('filter') === 'flash_sale';
                $isAllActive = !$currentCat && !$isFlash && (request()->routeIs('products.index') || request()->routeIs('home'));
            @endphp
            <div class="mt-3.5 pt-3 border-t border-slate-100/90 flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                <!-- All Categories -->
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $isAllActive && !request('q') ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    Semua Kategori
                </a>

                <!-- Deals / Flash Sale -->
                <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $isFlash ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🔥 Deals</span>
                </a>

                <!-- Laptop Gaming -->
                <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'laptop-gaming' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>💻 Laptop Gaming</span>
                </a>

                <!-- PC Rakitan -->
                <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'pc-rakitan' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🖥️ PC Rakitan</span>
                </a>

                <!-- Keyboard Mechanical -->
                <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'keyboard-mechanical' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>⌨️ Keyboard</span>
                </a>

                <!-- Mouse Gaming -->
                <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'mouse-gaming' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🖱️ Mouse</span>
                </a>

                <!-- Audio & Headset -->
                <a href="{{ route('products.index', ['category' => 'audio-headset']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'audio-headset' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🎧 Audio</span>
                </a>

                <!-- Monitor Gaming -->
                <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'monitor-gaming' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>📺 Monitor 240Hz</span>
                </a>

                <!-- Konsol & Handheld -->
                <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'konsol-handheld' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🎮 Konsol & Setup</span>
                </a>

                <!-- Komponen PC -->
                <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'pc-komponen' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>⚙️ Komponen</span>
                </a>

                <!-- Racing Sim -->
                <a href="{{ route('products.index', ['category' => 'racing-sim-vr']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition shrink-0 {{ $currentCat === 'racing-sim-vr' ? 'bg-[#5B3BF5] text-white shadow-md shadow-indigo-500/25' : 'bg-[#F6F7FB] text-slate-700 hover:bg-slate-200/80' }}">
                    <span>🏎️ Sim Racing</span>
                </a>
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
                                <div class="w-9 h-9 rounded-2xl bg-[#5B3BF5] flex items-center justify-center text-white font-black text-xs shadow-md shadow-indigo-500/30">
                                    KGZ
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-sm tracking-tight text-slate-900 leading-none">KGZ<span class="text-[#5B3BF5]">.STORE</span></span>
                                    <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider mt-0.5">Gaming Gear Marketplace</span>
                                </div>
                            </a>
                            <button @click="mobileMenuOpen = false" class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Mobile Categories -->
                        <div class="py-4 space-y-1">
                            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-slate-50">Semua Produk</a>
                            <a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="block px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50">🔥 Flash Sale</a>
                            <a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">💻 Laptop Gaming RTX</a>
                            <a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">🖥️ PC Rakitan</a>
                            <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">⌨️ Keyboard Mechanical</a>
                            <a href="{{ route('products.index', ['category' => 'mouse-gaming']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">🖱️ Mouse Gaming</a>
                            <a href="{{ route('products.index', ['category' => 'audio-headset']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">🎧 Audio & Headset</a>
                            <a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">📺 Monitor 240Hz</a>
                            <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">🎮 Konsol & Setup</a>
                        </div>

                        <!-- Mobile Auth -->
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            @guest
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('login') }}" class="py-2.5 text-center bg-slate-100 hover:bg-slate-200 font-bold rounded-full text-slate-800 transition text-xs">Masuk</a>
                                    <a href="{{ route('register') }}" class="py-2.5 text-center bg-[#5B3BF5] hover:bg-[#4A28E8] font-bold text-white rounded-full shadow-xs transition text-xs">Daftar</a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Slot -->
        <main class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>

        <!-- Modern Footer Inside White Container -->
        <footer class="bg-slate-50 border-t border-slate-150 mt-12 pt-10 pb-8 text-xs text-slate-500">
            <div class="max-w-[1640px] w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-8">
                    <!-- Brand Column -->
                    <div class="lg:col-span-2 space-y-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-2xl bg-[#5B3BF5] flex items-center justify-center text-white font-black text-sm shadow-md shadow-indigo-500/25">
                                KGZ
                            </div>
                            <span class="font-black text-base text-slate-900">KGZ<span class="text-[#5B3BF5]">.STORE</span></span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                            Platform belanja online spesialis perlengkapan gaming dan kebutuhan teknologi terlengkap untuk generasi Gen Z di Indonesia. 100% Original dan bergaransi resmi.
                        </p>
                    </div>

                    <!-- Categories Column -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-3 uppercase tracking-wider text-[11px]">Kategori Unggulan</h4>
                        <ul class="space-y-2 text-slate-500">
                            <li><a href="{{ route('products.index', ['category' => 'laptop-gaming']) }}" class="hover:text-[#5B3BF5] transition">Laptop Gaming RTX</a></li>
                            <li><a href="{{ route('products.index', ['category' => 'pc-rakitan']) }}" class="hover:text-[#5B3BF5] transition">PC Rakitan Custom</a></li>
                            <li><a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="hover:text-[#5B3BF5] transition">Keyboard Mechanical</a></li>
                            <li><a href="{{ route('products.index', ['category' => 'monitor-gaming']) }}" class="hover:text-[#5B3BF5] transition">Monitor OLED 240Hz</a></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-3 uppercase tracking-wider text-[11px]">Layanan Pelanggan</h4>
                        <ul class="space-y-2 text-slate-500">
                            <li><a href="{{ route('products.index', ['filter' => 'flash_sale']) }}" class="hover:text-rose-600 transition text-rose-600 font-bold">Flash Sale Harian</a></li>
                            <li><a href="{{ route('customer.orders.index') }}" class="hover:text-[#5B3BF5] transition">Lacak Pesanan</a></li>
                            <li><a href="{{ route('wishlist.index') }}" class="hover:text-[#5B3BF5] transition">Wishlist Gear</a></li>
                            <li><a href="{{ route('register') }}?seller=1" class="hover:text-[#5B3BF5] transition text-[#5B3BF5] font-bold">Buka Toko Seller</a></li>
                        </ul>
                    </div>

                    <!-- Logistics & Guarantee -->
                    <div>
                        <h4 class="font-bold text-slate-900 mb-3 uppercase tracking-wider text-[11px]">Jaminan Keamanan</h4>
                        <div class="space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 rounded-full font-semibold text-slate-700">
                                🛡️ 100% Produk Original
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 rounded-full font-semibold text-slate-700">
                                📦 Gratis Packing Kayu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="pt-6 border-t border-slate-200/70 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400">
                    <p>&copy; {{ date('Y') }} Kebutuhan Gaming Gen Z. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <span>Pembayaran Aman: QRIS, Virtual Account, GoPay, OVO, COD</span>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Global Toast Component -->
    <x-toast />

    @stack('scripts')
</body>
</html>
