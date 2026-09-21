@extends('layouts.admin')

@section('title', 'Admin Platform Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Top Hero Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800 relative overflow-hidden">
        <div class="relative z-10 max-w-3xl space-y-2">
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase tracking-wider">
                Kebutuhan Gaming Gen Z Overview
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Pusat Kontrol & Operasional Platform 🚀
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm">
                Pantau metrik volume transaksi, moderasi produk mencurigakan, kelola akun pengguna, dan konfigurasi kampanye promosi platform.
            </p>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold shadow-sm transition">
                Kelola Pengguna
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition">
                Moderasi Katalog Produk
            </a>
            <a href="{{ route('admin.marketing.vouchers') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition">
                Buat Voucher Platform
            </a>
        </div>
    </div>

    <!-- Platform KPI Metrics (5 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- GMV -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-[11px] font-bold text-slate-400">Total Nilai GMV</p>
            <h3 class="text-base font-black text-slate-900 mt-0.5">
                Rp {{ number_format($totalGmv, 0, ',', '.') }}
            </h3>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-[11px] font-bold text-slate-400">Total Transaksi</p>
            <h3 class="text-base font-black text-slate-900 mt-0.5">
                {{ number_format($totalOrdersCount) }} <span class="text-xs font-normal text-slate-400">Order</span>
            </h3>
        </div>

        <!-- Total Users -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <p class="text-[11px] font-bold text-slate-400">Total Pengguna</p>
            <h3 class="text-base font-black text-slate-900 mt-0.5">
                {{ number_format($totalUsersCount) }} <span class="text-xs font-normal text-slate-400">Akun</span>
            </h3>
        </div>

        <!-- Total Active Shops -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="text-[11px] font-bold text-slate-400">Mitra Toko Aktif</p>
            <h3 class="text-base font-black text-slate-900 mt-0.5">
                {{ number_format($totalShopsCount) }} <span class="text-xs font-normal text-slate-400">Toko</span>
            </h3>
        </div>

        <!-- Total Products -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="text-[11px] font-bold text-slate-400">Total Katalog</p>
            <h3 class="text-base font-black text-slate-900 mt-0.5">
                {{ number_format($totalProductsCount) }} <span class="text-xs font-normal text-slate-400">SKU</span>
            </h3>
        </div>
    </div>

    <!-- 2 Columns: Recent Platform Orders & Categories Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="font-black text-slate-900 text-base">Transaksi Terkini Sistem</h2>
                    <p class="text-xs text-slate-400">Aktivitas pembelian terbaru antar pengguna dan penjual</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider font-extrabold">
                        <tr>
                            <th class="pb-3">No. Pesanan</th>
                            <th class="pb-3">Pembeli</th>
                            <th class="pb-3">Total Belanja</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $ord)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 font-mono font-bold text-slate-900">
                                    {{ $ord->order_number }}
                                </td>
                                <td class="py-3 text-slate-700">
                                    {{ $ord->user->name }}
                                </td>
                                <td class="py-3 font-extrabold text-slate-900">
                                    Rp {{ number_format($ord->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="py-3">
                                    @php
                                        $badges = [
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-indigo-100 text-indigo-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-teal-100 text-teal-800',
                                            'completed' => 'bg-emerald-100 text-emerald-800',
                                            'cancelled' => 'bg-rose-100 text-rose-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $badges[$ord->status] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold rounded-lg text-xs transition">
                                        Inspeksi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Categories Breakdown (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="font-black text-slate-900 text-base">Kategori Populer</h2>
                    <p class="text-xs text-slate-400">Distribusi jumlah produk terdaftar</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Kelola
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($topCategories as $cat)
                    <div class="py-3 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">{{ $cat->name }}</span>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 font-extrabold text-[11px] rounded-lg">
                            {{ $cat->products_count }} Produk
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
