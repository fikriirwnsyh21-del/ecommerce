@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="w-full max-w-7xl 2xl:max-w-[1600px] mx-auto space-y-6" x-data="{
    reviewModalOpen: false,
    selectedItemId: null,
    selectedProductName: '',
    rating: 5,
    ratingLabels: {
        1: '1 Bintang - Sangat Kecewa',
        2: '2 Bintang - Kurang Puas',
        3: '3 Bintang - Cukup Baik',
        4: '4 Bintang - Puas & Rekomendasi',
        5: '5 Bintang - Sangat Puas & Mantap!'
    },
    copiedVa: false,
    copiedAmount: false,
    copiedTrx: false,
    checkingPayment: false,
    checkMessage: '',
    timeLeft: 899,
    timerString: '14:59',
    initTimer() {
        if ({{ $order->status === 'pending' ? 'true' : 'false' }}) {
            const interval = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                    const m = Math.floor(this.timeLeft / 60).toString().padStart(2, '0');
                    const s = (this.timeLeft % 60).toString().padStart(2, '0');
                    this.timerString = `${m}:${s}`;
                } else {
                    clearInterval(interval);
                    this.timerString = '00:00';
                }
            }, 1000);
        }
    },
    copyToClipboard(text, type) {
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(text).then(() => {
            if (type === 'va') { this.copiedVa = true; setTimeout(() => this.copiedVa = false, 2500); }
            if (type === 'amount') { this.copiedAmount = true; setTimeout(() => this.copiedAmount = false, 2500); }
            if (type === 'trx') { this.copiedTrx = true; setTimeout(() => this.copiedTrx = false, 2500); }
        });
    },
    checkPaymentStatus() {
        this.checkingPayment = true;
        this.checkMessage = 'Menghubungkan ke gateway pembayaran...';
        fetch('{{ route('orders.payment-status', $order->id) }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.is_paid) {
                this.checkMessage = 'Pembayaran terdeteksi! Memuat ulang halaman...';
                setTimeout(() => window.location.reload(), 1000);
            } else {
                setTimeout(() => {
                    this.checkingPayment = false;
                    this.checkMessage = 'Belum ada pembayaran masuk. Silakan selesaikan transaksi Anda.';
                    setTimeout(() => this.checkMessage = '', 4000);
                }, 1000);
            }
        })
        .catch(() => {
            this.checkingPayment = false;
            this.checkMessage = 'Gagal memeriksa status. Silakan coba lagi.';
            setTimeout(() => this.checkMessage = '', 3000);
        });
    },
    openReviewModal(itemId, productName) {
        this.selectedItemId = itemId;
        this.selectedProductName = productName;
        this.rating = 5;
        this.reviewModalOpen = true;
    }
}" x-init="initTimer()">

    <!-- Top Nav & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('customer.orders.index') }}" class="hover:text-emerald-600">Pesanan Saya</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">{{ $order->order_number }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Detail Transaksi</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-black border {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- Visual Order Status Timeline -->
    @php
        $statuses = [
            'pending' => ['step' => 1, 'label' => 'Pesanan Dibuat'],
            'paid' => ['step' => 2, 'label' => 'Sudah Dibayar'],
            'processing' => ['step' => 3, 'label' => 'Diproses Penjual'],
            'shipped' => ['step' => 4, 'label' => 'Sedang Dikirim'],
            'delivered' => ['step' => 5, 'label' => 'Telah Sampai'],
            'completed' => ['step' => 6, 'label' => 'Pesanan Selesai'],
        ];

        $currentStep = match ($order->status) {
            'pending' => 1,
            'paid' => 2,
            'processing' => 3,
            'shipped' => 4,
            'delivered' => 5,
            'completed' => 6,
            'cancelled' => 0,
            default => 1,
        };
    @endphp

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-xs">
        @if($order->status === 'cancelled')
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                <svg class="w-6 h-6 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h4 class="font-bold">Pesanan Dibatalkan</h4>
                    <p class="text-xs text-rose-700 mt-0.5">Pesanan ini telah dibatalkan. Stok barang telah dikembalikan ke sistem.</p>
                </div>
            </div>
        @else
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-6">Status Perjalanan Pesanan</h3>
            <div class="relative flex items-center justify-between">
                <!-- Connecting Line -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-gray-200 w-full z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-500 z-0 transition-all duration-500"
                     style="width: {{ (($currentStep - 1) / 5) * 100 }}%"></div>

                <!-- Steps -->
                @foreach($statuses as $stKey => $stData)
                    @php $isPassed = $currentStep >= $stData['step']; @endphp
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm border-2 transition-all {{ $isPassed ? 'bg-emerald-500 border-emerald-500 text-white shadow-sm' : 'bg-white border-gray-300 text-gray-400' }}">
                            @if(($isPassed && $currentStep > $stData['step']) || ($order->status === 'completed' && $stData['step'] === 6))
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $stData['step'] }}
                            @endif
                        </div>
                        <span class="mt-2 text-[10px] sm:text-xs font-semibold text-center max-w-[60px] sm:max-w-[80px] leading-tight {{ $isPassed ? 'text-gray-900' : 'text-gray-400' }}">
                            {{ $stData['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($order->status === 'pending')
        <!-- Realistic Payment Gateway Card -->
        <div class="bg-white rounded-3xl border-2 border-emerald-500/30 overflow-hidden shadow-lg space-y-0 relative">
            <!-- Top Banner with Countdown -->
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-300 animate-ping"></span>
                        <span class="text-xs font-black uppercase tracking-widest text-emerald-100">Menunggu Pembayaran</span>
                    </div>
                    <h2 class="text-lg sm:text-xl font-black">Selesaikan Pembayaran Anda</h2>
                    <p class="text-xs text-emerald-100">
                        Pesanan akan dibatalkan otomatis oleh sistem jika pembayaran tidak diterima dalam batas waktu.
                    </p>
                </div>

                <div class="bg-white/15 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/20 text-center shrink-0">
                    <span class="text-[10px] uppercase font-bold text-emerald-100 block">Sisa Waktu Pembayaran</span>
                    <span class="text-xl sm:text-2xl font-black font-mono text-amber-300 tracking-wider" x-text="timerString">14:59</span>
                </div>
            </div>

            <!-- Content Body based on payment method -->
            <div class="p-6 sm:p-8">
                @if($order->payment?->is_qris)
                    <!-- REALISTIC QRIS COMPONENT -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        <!-- Left: Authentic QRIS Box (5 cols) -->
                        <div class="lg:col-span-5 flex flex-col items-center">
                            <div class="w-full max-w-sm bg-white rounded-3xl border-2 border-rose-500 shadow-xl overflow-hidden p-5 flex flex-col items-center text-center relative">
                                
                                <!-- QRIS Official Banner Header -->
                                <div class="w-full flex items-center justify-between pb-3 border-b-2 border-rose-100 mb-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 bg-rose-600 text-white font-black text-xs tracking-tighter rounded">QRIS</span>
                                        <span class="text-[10px] font-black text-rose-800 uppercase tracking-tight">Pembayaran Nasional</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded">GPN</span>
                                </div>

                                <!-- Merchant Name & NMID -->
                                <div class="mb-3">
                                    <h3 class="font-black text-sm text-gray-900 tracking-tight">KEBUTUHAN GAMING GEN Z OFFICIAL STORE</h3>
                                    <p class="text-[10px] text-gray-400 font-mono">NMID: ID1020260920001 • Acquirer: Bank Indonesia</p>
                                </div>

                                <!-- High-Res QR Code Frame -->
                                <div class="relative p-3 bg-white rounded-2xl border-2 border-gray-900 shadow-inner flex items-center justify-center">
                                    <img src="{{ $order->payment?->qris_qr_url }}"
                                         alt="QRIS Barcode"
                                         class="w-60 h-60 sm:w-64 sm:h-64 object-contain rounded-lg">
                                    
                                    <!-- QRIS Center Logo Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="w-10 h-10 bg-white rounded-xl shadow-md border border-gray-200 flex items-center justify-center p-1">
                                            <span class="bg-rose-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded">QRIS</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- QR Caption -->
                                <p class="text-[11px] font-semibold text-gray-600 mt-3">
                                    Pindai kode QRIS di atas untuk membayar
                                </p>

                                <!-- Supported App Logos / Badges -->
                                <div class="flex flex-wrap items-center justify-center gap-1 mt-3 pt-3 border-t border-gray-100 w-full text-[10px] font-bold text-gray-600">
                                    <span class="px-1.5 py-0.5 bg-sky-50 text-sky-700 rounded border border-sky-100">GoPay</span>
                                    <span class="px-1.5 py-0.5 bg-purple-50 text-purple-700 rounded border border-purple-100">OVO</span>
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded border border-blue-100">DANA</span>
                                    <span class="px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded border border-orange-100">ShopeePay</span>
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-800 rounded border border-blue-100">BCA</span>
                                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-800 rounded border border-amber-100">Livin</span>
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-900 rounded border border-blue-100">BRImo</span>
                                </div>

                                <!-- Download QR Button -->
                                <a href="{{ $order->payment?->qris_qr_url }}" download="QRIS-{{ $order->order_number }}.png" target="_blank"
                                   class="mt-3 text-xs font-bold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1 hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Unduh / Buka Gambar QR</span>
                                </a>
                            </div>
                        </div>

                        <!-- Right: Amount, Transaction ID, Actions & Instructions (7 cols) -->
                        <div class="lg:col-span-7 space-y-5">
                            
                            <!-- Total Amount Card -->
                            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-gray-200">
                                    <div>
                                        <span class="text-xs text-gray-500 font-semibold block">Total Jumlah Pembayaran:</span>
                                        <span class="text-2xl sm:text-3xl font-black text-gray-900">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <button type="button"
                                            @click="copyToClipboard('{{ $order->grand_total }}', 'amount')"
                                            class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-2xs flex items-center gap-1.5 shrink-0">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span x-text="copiedAmount ? '✓ Tersalin!' : 'Salin Nominal'">Salin Nominal</span>
                                    </button>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                    <div>
                                        <span class="text-gray-500 block">Kode Referensi QRIS:</span>
                                        <span class="font-mono font-bold text-gray-800">{{ $order->payment?->transaction_id ?? $order->order_number }}</span>
                                    </div>
                                    <button type="button"
                                            @click="copyToClipboard('{{ $order->payment?->transaction_id ?? $order->order_number }}', 'trx')"
                                            class="text-emerald-600 hover:text-emerald-700 font-bold flex items-center gap-1">
                                        <span x-text="copiedTrx ? '✓ Tersalin' : 'Salin'">Salin</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Real-time Status Check Feedback -->
                            <template x-if="checkMessage">
                                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span x-text="checkMessage"></span>
                                </div>
                            </template>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button type="button"
                                        @click="checkPaymentStatus()"
                                        :disabled="checkingPayment"
                                        class="py-3 px-4 rounded-xl border border-gray-300 hover:border-gray-400 bg-white text-gray-800 text-xs font-bold shadow-2xs transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" :class="{ 'animate-spin': checkingPayment }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span x-text="checkingPayment ? 'Memeriksa...' : 'Cek Status Pembayaran'">Cek Status Pembayaran</span>
                                </button>

                                <!-- Instant Pay Simulation (Preserves 'Simulasi Bayar Instan' for tests) -->
                                <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black shadow-md hover:shadow-lg transition flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Simulasi Bayar Instan</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Step-by-Step Accordion -->
                            <div class="border border-gray-200 rounded-2xl divide-y divide-gray-100 overflow-hidden text-xs" x-data="{ openTab: 1 }">
                                <div class="p-3 bg-gray-50 font-black text-gray-800 uppercase tracking-wider text-[11px]">
                                    Panduan Cara Pembayaran QRIS
                                </div>

                                <!-- GoPay / Tokopedia -->
                                <div>
                                    <button type="button" @click="openTab = openTab === 1 ? null : 1" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                        <span>Cara Bayar via GoPay / Tokopedia</span>
                                        <svg class="w-4 h-4 transition-transform" :class="openTab === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="openTab === 1" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Buka aplikasi <strong>Gojek</strong> atau <strong>Tokopedia</strong> di smartphone Anda.</li>
                                            <li>Klik menu <strong>Bayar / Scan</strong> pada halaman utama.</li>
                                            <li>Arahkan kamera ke kode QRIS di samping layar ini.</li>
                                            <li>Periksa nama merchant <strong>KEBUTUHAN GAMING GEN Z</strong> dan jumlah nominal tagihan.</li>
                                            <li>Masukkan PIN GoPay Anda untuk mengonfirmasi pembayaran.</li>
                                        </ol>
                                    </div>
                                </div>

                                <!-- BCA Mobile -->
                                <div>
                                    <button type="button" @click="openTab = openTab === 2 ? null : 2" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                        <span>Cara Bayar via BCA Mobile (m-BCA)</span>
                                        <svg class="w-4 h-4 transition-transform" :class="openTab === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="openTab === 2" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Buka aplikasi <strong>BCA Mobile</strong> dan login ke <strong>m-BCA</strong>.</li>
                                            <li>Pilih tombol <strong>QRIS</strong> di bagian tengah menu navigasi bawah.</li>
                                            <li>Pindai barcode QRIS Kebutuhan Gaming Gen Z di atas.</li>
                                            <li>Pastikan nominal pembayaran sesuai dengan total belanja.</li>
                                            <li>Masukkan <strong>PIN m-BCA</strong> Anda. Transaksi selesai!</li>
                                        </ol>
                                    </div>
                                </div>

                                <!-- DANA / OVO / ShopeePay -->
                                <div>
                                    <button type="button" @click="openTab = openTab === 3 ? null : 3" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                        <span>Cara Bayar via DANA, OVO, atau ShopeePay</span>
                                        <svg class="w-4 h-4 transition-transform" :class="openTab === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="openTab === 3" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Buka aplikasi <strong>DANA</strong>, <strong>OVO</strong>, atau <strong>ShopeePay</strong>.</li>
                                            <li>Klik tombol <strong>Scan / Bayar</strong>.</li>
                                            <li>Scan kode QRIS di layar ini.</li>
                                            <li>Verifikasi nominal tagihan dan selesaikan dengan PIN akun Anda.</li>
                                        </ol>
                                    </div>
                                </div>

                                <!-- Livin Mandiri / BRImo -->
                                <div>
                                    <button type="button" @click="openTab = openTab === 4 ? null : 4" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                        <span>Cara Bayar via Livin' by Mandiri & BRImo</span>
                                        <svg class="w-4 h-4 transition-transform" :class="openTab === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="openTab === 4" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Login ke aplikasi <strong>Livin' by Mandiri</strong> atau <strong>BRImo</strong>.</li>
                                            <li>Pilih ikon menu <strong>QR Bayar / QRIS</strong>.</li>
                                            <li>Pindai barcode QRIS di layar ini dan konfirmasi dengan PIN Anda.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                @elseif($order->payment?->is_virtual_account)
                    <!-- REALISTIC VIRTUAL ACCOUNT COMPONENT -->
                    <div class="space-y-6">
                        <div class="p-6 rounded-2xl bg-gray-50 border border-gray-200 grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                            <div class="sm:col-span-8 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg font-mono font-black text-xs text-white bg-blue-700">
                                        {{ $order->payment?->bank_name ?? 'BANK' }}
                                    </span>
                                    <h3 class="font-black text-base text-gray-900">
                                        {{ $order->payment?->payment_method }}
                                    </h3>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Verifikasi Otomatis 24 Jam</span>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs text-gray-500 font-semibold block">Nomor Virtual Account:</span>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl sm:text-2xl font-mono font-black text-emerald-700 tracking-wider">
                                            {{ $order->payment?->formatted_va_number }}
                                        </span>
                                        <button type="button"
                                                @click="copyToClipboard('{{ $order->payment?->va_number }}', 'va')"
                                                class="px-3 py-1.5 bg-white border border-gray-300 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-2xs flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span x-text="copiedVa ? '✓ Tersalin!' : 'Salin No. VA'">Salin No. VA</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600">
                                    <span>Atas Nama: </span>
                                    <strong class="text-gray-900">KEBUTUHAN GAMING GEN Z / {{ strtoupper($order->shipping_address['recipient_name'] ?? 'PELANGGAN') }}</strong>
                                </div>
                            </div>

                            <div class="sm:col-span-4 sm:border-l sm:border-gray-200 sm:pl-6 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-500 font-semibold block">Total Tagihan:</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl font-black text-gray-900">
                                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                        </span>
                                        <button type="button"
                                                @click="copyToClipboard('{{ $order->grand_total }}', 'amount')"
                                                class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                            <span x-text="copiedAmount ? '✓' : 'Salin'">Salin</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="pt-2 flex flex-col gap-2">
                                    <button type="button"
                                            @click="checkPaymentStatus()"
                                            :disabled="checkingPayment"
                                            class="w-full py-2.5 px-3 rounded-xl border border-gray-300 hover:border-gray-400 bg-white text-gray-800 text-xs font-bold shadow-2xs transition flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-gray-500" :class="{ 'animate-spin': checkingPayment }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span x-text="checkingPayment ? 'Memeriksa...' : 'Cek Status Pembayaran'">Cek Status Pembayaran</span>
                                    </button>

                                    <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black shadow-md transition flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Simulasi Bayar Instan</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- VA Transfer Step Accordion -->
                        <div class="border border-gray-200 rounded-2xl divide-y divide-gray-100 overflow-hidden text-xs" x-data="{ openTab: 1 }">
                            <div class="p-3 bg-gray-50 font-black text-gray-800 uppercase tracking-wider text-[11px]">
                                Panduan Pembayaran Virtual Account
                            </div>
                            <div>
                                <button type="button" @click="openTab = openTab === 1 ? null : 1" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                    <span>Transfer via Mobile Banking (m-Banking)</span>
                                    <svg class="w-4 h-4 transition-transform" :class="openTab === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="openTab === 1" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Login ke aplikasi Mobile Banking Anda.</li>
                                        <li>Pilih menu <strong>Transfer</strong> &gt; <strong>Virtual Account</strong>.</li>
                                        <li>Masukkan nomor Virtual Account: <strong>{{ $order->payment?->va_number }}</strong>.</li>
                                        <li>Pastikan nama tagihan dan nominal tagihan sesuai.</li>
                                        <li>Masukkan PIN m-Banking Anda untuk menyelesaikan pembayaran.</li>
                                    </ol>
                                </div>
                            </div>
                            <div>
                                <button type="button" @click="openTab = openTab === 2 ? null : 2" class="w-full px-4 py-3 flex items-center justify-between font-bold text-left text-gray-800 hover:bg-gray-50">
                                    <span>Transfer via Mesin ATM</span>
                                    <svg class="w-4 h-4 transition-transform" :class="openTab === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="openTab === 2" class="px-4 pb-4 text-gray-600 space-y-1 leading-relaxed bg-white">
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Masukkan kartu ATM dan 6 digit PIN Anda.</li>
                                        <li>Pilih menu <strong>Transaksi Lainnya</strong> &gt; <strong>Transfer</strong> &gt; <strong>Ke Rek Virtual Account</strong>.</li>
                                        <li>Ketik nomor Virtual Account: <strong>{{ $order->payment?->va_number }}</strong>.</li>
                                        <li>Periksa rincian konfirmasi tagihan di layar mesin ATM.</li>
                                        <li>Tekan <strong>Ya / Benar</strong> untuk memproses pembayaran.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($order->payment?->is_cod)
                    <!-- REALISTIC COD COMPONENT -->
                    <div class="p-6 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-base text-gray-900">Metode Bayar di Tempat (COD)</h3>
                                <p class="text-xs text-gray-600">Pesanan telah diteruskan ke toko penjual untuk disiapkan dan dikirim.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-white rounded-xl border border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="text-gray-500 block">Uang Tunai yang Harus Disiapkan:</span>
                                <span class="text-xl font-black text-gray-900">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                            <span class="text-emerald-700 bg-emerald-50 font-bold px-3 py-1 rounded-lg border border-emerald-200">
                                Tanpa Biaya Tambahan
                            </span>
                        </div>

                        <div class="text-xs text-gray-600 space-y-1 bg-white/70 p-4 rounded-xl">
                            <strong class="text-gray-900 block font-bold">Aturan Pembayaran COD:</strong>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Siapkan uang tunai pas kepada kurir saat pesanan sampai.</li>
                                <li>Pastikan nomor HP yang tertera di alamat pengiriman selalu aktif.</li>
                                <li>Dilarang membuka kemasan segel paket sebelum pembayaran diserahkan kepada kurir.</li>
                            </ul>
                        </div>

                        <div class="pt-2">
                            <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                    Simulasi Bayar Instan (Konfirmasi Terima & Bayar)
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <!-- GENERAL EWALLET / TRANSFER -->
                    <div class="p-6 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-black text-base text-gray-900">{{ $order->payment?->payment_method ?? 'Pembayaran' }}</h3>
                                <p class="text-xs text-gray-500">Selesaikan pembayaran sesuai total tagihan di bawah ini.</p>
                            </div>
                            <span class="text-xl font-black text-emerald-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                    Simulasi Bayar Instan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- 2 Column Details: Shipping, Items, Payment -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Items & Delivery Info (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Products List -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                <h3 class="font-black text-gray-900 text-base pb-3 border-b border-gray-100">
                    Daftar Produk ({{ $order->items->count() }})
                </h3>

                <div class="divide-y divide-gray-100 space-y-4">
                    @foreach($order->items as $item)
                        <div class="pt-4 first:pt-0 space-y-3">
                            <div class="flex items-start gap-4">
                                <img src="{{ $item->product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80' }}" alt="{{ $item->product_name }}"
                                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                                     class="w-16 h-16 rounded-xl object-cover border border-gray-100 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs sm:text-sm text-gray-900 leading-snug">{{ $item->product_name }}</h4>
                                    @if($item->variant_name)
                                        <p class="text-[11px] text-gray-500">{{ $item->variant_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-black text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Review Section if completed -->
                            @if($order->status === 'completed')
                                <div class="pt-3 mt-2 border-t border-gray-100">
                                    @if($item->is_reviewed || $item->review)
                                        <div class="p-3.5 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl space-y-1.5 text-xs">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-emerald-800 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    Ulasan Anda Telah Terbit
                                                </span>
                                                <div class="flex text-amber-500 font-bold text-sm">
                                                    @for($s = 1; $s <= ($item->review?->rating ?? 5); $s++)
                                                        <span>★</span>
                                                    @endfor
                                                    @for($s = ($item->review?->rating ?? 5) + 1; $s <= 5; $s++)
                                                        <span class="text-gray-300">★</span>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($item->review?->comment)
                                                <p class="text-gray-700 leading-relaxed italic">"{{ $item->review->comment }}"</p>
                                            @endif
                                            @if($item->review?->photo_path)
                                                <div class="pt-1.5">
                                                    <img src="{{ asset('storage/' . $item->review->photo_path) }}" alt="Foto Ulasan" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-xl border border-emerald-200 shadow-2xs">
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs text-gray-400">Bagikan pengalaman Anda menggunakan produk ini</p>
                                            <button type="button"
                                                    @click="openReviewModal({{ $item->id }}, '{{ addslashes($item->product_name) }}')"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition transform hover:scale-[1.02] active:scale-98">
                                                <svg class="w-4 h-4 text-amber-100" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span>Beri Ulasan Produk</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping & Address -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                <h3 class="font-black text-gray-900 text-base pb-3 border-b border-gray-100">
                    Informasi Pengiriman
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="space-y-1">
                        <span class="text-gray-400 font-semibold block text-xs">Kurir & Layanan:</span>
                        <p class="font-bold text-gray-900">{{ $order->shipping_courier }}</p>
                        @if($order->tracking_number)
                            <div class="mt-2 p-2.5 bg-gray-50 border border-gray-200 rounded-xl inline-block">
                                <span class="text-gray-500 text-[11px] block">Nomor Resi:</span>
                                <span class="font-mono font-black text-emerald-600 text-xs sm:text-sm">{{ $order->tracking_number }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <span class="text-gray-400 font-semibold block text-xs">Alamat Penerima:</span>
                        <p class="font-bold text-gray-900">{{ $order->shipping_address['recipient_name'] ?? '-' }} ({{ $order->shipping_address['phone'] ?? '-' }})</p>
                        <p class="text-gray-600 leading-relaxed text-xs">
                            {{ $order->shipping_address['address_line'] ?? '' }}, 
                            {{ $order->shipping_address['district'] ?? '' }}, 
                            {{ $order->shipping_address['city'] ?? '' }}, 
                            {{ $order->shipping_address['province'] ?? '' }} 
                            {{ $order->shipping_address['postal_code'] ?? '' }}
                        </p>
                    </div>
                </div>

                @if($order->notes)
                    <div class="pt-3 border-t border-gray-100 text-xs">
                        <span class="text-gray-400 font-semibold">Catatan Pembeli:</span>
                        <p class="text-gray-700 italic mt-0.5">"{{ $order->notes }}"</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right: Payment Info & Cost Summary (4 cols) -->
        <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">
            
            <!-- Payment Method Box -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-3 text-xs sm:text-sm">
                <h3 class="font-black text-gray-900 text-base pb-2 border-b border-gray-100">Informasi Pembayaran</h3>
                
                @if($order->payment)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Metode:</span>
                        <span class="font-bold text-gray-900">{{ $order->payment->payment_method }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Status Pembayaran:</span>
                        <span class="font-extrabold uppercase {{ $order->payment->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $order->payment->status === 'paid' ? 'LUNAS' : 'MENUNGGU' }}
                        </span>
                    </div>
                    @if($order->payment->paid_at)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Dibayar Pada:</span>
                            <span class="font-semibold text-gray-900">{{ $order->payment->paid_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Cost Summary -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-3 text-xs sm:text-sm">
                <h3 class="font-black text-gray-900 text-base pb-2 border-b border-gray-100">Rincian Biaya</h3>

                <div class="flex items-center justify-between text-gray-600">
                    <span>Total Harga Produk</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between text-emerald-600">
                        <span>Potongan Diskon / Voucher</span>
                        <span class="font-bold">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="font-bold text-gray-900 text-sm">Total Belanja</span>
                    <span class="text-lg font-black text-emerald-600">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Contextual Action Buttons -->
            <div class="space-y-2.5">
                @if($order->status === 'pending')
                    <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <span>Simulasi Bayar Instan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>

                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit"
                                class="w-full py-2.5 border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs rounded-xl transition">
                            Batalkan Pesanan
                        </button>
                    </form>
                @elseif(in_array($order->status, ['shipped', 'delivered']))
                    <form action="{{ route('orders.confirm-delivered', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <span>Konfirmasi Pesanan Diterima</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                @elseif(in_array($order->status, ['paid', 'processing']))
                    <form action="{{ route('orders.confirm-delivered', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <span>Selesaikan Pesanan Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                @elseif($order->status === 'completed')
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-center">
                        <div class="flex items-center justify-center gap-1.5 font-bold text-sm text-emerald-700">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pesanan Telah Selesai</span>
                        </div>
                        <p class="text-xs text-emerald-600 mt-1 font-medium">Seluruh perjalanan pesanan telah selesai. Terima kasih telah berbelanja!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Review Modal (Alpine.js) -->
    <div x-show="reviewModalOpen" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:p-0">
            <!-- Dark Backdrop (No blur to prevent GPU glitch) -->
            <div class="fixed inset-0 bg-slate-950/60 transition-opacity" @click="reviewModalOpen = false"></div>

            <!-- Modal Content Dialog (relative z-10 and click.stop) -->
            <div class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8"
                 @click.stop>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-black text-sm">
                            ★
                        </div>
                        <h3 class="text-base font-black text-gray-900">Ulas Produk</h3>
                    </div>
                    <button type="button" @click="reviewModalOpen = false" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-base flex items-center justify-center shrink-0">
                        📦
                    </div>
                    <p class="text-xs text-gray-700 font-bold line-clamp-2 leading-relaxed" x-text="selectedProductName"></p>
                </div>

                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="order_item_id" x-bind:value="selectedItemId" :value="selectedItemId">
                    <input type="hidden" name="rating" x-bind:value="rating" :value="rating">

                    <!-- Star Rating Interactive Picker -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Rating Kepuasan</label>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                    <button type="button" @click="rating = star" class="text-2xl transition-transform hover:scale-125 focus:outline-hidden"
                                            :class="star <= rating ? 'text-amber-400' : 'text-gray-300'">
                                        ★
                                    </button>
                                </template>
                            </div>
                            <span class="text-xs font-bold text-amber-600 ml-1.5" x-text="ratingLabels[rating]"></span>
                        </div>
                    </div>

                    <!-- Review Comment -->
                    <div>
                        <label for="comment" class="block text-xs font-bold text-gray-700 mb-1">Pengalaman & Ulasan Anda</label>
                        <textarea id="comment" name="comment" rows="3" required placeholder="Ceritakan kepuasan Anda mengenai kualitas gear, performa gaming, packing, dan pelayanan toko..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-250 rounded-xl text-xs sm:text-sm text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 outline-hidden transition"></textarea>
                    </div>

                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Foto Ulasan Produk (Opsional)</label>
                        <input type="file" name="photo" accept="image/png,image/jpeg,image/webp"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                        <p class="text-[10px] text-gray-400 mt-1">Format foto JPG, PNG, atau WEBP (Maksimal 2 MB).</p>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-1.5">
                            <span>Kirim Ulasan Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
