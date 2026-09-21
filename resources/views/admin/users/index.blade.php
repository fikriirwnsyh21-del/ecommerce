@extends('layouts.admin')

@section('title', 'Manajemen Pengguna Platform')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500">Kelola hak akses, moderasi akun, dan suspensi pengguna jika terjadi pelanggaran</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Role Filters -->
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto text-xs">
            <a href="{{ route('admin.users.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ !request('role') ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Semua Role
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('role') === 'customer' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Pembeli
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'seller']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('role') === 'seller' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Penjual
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition shrink-0 {{ request('role') === 'admin' ? 'bg-emerald-50 text-emerald-700 font-black' : 'text-slate-500 hover:bg-slate-100' }}">
                Admin
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="w-full md:w-80">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no telp..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-black">
                    <tr>
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Toko Terkait</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4">Bergabung</th>
                        <th class="py-3.5 px-4 text-right">Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 font-extrabold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $u->name }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $roleBadges = [
                                        'admin' => 'bg-rose-100 text-rose-800',
                                        'seller' => 'bg-indigo-100 text-indigo-800',
                                        'customer' => 'bg-emerald-100 text-emerald-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $roleBadges[$u->role->slug ?? ''] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $u->role->name ?? 'User' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ $u->shop->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $u->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $u->is_active ? 'Aktif' : 'Disuspend' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-status', $u->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin mengubah status akun pengguna ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs font-bold rounded-lg transition {{ $u->is_active ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                            {{ $u->is_active ? 'Suspend' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-300 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
