@props(['items'])

<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $item)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                @endif

                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-[#012A4A] dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                        {{ $item['name'] }}
                    </a>
                @else
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $item['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
