@extends('layouts.admin')

@section('title', 'Inspeksi Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Inspeksi Pesanan #{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-500">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-700">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Status Overview -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Transaksi Platform</p>
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
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase {{ $badges[$order->status] ?? 'bg-slate-100 text-slate-800' }}">
                    {{ $order->status }}
                </span>
                @if($order->tracking_number)
                    <span class="text-xs text-slate-500">No. Resi: <strong class="font-mono text-slate-900">{{ $order->tracking_number }}</strong></span>
                @endif
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs text-slate-400">Total Transaksi</p>
            <p class="text-lg font-black text-emerald-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- 2 Columns: Shipping & Buyer -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Shipping Address -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-3">
            <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">
                Alamat Tujuan Pengiriman
            </h3>
            <div class="text-xs space-y-1 text-slate-600">
                <p class="font-bold text-slate-900 text-sm">{{ $order->shipping_address['recipient_name'] ?? '-' }}</p>
                <p class="text-slate-500">{{ $order->shipping_address['phone'] ?? '-' }}</p>
                <p class="pt-1 leading-relaxed">{{ $order->shipping_address['full_address'] ?? '-' }}</p>
                <p class="text-slate-400">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }}</p>
                <div class="pt-2">
                    <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-[11px] font-bold">
                        Kurir: {{ $order->shipping_courier }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Buyer & Payment Details -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-3">
            <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">
                Detail Pembeli & Pembayaran
            </h3>
            <div class="text-xs space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nama Akun:</span>
                    <span class="font-bold text-slate-800">{{ $order->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Email:</span>
                    <span class="font-bold text-slate-800">{{ $order->user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Metode Bayar:</span>
                    <span class="font-bold text-slate-800">{{ $order->payment?->payment_method ?? 'bank_transfer' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Status Bayar:</span>
                    <span class="font-extrabold {{ $order->payment?->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ strtoupper($order->payment?->status ?? 'PENDING') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Items in this Order -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
        <h3 class="font-black text-slate-900 text-sm pb-3 border-b border-slate-100">
            Daftar Produk yang Dipesan ({{ $order->items->count() }} Item)
        </h3>

        <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <div class="py-3.5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                             class="w-12 h-12 rounded-xl object-cover border border-slate-100 shrink-0">
                        <div>
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900">{{ $item->product_name }}</h4>
                            <p class="text-[11px] text-emerald-600 font-semibold">Toko: {{ $item->shop->name ?? '-' }}</p>
                            @if($item->variant_name)
                                <p class="text-[10px] text-slate-500">Varian: {{ $item->variant_name }}</p>
                            @endif
                            <p class="text-xs text-slate-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <span class="font-black text-sm text-slate-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
