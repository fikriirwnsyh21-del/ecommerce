@extends('layouts.app')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="w-full max-w-7xl 2xl:max-w-[1600px] mx-auto space-y-6" x-data="{
    selectedAddressId: {{ $primaryAddress ? $primaryAddress->id : 'null' }},
    selectedPayment: 'QRIS (Semua Pembayaran)',
    shippingCost: 15000,
    subtotal: {{ $selectedItems->sum(fn($i) => $i->subtotal) }},
    discountAmount: 0,
    appliedVoucherCode: '',
    voucherInput: '',
    voucherMessage: '',
    voucherSuccess: false,
    addressModalOpen: false,
    addAddressModalOpen: {{ $errors->any() ? 'true' : 'false' }},

    get grandTotal() {
        return Math.max(0, this.subtotal + this.shippingCost - this.discountAmount);
    },

    updateCourier(cost) {
        this.shippingCost = Number(cost);
    },

    checkVoucher() {
        if (!this.voucherInput) return;
        fetch('{{ route('checkout.voucher') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: this.voucherInput,
                subtotal: this.subtotal
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.discountAmount = data.discount;
                this.appliedVoucherCode = data.voucherCode;
                this.voucherSuccess = true;
                this.voucherMessage = data.message;
            } else {
                this.discountAmount = 0;
                this.appliedVoucherCode = '';
                this.voucherSuccess = false;
                this.voucherMessage = data.message;
            }
        })
        .catch(() => {
            this.voucherSuccess = false;
            this.voucherMessage = 'Gagal memeriksa voucher.';
        });
    }
}">

    <!-- Title -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Checkout Pesanan</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Selesaikan rincian alamat pengiriman dan pembayaran</p>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf
        
        <input type="hidden" name="address_id" :value="selectedAddressId">
        <input type="hidden" name="voucher_code" :value="appliedVoucherCode">

        <!-- Left Column: Address, Items, Shipping, Payment (8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- 1. Alamat Pengiriman -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2 font-black text-gray-900 text-base">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Alamat Pengiriman</span>
                    </div>
                    @if($addresses->isNotEmpty())
                        <button type="button" @click="addressModalOpen = true" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 cursor-pointer">
                            Pilih Alamat Lain
                        </button>
                    @else
                        <button type="button" @click="addAddressModalOpen = true" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 cursor-pointer">
                            + Tambah Alamat
                        </button>
                    @endif
                </div>

                @if($addresses->isEmpty())
                    <div class="p-5 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-950 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <div class="font-bold text-xs sm:text-sm text-amber-900">Anda belum menambahkan alamat pengiriman</div>
                            <p class="text-xs text-amber-700">Tambahkan alamat sekarang agar barang dapat langsung dikirim ke tujuan Anda.</p>
                        </div>
                        <button type="button" @click="addAddressModalOpen = true"
                                class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition shrink-0 cursor-pointer flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Tambah Alamat Sekarang</span>
                        </button>
                    </div>
                @else
                    @foreach($addresses as $addr)
                        <div x-show="selectedAddressId === {{ $addr->id }}" class="space-y-1 text-xs sm:text-sm">
                            <div class="flex items-center gap-2 font-bold text-gray-900">
                                <span>{{ $addr->recipient_name }}</span>
                                <span>({{ $addr->phone }})</span>
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px]">{{ $addr->label }}</span>
                            </div>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $addr->address_line }}, {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- 2. Produk yang Dibeli -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                <h3 class="font-black text-gray-900 text-base pb-3 border-b border-gray-100">
                    Produk yang Dipesan ({{ $selectedItems->count() }} Barang)
                </h3>

                <div class="divide-y divide-gray-100 space-y-3">
                    @foreach($selectedItems as $item)
                        <div class="flex items-center gap-4 pt-3 first:pt-0">
                            <img src="{{ $item->product->thumbnail_url }}" alt="{{ $item->product->name }}"
                                 onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';"
                                 class="w-16 h-16 rounded-xl object-cover border border-gray-100 shrink-0">
                            <div class="flex-1 space-y-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-gray-900 line-clamp-1">{{ $item->product->name }}</h4>
                                @if($item->variant)
                                    <p class="text-[11px] text-gray-400">Varian: {{ $item->variant->value }}</p>
                                @endif
                                <div class="text-xs text-gray-500">
                                    {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-sm font-black text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Opsi Pengiriman -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-4">
                <h3 class="font-black text-gray-900 text-base pb-3 border-b border-gray-100">
                    Pilih Kurir Pengiriman
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($couriers as $idx => $cour)
                        <label class="p-4 rounded-2xl border cursor-pointer hover:border-emerald-500 transition flex flex-col justify-between"
                               :class="shippingCost === {{ $cour['cost'] }} ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200'">
                            <div class="flex items-center justify-between mb-2">
                                <input type="radio" name="shipping_courier" value="{{ $cour['name'] }}"
                                       @click="updateCourier({{ $cour['cost'] }})"
                                       {{ $idx === 0 ? 'checked' : '' }}
                                       class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="text-xs font-black text-emerald-600">Rp {{ number_format($cour['cost'], 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-xs sm:text-sm text-gray-900 block">{{ $cour['service'] }}</span>
                                <span class="text-[11px] text-gray-400">Estimasi {{ $cour['etd'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 4. Metode Pembayaran -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2 font-black text-gray-900 text-base">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span>Metode Pembayaran</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Enkripsi Aman & Bebas Biaya</span>
                    </span>
                </div>

                <div class="space-y-6">
                    @foreach($paymentMethods as $cat)
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-wider flex items-center gap-2">
                                    <span>{{ $cat['category'] }}</span>
                                </h4>
                                @if(isset($cat['badge']))
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full {{ $cat['badge'] === 'Paling Populer' ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $cat['badge'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($cat['methods'] as $m)
                                    <label class="p-3.5 sm:p-4 rounded-2xl border cursor-pointer flex items-start gap-3.5 transition relative select-none group"
                                           :class="selectedPayment === '{{ $m['name'] }}' ? 'border-emerald-500 bg-emerald-50/40 ring-2 ring-emerald-500/20 shadow-xs' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50/50'">
                                        
                                        <!-- Radio -->
                                        <div class="mt-1 shrink-0">
                                            <input type="radio" name="payment_method" value="{{ $m['name'] }}"
                                                   x-model="selectedPayment"
                                                   class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                        </div>

                                        <!-- Brand Icon -->
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shrink-0 shadow-2xs {{ 
                                            $m['icon'] === 'QRIS' ? 'bg-gradient-to-br from-rose-600 to-red-700 text-white' : (
                                            $m['icon'] === 'BCA' ? 'bg-blue-700 text-white font-mono' : (
                                            $m['icon'] === 'Mandiri' ? 'bg-sky-900 text-amber-300 font-mono' : (
                                            $m['icon'] === 'BRI' ? 'bg-blue-600 text-white font-mono' : (
                                            $m['icon'] === 'BNI' ? 'bg-teal-700 text-amber-200 font-mono' : (
                                            $m['icon'] === 'GoPay' ? 'bg-cyan-500 text-white' : (
                                            $m['icon'] === 'DANA' ? 'bg-blue-500 text-white' : 'bg-emerald-600 text-white'
                                            )))))) }}">
                                            @if($m['icon'] === 'QRIS')
                                                <span class="tracking-tighter font-black text-[11px]">QRIS</span>
                                            @elseif($m['icon'] === 'COD')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            @else
                                                <span>{{ $m['icon'] }}</span>
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <span class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">
                                                    {{ $m['name'] }}
                                                </span>
                                                <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md shrink-0">
                                                    {{ $m['badge'] ?? 'Bebas Biaya' }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-gray-500 leading-tight">
                                                {{ $m['desc'] }}
                                            </p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Dynamic Helper Callout -->
                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 text-xs text-gray-700 space-y-1">
                    <template x-if="selectedPayment.includes('QRIS')">
                        <div class="flex items-start gap-2.5">
                            <span class="text-lg">📱</span>
                            <div>
                                <strong class="text-gray-900 block text-xs">Instruksi Pembayaran QRIS:</strong>
                                <span class="text-gray-600 leading-relaxed">Setelah menekan tombol <em>Bayar Sekarang</em>, barcode QRIS standar Bank Indonesia akan langsung tampil di layar lengkap dengan hitung mundur 15 menit. Anda dapat memindainya melalui GoPay, OVO, DANA, ShopeePay, BCA Mobile, Livin Mandiri, BRImo, atau aplikasi bank apa pun.</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="selectedPayment.includes('Virtual Account')">
                        <div class="flex items-start gap-2.5">
                            <span class="text-lg">💳</span>
                            <div>
                                <strong class="text-gray-900 block text-xs">Instruksi Pembayaran Virtual Account:</strong>
                                <span class="text-gray-600 leading-relaxed">Nomor Virtual Account unik akan otomatis diterbitkan. Anda dapat mentransfer via m-Banking, ATM, atau Internet Banking. Sistem akan memverifikasi pembayaran secara instan tanpa perlu konfirmasi manual.</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="selectedPayment.includes('GoPay') || selectedPayment.includes('DANA')">
                        <div class="flex items-start gap-2.5">
                            <span class="text-lg">⚡</span>
                            <div>
                                <strong class="text-gray-900 block text-xs">Instruksi Pembayaran Dompet Digital:</strong>
                                <span class="text-gray-600 leading-relaxed">Saldo dompet digital Anda akan diproses secara langsung dan aman melalui gerbang pembayaran resmi Kebutuhan Gaming Gen Z.</span>
                            </div>
                        </div>
                    </template>
                    <template x-if="selectedPayment.includes('COD')">
                        <div class="flex items-start gap-2.5">
                            <span class="text-lg">📦</span>
                            <div>
                                <strong class="text-gray-900 block text-xs">Instruksi Bayar di Tempat (Cash on Delivery):</strong>
                                <span class="text-gray-600 leading-relaxed">Harap siapkan uang tunai pas kepada kurir saat pesanan sampai di alamat Anda. Pastikan nomor handphone penerima selalu aktif.</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- 5. Catatan untuk Penjual -->
            <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-xs space-y-2">
                <label for="notes" class="block font-black text-gray-900 text-base">Catatan Pesanan (Opsional)</label>
                <input type="text" id="notes" name="notes" placeholder="Contoh: Titip ke satpam jika sedang tidak ada di rumah"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs sm:text-sm text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500">
            </div>

        </div>

        <!-- Right Column: Voucher & Summary (4 cols) -->
        <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">
            
            <!-- Voucher Input Box -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs space-y-3">
                <h3 class="font-bold text-gray-900 text-sm">Gunakan Promo / Voucher</h3>
                <div class="flex gap-2">
                    <input type="text" x-model="voucherInput" placeholder="Kode Voucher (ex: ONGKIRHEMAT)"
                           class="flex-1 px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs uppercase font-bold focus:bg-white focus:ring-1 focus:ring-emerald-500">
                    <button type="button" @click="checkVoucher()"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        Pakai
                    </button>
                </div>

                <template x-if="voucherMessage">
                    <p class="text-xs font-semibold" :class="voucherSuccess ? 'text-emerald-600' : 'text-rose-600'" x-text="voucherMessage"></p>
                </template>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-xs space-y-4">
                <h3 class="font-black text-gray-900 text-base pb-3 border-b border-gray-100">Ringkasan Pembayaran</h3>

                <div class="space-y-2.5 text-xs sm:text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <span>Total Harga Produk</span>
                        <span class="font-bold text-gray-900">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(subtotal)"></span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>Biaya Pengiriman</span>
                        <span class="font-bold text-gray-900">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(shippingCost)"></span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-emerald-600" x-show="discountAmount > 0">
                        <span class="font-semibold">Potongan Voucher</span>
                        <span class="font-bold">
                            -Rp <span x-text="new Intl.NumberFormat('id-ID').format(discountAmount)"></span>
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-gray-500 text-xs">
                        <span>Biaya Layanan</span>
                        <span class="font-bold text-emerald-600">GRATIS</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="font-bold text-gray-900 text-sm">Total Tagihan</span>
                    <span class="text-xl font-black text-emerald-600">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(grandTotal)"></span>
                    </span>
                </div>

                <button type="submit"
                        :disabled="!selectedAddressId"
                        class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 disabled:pointer-events-none text-white font-black text-sm rounded-xl shadow-md hover:shadow-lg transition duration-150 flex items-center justify-center gap-2">
                    <span>Bayar Sekarang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

        </div>
    </form>

    <!-- Modal Select Other Address -->
    <div x-show="addressModalOpen" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="addressModalOpen = false"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative z-20 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 p-6 sm:p-8" @click.stop>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                        <h3 class="text-base font-black text-gray-900">Pilih Alamat Pengiriman</h3>
                        <button type="button" @click="addressModalOpen = false" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @if($addresses->isEmpty())
                        <div class="py-8 text-center">
                            <p class="text-xs text-gray-500 mb-4">Anda belum memiliki alamat tersimpan.</p>
                            <button type="button" @click="addressModalOpen = false; addAddressModalOpen = true"
                                    class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                                + Tambah Alamat Sekarang
                            </button>
                        </div>
                    @else
                        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                            @foreach($addresses as $addr)
                                <div class="p-4 rounded-2xl border cursor-pointer hover:border-emerald-500 transition"
                                     :class="selectedAddressId === {{ $addr->id }} ? 'border-emerald-500 bg-emerald-50/20' : 'border-gray-200'"
                                     @click="selectedAddressId = {{ $addr->id }}; addressModalOpen = false">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-xs text-gray-900">{{ $addr->label }}</span>
                                        @if($addr->is_primary)
                                            <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded">Utama</span>
                                        @endif
                                    </div>
                                    <p class="text-xs font-semibold text-gray-800">{{ $addr->recipient_name }} ({{ $addr->phone }})</p>
                                    <p class="text-[11px] text-gray-500 line-clamp-2 mt-1">{{ $addr->full_address }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-4 mt-2 border-t border-gray-100 flex items-center justify-between">
                            <button type="button" @click="addressModalOpen = false; addAddressModalOpen = true"
                                    class="text-xs font-bold text-emerald-600 hover:underline cursor-pointer">
                                + Tambah Alamat Baru
                            </button>
                            <a href="{{ route('customer.addresses.index', [], false) }}" class="text-xs text-gray-500 hover:underline">
                                Kelola Semua Alamat &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Address Directly from Checkout -->
    <div x-show="addAddressModalOpen" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="addAddressModalOpen = false"></div>

        <!-- Content Container -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative z-20 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 p-6 sm:p-8" @click.stop>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                        <h3 class="text-lg font-black text-gray-900">Tambah Alamat Pengiriman Baru</h3>
                        <button type="button" @click="addAddressModalOpen = false" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs mb-4">
                            <div class="font-bold mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Mohon lengkapi formulir dengan benar:</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 ml-1 text-rose-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.addresses.store', [], false) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="redirect" value="checkout">
                        <input type="hidden" name="is_primary" value="1">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Penerima <span class="text-rose-500">*</span></label>
                                <input type="text" name="recipient_name" value="{{ old('recipient_name') }}" required placeholder="Nama lengkap penerima"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Telepon <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Label Alamat <span class="text-rose-500">*</span></label>
                                <input type="text" name="label" value="{{ old('label', 'Rumah') }}" required placeholder="Rumah, Kantor, dll"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kode Pos <span class="text-rose-500">*</span></label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="5 digit kode pos"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kota / Kab <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city') }}" required placeholder="cth. Jakarta Selatan"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district') }}" required placeholder="cth. Kebayoran Baru"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                                <input type="text" name="province" value="{{ old('province') }}" required placeholder="cth. DKI Jakarta"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address_line" rows="3" required placeholder="Nama jalan, nomor rumah/gedung, RT/RW, kelurahan, patokan lokasi"
                                      class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">{{ old('address_line') }}</textarea>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                            <button type="button" @click="addAddressModalOpen = false"
                                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl cursor-pointer transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition cursor-pointer">
                                Simpan & Gunakan Alamat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
