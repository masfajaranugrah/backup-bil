@if ($paginator->hasPages())
  @php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $pages = collect([1, $current - 1, $current, $current + 1, $last])
      ->filter(fn ($page) => $page >= 1 && $page <= $last)
      ->unique()
      ->sort()
      ->values();
    $previousPage = null;
  @endphp

  <nav role="navigation" aria-label="Pagination Navigation">
    <ul class="pagination pagination-sm mb-0">
      @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
        </li>
      @endif

      @foreach ($pages as $page)
        @if (!is_null($previousPage) && $page > $previousPage + 1)
          <li class="page-item disabled pagination-ellipsis" aria-disabled="true">
            <span class="page-link">...</span>
          </li>
        @endif

        @if ($page == $current)
          <li class="page-item active" aria-current="page">
            <span class="page-link">{{ $page }}</span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
          </li>
        @endif

        @php $previousPage = $page; @endphp
      @endforeach

      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
        </li>
      @else
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>
      @endif
    </ul>
  </nav>
@endif
