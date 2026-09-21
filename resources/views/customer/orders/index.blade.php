@extends('layouts.app')

@section('title', 'Daftar Pesanan Saya')

@section('content')
<div class="space-y-6">

    <!-- Title & Filter Tabs -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs space-y-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Pesanan Saya</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Pantau status transaksi dan riwayat belanja Anda</p>
        </div>

        <!-- Status Filter Tabs -->
        @php
            $currentStatus = request('status', 'all');
            $tabs = [
                'all' => 'Semua',
                'pending' => 'Menunggu Pembayaran',
                'processing' => 'Diproses',
                'shipped' => 'Sedang Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs sm:text-sm">
            @foreach($tabs as $key => $label)
                <a href="{{ route('customer.orders.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap {{ $currentStatus === $key ? 'bg-emerald-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-200 shadow-xs flex flex-col items-center justify-center my-8">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Transaksi</h3>
            <p class="text-sm text-gray-500 max-w-sm mb-6">
                Anda belum memiliki pesanan dengan status ini. Temukan barang idaman Anda sekarang!
            </p>
            <a href="{{ route('products.index') }}"
               class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs hover:border-gray-300 transition">
                    
                    <!-- Card Header -->
                    <div class="p-4 sm:p-5 bg-gray-50/70 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="font-black text-gray-900 text-sm">{{ $order->order_number }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold border {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div class="p-4 sm:p-5 divide-y divide-gray-100 space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 pt-3 first:pt-0">
                                <img src="{{ $item->product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}"
                                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                                     class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-cover bg-gray-50 border border-gray-100 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 truncate">{{ $item->product_name }}</h4>
                                    @if($item->variant_name)
                                        <p class="text-[11px] text-gray-400">{{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $item->quantity }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs sm:text-sm font-black text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Card Footer & Actions -->
                    <div class="p-4 sm:p-5 bg-white border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-gray-500 block">Total Tagihan:</span>
                            <span class="text-base sm:text-lg font-black text-emerald-600">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <a href="{{ route('orders.show', $order->id) }}"
                               class="px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 font-bold text-xs rounded-xl transition">
                                Detail Pesanan
                            </a>

                            @if($order->status === 'pending')
                                <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                        Bayar Sekarang
                                    </button>
                                </form>
                            @elseif(in_array($order->status, ['shipped', 'delivered']))
                                <form action="{{ route('orders.confirm-delivered', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                        Selesaikan Pesanan
                                    </button>
                                </form>
                            @elseif($order->status === 'completed')
                                <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                    Beri Ulasan
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
