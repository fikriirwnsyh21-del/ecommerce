@extends('layouts.seller')

@section('title', 'Pengaturan Profil Toko')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Pengaturan Profil Toko</h1>
            <p class="text-xs text-gray-500">Kelola identitas, alamat pengiriman asal, logo, dan banner tokomu</p>
        </div>
        @if($shop->slug)
            <a href="{{ route('shops.show', $shop->slug) }}" target="_blank"
               class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                <span>Lihat Toko Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        @endif
    </div>

    <!-- Shop Settings Form -->
    <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Utama Toko -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                1. Identitas Toko
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Nama Toko <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Deskripsi / Slogan Toko
                    </label>
                    <textarea name="description" rows="4"
                              placeholder="Ceritakan tentang tokomu, komitmen kualitas, dan spesialisasi produk..."
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">{{ old('description', $shop->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Alamat & Lokasi Toko -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                2. Lokasi & Alamat Asal Pengiriman
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Kota / Kabupaten <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city', $shop->city) }}" required
                           placeholder="Contoh: Jakarta Barat"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Provinsi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="province" value="{{ old('province', $shop->province) }}" required
                           placeholder="Contoh: DKI Jakarta"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                    Alamat Lengkap Toko / Gudang <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="address" value="{{ old('address', $shop->address) }}" required
                       placeholder="Nama jalan, gedung, nomor, RT/RW..."
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
            </div>
        </div>

        <!-- Media Visual Toko (Logo & Banner) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <h2 class="text-sm font-black text-gray-900 pb-2 border-b border-gray-100 uppercase tracking-wider">
                3. Logo & Banner Toko
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Logo -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Logo Toko (Rasio 1:1)</label>
                    @if($shop->logo)
                        <div class="w-20 h-20 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo Toko" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                           class="w-full p-2 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-xs file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
                </div>

                <!-- Banner -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Banner Toko (Landscape)</label>
                    @if($shop->banner)
                        <div class="w-full h-20 rounded-2xl overflow-hidden border border-gray-200">
                            <img src="{{ asset('storage/' . $shop->banner) }}" alt="Banner Toko" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="banner" accept="image/*"
                           class="w-full p-2 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-xs file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                Simpan Perubahan Toko
            </button>
        </div>
    </form>

</div>
@endsection
