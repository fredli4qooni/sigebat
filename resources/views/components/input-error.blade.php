@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1.5 text-sm text-merah font-medium">
                <svg class="w-4 h-4 flex-shrink-0 fill-current text-merah" viewBox="0 0 256 256">
                    <path d="M236.8,188,148.8,36a24,24,0,0,0-41.6,0L19.2,188A23.68,23.68,0,0,0,40,224H216a23.68,23.68,0,0,0,20.8-36ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"/>
                </svg>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
