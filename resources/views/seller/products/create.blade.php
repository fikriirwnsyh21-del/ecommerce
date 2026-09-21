@extends('layouts.seller')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    variants: [],
    addVariant() {
        this.variants.push({ name: '', price: '', stock: '' });
    },
    removeVariant(index) {
        this.variants.splice(index, 1);
    }
}">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Tambah Produk Baru</h1>
            <p class="text-xs text-gray-500">Lengkapi detail informasi untuk mulai menjual produk ini di Kebutuhan Gaming Gen Z</p>
        </div>
        <a href="{{ route('seller.products.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700">
            &larr; Kembali
        </a>
    </div>

    <!-- Product Form -->
    <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Kemeja Flannel Pria Lengan Panjang Premium"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select name="category_id" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
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
                            <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Bekas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                              placeholder="Jelaskan spesifikasi, material, keunggulan, dan kelengkapan produk..."
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">{{ old('description') }}</textarea>
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
                    <input type="number" name="price" value="{{ old('price') }}" required min="100"
                           placeholder="Contoh: 150000"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Harga Diskon (Opsional)
                    </label>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0"
                           placeholder="Contoh: 125000"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Stok Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" required min="0"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Berat (Gram) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="weight" value="{{ old('weight', 500) }}" required min="1"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>
            </div>
        </div>

        <!-- 3. Foto Produk -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                3. Foto Produk (Maks. 5 Foto)
            </h2>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                    Unggah Gambar
                </label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full p-2 bg-gray-50 border border-dashed border-gray-300 rounded-2xl text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[10px] text-gray-400 mt-1">Format didukung: JPG, PNG, WEBP. Maksimal 2MB per gambar. Gambar pertama akan menjadi foto utama produk.</p>
            </div>
        </div>

        <!-- 4. Varian Produk (Opsional - Alpine Repeater) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider">
                        4. Varian Produk (Opsional)
                    </h2>
                    <p class="text-xs text-gray-400">Tambahkan opsi ukuran, warna, atau model</p>
                </div>
                <button type="button" @click="addVariant()"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Varian</span>
                </button>
            </div>

            <template x-if="variants.length === 0">
                <p class="text-xs text-gray-400 italic py-2">Belum ada varian ditambahkan. Produk akan dijual sebagai varian tunggal.</p>
            </template>

            <div class="space-y-3">
                <template x-for="(variant, idx) in variants" :key="idx">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col sm:flex-row items-center gap-3">
                        <div class="w-full sm:w-1/3">
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">Nama Varian (misal: Merah / XL)</label>
                            <input type="text" :name="`variants[${idx}][name]`" x-model="variant.name" required
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="w-full sm:w-1/3">
                            <label class="block text-[10px] font-bold text-gray-500 mb-1">Harga Varian (Rp)</label>
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
                Simpan & Terbitkan Produk
            </button>
        </div>
    </form>

</div>
@endsection
