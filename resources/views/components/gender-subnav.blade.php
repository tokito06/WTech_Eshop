@props([
    'routeName',
    'activeSex' => request('sex'),
    'query' => request()->except(['page', 'sex'])
])

@php
    $sexLabels = [
        'men' => 'Men',
        'women' => 'Women',
        'kids' => 'Kids',
    ];
@endphp

<nav class="category-nav">
    <ul class="nav d-flex flex-nowrap overflow-auto" style="scrollbar-width:none;">
        @foreach($sexLabels as $value => $label)
            <li class="nav-item">
                <a class="nav-link {{ $activeSex === $value ? 'active' : '' }}" href="{{ route($routeName, array_merge($query, ['sex' => $value])) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</nav>
