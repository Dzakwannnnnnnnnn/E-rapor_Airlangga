@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'flex flex-col gap-1 mt-1']) }}>
        @foreach ((array) $messages as $message)
            <p class="flex items-center gap-1.5 text-[11px] font-semibold text-rose-600 leading-relaxed">
                <i class="fa-solid fa-circle-exclamation text-[10px] shrink-0"></i>
                <span>{{ $message }}</span>
            </p>
        @endforeach
    </div>
@endif
