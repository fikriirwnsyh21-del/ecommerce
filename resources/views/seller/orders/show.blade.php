@extends('layouts.seller')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Detail Pesanan #{{ $order->order_number }}</h1>
            <p class="text-xs text-gray-500">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('seller.orders.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Status Banner & Action Card -->
    <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Pesanan Saat Ini</p>
                <div class="flex items-center gap-2 mt-1">
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
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $order->status }}
                    </span>
                    @if($order->tracking_number)
                        <span class="text-xs text-gray-500">Nomor Resi: <strong class="font-mono text-gray-900">{{ $order->tracking_number }}</strong></span>
                    @endif
                </div>
            </div>

            <!-- Action Buttons for Seller -->
            <div class="flex items-center gap-3">
                @if($order->status === 'paid')
                    <form action="{{ route('seller.orders.process', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                            Terima & Proses Pesanan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Input Tracking Number if Processing -->
        @if($order->status === 'processing')
            <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-3">
                <div class="flex items-center gap-2 text-emerald-900">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="font-black text-xs sm:text-sm">Kirim Pesanan & Masukkan Nomor Resi</h4>
                </div>
                <form action="{{ route('seller.orders.ship', $order->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 items-center">
                    @csrf
                    <input type="text" name="tracking_number" required placeholder="Contoh: JP82718291039 (JNE / J&T)"
                           class="w-full sm:flex-1 px-4 py-2.5 bg-white border border-emerald-300 rounded-xl text-xs font-mono outline-none focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition shrink-0">
                        Konfirmasi Kirim Pesanan
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- 2 Column: Shipping Info & Buyer Info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Shipping Address -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-3">
            <h3 class="font-black text-gray-900 text-xs uppercase tracking-wider pb-2 border-b border-gray-100">
                Alamat Tujuan Pengiriman
            </h3>
            <div class="text-xs space-y-1.5 text-gray-600">
                <p class="font-extrabold text-gray-900 text-sm">{{ $order->shipping_address['recipient_name'] ?? '-' }}</p>
                <p class="text-gray-500">{{ $order->shipping_address['phone'] ?? '-' }}</p>
                <p class="pt-1 leading-relaxed">{{ $order->shipping_address['full_address'] ?? '-' }}</p>
                <p class="text-gray-400">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-800 rounded-lg text-[11px] font-bold">
                        Kurir: {{ $order->shipping_courier }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Buyer Info -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-3">
            <h3 class="font-black text-gray-900 text-xs uppercase tracking-wider pb-2 border-b border-gray-100">
                Informasi Pembeli & Transaksi
            </h3>
            <div class="text-xs space-y-2 text-gray-600">
                <div class="flex justify-between">
                    <span class="text-gray-400">Nama Akun:</span>
                    <span class="font-bold text-gray-800">{{ $order->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Email:</span>
                    <span class="font-bold text-gray-800">{{ $order->user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Metode Bayar:</span>
                    <span class="font-bold text-gray-800">{{ $order->payment?->payment_method ?? 'bank_transfer' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status Bayar:</span>
                    <span class="font-extrabold {{ $order->payment?->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ strtoupper($order->payment?->status ?? 'PENDING') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items in this Order belonging to Seller -->
    <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
        <h3 class="font-black text-gray-900 text-sm pb-3 border-b border-gray-100">
            Daftar Produk yang Dipesan
        </h3>

        <div class="divide-y divide-gray-100">
            @foreach($order->items as $item)
                <div class="py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                             class="w-14 h-14 rounded-xl object-cover border border-gray-100 shrink-0">
                        <div>
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900">{{ $item->product_name }}</h4>
                            @if($item->variant_name)
                                <p class="text-[11px] text-gray-500">Varian: {{ $item->variant_name }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <span class="font-black text-sm text-gray-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
