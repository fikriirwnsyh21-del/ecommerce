@extends('layouts.admin')

@section('title', 'Moderasi Produk Platform')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Moderasi Produk Platform</h1>
            <p class="text-xs text-slate-500">Pantau seluruh produk dari penjual, nonaktifkan atau hapus produk yang melanggar ketentuan</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto text-xs">
            <a href="{{ route('admin.products.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ !request('status') ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Semua Produk
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'active']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('status') === 'active' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Aktif
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'inactive']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('status') === 'inactive' ? 'bg-rose-50 text-rose-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Dinonaktifkan (Take Down)
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="w-full md:w-80">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU produk..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-black">
                    <tr>
                        <th class="py-3.5 px-4">Produk</th>
                        <th class="py-3.5 px-4">Toko Penjual</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4">Stok</th>
                        <th class="py-3.5 px-4">Status Moderasi</th>
                        <th class="py-3.5 px-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $p)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}"
                                         class="w-12 h-12 rounded-xl object-cover border border-slate-100 shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $p->slug) }}" target="_blank"
                                           class="font-bold text-slate-900 hover:text-emerald-600 truncate block text-xs">
                                            {{ $p->name }}
                                        </a>
                                        <p class="text-[10px] font-mono text-slate-400 mt-0.5">SKU: {{ $p->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('shops.show', $p->shop->slug) }}" target="_blank" class="font-semibold text-emerald-600 hover:underline">
                                    {{ $p->shop->name }}
                                </a>
                                <p class="text-[10px] text-slate-400">{{ $p->shop->city }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $p->category->name }}
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                Rp {{ number_format($p->final_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-700">
                                {{ $p->stock }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $p->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $p->is_active ? 'Aktif' : 'Take Down' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.products.toggle-status', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ $p->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $p->is_active ? 'Take Down' : 'Pulihkan' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen dari platform?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus Permanen">
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
        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection
