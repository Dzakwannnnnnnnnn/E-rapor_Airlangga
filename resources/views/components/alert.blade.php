@props([
    'type' => 'info',
    'message' => null,
    'dismissible' => false,
])

@php
    $config = [
        'success' => [
            'bg' => 'bg-emerald-50/80',
            'border' => 'border-emerald-250 border-l-4 border-l-emerald-550',
            'text' => 'text-emerald-900',
            'icon' => 'fa-circle-check',
            'iconColor' => 'text-emerald-550',
            'title' => 'Sukses'
        ],
        'danger' => [
            'bg' => 'bg-rose-50/80',
            'border' => 'border-rose-250 border-l-4 border-l-rose-550',
            'text' => 'text-rose-900',
            'icon' => 'fa-circle-exclamation',
            'iconColor' => 'text-rose-550',
            'title' => 'Terjadi Kesalahan'
        ],
        'error' => [
            'bg' => 'bg-rose-50/80',
            'border' => 'border-rose-250 border-l-4 border-l-rose-550',
            'text' => 'text-rose-900',
            'icon' => 'fa-circle-exclamation',
            'iconColor' => 'text-rose-550',
            'title' => 'Terjadi Kesalahan'
        ],
        'warning' => [
            'bg' => 'bg-amber-50/80',
            'border' => 'border-amber-250 border-l-4 border-l-amber-550',
            'text' => 'text-amber-900',
            'icon' => 'fa-triangle-exclamation',
            'iconColor' => 'text-amber-550',
            'title' => 'Peringatan'
        ],
        'info' => [
            'bg' => 'bg-blue-50/80',
            'border' => 'border-blue-250 border-l-4 border-l-blue-550',
            'text' => 'text-blue-900',
            'icon' => 'fa-circle-info',
            'iconColor' => 'text-blue-550',
            'title' => 'Informasi'
        ]
    ];

    $theme = $config[$type] ?? $config['info'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl p-4 flex items-start gap-3.5 border {$theme['bg']} {$theme['border']} shadow-sm select-none transition-all duration-150 relative overflow-hidden"]) }}>
    <div class="w-9 h-9 rounded-xl bg-white/60 border border-white flex items-center justify-center shrink-0 {{ $theme['iconColor'] }} shadow-sm">
        <i class="fa-solid {{ $theme['icon'] }} text-base"></i>
    </div>
    <div class="flex-1 min-w-0">
        <h5 class="font-extrabold text-[10px] uppercase tracking-widest {{ $theme['iconColor'] }} mb-0.5">{{ $theme['title'] }}</h5>
        <div class="text-xs font-semibold leading-relaxed {{ $theme['text'] }}">
            {{ $message ?? $slot }}
        </div>
    </div>
    @if($dismissible)
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg hover:bg-white/40 shrink-0">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    @endif
</div>
