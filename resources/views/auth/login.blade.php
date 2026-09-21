@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
<div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10" x-data="{
    fillCredentials(email, pass) {
        $refs.emailInput.value = email;
        $refs.passwordInput.value = pass;
    }
}">
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang!</h1>
        <p class="text-sm text-gray-500 mt-2">Masuk ke akun Kebutuhan Gaming Gen Z Anda untuk mulai berbelanja atau mengelola toko.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 text-emerald-800 text-sm border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Demo Accounts -->
    <div class="mb-6 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs">
        <p class="font-semibold text-gray-700 mb-2">Akun Demo Cepat (Klik untuk mengisi):</p>
        <div class="grid grid-cols-3 gap-2">
            <button type="button" @click="fillCredentials('customer@example.com', 'password')" class="px-2 py-1.5 bg-white border border-gray-300 rounded-lg text-gray-800 hover:border-emerald-500 hover:text-emerald-600 font-medium transition text-center shadow-2xs">
                Customer
            </button>
            <button type="button" @click="fillCredentials('seller@example.com', 'password')" class="px-2 py-1.5 bg-white border border-gray-300 rounded-lg text-gray-800 hover:border-emerald-500 hover:text-emerald-600 font-medium transition text-center shadow-2xs">
                Seller
            </button>
            <button type="button" @click="fillCredentials('admin@example.com', 'password')" class="px-2 py-1.5 bg-white border border-gray-300 rounded-lg text-gray-800 hover:border-emerald-500 hover:text-emerald-600 font-medium transition text-center shadow-2xs">
                Admin
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input id="email" x-ref="emailInput" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm bg-white text-gray-900"
                   placeholder="nama@email.com">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
            </div>
            <input id="password" x-ref="passwordInput" type="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm bg-white text-gray-900"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                <span class="text-sm text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <button type="submit"
                class="w-full py-3.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-150 flex items-center justify-center gap-2 text-sm">
            <span>Masuk Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </form>

    <div class="mt-8 text-center text-sm text-gray-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 underline ml-1">
            Daftar Sekarang
        </a>
    </div>
</div>
@endsection
