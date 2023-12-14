@props([
    'color' => 'blue',
])
<strong class="chip {{ $color }}">{{ $slot }}</strong>
