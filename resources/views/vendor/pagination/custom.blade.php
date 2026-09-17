@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-600">
            Halaman <span class="font-semibold text-gray-800">{{ $paginator->currentPage() }}</span>
            dari <span class="font-semibold text-gray-800">{{ $paginator->lastPage() }}</span>
        </p>

        <div class="flex flex-wrap items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-9 items-center rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-400 cursor-not-allowed">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    class="inline-flex h-9 items-center rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700">
                    Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-9 min-w-9 items-center justify-center px-2 text-sm text-gray-500">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-blue-600 px-3 text-sm font-semibold text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    class="inline-flex h-9 items-center rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700">
                    Berikutnya
                </a>
            @else
                <span class="inline-flex h-9 items-center rounded-lg border border-gray-200 bg-gray-100 px-3 text-sm text-gray-400 cursor-not-allowed">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif
