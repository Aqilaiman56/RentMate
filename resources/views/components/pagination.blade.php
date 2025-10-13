{{-- resources/views/components/pagination.blade.php --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="custom-pagination">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item">
                    <span class="pagination-link disabled">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="pagination-text">Previous</span>
                    </span>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link prev" rel="prev">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        <span class="pagination-text">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $showPages = 2; // Show 2 pages on each side of current page
            @endphp

            {{-- First Page --}}
            @if($currentPage > 3)
                <li class="pagination-item">
                    <a href="{{ $paginator->url(1) }}" class="pagination-link">1</a>
                </li>
                @if($currentPage > 4)
                    <li class="pagination-item">
                        <span class="pagination-dots">...</span>
                    </li>
                @endif
            @endif

            {{-- Pages Around Current Page --}}
            @for ($i = max(1, $currentPage - $showPages); $i <= min($lastPage, $currentPage + $showPages); $i++)
                @if ($i == $currentPage)
                    <li class="pagination-item">
                        <span class="pagination-link active">{{ $i }}</span>
                    </li>
                @else
                    <li class="pagination-item">
                        <a href="{{ $paginator->url($i) }}" class="pagination-link">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if($currentPage < $lastPage - 2)
                @if($currentPage < $lastPage - 3)
                    <li class="pagination-item">
                        <span class="pagination-dots">...</span>
                    </li>
                @endif
                <li class="pagination-item">
                    <a href="{{ $paginator->url($lastPage) }}" class="pagination-link">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link next" rel="next">
                        <span class="pagination-text">Next</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </li>
            @else
                <li class="pagination-item">
                    <span class="pagination-link disabled">
                        <span class="pagination-text">Next</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </span>
                </li>
            @endif
        </ul>

        {{-- Results Info --}}
        @if($paginator->total() > 0)
            <div class="pagination-info">
                Showing <strong>{{ $paginator->firstItem() }}</strong> to <strong>{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> results
            </div>
        @endif
    </nav>

    <style>
        .custom-pagination {
            width: 100%;
        }

        .pagination-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
            flex-wrap: wrap;
        }

        .pagination-item {
            margin: 0;
        }

        .pagination-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #E5E7EB;
            background: white;
            color: #4461F2;
            cursor: pointer;
        }

        .pagination-link:hover:not(.disabled):not(.active) {
            background: #4461F2;
            color: white;
            border-color: #4461F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
        }

        .pagination-link.active {
            background: #4461F2;
            color: white;
            border-color: #4461F2;
            cursor: default;
            box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
        }

        .pagination-link.disabled {
            background: #F3F4F6;
            color: #9CA3AF;
            border-color: #E5E7EB;
            cursor: not-allowed;
        }

        /* Previous and Next buttons - Special styling */
        .pagination-link.prev,
        .pagination-link.next {
            background: linear-gradient(135deg, #4461F2 0%, #5B7CFF 100%);
            color: white;
            font-weight: 700;
            padding: 0 16px;
            min-width: 110px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .pagination-link.prev::before,
        .pagination-link.next::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .pagination-link.prev:hover::before,
        .pagination-link.next:hover::before {
            left: 100%;
        }

        .pagination-link.prev:hover,
        .pagination-link.next:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 20px rgba(68, 97, 242, 0.4);
        }

        .pagination-link svg {
            flex-shrink: 0;
        }

        .pagination-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            background: transparent;
            color: #9CA3AF;
            border: none;
            cursor: default;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .pagination-info {
            text-align: center;
            color: #6B7280;
            font-size: 14px;
            margin-top: 15px;
            font-weight: 500;
        }

        .pagination-info strong {
            color: #4461F2;
            font-weight: 700;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .pagination-list {
                gap: 4px;
            }
            
            .pagination-link {
                min-width: 36px;
                height: 36px;
                padding: 0 8px;
                font-size: 13px;
            }
            
            .pagination-link.prev,
            .pagination-link.next {
                min-width: 90px;
                padding: 0 12px;
            }

            .pagination-text {
                display: none;
            }

            .pagination-link.prev,
            .pagination-link.next {
                min-width: 40px;
            }
            
            /* On mobile, show fewer page numbers */
            .pagination-list {
                max-width: 100%;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .pagination-link {
                min-width: 32px;
                height: 32px;
                font-size: 12px;
            }
        }
    </style>
@endif