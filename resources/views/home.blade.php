@extends('layouts.app')

@section('title', 'Kebutuhan Gaming Gen Z - Gear Gaming & Tech Terlengkap & Terpercaya')

@section('content')
<div class="space-y-10">

    <!-- 1. Hero Promotional Slider (Alpine.js) -->
    @if($banners->isNotEmpty())
        <div class="relative overflow-hidden rounded-3xl shadow-md border border-slate-200/80 bg-slate-900"
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

            <div class="relative h-56 sm:h-80 md:h-[420px] lg:h-[480px] xl:h-[540px] w-full">
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
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/35 to-transparent flex items-end p-6 sm:p-10 lg:p-14">
                            <div class="max-w-2xl text-white">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-emerald-300 text-xs font-bold mb-3">
                                    <span>🎮 Official Gaming Gear Drop</span>
                                </div>
                                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-black tracking-tight leading-tight mb-3">
                                    {{ $banner->title }}
                                </h2>
                                @if($banner->target_url)
                                    <a href="{{ $banner->target_url }}"
                                       class="inline-flex items-center gap-2 mt-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs sm:text-sm rounded-xl shadow-lg hover:shadow-emerald-500/25 transition">
                                        <span>Eksplorasi Gear Sekarang</span>
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
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center backdrop-blur-xs transition shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="activeSlide = (activeSlide + 1) % slidesCount"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center backdrop-blur-xs transition shadow-md">
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

    <!-- 2. Quick Services & Trust Bar (Layanan Unggulan) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
            <!-- Feature 1 -->
            <div class="flex items-center gap-3 pt-2 sm:pt-0 sm:px-3 first:pl-0">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 leading-tight">Pengiriman Instan 2 Jam</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">GoSend & GrabExpress Jabodetabek</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="flex items-center gap-3 pt-2 sm:pt-0 sm:px-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 leading-tight">Garansi Resmi 100%</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Distributor Resmi & Ganti Unit Baru</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="flex items-center gap-3 pt-2 sm:pt-0 sm:px-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0 border border-cyan-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 leading-tight">Gratis Rakit & Tuning</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Teknisi Ahli & Cable Mgmt Rapi</p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="flex items-center gap-3 pt-2 sm:pt-0 sm:px-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 leading-tight">Free Packing Kayu</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Asuransi 100% Seluruh Indonesia</p>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="flex items-center gap-3 pt-2 sm:pt-0 sm:px-3 last:pr-0">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 leading-tight">Cicilan 0% & QRIS</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Bayar Instan & Fleksibel</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Kategori Pilihan -->
    <section class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Kategori Gear & Elektronik</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Temukan gear gaming & hardware impian Anda berdasarkan kategori spesialis</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 2xl:grid-cols-15 gap-3 sm:gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                   class="group flex flex-col items-center text-center p-3 rounded-2xl hover:bg-emerald-50/70 border border-slate-100 hover:border-emerald-300 hover:shadow-2xs transition-all duration-200">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs border border-slate-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($cat->icon === 'laptop')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'computer-desktop')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'cpu-chip')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M3 9h2m-2 6h2m16-6h2m-2 6h2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            @elseif($cat->icon === 'bolt')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            @elseif($cat->icon === 'fan')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                            @elseif($cat->icon === 'keyboard')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2zm3 4h.01M10 10h.01M14 10h.01M17 10h.01M7 13h.01M10 13h.01M14 13h.01M17 13h.01M7 16h10"/>
                            @elseif($cat->icon === 'cursor-arrow-rays')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                            @elseif($cat->icon === 'speaker-wave')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            @elseif($cat->icon === 'tv')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 5h18v10H3V5z"/>
                            @elseif($cat->icon === 'puzzle-piece')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            @elseif($cat->icon === 'device-phone-mobile')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'video-camera')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            @elseif($cat->icon === 'steering-wheel')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0v-8m0 0l-6.5 3.5M12 13l6.5 3.5"/>
                            @elseif($cat->icon === 'wrench-screwdriver')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            @endif
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-emerald-700 line-clamp-2 leading-tight">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 4. Flash Sale with Live Alpine Countdown Timer -->
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
                            <span class="text-xs px-2.5 py-0.5 bg-amber-400 text-rose-950 rounded-full font-extrabold uppercase shadow-xs">Terbatas</span>
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
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($flashSales as $fs)
                    @php $p = $fs->product; @endphp
                    <div class="bg-white text-slate-900 rounded-2xl overflow-hidden shadow-md flex flex-col justify-between p-3 group hover:shadow-xl transition-all border border-rose-100/50">
                        <a href="{{ route('products.show', $p->slug) }}" class="block aspect-square relative rounded-xl overflow-hidden bg-slate-100 mb-2.5">
                            <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}"
                                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <span class="absolute top-2 left-2 px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded-md shadow-2xs">
                                FLASH SALE
                            </span>
                        </a>

                        <div>
                            <a href="{{ route('products.show', $p->slug) }}" class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-2 hover:text-emerald-600 leading-tight mb-1">
                                {{ $p->name }}
                            </a>
                            <div class="text-sm sm:text-base font-black text-rose-600">
                                Rp {{ number_format($fs->discount_price, 0, ',', '.') }}
                            </div>
                            <div class="text-[11px] text-slate-400 line-through mb-2">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>

                            <!-- Progress Bar Sold -->
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden relative">
                                <div class="bg-gradient-to-r from-rose-500 to-amber-400 h-full rounded-full transition-all duration-500"
                                     style="width: {{ $fs->percentage_sold }}%"></div>
                                <span class="absolute inset-0 flex items-center justify-center text-[9px] font-black text-slate-700">
                                    Terjual {{ $fs->percentage_sold }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- 5. Esports Ready & Tournament Pro Gear -->
    @if(isset($esportsGear) && $esportsGear->isNotEmpty())
        <section class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-800 rounded-md">
                            STANDAR TURNAMEN ESPORTS
                        </span>
                        <span class="text-xs text-slate-400 font-bold hidden sm:inline">• 8000Hz Polling & Rapid Trigger</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">🏆 Turnamen Esports Pro Gear</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Keyboard magnetic rapid trigger, mouse ultralight 4K/8K, monitor OLED 240Hz, dan IEM kompetitif</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'keyboard-mechanical']) }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 shrink-0">
                    <span>Lihat Semua Gear Esports</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                @foreach($esportsGear as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- 6. Pusat Solusi & Setup Gaming Gen Z (4 Interactive Service Tiles) -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Tile 1: Simulasi Rakit PC -->
        <a href="{{ route('products.index', ['q' => 'PC Rakitan']) }}" class="group relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-3xl border border-slate-700/60 hover:border-emerald-500 transition shadow-md flex flex-col justify-between h-48">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider">Custom Builder</span>
                    <h3 class="text-lg font-black text-white mt-1 leading-snug">Simulasi Rakit PC Gaming</h3>
                    <p class="text-xs text-slate-300 mt-1">Kustom spek Intel / AMD & GeForce RTX</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition">
                    🛠️
                </div>
            </div>
            <div class="flex items-center gap-1 text-xs font-bold text-emerald-400 group-hover:translate-x-1 transition">
                <span>Rakit Spek Impian</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>

        <!-- Tile 2: Upgrade RAM & SSD -->
        <a href="{{ route('products.index', ['q' => 'RAM SSD']) }}" class="group relative overflow-hidden bg-gradient-to-br from-emerald-950 to-slate-900 text-white p-5 rounded-3xl border border-emerald-800/40 hover:border-emerald-500 transition shadow-md flex flex-col justify-between h-48">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-teal-400 uppercase tracking-wider">Booster Speed</span>
                    <h3 class="text-lg font-black text-white mt-1 leading-snug">Upgrade RAM & NVMe SSD</h3>
                    <p class="text-xs text-slate-300 mt-1">DDR5 & Gen4 SSD transfer 7000MB/s</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-teal-500/20 text-teal-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition">
                    ⚡
                </div>
            </div>
            <div class="flex items-center gap-1 text-xs font-bold text-teal-400 group-hover:translate-x-1 transition">
                <span>Lihat Pilihan Upgrade</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>

        <!-- Tile 3: Setup Meja RGB -->
        <a href="{{ route('products.index', ['category' => 'kursi-setup-meja']) }}" class="group relative overflow-hidden bg-gradient-to-br from-indigo-950 to-slate-900 text-white p-5 rounded-3xl border border-indigo-800/40 hover:border-indigo-400 transition shadow-md flex flex-col justify-between h-48">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Aesthetic Room</span>
                    <h3 class="text-lg font-black text-white mt-1 leading-snug">Setup Meja & Kursi Gaming</h3>
                    <p class="text-xs text-slate-300 mt-1">Standing desk, ergonomic chair & lightbar</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition">
                    💡
                </div>
            </div>
            <div class="flex items-center gap-1 text-xs font-bold text-indigo-400 group-hover:translate-x-1 transition">
                <span>Dekorasi Setup Kamar</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>

        <!-- Tile 4: Streaming Gear Studio -->
        <a href="{{ route('products.index', ['category' => 'streaming-gear']) }}" class="group relative overflow-hidden bg-gradient-to-br from-rose-950 to-slate-900 text-white p-5 rounded-3xl border border-rose-800/40 hover:border-rose-400 transition shadow-md flex flex-col justify-between h-48">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Esports & Live Stream</span>
                    <h3 class="text-lg font-black text-white mt-1 leading-snug">Studio Streaming & Creator</h3>
                    <p class="text-xs text-slate-300 mt-1">Capture card 4K, mic XLR & audio deck</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition">
                    🎥
                </div>
            </div>
            <div class="flex items-center gap-1 text-xs font-bold text-rose-400 group-hover:translate-x-1 transition">
                <span>Cek Gear Content Creator</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>
    </section>

    <!-- 7. PC Builder & Hardware Enthusiast Paradise -->
    @if(isset($pcBuilders) && $pcBuilders->isNotEmpty())
        <section class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-teal-100 text-teal-800 rounded-md">
                            PERFORMA MAKSIMAL
                        </span>
                        <span class="text-xs text-slate-400 font-bold hidden sm:inline">• GeForce RTX 40 Series & AMD Ryzen X3D</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">🛠️ PC Rakitan & Hardware Builder's Paradise</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Komponen PC desktop, kartu grafis high-end, RAM DDR5 6000MHz, Gen 4 NVMe & liquid cooler premium</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'pc-komponen']) }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 shrink-0">
                    <span>Eksplorasi Hardware PC</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                @foreach($pcBuilders as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- 8. Official Brand Partner Strip -->
    <section class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-2xs">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Brand Partner Resmi:</span>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-md">100% Authorized</span>
            </div>
            <div class="flex flex-wrap items-center justify-center md:justify-end gap-3 sm:gap-6 text-xs font-black text-slate-600">
                <a href="{{ route('products.index', ['q' => 'ROG']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">ASUS ROG</a>
                <a href="{{ route('products.index', ['q' => 'GeForce RTX']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">NVIDIA GEFORCE</a>
                <a href="{{ route('products.index', ['q' => 'Razer']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">RAZER</a>
                <a href="{{ route('products.index', ['q' => 'PlayStation']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">PLAYSTATION</a>
                <a href="{{ route('products.index', ['q' => 'Logitech']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">LOGITECH G</a>
                <a href="{{ route('products.index', ['q' => 'Corsair']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">CORSAIR</a>
                <a href="{{ route('products.index', ['q' => 'SteelSeries']) }}" class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 hover:text-emerald-600 border border-slate-100 transition">STEELSERIES</a>
            </div>
        </div>
    </section>

    <!-- 9. Produk Terlaris (Best Sellers) -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Produk Terlaris</span>
                    <span class="px-2.5 py-0.5 text-xs bg-emerald-100 text-emerald-800 rounded-full font-bold">Populer</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Produk paling banyak dibeli dan disukai komunitas gamers</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
            @foreach($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- 10. Konsol Next-Gen, Handheld & Streaming Studio -->
    @if(isset($consoleStream) && $consoleStream->isNotEmpty())
        <section class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xs border border-slate-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 rounded-md">
                            KONSOL & BROADCAST SUITE
                        </span>
                        <span class="text-xs text-slate-400 font-bold hidden sm:inline">• 4K HDR & Portable Gaming</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">🎮 Konsol Next-Gen, Handheld & Streaming Studio</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">PlayStation 5 Pro, Steam Deck OLED, Elgato Stream Deck, sim racing wheel & setup ruangan gaming idaman</p>
                </div>
                <a href="{{ route('products.index', ['category' => 'konsol-handheld']) }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 shrink-0">
                    <span>Lihat Konsol & Studio</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                @foreach($consoleStream as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- 8. Promotional Callout Banner -->
    <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-cyan-800 rounded-3xl p-6 sm:p-10 lg:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8 border border-emerald-600/30">
        <div class="max-w-2xl">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-xs text-xs font-bold rounded-full uppercase tracking-wider">Mitra Tech & Gaming Store</span>
            <h3 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight mt-3 mb-2">Punya Toko Atau Brand Gaming?</h3>
            <p class="text-sm text-emerald-50 leading-relaxed">
                Bergabunglah bersama ribuan seller tech & gaming terpercaya di Kebutuhan Gaming Gen Z. Jual laptop gaming RTX, komponen PC rakitan, peripheral esports, dan gadget original dengan sistem otomatis dan penarikan saldo instan.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <a href="{{ route('register') }}?seller=1"
               class="px-7 py-4 bg-white text-emerald-800 hover:bg-emerald-50 font-black rounded-2xl shadow-xl hover:shadow-2xl transition duration-150 text-sm">
                Buka Toko Official Gratis
            </a>
        </div>
    </div>

    <!-- 9. Rekomendasi Pilihan Untuk Anda -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Rekomendasi Untuk Anda</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pilihan terbaik dikurasi khusus dengan spesifikasi dan rating tertinggi</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
            @foreach($recommended as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

</div>
@endsection
