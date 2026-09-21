@extends('layouts.admin')

@section('title', 'Semua Transaksi Platform')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Semua Transaksi Kebutuhan Gaming Gen Z</h1>
            <p class="text-xs text-slate-500">Pantau seluruh order, status pembayaran, dan proses pengiriman dari seluruh toko</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto text-xs">
            @php $st = request('status'); @endphp
            <a href="{{ route('admin.orders.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ !$st ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Semua
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'pending' ? 'bg-amber-50 text-amber-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Menunggu Bayar
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'paid' ? 'bg-blue-50 text-blue-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Lunas
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'processing' ? 'bg-indigo-50 text-indigo-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Diproses
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'shipped' ? 'bg-purple-50 text-purple-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Dikirim
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'completed' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Selesai
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'cancelled' ? 'bg-rose-50 text-rose-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Batal
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="w-full md:w-80">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Order / Email pembeli..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-black">
                    <tr>
                        <th class="py-3.5 px-4">No. Pesanan</th>
                        <th class="py-3.5 px-4">Pembeli</th>
                        <th class="py-3.5 px-4">Toko Terkait</th>
                        <th class="py-3.5 px-4">Total Transaksi</th>
                        <th class="py-3.5 px-4">Status Pesanan</th>
                        <th class="py-3.5 px-4">Metode Bayar</th>
                        <th class="py-3.5 px-4 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $ord)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-mono font-bold text-slate-900">{{ $ord->order_number }}</p>
                                <p class="text-[10px] text-slate-400">{{ $ord->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $ord->user->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $ord->user->email }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                @php
                                    $shops = $ord->items->map(fn($item) => $item->shop->name ?? 'Toko')->unique();
                                @endphp
                                {{ $shops->implode(', ') }}
                            </td>
                            <td class="py-3.5 px-4 font-black text-slate-900">
                                Rp {{ number_format($ord->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
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
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $badges[$ord->status] ?? 'bg-slate-100 text-slate-800' }}">
                                    {{ $ord->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                {{ strtoupper($ord->payment?->payment_method ?? 'bank_transfer') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}"
                                   class="px-3 py-1 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold rounded-lg text-xs transition">
                                    Inspeksi
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
