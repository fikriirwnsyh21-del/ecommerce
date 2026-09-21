@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Manajemen Kategori Produk</h1>
            <p class="text-xs text-slate-500">Kelola hierarki kategori, ikon, dan struktur katalog belanja platform</p>
        </div>
    </div>

    <!-- 2 Columns: Add Form & Categories Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Add Category Form (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4 lg:sticky lg:top-24">
            <h2 class="text-sm font-black text-slate-900 pb-2 border-b border-slate-100 uppercase tracking-wider">
                + Tambah Kategori Baru
            </h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Laptop Gaming"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Induk (Opsional)</label>
                    <select name="parent_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Kategori Utama (Tanpa Induk) --</option>
                        @foreach($parentCategories as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ikon Lucide / Emoji</label>
                    <input type="text" name="icon" placeholder="Contoh: laptop, cpu-chip, atau 🎮"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi kategori..."
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Urutan Tampil (Sort Order)</label>
                    <input type="number" name="sort_order" value="0" min="0"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Categories Table (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-black">
                        <tr>
                            <th class="py-3.5 px-4">Nama Kategori</th>
                            <th class="py-3.5 px-4">Slug</th>
                            <th class="py-3.5 px-4">Kategori Induk</th>
                            <th class="py-3.5 px-4">Jumlah Produk</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $cat)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                            {{ $cat->icon ?? '📁' }}
                                        </span>
                                        <span class="font-extrabold text-slate-900">{{ $cat->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                                    {{ $cat->slug }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $cat->parent?->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold rounded text-[11px]">
                                        {{ $cat->products_count }} Produk
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
