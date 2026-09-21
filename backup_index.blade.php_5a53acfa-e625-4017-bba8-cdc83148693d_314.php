@extends('layouts.app')

@section('title', 'Katalog Produk PasarKeren')

@section('content')
<div class="space-y-6" x-data="{ mobileFilterOpen: false }">

    <!-- Breadcrumb & Header Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('home') }}" class="hover:text-emerald-600">Home</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">Semua Produk</span>
                @if(request('q'))
                    <span>/</span>
                    <span class="text-emerald-600 font-bold">"{{ request('q') }}"</span>
                @endif
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                @if(request('q'))
                    Hasil pencarian untuk: "{{ request('q') }}"
                @elseif(request('category'))
                    Kategori: {{ ucwords(str_replace('-', ' ', request('category'))) }}
                @else
                    Semua Katalog Produk
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Menampilkan {{ $products->total() }} pilihan produk terbaik</p>
        </div>

        <!-- Sorting & Mobile Filter Trigger -->
        <div class="flex items-center gap-3">
            <!-- Mobile Filter Toggle Button -->
            <button @click="mobileFilterOpen = true"
                    class="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-xs font-bold text-gray-700 hover:border-emerald-500">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Filter</span>
            </button>

            <!-- Sorting Select -->
            <form id="sortForm" method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <label for="sortSelect" class="text-xs font-bold text-gray-500 hidden sm:inline">Urutkan:</label>
                <select id="sortSelect"
                        name="sort"
                        onchange="document.getElementById('sortForm').submit()"
                        class="px-3 py-2 bg-white border border-gray-300 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-xl text-xs sm:text-sm font-semibold text-gray-800 outline-hidden">
                    <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Laris</option>
                    <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Main Content Grid with Sidebar Filter -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Sidebar Filter (Desktop) -->
        <aside class="hidden lg:block bg-white rounded-3xl p-6 border border-gray-200 shadow-xs h-fit sticky top-24">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                <span class="font-black text-gray-900 text-base">Filter Produk</span>
                <a href="{{ route('products.index') }}" class="text-xs text-emerald-600 font-bold hover:underline">Reset</a>
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <!-- Category Filter -->
                <div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Kategori</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-2.5 text-xs text-gray-700 cursor-pointer hover:text-emerald-600">
                                <input type="radio" name="category" value="{{ $cat->slug }}"
                                       {{ request('category') === $cat->slug ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="truncate">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Harga (Rp)</h3>
                    <div class="space-y-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Harga Minimum"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Harga Maksimum"
                               class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>

                <!-- Rating Filter -->
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Rating Minimal</h3>
                    <div class="space-y-2">
                        @foreach([5 => '5 Bintang Sempurna', 4 => '4 Bintang ke atas', 3 => '3 Bintang ke atas'] as $stars => $label)
                            <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer hover:text-emerald-600">
                                <input type="radio" name="rating" value="{{ $stars }}"
                                       {{ request('rating') == $stars ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="flex items-center gap-1 text-amber-500 font-bold">
                                    ★ {{ $stars }}
                                    <span class="text-gray-500 font-normal">({{ $label }})</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location Filter -->
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Lokasi Toko</h3>
                    <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                        @foreach($cities as $city)
                            <label class="flex items-center gap-2.5 text-xs text-gray-700 cursor-pointer hover:text-emerald-600">
                                <input type="radio" name="location" value="{{ $city }}"
                                       {{ request('location') === $city ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="truncate">{{ $city }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Stock Ready Filter -->
                <div class="pt-4 border-t border-gray-100">
                    <label class="flex items-center gap-2.5 text-xs text-gray-800 cursor-pointer font-semibold">
                        <input type="checkbox" name="stock" value="ready"
                               {{ request('stock') === 'ready' ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                        <span>Hanya Stok Tersedia</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Terapkan Filter
                </button>
            </form>
        </aside>

        <!-- Product Listing Grid -->
        <section class="lg:col-span-3 space-y-6">
            @if($products->isEmpty())
                <!-- 31. Empty State Component -->
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-200 shadow-xs flex flex-col items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Produk Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 max-w-sm mb-6">
                        Maaf, tidak ada produk yang cocok dengan kriteria filter atau kata kunci pencarian Anda. Coba kata kunci lain atau reset filter.
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md transition">
                        Reset Semua Filter
                    </a>
                </div>
            @else
                <!-- Responsive Product Grid (2 Mobile, 3 Tablet, 3-4 Desktop) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-3.5 sm:gap-5">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </section>

    </div>

    <!-- Mobile Filter Modal Drawer -->
    <div x-show="mobileFilterOpen" x-cloak
         class="fixed inset-0 z-50 overflow-hidden lg:hidden"
         aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="mobileFilterOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-xs bg-white p-6 shadow-xl flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Filter Produk</h2>
                        <button @click="mobileFilterOpen = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <!-- Category -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Kategori</h3>
                            <div class="space-y-1.5 max-h-40 overflow-y-auto">
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-2 text-xs text-gray-700">
                                        <input type="radio" name="category" value="{{ $cat->slug }}"
                                               {{ request('category') === $cat->slug ? 'checked' : '' }}
                                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                        <span>{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Rentang Harga</h3>
                            <div class="space-y-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min (Rp)"
                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maks (Rp)"
                                       class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-3 bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-md">
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
