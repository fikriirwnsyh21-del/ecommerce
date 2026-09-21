@extends('layouts.app')

@section('title', 'Katalog Gear & Elektronik - Kebutuhan Gaming Gen Z')

@section('content')
<div class="space-y-6" x-data="{ mobileFilterOpen: false }">

    <!-- Catalog Page Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#F6F7FB] p-5 sm:p-6 rounded-[28px]">
        <div>
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('home') }}" class="hover:text-[#5B3BF5]">Home</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 font-semibold">Katalog Gear</span>
                @if(request('q'))
                    <span class="text-slate-300">/</span>
                    <span class="text-[#5B3BF5] font-bold">"{{ request('q') }}"</span>
                @elseif(request('category'))
                    <span class="text-slate-300">/</span>
                    <span class="text-[#5B3BF5] font-bold">{{ ucwords(str_replace('-', ' ', request('category'))) }}</span>
                @endif
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                @if(request('q'))
                    Hasil pencarian: "{{ request('q') }}"
                @elseif(request('category'))
                    Kategori: {{ ucwords(str_replace('-', ' ', request('category'))) }}
                @else
                    Semua Katalog Gear & Hardware
                @endif
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $products->total() }} pilihan perlengkapan gaming dan komponen PC resmi</p>
        </div>

        <!-- Sorting & Mobile Filter Trigger -->
        <div class="flex items-center gap-3">
            <!-- Mobile Filter Trigger -->
            <button @click="mobileFilterOpen = true"
                    class="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-700 hover:border-[#5B3BF5] transition shadow-2xs">
                <svg class="w-4 h-4 text-[#5B3BF5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter</span>
            </button>

            <!-- Sorting Select Pill -->
            <form id="sortForm" method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <label for="sortSelect" class="text-xs font-bold text-slate-400 hidden sm:inline">Urutkan:</label>
                <div class="relative">
                    <select id="sortSelect"
                            name="sort"
                            onchange="document.getElementById('sortForm').submit()"
                            class="appearance-none pl-4 pr-9 py-2 bg-white border border-slate-200/90 focus:border-[#5B3BF5] focus:ring-2 focus:ring-indigo-500/15 rounded-full text-xs font-bold text-slate-800 outline-hidden transition shadow-2xs cursor-pointer">
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Laris</option>
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                    <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content Layout (Left Filter Sidebar + Right Products Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 xl:gap-8 items-start">

        <!-- Sidebar Filter (Desktop) -->
        <aside class="hidden lg:block lg:col-span-3 xl:col-span-3 bg-[#F6F7FB] rounded-[32px] p-6 border border-slate-100 sticky top-24">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200/80 mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-[#5B3BF5] text-white flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <span class="font-black text-slate-900 text-sm">Filters</span>
                </div>
                <a href="{{ route('products.index') }}" class="text-xs text-[#5B3BF5] font-bold hover:underline">Reset</a>
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- 1. Price Range with Stylized Histogram Chart -->
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Price Range</h3>
                        <span class="text-[11px] font-bold text-[#5B3BF5]">IDR (Rp)</span>
                    </div>

                    <!-- Stylized Purple Histogram Visual Bars (Matches Reference) -->
                    <div class="h-14 flex items-end gap-1 px-2 mb-3 bg-white/70 rounded-2xl p-2 border border-slate-200/60">
                        <div class="w-full bg-indigo-200 rounded-xs h-[25%]"></div>
                        <div class="w-full bg-indigo-200 rounded-xs h-[40%]"></div>
                        <div class="w-full bg-indigo-300 rounded-xs h-[55%]"></div>
                        <div class="w-full bg-indigo-400 rounded-xs h-[75%]"></div>
                        <div class="w-full bg-[#5B3BF5] rounded-xs h-[95%]"></div>
                        <div class="w-full bg-[#5B3BF5] rounded-xs h-[100%]"></div>
                        <div class="w-full bg-[#5B3BF5] rounded-xs h-[85%]"></div>
                        <div class="w-full bg-indigo-400 rounded-xs h-[65%]"></div>
                        <div class="w-full bg-indigo-300 rounded-xs h-[45%]"></div>
                        <div class="w-full bg-indigo-300 rounded-xs h-[50%]"></div>
                        <div class="w-full bg-indigo-200 rounded-xs h-[30%]"></div>
                        <div class="w-full bg-indigo-200 rounded-xs h-[20%]"></div>
                    </div>

                    <!-- Dual Price Pills Input -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-1">Min (Rp)</span>
                            <input type="number"
                                   name="min_price"
                                   value="{{ request('min_price') }}"
                                   placeholder="0"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-800 focus:border-[#5B3BF5] focus:ring-2 focus:ring-indigo-500/15 outline-hidden shadow-2xs">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block mb-1">Max (Rp)</span>
                            <input type="number"
                                   name="max_price"
                                   value="{{ request('max_price') }}"
                                   placeholder="50.000.000"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-800 focus:border-[#5B3BF5] focus:ring-2 focus:ring-indigo-500/15 outline-hidden shadow-2xs">
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery Options Toggle (Segmented Pill) -->
                <div class="pt-4 border-t border-slate-200/80">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2.5">Opsi Pengiriman</h3>
                    <div class="grid grid-cols-2 p-1 bg-white rounded-full border border-slate-200/90 text-xs font-bold shadow-2xs">
                        <label class="cursor-pointer text-center py-1.5 rounded-full transition {{ request('location') ? 'text-slate-500' : 'bg-[#5B3BF5] text-white shadow-xs' }}">
                            <input type="radio" name="location" value="" {{ !request('location') ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                            <span>Semua Area</span>
                        </label>
                        <label class="cursor-pointer text-center py-1.5 rounded-full transition {{ request('location') === 'Jakarta Pusat' ? 'bg-[#5B3BF5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                            <input type="radio" name="location" value="Jakarta Pusat" {{ request('location') === 'Jakarta Pusat' ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only">
                            <span>Jabodetabek</span>
                        </label>
                    </div>
                </div>

                <!-- 3. Star Rating Filter -->
                <div class="pt-4 border-t border-slate-200/80">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2.5">Star Rating</h3>
                    <div class="space-y-2">
                        @foreach([5 => '5.0 Rating Sempurna', 4 => '4.0 ke atas', 3 => '3.0 ke atas'] as $stars => $label)
                            <label class="flex items-center justify-between p-2 rounded-2xl bg-white hover:bg-indigo-50/50 border border-slate-200/60 cursor-pointer transition">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="rating" value="{{ $stars }}"
                                           {{ request('rating') == $stars ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="w-4 h-4 text-[#5B3BF5] focus:ring-indigo-500 border-slate-300">
                                    <div class="flex items-center text-amber-400 gap-0.5 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $stars ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-500">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 4. Brand Partner Official Checkboxes -->
                <div class="pt-4 border-t border-slate-200/80">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2.5">Brand Partner</h3>
                    <div class="space-y-1.5">
                        @php
                            $brands = [
                                'ASUS ROG' => 'ASUS ROG',
                                'Razer' => 'Razer',
                                'Logitech G' => 'Logitech',
                                'Corsair' => 'Corsair',
                                'Sony PlayStation' => 'PlayStation',
                                'SteelSeries' => 'SteelSeries'
                            ];
                        @endphp
                        @foreach($brands as $label => $keyword)
                            <label class="flex items-center justify-between p-2 rounded-2xl bg-white hover:bg-slate-50 border border-slate-200/60 cursor-pointer text-xs transition">
                                <span class="font-bold text-slate-800">{{ $label }}</span>
                                <input type="radio" name="q" value="{{ $keyword }}"
                                       {{ request('q') === $keyword ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 text-[#5B3BF5] focus:ring-indigo-500 border-slate-300">
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 5. Stock Ready Toggle -->
                <div class="pt-4 border-t border-slate-200/80">
                    <label class="flex items-center justify-between p-2.5 bg-white rounded-2xl border border-slate-200/60 cursor-pointer">
                        <span class="text-xs font-bold text-slate-800">Hanya Stok Ready</span>
                        <input type="checkbox" name="stock" value="ready"
                               {{ request('stock') === 'ready' ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="w-4 h-4 rounded text-[#5B3BF5] focus:ring-indigo-500 border-slate-300">
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-[#5B3BF5] hover:bg-[#4A28E8] text-white font-black text-xs rounded-full shadow-md shadow-indigo-500/25 transition">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- Right: Products Catalog Grid -->
        <section class="lg:col-span-9 xl:col-span-9 space-y-6">
            @if($products->isEmpty())
                <!-- Empty State -->
                <div class="bg-[#F6F7FB] rounded-[32px] p-12 text-center border border-slate-200/60 flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-indigo-100 text-[#5B3BF5] flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 mb-1">Gear Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mb-6">
                        Maaf, tidak ada gear yang cocok dengan filter atau kata kunci Anda. Silakan coba kata kunci lain.
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="px-6 py-2.5 bg-[#5B3BF5] hover:bg-[#4A28E8] text-white font-bold text-xs rounded-full shadow-md shadow-indigo-500/20 transition">
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <!-- Product Grid (Responsive: 2 Mobile, 3 Tablet, 3-4 Desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </section>

    </div>

    <!-- Mobile Filter Drawer -->
    <div x-show="mobileFilterOpen" x-cloak
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="mobileFilterOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-xs bg-white p-6 shadow-2xl flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <span class="font-black text-slate-900 text-sm">Filter Gear</span>
                        <button @click="mobileFilterOpen = false" class="p-1 text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" class="space-y-4 text-xs">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        
                        <div>
                            <span class="font-black text-slate-900 block mb-2">Harga Min & Max</span>
                            <div class="space-y-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min Rp" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-full">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Rp" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-full">
                            </div>
                        </div>

                        <div>
                            <span class="font-black text-slate-900 block mb-2">Rating Minimal</span>
                            <select name="rating" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-full">
                                <option value="">Semua Rating</option>
                                <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5.0 Sempurna</option>
                                <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4.0+ Bintang</option>
                            </select>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-2.5 bg-[#5B3BF5] text-white font-bold rounded-full">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
