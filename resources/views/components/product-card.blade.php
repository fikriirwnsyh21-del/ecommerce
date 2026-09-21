@props(['product'])

@php
    $finalPrice = $product->final_price;
    $hasDiscount = $product->discount_percent > 0;
    $isFlashSale = $product->relationLoaded('activeFlashSale') && $product->activeFlashSale;
@endphp

<div class="group bg-white rounded-2xl border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-lg transition-all duration-200 flex flex-col justify-between overflow-hidden relative h-full shadow-2xs"
     x-data="{ isHovered: false }"
     @mouseenter="isHovered = true"
     @mouseleave="isHovered = false">
    
    <!-- Image with Badges -->
    <a href="{{ route('products.show', $product->slug) }}" class="relative block aspect-square bg-slate-100 overflow-hidden">
        <img src="{{ $product->thumbnail_url }}"
             alt="{{ $product->name }}"
             loading="lazy"
             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
             class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
        
        <!-- Flash Sale / Discount Badges -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
            @if($isFlashSale)
                <span class="px-2 py-0.8 bg-rose-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider shadow-sm flex items-center gap-1">
                    <svg class="w-3 h-3 text-amber-300 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                    Flash Sale
                </span>
            @elseif($hasDiscount)
                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-200 text-[10px] font-black rounded-md shadow-2xs">
                    {{ $product->discount_percent }}% OFF
                </span>
            @endif
        </div>

        <!-- Quick Wishlist Button -->
        @auth
            <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2.5 right-2.5 z-10" @click.stop>
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit"
                        class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-slate-400 hover:text-rose-500 shadow-sm hover:scale-110 transition-transform"
                        title="Tambah ke Wishlist">
                    <svg class="w-4 h-4 {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'text-rose-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </form>
        @endauth
    </a>

    <!-- Card Content -->
    <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between">
        <div>
            <!-- Product Name -->
            <a href="{{ route('products.show', $product->slug) }}"
               class="text-xs sm:text-sm font-bold text-slate-800 line-clamp-2 hover:text-emerald-600 transition-colors leading-snug mb-1.5">
                {{ $product->name }}
            </a>

            <!-- Price Info -->
            <div class="flex items-baseline gap-2 mb-1">
                <span class="text-sm sm:text-base font-black text-slate-900">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </span>
            </div>

            @if($hasDiscount || $isFlashSale)
                <div class="flex items-center gap-1.5 mb-2">
                    <span class="text-[11px] text-slate-400 line-through">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.2 rounded">
                        -{{ $product->discount_percent }}%
                    </span>
                </div>
            @endif

            <!-- Rating & Sold Count -->
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
                <div class="flex items-center text-amber-500 font-bold gap-0.5 text-[11px]">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ number_format($product->rating_avg, 1) }}</span>
                </div>
                <span class="text-slate-300">•</span>
                <span class="text-[11px] text-slate-500">{{ $product->sales_count >= 1000 ? number_format($product->sales_count / 1000, 1) . 'rb' : $product->sales_count }} terjual</span>
            </div>
        </div>

        <!-- Shop & Location -->
        <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="{{ route('shops.show', $product->shop->slug) }}" class="flex items-center gap-1 hover:text-emerald-600 truncate max-w-[125px]">
                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                <span class="truncate font-semibold text-[11px] text-slate-700">{{ $product->shop->name }}</span>
            </a>
            <span class="text-slate-400 truncate text-[10px]">{{ $product->shop->city }}</span>
        </div>
    </div>
</div>
