@extends('layouts.app')

@section('title', 'PasarKeren - Belanja Online Mudah, Cepat & Terpercaya')

@section('content')
<div class="space-y-10">

    <!-- 1. Hero Promotional Slider (Alpine.js) -->
    @if($banners->isNotEmpty())
        <div class="relative overflow-hidden rounded-3xl shadow-lg border border-gray-100 bg-gray-900"
             x-data="{
                 activeSlide: 0,
                 slidesCount: {{ $banners->count() }},
                 timer: null,
                 startAutoPlay() {
                     this.timer = setInterval(() => {
                         this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                     }, 5000);
                 },
                 stopAutoPlay() {
                     clearInterval(this.timer);
                 }
             }"
             x-init="startAutoPlay()"
             @mouseenter="stopAutoPlay()"
             @mouseleave="startAutoPlay()">

            <div class="relative h-48 sm:h-72 md:h-96 w-full">
                @foreach($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 scale-98"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full">
                        <img src="{{ $banner->image_url }}"
                             alt="{{ $banner->title }}"
                             class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end p-6 sm:p-10">
                            <div class="max-w-xl text-white">
                                <h2 class="text-xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight mb-2">
                                    {{ $banner->title }}
                                </h2>
                                @if($banner->target_url)
                                    <a href="{{ $banner->target_url }}"
                                       class="inline-flex items-center gap-2 mt-2 px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg transition">
                                        <span>Lihat Promo</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Arrows -->
            <button @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white text-gray-800 flex items-center justify-center backdrop-blur-xs transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="activeSlide = (activeSlide + 1) % slidesCount"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white text-gray-800 flex items-center justify-center backdrop-blur-xs transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                @foreach($banners as $index => $banner)
                    <button @click="activeSlide = {{ $index }}"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-emerald-500' : 'w-2 bg-white/60'">
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2. Kategori Pilihan -->
    <section class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Kategori Pilihan</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Temukan produk impian Anda berdasarkan kategori populer</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-10 gap-3 sm:gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                   class="group flex flex-col items-center text-center p-3 rounded-2xl hover:bg-emerald-50/60 border border-gray-100 hover:border-emerald-200 transition-all duration-200">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all shadow-2xs">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($cat->icon === 'device-phone-mobile')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'computer-desktop')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'puzzle-piece')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                            @elseif($cat->icon === 'bolt')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            @elseif($cat->icon === 'home')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            @elseif($cat->icon === 'heart')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            @elseif($cat->icon === 'truck')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            @endif
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 group-hover:text-emerald-600 line-clamp-2 leading-tight">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 3. Flash Sale with Live Alpine Countdown Timer -->
    @if($flashSales->isNotEmpty())
        <section class="bg-gradient-to-r from-rose-600 via-rose-500 to-amber-500 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden"
                 x-data="{
                     endTime: new Date('{{ $flashSaleEndTime }}').getTime(),
                     hours: '00',
                     minutes: '00',
                     seconds: '00',
                     init() {
                         this.updateTimer();
                         setInterval(() => { this.updateTimer(); }, 1000);
                     },
                     updateTimer() {
                         const now = new Date().getTime();
                         const diff = Math.max(0, this.endTime - now);
                         const h = Math.floor(diff / (1000 * 60 * 60));
                         const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                         const s = Math.floor((diff % (1000 * 60)) / 1000);
                         this.hours = String(h).padStart(2, '0');
                         this.minutes = String(m).padStart(2, '0');
                         this.seconds = String(s).padStart(2, '0');
                     }
                 }">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-amber-300 shadow-inner">
                        <svg class="w-7 h-7 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center gap-2">
                            <span>SUPER FLASH SALE</span>
                            <span class="text-xs px-2.5 py-0.5 bg-amber-400 text-rose-900 rounded-full font-extrabold uppercase">Terbatas</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-rose-100 mt-0.5">Diskon terbesar hanya hari ini, jangan sampai kehabisan!</p>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="flex items-center gap-2 text-rose-900">
                    <span class="text-xs text-white/90 font-bold uppercase tracking-wider mr-1 hidden sm:inline">Berakhir dalam:</span>
                    <div class="px-3 py-2 bg-white rounded-xl font-black text-base sm:text-lg shadow-md" x-text="hours">00</div>
                    <span class="text-white font-black text-lg">:</span>
                    <div class="px-3 py-2 bg-white rounded-xl font-black text-base sm:text-lg shadow-md" x-text="minutes">00</div>
                    <span class="text-white font-black text-lg">:</span>
                    <div class="px-3 py-2 bg-white rounded-xl font-black text-base sm:text-lg shadow-md" x-text="seconds">00</div>
                </div>
            </div>

            <!-- Flash Sale Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($flashSales as $fs)
                    @php $p = $fs->product; @endphp
                    <div class="bg-white text-gray-900 rounded-2xl overflow-hidden shadow-md flex flex-col justify-between p-3 group hover:shadow-xl transition-all">
                        <a href="{{ route('products.show', $p->slug) }}" class="block aspect-square relative rounded-xl overflow-hidden bg-gray-100 mb-2.5">
                            <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <span class="absolute top-2 left-2 px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded-md">
                                FLASH SALE
                            </span>
                        </a>

                        <div>
                            <a href="{{ route('products.show', $p->slug) }}" class="text-xs sm:text-sm font-bold text-gray-800 line-clamp-2 hover:text-emerald-600 leading-tight mb-1">
                                {{ $p->name }}
                            </a>
                            <div class="text-sm sm:text-base font-black text-rose-600">
                                Rp {{ number_format($fs->discount_price, 0, ',', '.') }}
                            </div>
                            <div class="text-[11px] text-gray-400 line-through mb-2">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>

                            <!-- Progress Bar Sold -->
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden relative">
                                <div class="bg-gradient-to-r from-rose-500 to-amber-400 h-full rounded-full transition-all duration-500"
                                     style="width: {{ $fs->percentage_sold }}%"></div>
                                <span class="absolute inset-0 flex items-center justify-center text-[9px] font-black text-gray-700">
                                    Terjual {{ $fs->percentage_sold }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 4. Produk Terlaris (Best Sellers) -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span>Produk Terlaris</span>
                    <span class="px-2.5 py-0.5 text-xs bg-emerald-100 text-emerald-800 rounded-full font-bold">Populer</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Produk paling banyak dibeli dan disukai pelanggan</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 5. Promotional Callout Banner -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl p-6 sm:p-10 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="max-w-xl">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-xs text-xs font-bold rounded-full uppercase tracking-wider">Keuntungan Eksklusif</span>
            <h3 class="text-2xl sm:text-3xl font-black tracking-tight mt-3 mb-2">Mau Buka Toko Online Sendiri?</h3>
            <p class="text-sm text-emerald-50 leading-relaxed">
                Bergabunglah bersama ribuan seller sukses di PasarKeren. Nikmati fasilitas katalog lengkap, manajemen pesanan mudah, promosi flash sale, dan penarikan saldo instan.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <a href="{{ route('register') }}?seller=1"
               class="px-6 py-3.5 bg-white text-emerald-700 hover:bg-emerald-50 font-black rounded-2xl shadow-lg transition duration-150 text-sm">
                Buka Toko Sekarang
            </a>
        </div>
    </div>

    <!-- 6. Rekomendasi Pilihan Untuk Anda -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Rekomendasi Untuk Anda</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Pilihan terbaik dikurasi khusus dengan rating tertinggi</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($recommended as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

</div>
@endsection
