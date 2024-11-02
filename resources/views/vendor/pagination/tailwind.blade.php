@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ Lang::get('pagination.pagination-navigation') }}" class="flex items-center justify-between">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="bg-white border border-gray-300 cursor-default dark:bg-gray-800 font-medium inline-flex items-center leading-5 px-4 py-2 relative rounded-md text-gray-500 text-sm">
                    {!! Lang::get('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="active:bg-gray-100 active:text-gray-700 bg-white border border-gray-300 dark:bg-gray-800 dark:text-gray-400 duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring font-medium hover:text-gray-500 inline-flex items-center leading-5 px-4 py-2 relative ring-gray-300 rounded-md text-gray-700 text-sm transition">
                    {!! Lang::get('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="active:bg-gray-100 active:text-gray-700 bg-white border border-gray-300 dark:bg-gray-800 dark:text-gray-400 duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring font-medium hover:text-gray-500 inline-flex items-center leading-5 ml-3 px-4 py-2 relative ring-gray-300 rounded-md text-gray-700 text-sm transition">
                    {!! Lang::get('pagination.next') !!}
                </a>
            @else
                <span class="bg-white border border-gray-300 cursor-default dark:bg-gray-800 font-medium inline-flex items-center leading-5 ml-3 px-4 py-2 relative rounded-md text-gray-500 text-sm">
                    {!! Lang::get('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="dark:text-gray-300 dark:text-gray-400 leading-5 text-gray-700 text-sm">
                    {!! Lang::get('pagination.showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! Lang::get('pagination.to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! Lang::get('pagination.of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! Lang::get('pagination.results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex relative rounded-md shadow-sm z-0">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ Lang::get('pagination.previous') }}">
                            <span class="bg-white border border-gray-300 cursor-default dark:bg-gray-800 font-medium inline-flex items-center leading-5 px-2 py-2 relative rounded-l-md text-gray-500 text-sm" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="active:bg-gray-100 active:text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring focus:z-10 font-medium hover:text-gray-400 inline-flex items-center leading-5 px-2 py-2 relative ring-gray-300 rounded-l-md text-gray-500 text-sm transition" aria-label="{{ Lang::get('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="-ml-px bg-white border border-gray-300 cursor-default dark:bg-gray-800 dark:text-gray-400 font-medium inline-flex items-center leading-5 px-4 py-2 relative text-gray-700 text-sm">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="-ml-px bg-white border border-gray-300 cursor-default dark:bg-gray-800 dark:text-white font-bold inline-flex items-center leading-5 px-4 py-2 relative text-gray-500 text-sm">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="-ml-px active:bg-gray-100 active:text-gray-700 bg-white border border-gray-300 dark:bg-gray-800 dark:text-gray-400 duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring focus:z-10 font-medium hover:text-gray-500 inline-flex items-center leading-5 px-4 py-2 relative ring-gray-300 text-gray-700 text-sm transition" aria-label="{{ Lang::get('pagination.go-to-page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="-ml-px active:bg-gray-100 active:text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 duration-150 ease-in-out focus:border-blue-300 focus:outline-none focus:ring focus:z-10 font-medium hover:text-gray-400 inline-flex items-center leading-5 px-2 py-2 relative ring-gray-300 rounded-r-md text-gray-500 text-sm transition" aria-label="{{ Lang::get('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ Lang::get('pagination.next') }}">
                            <span class="-ml-px bg-white border border-gray-300 cursor-default dark:bg-gray-800 font-medium inline-flex items-center leading-5 px-2 py-2 relative rounded-r-md text-gray-500 text-sm" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
