@props(['active', 'brackets' => 'text-2xl font-extrabold'])

@php
    $brackets .= ($active ?? false) ? '' : ' hidden';
@endphp

<a {{ $attributes->merge(['class' => 'inline-flex items-center font-medium leading-5 text-white hover:text-gray-400 hover:border-gray-300 hover:underline focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out']) }}>
    <span class="{{ $brackets }}">[</span>
    {{ $slot }}
    <span class="ml-1 {{ $brackets }}">]</span>
</a>
