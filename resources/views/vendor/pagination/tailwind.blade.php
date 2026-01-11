@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="custom-pagination">
        <div class="pagination-wrapper">
            {{-- Results Info --}}
            <p class="pagination-info">
                عرض
                <span class="pagination-info-highlight">{{ $paginator->firstItem() }}</span>
                إلى
                <span class="pagination-info-highlight">{{ $paginator->lastItem() }}</span>
                من
                <span class="pagination-info-highlight">{{ $paginator->total() }}</span>
                نتيجة
            </p>

            {{-- Pagination Links --}}
            <div class="pagination-links">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="page-btn page-btn-disabled" aria-disabled="true">
                        <i class="bi bi-chevron-right"></i>
                        السابق
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn page-btn-nav" rel="prev">
                        <i class="bi bi-chevron-right"></i>
                        السابق
                    </a>
                @endif

                {{-- Pagination Elements --}}
                <div class="page-numbers">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="page-btn page-btn-dots" aria-disabled="true">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="page-btn page-btn-active"
                                        aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn page-btn-nav" rel="next">
                        التالي
                        <i class="bi bi-chevron-left"></i>
                    </a>
                @else
                    <span class="page-btn page-btn-disabled" aria-disabled="true">
                        التالي
                        <i class="bi bi-chevron-left"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>

    <style>
        .custom-pagination {
            margin-top: 2rem;
        }

        .pagination-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .pagination-info {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        .pagination-info-highlight {
            font-weight: 600;
            color: var(--espresso, #2C1810);
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-numbers {
            display: flex;
            gap: 0.25rem;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 40px;
            height: 40px;
            padding: 0 14px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            color: var(--espresso, #2C1810);
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .page-btn:hover:not(.page-btn-disabled):not(.page-btn-active) {
            background: var(--gold, #c9a227);
            color: #fff;
            border-color: var(--gold, #c9a227);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(201, 162, 39, 0.3);
        }

        .page-btn-nav {
            padding: 0 16px;
        }

        .page-btn-active {
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(201, 162, 39, 0.35);
            font-weight: 600;
        }

        .page-btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-btn-dots {
            background: transparent;
            border: none;
            box-shadow: none;
            cursor: default;
        }

        /* Dark Mode */
        [data-theme="dark"] .pagination-info {
            color: #9ca3af;
        }

        [data-theme="dark"] .pagination-info-highlight {
            color: #e5e7eb;
        }

        [data-theme="dark"] .page-btn {
            background: rgba(255, 255, 255, 0.08);
            color: #e5e7eb;
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .page-btn:hover:not(.page-btn-disabled):not(.page-btn-active) {
            background: var(--gold, #c9a227);
            color: #fff;
            border-color: var(--gold, #c9a227);
        }

        [data-theme="dark"] .page-btn-active {
            background: linear-gradient(135deg, #c9a227 0%, #e8c547 100%);
            color: #fff;
        }

        [data-theme="dark"] .page-btn-dots {
            background: transparent;
            color: #6b7280;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .pagination-links {
                gap: 0.375rem;
            }

            .page-btn {
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                font-size: 0.8125rem;
            }

            .page-btn-nav span {
                display: none;
            }
        }
    </style>
@endif
