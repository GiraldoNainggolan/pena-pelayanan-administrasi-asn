@php
    $toastMessages = collect([
        ['type' => 'success', 'title' => 'Berhasil', 'message' => session('status')],
        ['type' => 'warning', 'title' => 'Perhatian', 'message' => session('warning')],
        ['type' => 'error', 'title' => 'Gagal', 'message' => session('error')],
    ])->filter(fn (array $toast): bool => filled($toast['message']));
@endphp

@if($toastMessages->isNotEmpty() || $errors->any())
    <div class="fixed inset-x-4 top-4 z-[100] flex flex-col items-end gap-3 sm:left-auto sm:right-5 sm:w-full sm:max-w-sm"
         aria-live="polite" aria-atomic="false" data-toast-container>
        @foreach($toastMessages as $toast)
            <div data-toast data-timeout="{{ $toast['type'] === 'warning' ? 9000 : 6500 }}"
                 role="{{ $toast['type'] === 'error' ? 'alert' : 'status' }}"
                 class="toast-item pointer-events-auto w-full overflow-hidden rounded-xl border bg-white shadow-xl
                    @if($toast['type'] === 'success') border-emerald-200
                    @elseif($toast['type'] === 'warning') border-amber-200
                    @else border-rose-200 @endif">
                <div class="flex gap-3 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                        @if($toast['type'] === 'success') bg-emerald-100 text-emerald-700
                        @elseif($toast['type'] === 'warning') bg-amber-100 text-amber-700
                        @else bg-rose-100 text-rose-700 @endif">
                        @if($toast['type'] === 'success')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        @elseif($toast['type'] === 'warning')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.05 3.376c.866-1.5 3.032-1.5 3.898 0l7.355 12.75ZM12 15.75h.008v.008H12v-.008Z" /></svg>
                        @else
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75 14.25 14.25m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-900">{{ $toast['title'] }}</p>
                        <p class="mt-0.5 text-xs leading-5 text-slate-600">{{ $toast['message'] }}</p>
                    </div>
                    <button type="button" data-toast-close class="-mr-1 -mt-1 rounded-md p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500" aria-label="Tutup notifikasi">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="h-1 bg-slate-100"><div data-toast-progress class="h-full w-full origin-left
                    @if($toast['type'] === 'success') bg-emerald-500
                    @elseif($toast['type'] === 'warning') bg-amber-500
                    @else bg-rose-500 @endif"></div></div>
            </div>
        @endforeach

        @if($errors->any())
            <div data-toast data-timeout="10000" role="alert"
                 class="toast-item pointer-events-auto w-full overflow-hidden rounded-xl border border-rose-200 bg-white shadow-xl">
                <div class="flex gap-3 p-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.05 3.376c.866-1.5 3.032-1.5 3.898 0l7.355 12.75ZM12 15.75h.008v.008H12v-.008Z" /></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-900">Periksa kembali input Anda</p>
                        <ul class="mt-1.5 list-disc space-y-1 pl-4 text-xs leading-5 text-slate-600">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" data-toast-close class="-mr-1 -mt-1 rounded-md p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500" aria-label="Tutup notifikasi">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="h-1 bg-slate-100"><div data-toast-progress class="h-full w-full origin-left bg-rose-500"></div></div>
            </div>
        @endif
    </div>
@endif