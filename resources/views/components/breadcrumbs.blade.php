@props(['items' => []])

@if(count($items) > 0)
<nav class="flex mb-4 sm:mb-6 bg-white dark:bg-gray-800 rounded-lg shadow px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 dark:border-gray-700 overflow-x-auto" aria-label="Breadcrumb" data-aos="fade-down">
    <ol class="inline-flex items-center space-x-1 sm:space-x-2 md:space-x-3 whitespace-nowrap">
        <li class="inline-flex items-center">
            <a href="{{ route('landing') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="hidden sm:inline">Home</span>
                <span class="sm:hidden">🏠</span>
            </a>
        </li>
        @foreach($items as $index => $item)
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @if($loop->last)
                        <span class="ml-1 text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 sm:ml-2 truncate max-w-[120px] sm:max-w-none" aria-current="page">{{ $item['name'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}" class="ml-1 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 sm:ml-2 transition-colors truncate max-w-[120px] sm:max-w-none">{{ $item['name'] }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
@endif
