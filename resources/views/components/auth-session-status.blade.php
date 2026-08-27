@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm text-emerald-800 font-bold flex items-center gap-2.5 shadow-sm']) }}>
        <span class="iconify text-lg text-emerald-600 shrink-0" data-icon="lucide:check-circle-2"></span>
        <span>{{ $status }}</span>
    </div>
@endif
