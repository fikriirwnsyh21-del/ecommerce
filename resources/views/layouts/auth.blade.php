<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F5F5F5]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk / Daftar') - Kebutuhan Gaming Gen Z</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-[#212121] flex flex-col justify-between">
    <!-- Mini Header -->
    <header class="bg-white border-b border-gray-200 py-4 px-6 sm:px-12 shadow-xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 via-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-105 transition-transform">
                    🎮
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="font-black text-xl tracking-tight text-gray-900 leading-none">Kebutuhan<span class="text-emerald-500">Gaming</span></span>
                        <span class="px-1.5 py-0.5 bg-emerald-500 text-white font-black text-[9px] rounded-md tracking-wider uppercase shadow-2xs">Gen Z</span>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium tracking-wider uppercase">Gaming & Tech Store</span>
                </div>
            </a>
            <a href="{{ route('home') }}" class="text-sm font-medium text-emerald-500 hover:text-emerald-600 flex items-center gap-1">
                <span>Kembali ke Belanja</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Mini Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} Kebutuhan Gaming Gen Z Indonesia. Seluruh hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>
</body>
</html>
