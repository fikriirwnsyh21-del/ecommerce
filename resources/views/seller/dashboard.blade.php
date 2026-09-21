@extends('layouts.seller')

@section('title', 'Dashboard Penjual')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-2">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider text-emerald-100">
                Pusat Operasional Toko
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
                Halo, {{ $shop->name }}! 👋
            </h1>
            <p class="text-emerald-100 text-xs sm:text-sm">
                Pantau performa tokomu, kelola stok barang, dan proses pesanan pembeli dengan cepat untuk reputasi toko terbaik.
            </p>
        </div>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('seller.products.create') }}" class="px-4 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 rounded-xl text-xs font-extrabold shadow-sm transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Produk Baru</span>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="px-4 py-2.5 bg-emerald-800/60 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold transition">
                Lihat Daftar Pesanan
            </a>
        </div>
    </div>

    <!-- Stat Metrics (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Revenue -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400">Total Pendapatan</p>
                <h3 class="text-lg font-black text-gray-900 mt-0.5">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400">Perlu Diproses</p>
                <h3 class="text-lg font-black text-amber-600 mt-0.5">
                    {{ $pendingOrdersCount }} <span class="text-xs font-normal text-gray-500">Pesanan</span>
                </h3>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400">Total Produk</p>
                <h3 class="text-lg font-black text-gray-900 mt-0.5">
                    {{ $totalProductsCount }} <span class="text-xs font-normal text-gray-500">Item</span>
                </h3>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400">Stok Menipis (≤5)</p>
                <h3 class="text-lg font-black text-rose-600 mt-0.5">
                    {{ $lowStockCount }} <span class="text-xs font-normal text-gray-500">Produk</span>
                </h3>
            </div>
        </div>
    </div>

    <!-- 2 Column Section: Recent Orders & Stock Warning -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h2 class="font-black text-gray-900 text-base">Pesanan Terbaru</h2>
                    <p class="text-xs text-gray-400">Transaksi masuk yang perlu perhatian tokomu</p>
                </div>
                <a href="{{ route('seller.orders.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="py-12 text-center space-y-2">
                    <p class="text-xs text-gray-400">Belum ada pesanan masuk saat ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-gray-400 border-b border-gray-100 uppercase text-[10px] tracking-wider font-extrabold">
                            <tr>
                                <th class="pb-3">No. Pesanan</th>
                                <th class="pb-3">Pembeli</th>
                                <th class="pb-3">Item Pesanan</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentOrders as $ord)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-3 font-mono font-bold text-gray-900">
                                        {{ $ord->order_number }}
                                    </td>
                                    <td class="py-3 font-medium text-gray-700">
                                        {{ $ord->user->name }}
                                    </td>
                                    <td class="py-3 text-gray-500">
                                        {{ $ord->items->count() }} Produk
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
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $badges[$ord->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('seller.orders.show', $ord->id) }}" class="px-3 py-1 bg-gray-100 hover:bg-emerald-50 hover:text-emerald-700 text-gray-700 font-bold rounded-lg text-xs transition">
                                            Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Low Stock Alert Box (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h2 class="font-black text-gray-900 text-base">Stok Menipis</h2>
                    <p class="text-xs text-gray-400">Segera restock sebelum kehabisan</p>
                </div>
            </div>

            @if($lowStockProducts->isEmpty())
                <div class="py-8 text-center text-xs text-gray-400">
                    <p>Semua stok produkmu aman! 👍</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($lowStockProducts as $lp)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-gray-900 truncate">{{ $lp->name }}</h4>
                                <p class="text-[11px] text-rose-600 font-bold">Tersisa {{ $lp->stock }} unit</p>
                            </div>
                            <a href="{{ route('seller.products.edit', $lp->id) }}" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg shrink-0 transition">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
