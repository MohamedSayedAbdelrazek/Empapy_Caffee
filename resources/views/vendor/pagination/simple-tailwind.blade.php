@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="custom-pagination">
        <div class="pagination-wrapper">
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

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 40px;
            height: 40px;
            padding: 0 16px;
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

        .page-btn:hover:not(.page-btn-disabled) {
            background: var(--gold, #c9a227);
            color: #fff;
            border-color: var(--gold, #c9a227);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(201, 162, 39, 0.3);
        }

        .page-btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Dark Mode */
        [data-theme="dark"] .page-btn {
            background: rgba(255, 255, 255, 0.08);
            color: #e5e7eb;
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .page-btn:hover:not(.page-btn-disabled) {
            background: var(--gold, #c9a227);
            color: #fff;
            border-color: var(--gold, #c9a227);
        }
    </style>
@endif
