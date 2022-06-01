@php
    $classes = ($active ?? false)
                ? 'text-orange-applus transition'
                : 'transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
