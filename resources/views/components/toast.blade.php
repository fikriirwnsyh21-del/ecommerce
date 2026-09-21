<div x-data="{
    show: false,
    message: '',
    type: 'success',
    init() {
        @if(session('success'))
            this.trigger('{{ session('success') }}', 'success');
        @elseif(session('error'))
            this.trigger('{{ session('error') }}', 'error');
        @endif

        window.addEventListener('notify', (e) => {
            this.trigger(e.detail.message || e.detail, e.detail.type || 'success');
        });
    },
    trigger(msg, t = 'success') {
        this.message = msg;
        this.type = t;
        this.show = true;
        setTimeout(() => { this.show = false; }, 4000);
    }
}"
x-cloak
x-show="show"
x-transition:enter="transition ease-out duration-300 transform"
x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed bottom-6 right-6 z-50 max-w-sm w-full bg-white rounded-2xl shadow-2xl border p-4 flex items-center gap-3"
:class="{
    'border-emerald-200 text-emerald-950': type === 'success',
    'border-rose-200 text-rose-950': type === 'error',
    'border-blue-200 text-blue-950': type === 'info'
}">
    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
         :class="{
            'bg-emerald-100 text-emerald-600': type === 'success',
            'bg-rose-100 text-rose-600': type === 'error',
            'bg-blue-100 text-blue-600': type === 'info'
         }">
        <template x-if="type === 'success'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </template>
        <template x-if="type === 'error'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </template>
        <template x-if="type === 'info'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </template>
    </div>

    <div class="flex-1 text-sm font-medium" x-text="message"></div>

    <button @click="show = false" class="text-gray-400 hover:text-gray-600 p-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
