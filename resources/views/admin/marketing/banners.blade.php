@extends('layouts.admin')

@section('title', 'Manajemen Banner Promosi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Banner Promosi Platform</h1>
            <p class="text-xs text-slate-500">Kelola slider hero banner beranda dan banner promosi musiman</p>
        </div>
    </div>

    <!-- 2 Columns: Add Banner Form & Current Banners -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Form (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4 lg:sticky lg:top-24">
            <h2 class="text-sm font-black text-slate-900 pb-2 border-b border-slate-100 uppercase tracking-wider">
                + Tambah Banner Baru
            </h2>

            <form action="{{ route('admin.marketing.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Promosi <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Super Promo Gajian Diskon 50%"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target URL</label>
                    <input type="text" name="target_url" placeholder="/products?filter=flash_sale"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Posisi Banner</label>
                    <select name="position" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="hero">Hero Slider Beranda (Utama)</option>
                        <option value="promo">Banner Promo Tengah</option>
                        <option value="sidebar">Sidebar Katalog</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Gambar Banner <span class="text-rose-500">*</span></label>
                    <input type="file" name="image" required accept="image/*"
                           class="w-full p-2 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-xs file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700">
                    <p class="text-[10px] text-slate-400 mt-1">Rekomendasi rasio 16:9 atau 21:9 landscape (maks 4MB).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="0" min="0"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                    Unggah & Terbitkan Banner
                </button>
            </form>
        </div>

        <!-- Banners List (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-black text-slate-900 text-sm">Banner Terpasang</h3>
                <span class="text-xs text-slate-400">{{ $banners->total() }} Banner</span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($banners as $banner)
                    <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="w-28 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-extrabold text-slate-900 text-xs">{{ $banner->title }}</h4>
                                <p class="text-[11px] text-slate-400 truncate">Link: {{ $banner->target_url ?? '/' }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 font-bold rounded text-[10px] uppercase">
                                        {{ $banner->position }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">Urutan: {{ $banner->sort_order }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <form action="{{ route('admin.marketing.banners.toggle', $banner->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.marketing.banners.destroy', $banner->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $banners->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
