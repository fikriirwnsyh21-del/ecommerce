@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10" x-data="{ isSeller: {{ old('register_as_seller') ? 'true' : 'false' }} }">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Buat Akun Baru</h1>
        <p class="text-sm text-gray-500 mt-2">Daftar akun Kebutuhan Gaming Gen Z untuk pengalaman berbelanja gear gaming terbaik.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                   placeholder="Contoh: Budi Pratama">
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                   placeholder="nama@email.com">
        </div>

        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp / HP</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                   placeholder="08123456789">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                       placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Ulangi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                       placeholder="Ketik ulang password">
            </div>
        </div>

        <!-- Toggle Seller Mode -->
        <div class="pt-2 pb-2">
            <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="register_as_seller" value="1" x-model="isSeller"
                           class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                    <div>
                        <span class="font-bold text-gray-900 text-sm">Saya ingin langsung membuka Toko (Seller)</span>
                        <p class="text-xs text-gray-500">Mulai berjualan produk Anda sendiri kepada jutaan pembeli</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Additional Seller Fields -->
        <div x-show="isSeller" x-cloak class="space-y-4 pt-2 border-t border-gray-100">
            <div>
                <label for="shop_name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Toko</label>
                <input id="shop_name" type="text" name="shop_name" value="{{ old('shop_name') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                       placeholder="Contoh: Berkah Gadget Store">
            </div>
            <div>
                <label for="shop_city" class="block text-sm font-semibold text-gray-700 mb-1">Kota Toko</label>
                <input id="shop_city" type="text" name="shop_city" value="{{ old('shop_city') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-900"
                       placeholder="Contoh: Jakarta Pusat">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full py-3.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-150 flex items-center justify-center gap-2 text-sm">
                <span>Daftar Sekarang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>

    <div class="mt-8 text-center text-sm text-gray-500">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 underline ml-1">
            Masuk di sini
        </a>
    </div>
</div>
@endsection
