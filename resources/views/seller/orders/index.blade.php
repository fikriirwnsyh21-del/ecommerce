@extends('layouts.seller')

@section('title', 'Kelola Pesanan Penjual')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Kelola Pesanan</h1>
            <p class="text-xs text-gray-500">Proses transaksi dan masukkan nomor resi pengiriman untuk pembeli</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs">
            @php
                $st = request('status');
            @endphp
            <a href="{{ route('seller.orders.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ !$st ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'paid']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'paid' ? 'bg-blue-50 text-blue-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Perlu Diproses
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'processing']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'processing' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Sedang Diproses
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'shipped']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'shipped' ? 'bg-purple-50 text-purple-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Sedang Dikirim
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'completed']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Selesai
            </a>
            <a href="{{ route('seller.orders.index', ['status' => 'cancelled']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ $st === 'cancelled' ? 'bg-rose-50 text-rose-700' : 'text-gray-500 hover:bg-gray-100' }}">
                Dibatalkan
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="{{ route('seller.orders.index') }}" class="w-full md:w-72">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Pesanan / Pembeli..."
                       class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white transition outline-none">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center border border-gray-200 shadow-xs space-y-3">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="font-bold text-gray-800 text-sm">Tidak ada pesanan pada filter ini</h3>
                <p class="text-xs text-gray-400">Pesanan baru akan muncul secara real-time saat ada transaksi dari pembeli.</p>
            </div>
        @else
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                    <!-- Top Bar: Order Number, Date, Status -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 gap-2">
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-gray-900 text-xs sm:text-sm">{{ $order->order_number }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs font-semibold text-gray-700">Pembeli: {{ $order->user->name }}</span>
                        </div>

                        <div>
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
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="divide-y divide-gray-100">
                        @foreach($order->items as $item)
                            <div class="py-3 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}"
                                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                                         class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0">
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-900">{{ $item->product_name }}</h4>
                                        @if($item->variant_name)
                                            <p class="text-[10px] text-gray-500">Varian: {{ $item->variant_name }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="font-black text-xs text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer / Actions -->
                    <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-[11px] text-gray-400">Kurir Pengiriman:</p>
                            <p class="text-xs font-bold text-gray-700">{{ $order->shipping_courier }} @if($order->tracking_number) (Resi: <span class="font-mono text-emerald-700">{{ $order->tracking_number }}</span>) @endif</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('seller.orders.show', $order->id) }}"
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                                Detail Pesanan
                            </a>

                            @if($order->status === 'paid')
                                <form action="{{ route('seller.orders.process', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                                        Proses Pesanan
                                    </button>
                                </form>
                            @elseif($order->status === 'processing')
                                <a href="{{ route('seller.orders.show', $order->id) }}"
                                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                                    Kirim & Input Resi &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="p-4 bg-white rounded-2xl border border-gray-200 shadow-xs">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
