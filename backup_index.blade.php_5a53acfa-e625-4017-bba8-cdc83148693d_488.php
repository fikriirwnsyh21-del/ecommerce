@extends('layouts.seller')

@section('title', 'Daftar Produk Toko')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Daftar Produk</h1>
            <p class="text-xs text-gray-500">Kelola informasi, foto, stok, dan harga produk tokomu</p>
        </div>
        <a href="{{ route('seller.products.create') }}"
           class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Produk Baru</span>
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs">
            <a href="{{ route('seller.products.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ !request('status') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('seller.products.index', ['status' => 'active']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('status') === 'active' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Aktif
            </a>
            <a href="{{ route('seller.products.index', ['status' => 'inactive']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('status') === 'inactive' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Nonaktif
            </a>
            <a href="{{ route('seller.products.index', ['status' => 'low_stock']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('status') === 'low_stock' ? 'bg-rose-50 text-rose-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Stok Menipis (≤5)
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('seller.products.index') }}" class="w-full md:w-72">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                       class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white transition outline-none">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-xs overflow-hidden">
        @if($products->isEmpty())
            <div class="py-16 text-center space-y-3">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="font-bold text-gray-800 text-sm">Tidak ada produk ditemukan</h3>
                <p class="text-xs text-gray-400">Silakan tambahkan produk baru atau ubah kriteria pencarian.</p>
                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-xs">
                    + Tambah Produk
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/75 border-b border-gray-200 text-gray-400 uppercase text-[10px] tracking-wider font-black">
                        <tr>
                            <th class="py-3.5 px-4">Info Produk</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Harga</th>
                            <th class="py-3.5 px-4">Stok</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $p)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}"
                                             class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0">
                                        <div class="min-w-0">
                                            <a href="{{ route('products.show', $p->slug) }}" target="_blank"
                                               class="font-bold text-gray-900 hover:text-emerald-600 truncate block text-xs">
                                                {{ $p->name }}
                                            </a>
                                            <p class="text-[10px] font-mono text-gray-400 mt-0.5">SKU: {{ $p->sku }}</p>
                                            @if($p->variants->isNotEmpty())
                                                <span class="inline-block mt-0.5 text-[9px] font-bold px-1.5 py-0.2 bg-blue-50 text-blue-700 rounded">
                                                    {{ $p->variants->count() }} Varian
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-gray-600">
                                    {{ $p->category->name }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-extrabold text-gray-900">
                                        Rp {{ number_format($p->final_price, 0, ',', '.') }}
                                    </p>
                                    @if($p->discount_percent)
                                        <p class="text-[10px] text-gray-400 line-through">
                                            Rp {{ number_format($p->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-bold {{ $p->stock <= 5 ? 'text-rose-600' : 'text-gray-700' }}">
                                    {{ $p->stock }} unit
                                </td>
                                <td class="py-3.5 px-4">
                                    <form action="{{ route('seller.products.toggle-active', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-extrabold transition {{ $p->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('seller.products.edit', $p->id) }}"
                                           class="p-1.5 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Edit Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form action="{{ route('seller.products.destroy', $p->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
