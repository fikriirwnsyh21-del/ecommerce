@extends('layouts.seller')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    variants: {{ Js::from($product->variants->map(fn($v) => [
        'id' => $v->id,
        'name' => $v->name,
        'price' => (float)$v->price,
        'stock' => (int)$v->stock
    ])) }},
    addVariant() {
        this.variants.push({ id: null, name: '', price: '', stock: '' });
    },
    removeVariant(index) {
        this.variants.splice(index, 1);
    }
}">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Edit Produk</h1>
            <p class="text-xs text-gray-500">Perbarui informasi, harga, stok, atau varian produk</p>
        </div>
        <a href="{{ route('seller.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700">
            &larr; Kembali
        </a>
    </div>

    <!-- Product Form -->
    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Informasi Dasar -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                1. Informasi Dasar Produk
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kondisi <span class="text-rose-500">*</span>
                        </label>
                        <select name="condition" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>Bekas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-gray-700">Tampilkan Produk di Marketplace (Aktif)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. Harga & Pengiriman -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                2. Harga, Stok & Pengiriman
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Harga Normal (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" required min="100"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Harga Diskon (Opsional)
                    </label>
                    <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price ? (int)$product->discount_price : '') }}" min="0"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Stok Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Berat (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" required min="1"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>
            </div>
        </div>

        <!-- 3. Foto Produk -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                3. Foto Produk
            </h2>

            @if($product->images->isNotEmpty())
                <div class="space-y-2">
                    <p class="text-xs font-bold text-gray-600">Foto Saat Ini:</p>
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <div class="relative w-20 h-20 rounded-2xl overflow-hidden border border-gray-200 shrink-0">
                                <img src="{{ $img->url }}" class="w-full h-full object-cover">
                                @if($img->is_primary)
                                    <span class="absolute bottom-1 left-1 right-1 bg-emerald-600 text-white text-[8px] font-bold text-center py-0.5 rounded">Utama</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                    Unggah Tambahan Foto Baru (Opsional)
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full p-2 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
        </div>

        <!-- 4. Varian Produk -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider">
                        4. Varian Produk
                    </h2>
                    <p class="text-xs text-gray-400">Atur varian spesifik (ukuran, warna, dsb.)</p>
                </div>
                <button type="button" @click="addVariant()"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Varian</span>
                </button>
            </div>

            <template x-if="variants.length === 0">
                <p class="text-xs text-gray-400 italic py-2">Tidak ada varian tambahan.</p>
            </template>

            <div class="space-y-3">
                <template x-for="(variant, idx) in variants" :key="idx">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col sm:flex-row items-center gap-3">
                        <input type="hidden" :name="`variants[${idx}][id]`" :value="variant.id">
                        
                        <div class="w-full sm:w-1/3">
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">Nama Varian</label>
                            <input type="text" :name="`variants[${idx}][name]`" x-model="variant.name" required
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">Harga (Rp)</label>
                            <input type="number" :name="`variants[${idx}][price]`" x-model="variant.price" required min="100"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="w-full sm:w-1/4">
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">Stok</label>
                            <input type="number" :name="`variants[${idx}][stock]`" x-model="variant.stock" required min="0"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="sm:pt-4">
                            <button type="button" @click="removeVariant(idx)"
                                    class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Hapus Varian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('seller.products.index') }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
