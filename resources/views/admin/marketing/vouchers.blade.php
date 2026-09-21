@extends('layouts.admin')

@section('title', 'Manajemen Voucher Platform')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">Voucher Diskon Platform</h1>
            <p class="text-xs text-slate-500">Buat kupon promo diskon nominal tetap atau persentase belanja bagi pembeli</p>
        </div>
    </div>

    <!-- 2 Columns: Add Form & Voucher Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Form (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4 lg:sticky lg:top-24">
            <h2 class="text-sm font-black text-slate-900 pb-2 border-b border-slate-100 uppercase tracking-wider">
                + Buat Voucher Baru
            </h2>

            <form action="{{ route('admin.marketing.vouchers.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Voucher <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" required placeholder="Contoh: GAJIANHEMAT"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Promo <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Diskon Gajian 25rb"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Diskon</label>
                        <select name="type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="fixed">Nominal (Rp)</option>
                            <option value="percentage">Persentase (%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Diskon <span class="text-rose-500">*</span></label>
                        <input type="number" name="amount" required min="1" placeholder="25000 / 10"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Min. Belanja (Rp)</label>
                        <input type="number" name="min_purchase" value="50000" min="0"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Maks. Diskon (Rp)</label>
                        <input type="number" name="max_discount" placeholder="Opsional" min="0"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kuota Pemakaian <span class="text-rose-500">*</span></label>
                    <input type="number" name="quota" value="100" required min="1"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Mulai Berlaku</label>
                        <input type="datetime-local" name="start_date" required value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Berakhir</label>
                        <input type="datetime-local" name="end_date" required value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}"
                               class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition mt-2">
                    Buat Voucher
                </button>
            </form>
        </div>

        <!-- Table (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-black">
                        <tr>
                            <th class="py-3.5 px-4">Kode & Nama</th>
                            <th class="py-3.5 px-4">Potongan</th>
                            <th class="py-3.5 px-4">Min. Belanja</th>
                            <th class="py-3.5 px-4">Kuota / Terpakai</th>
                            <th class="py-3.5 px-4">Masa Berlaku</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($vouchers as $v)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-black text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                                        {{ $v->code }}
                                    </span>
                                    <p class="font-bold text-slate-900 mt-1">{{ $v->name }}</p>
                                </td>
                                <td class="py-3.5 px-4 font-black text-slate-900">
                                    @if($v->type === 'fixed')
                                        Rp {{ number_format($v->amount, 0, ',', '.') }}
                                    @else
                                        {{ $v->amount }}%
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    Rp {{ number_format($v->min_purchase, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 font-bold">
                                    {{ $v->used_count }} / {{ $v->quota }}
                                </td>
                                <td class="py-3.5 px-4 text-[11px] text-slate-500">
                                    {{ $v->start_date->format('d M') }} - {{ $v->end_date->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('admin.marketing.vouchers.toggle', $v->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ $v->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                                {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.marketing.vouchers.destroy', $v->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $vouchers->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
