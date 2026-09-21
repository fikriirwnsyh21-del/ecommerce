@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@php
    $basePrice = $product->final_price;
    $variantsData = $product->variants->map(function ($v) use ($basePrice) {
        return [
            'id' => $v->id,
            'name' => $v->name,
            'value' => $v->value,
            'price_adjustment' => (float) $v->price_adjustment,
            'stock' => $v->stock,
        ];
    });
@endphp

@section('content')
<div class="space-y-8" x-data="{
    activeImg: '{{ $product->thumbnail_url }}',
    basePrice: {{ $basePrice }},
    originalPrice: {{ $product->price }},
    selectedVariantId: {{ $product->variants->isNotEmpty() ? $product->variants->first()->id : 'null' }},
    variants: {{ Js::from($variantsData) }},
    quantity: 1,
    get currentVariant() {
        return this.variants.find(v => v.id === this.selectedVariantId) || null;
    },
    get currentPrice() {
        let p = this.basePrice;
        if (this.currentVariant) {
            p += this.currentVariant.price_adjustment;
        }
        return p;
    },
    get currentStock() {
        if (this.currentVariant) {
            return this.currentVariant.stock;
        }
        return {{ $product->stock }};
    },
    increment() {
        if (this.quantity < this.currentStock) {
            this.quantity++;
        }
    },
    decrement() {
        if (this.quantity > 1) {
            this.quantity--;
        }
    }
}">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-600">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-emerald-600">Produk</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-emerald-600">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-gray-900 font-semibold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
    </nav>

    <!-- Main Product Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-xs">
        
        <!-- Left: Image Gallery (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Main Display Image -->
            <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center relative">
                <img :src="activeImg" alt="{{ $product->name }}" class="w-full h-full object-cover object-center transition-all duration-300">
                @if($product->activeFlashSale)
                    <div class="absolute top-3 left-3 px-3 py-1 bg-rose-600 text-white font-black text-xs rounded-lg uppercase tracking-wider shadow-md">
                        Flash Sale
                    </div>
                @endif
            </div>

            <!-- Thumbnails Strip -->
            @if($product->images->isNotEmpty())
                <div class="flex items-center gap-2.5 overflow-x-auto pb-2">
                    @foreach($product->images as $img)
                        <button type="button"
                                @click="activeImg = '{{ $img->url }}'"
                                class="w-16 h-16 rounded-xl overflow-hidden border-2 shrink-0 transition"
                                :class="activeImg === '{{ $img->url }}' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-200 hover:border-gray-300'">
                            <img src="{{ $img->url }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Middle: Product Info & Variant Selection (4 cols) -->
        <div class="lg:col-span-4 space-y-5">
            <div>
                <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg mb-2">
                    {{ $product->category->name }}
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>
            </div>

            <!-- Rating & Stats Bar -->
            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-500 pb-3 border-b border-gray-100">
                <div class="flex items-center text-amber-500 font-bold gap-1">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>{{ number_format($product->rating_avg, 1) }}</span>
                </div>
                <span>•</span>
                <span>{{ $product->reviews_count }} Ulasan</span>
                <span>•</span>
                <span>{{ $product->sales_count }} Terjual</span>
            </div>

            <!-- Pricing Box -->
            <div class="space-y-1">
                <div class="text-2xl sm:text-3xl font-black text-emerald-600">
                    Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentPrice)"></span>
                </div>
                @if($product->discount_percent > 0 || $product->activeFlashSale)
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400 line-through">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="px-1.5 py-0.5 bg-rose-50 text-rose-600 font-extrabold rounded">
                            Diskon {{ $product->discount_percent }}%
                        </span>
                    </div>
                @endif
            </div>

            <!-- Variant Selector (if available) -->
            @if($product->variants->isNotEmpty())
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                        Pilih Varian:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variants as $v)
                            <button type="button"
                                    @click="selectedVariantId = {{ $v->id }}"
                                    class="px-3.5 py-2 rounded-xl text-xs font-bold border transition duration-150"
                                    :class="selectedVariantId === {{ $v->id }}
                                        ? 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-2xs'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                {{ $v->value }}
                                @if($v->price_adjustment != 0)
                                    <span class="text-[10px] text-gray-500">
                                        ({{ $v->price_adjustment > 0 ? '+' : '' }}Rp {{ number_format($v->price_adjustment, 0, ',', '.') }})
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Brief Specifications -->
            <div class="pt-3 border-t border-gray-100 space-y-2 text-xs text-gray-600">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Kondisi</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ $product->condition === 'new' ? 'Baru' : 'Bekas' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Berat Satuan</span>
                    <span class="font-semibold text-gray-800">{{ $product->weight }} gram</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Kategori</span>
                    <span class="font-semibold text-gray-800">{{ $product->category->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-400">Sisa Stok</span>
                    <span class="font-semibold text-emerald-600" x-text="currentStock + ' unit'"></span>
                </div>
            </div>

            <!-- Shop Info Card -->
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-emerald-100 text-emerald-700 font-black text-base flex items-center justify-center shrink-0 border border-emerald-300">
                        {{ strtoupper(substr($product->shop->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h4 class="font-bold text-gray-900 text-sm truncate max-w-[140px]">{{ $product->shop->name }}</h4>
                            <span class="px-1.5 py-0.2 bg-emerald-500 text-white text-[9px] font-extrabold rounded">OFFICIAL</span>
                        </div>
                        <p class="text-xs text-gray-400">{{ $product->shop->city }} • Rating {{ number_format($product->shop->rating, 1) }}</p>
                    </div>
                </div>
                <a href="{{ route('shops.show', $product->shop->slug) }}"
                   class="px-3 py-1.5 bg-white border border-gray-300 hover:border-emerald-500 text-xs font-bold text-gray-800 hover:text-emerald-600 rounded-xl transition shadow-2xs">
                    Kunjungi Toko
                </a>
            </div>
        </div>

        <!-- Right: Checkout & Action Box (3 cols) -->
        <div class="lg:col-span-3">
            <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-5 lg:sticky lg:top-24">
                <h3 class="font-bold text-gray-900 text-sm">Atur Jumlah & Catatan</h3>

                <!-- Quantity Control -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">Jumlah Pembelian:</span>
                        <span class="text-xs text-gray-500" x-text="'Stok: ' + currentStock"></span>
                    </div>
                    <div class="flex items-center border border-gray-300 rounded-xl w-36 overflow-hidden">
                        <button type="button" @click="decrement()" class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 text-gray-600 font-bold transition">
                            -
                        </button>
                        <input type="number" x-model="quantity" min="1" :max="currentStock"
                               class="w-16 h-10 text-center font-bold text-sm border-x border-gray-300 focus:outline-hidden [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button type="button" @click="increment()" class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 text-gray-600 font-bold transition">
                            +
                        </button>
                    </div>
                </div>

                <!-- Subtotal Estimation -->
                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-medium">Subtotal:</span>
                    <span class="text-lg font-black text-gray-900">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentPrice * quantity)"></span>
                    </span>
                </div>

                <!-- Action Buttons (Add to Cart & Buy Now) -->
                <div class="space-y-2.5 pt-2">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="selectedVariantId">
                        <input type="hidden" name="quantity" :value="quantity">
                        
                        <button type="submit"
                                class="w-full py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-sm rounded-xl border border-emerald-300 transition duration-150 flex items-center justify-center gap-2 shadow-2xs">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span>+ Keranjang</span>
                        </button>
                    </form>

                    <form action="{{ route('cart.buy-now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="selectedVariantId">
                        <input type="hidden" name="quantity" :value="quantity">
                        
                        <button type="submit"
                                class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-150 flex items-center justify-center gap-2">
                            <span>Beli Sekarang</span>
                        </button>
                    </form>
                </div>

                <!-- Wishlist Toggle -->
                @auth
                    <form action="{{ route('wishlist.toggle') }}" method="POST" class="pt-2 border-t border-gray-100">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                                class="w-full py-2 text-xs font-bold text-gray-600 hover:text-rose-500 flex items-center justify-center gap-2 transition">
                            <svg class="w-4 h-4 {{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'text-rose-500 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>{{ auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>

    </div>

    <!-- Product Description & Customer Reviews Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Description (8 cols) -->
        <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-xs space-y-6">
            <h2 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight pb-3 border-b border-gray-100">
                Deskripsi Produk Lengkap
            </h2>
            <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                {{ $product->description }}
            </div>

            <!-- Customer Reviews List -->
            <div class="pt-8 border-t border-gray-100 space-y-6" x-data="{ previewPhoto: null }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Ulasan Pembeli</h3>
                        <p class="text-xs text-gray-500">Berdasarkan {{ $product->reviews_count }} pembeli terverifikasi</p>
                    </div>
                    <div class="flex items-center gap-2 bg-amber-50 px-3.5 py-2 rounded-xl border border-amber-200">
                        <span class="text-lg font-black text-amber-600 flex items-center gap-1">
                            <svg class="w-5 h-5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($product->rating_avg, 1) }}
                        </span>
                        <span class="text-xs text-amber-800 font-bold">/ 5.0</span>
                    </div>
                </div>

                @if($product->reviews->isEmpty())
                    <div class="p-6 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-sm text-gray-500">Belum ada ulasan untuk produk ini.</p>
                        <p class="text-xs text-gray-400 mt-1">Beli produk ini dan jadilah pembeli pertama yang memberikan review!</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($product->reviews as $review)
                            <div class="py-4 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center border border-emerald-200">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-bold text-gray-800 text-xs sm:text-sm">{{ $review->user->name }}</span>
                                                <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md">Pembeli Terverifikasi</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                                    {{ $review->comment }}
                                </p>
                                @if($review->photo_path)
                                    <div class="pt-1">
                                        <img src="{{ Storage::url($review->photo_path) }}"
                                             alt="Foto Pembeli"
                                             @click="previewPhoto = '{{ Storage::url($review->photo_path) }}'"
                                             class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-xl border border-gray-200 hover:opacity-90 transition cursor-pointer shadow-2xs">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Photo Lightbox Modal -->
                    <div x-show="previewPhoto"
                         x-cloak
                         @keydown.escape.window="previewPhoto = null"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75">
                        <div class="relative max-w-xl max-h-[90vh] bg-white p-2 rounded-2xl overflow-hidden shadow-2xl" @click.away="previewPhoto = null">
                            <button type="button" @click="previewPhoto = null" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition">
                                ✕
                            </button>
                            <img :src="previewPhoto" alt="Zoom Foto Ulasan" class="max-w-full max-h-[80vh] rounded-xl object-contain mx-auto">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products (4 cols) -->
        <div class="lg:col-span-4 space-y-4">
            <h3 class="font-black text-gray-900 text-base">Produk Serupa</h3>
            <div class="grid grid-cols-2 lg:grid-cols-1 gap-4">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
