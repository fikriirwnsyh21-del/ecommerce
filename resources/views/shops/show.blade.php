@extends('layouts.app')

@section('title', $shop->name . ' - Official Store')
@section('meta_description', Str::limit($shop->description, 150))

@section('content')
<div class="space-y-8">

    <!-- Shop Profile Header Card -->
    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-xs">
        <!-- Shop Banner -->
        <div class="h-40 sm:h-56 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-800 relative">
            @if($shop->banner)
                <img src="{{ asset('storage/' . $shop->banner) }}" alt="{{ $shop->name }} Banner" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full opacity-20 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            @endif
        </div>

        <!-- Shop Info Bar -->
        <div class="p-6 sm:p-8 -mt-12 sm:-mt-16 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 relative z-10">
            <div class="flex items-end gap-4 sm:gap-6">
                <!-- Shop Logo -->
                <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl bg-white p-1.5 shadow-xl border-2 border-white shrink-0">
                    <div class="w-full h-full rounded-xl bg-emerald-100 text-emerald-700 font-black text-2xl sm:text-3xl flex items-center justify-center">
                        {{ strtoupper(substr($shop->name, 0, 1)) }}
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">{{ $shop->name }}</h1>
                        <span class="px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-extrabold rounded-md uppercase">Official Store</span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-xl line-clamp-2">
                        {{ $shop->description }}
                    </p>
                </div>
            </div>

            <!-- Stats & Follow CTA -->
            <div class="flex items-center gap-4 sm:gap-6 text-center text-xs">
                <div>
                    <span class="block text-base sm:text-lg font-black text-gray-900">{{ number_format($shop->rating, 1) }} ★</span>
                    <span class="text-gray-400">Rating Toko</span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div>
                    <span class="block text-base sm:text-lg font-black text-gray-900">{{ $shop->products_count }}</span>
                    <span class="text-gray-400">Total Produk</span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div>
                    <span class="block text-base sm:text-lg font-black text-gray-900">{{ number_format($shop->followers_count) }}</span>
                    <span class="text-gray-400">Pengikut</span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div>
                    <span class="block text-base sm:text-lg font-black text-emerald-600 truncate">{{ $shop->city }}</span>
                    <span class="text-gray-400">Lokasi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Products Filter & Catalog Section -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200">
            <!-- Search Inside Shop -->
            <form method="GET" action="{{ route('shops.show', $shop->slug) }}" class="flex-1 max-w-md relative">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang di toko ini..."
                       class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs sm:text-sm focus:bg-white focus:ring-1 focus:ring-emerald-500">
                <button type="submit" class="absolute right-2 top-2.5 text-gray-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            <!-- Sorting Inside Shop -->
            <form id="shopSortForm" method="GET" action="{{ route('shops.show', $shop->slug) }}" class="flex items-center gap-2">
                @foreach(request()->except('sort', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <label for="shopSort" class="text-xs font-bold text-gray-500">Urutkan:</label>
                <select id="shopSort" name="sort" onchange="document.getElementById('shopSortForm').submit()"
                        class="px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs sm:text-sm font-semibold text-gray-800">
                    <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terlaris</option>
                    <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Harga Termahal</option>
                </select>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-200">
                <p class="text-gray-500 text-sm">Tidak ada produk ditemukan di toko ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="pt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
