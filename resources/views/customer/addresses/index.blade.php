@extends('layouts.app')

@section('title', 'Daftar Alamat Pengiriman')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6" x-data="{
    modalOpen: {{ $errors->any() ? 'true' : 'false' }},
    isEdit: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
    editUrl: '{{ old('_method') === 'PUT' && old('edit_url') ? old('edit_url') : route('customer.addresses.store', [], false) }}',
    addressForm: {
        recipient_name: @js(old('recipient_name', '')),
        phone: @js(old('phone', '')),
        label: @js(old('label', 'Rumah')),
        province: @js(old('province', '')),
        city: @js(old('city', '')),
        district: @js(old('district', '')),
        postal_code: @js(old('postal_code', '')),
        address_line: @js(old('address_line', '')),
        is_primary: {{ old('is_primary') ? 'true' : ($addresses->isEmpty() ? 'true' : 'false') }}
    },
    openAddModal() {
        this.isEdit = false;
        this.editUrl = '{{ route('customer.addresses.store', [], false) }}';
        this.addressForm = {
            recipient_name: '',
            phone: '',
            label: 'Rumah',
            province: '',
            city: '',
            district: '',
            postal_code: '',
            address_line: '',
            is_primary: {{ $addresses->isEmpty() ? 'true' : 'false' }}
        };
        this.modalOpen = true;
    },
    openEditModal(addr) {
        this.isEdit = true;
        this.editUrl = '/account/addresses/' + addr.id;
        this.addressForm = {
            recipient_name: addr.recipient_name,
            phone: addr.phone,
            label: addr.label,
            province: addr.province,
            city: addr.city,
            district: addr.district,
            postal_code: addr.postal_code,
            address_line: addr.address_line,
            is_primary: !!addr.is_primary
        };
        this.modalOpen = true;
    },
    closeModal() {
        this.modalOpen = false;
    }
}">

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-gray-200 shadow-xs">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Daftar Alamat Pengiriman</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola alamat tujuan untuk pengiriman belanja Anda</p>
        </div>
        <button type="button" @click="openAddModal()"
                class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Alamat</span>
        </button>
    </div>

    <!-- Address Cards List -->
    @if($addresses->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-gray-200 shadow-xs">
            <div class="w-16 h-16 mx-auto mb-4 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="font-bold text-gray-900 text-base mb-1">Belum Ada Alamat Tersimpan</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-5 max-w-sm mx-auto">Tambahkan alamat pengiriman agar Anda dapat memproses pesanan dengan cepat dan mudah.</p>
            <button type="button" @click="openAddModal()" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-md transition cursor-pointer">
                + Tambah Alamat Sekarang
            </button>
        </div>
    @else
        <div class="space-y-4">
            @foreach($addresses as $addr)
                <div class="bg-white p-5 sm:p-6 rounded-2xl border transition {{ $addr->is_primary ? 'border-emerald-500 bg-emerald-50/20 shadow-xs' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-black text-sm text-gray-900">{{ $addr->label }}</span>
                            @if($addr->is_primary)
                                <span class="px-2 py-0.5 bg-emerald-500 text-white text-[10px] font-extrabold rounded-md uppercase tracking-wider">
                                    Alamat Utama
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 text-xs">
                            @if(! $addr->is_primary)
                                <form action="{{ route('customer.addresses.primary', $addr->id, false) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="font-bold text-emerald-600 hover:underline cursor-pointer">
                                        Jadikan Utama
                                    </button>
                                </form>
                                <span>•</span>
                            @endif
                            <button type="button" @click="openEditModal({{ Js::from($addr) }})" class="font-bold text-gray-600 hover:text-emerald-600 cursor-pointer">
                                Ubah
                            </button>
                            <span>•</span>
                            <form action="{{ route('customer.addresses.destroy', $addr->id, false) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-bold text-rose-500 hover:underline cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="text-sm font-bold text-gray-800 mb-1">
                        {{ $addr->recipient_name }} <span class="font-normal text-gray-500">({{ $addr->phone }})</span>
                    </div>

                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        {{ $addr->address_line }}, {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add/Edit Address Modal (Alpine.js) -->
    <div x-show="modalOpen" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop (Dark overlay without blur) -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" @click="closeModal()"></div>

        <!-- Modal Content Container -->
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative z-20 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 p-6 sm:p-8" @click.stop>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                        <h3 class="text-lg font-black text-gray-900" x-text="isEdit ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Baru'"></h3>
                        <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                <!-- Validation Error Alert inside Modal -->
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

                <form :action="editUrl" action="{{ route('customer.addresses.store', [], false) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" :disabled="!isEdit">
                    <input type="hidden" name="edit_url" :value="editUrl">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Penerima <span class="text-rose-500">*</span></label>
                            <input type="text" name="recipient_name" x-model="addressForm.recipient_name" required placeholder="Nama lengkap penerima"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('recipient_name') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('recipient_name')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" x-model="addressForm.phone" required placeholder="08xxxxxxxxxx"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('phone') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('phone')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Label Alamat <span class="text-rose-500">*</span></label>
                            <input type="text" name="label" x-model="addressForm.label" required placeholder="Rumah, Kantor, dll"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('label') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('label')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" name="postal_code" x-model="addressForm.postal_code" required placeholder="5 digit kode pos"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('postal_code') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('postal_code')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kota / Kab <span class="text-rose-500">*</span></label>
                            <input type="text" name="city" x-model="addressForm.city" required placeholder="cth. Jakarta Selatan"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('city') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('city')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="district" x-model="addressForm.district" required placeholder="cth. Kebayoran Baru"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('district') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('district')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi <span class="text-rose-500">*</span></label>
                            <input type="text" name="province" x-model="addressForm.province" required placeholder="cth. DKI Jakarta"
                                   class="w-full px-3 py-2.5 bg-gray-50 border @error('province') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none">
                            @error('province')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="address_line" x-model="addressForm.address_line" rows="3" required placeholder="Nama jalan, nomor rumah/gedung, RT/RW, kelurahan, patokan lokasi"
                                  class="w-full px-3 py-2 bg-gray-50 border @error('address_line') border-rose-400 bg-rose-50/20 @else border-gray-300 @enderror rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-1 focus:ring-emerald-500 outline-none"></textarea>
                        @error('address_line')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="is_primary" value="1" x-model="addressForm.is_primary"
                                   class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                            <span class="text-xs font-semibold text-gray-700">Jadikan sebagai alamat utama</span>
                        </label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="closeModal()"
                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl cursor-pointer transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition cursor-pointer">
                            Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
