@if ($paginator->hasPages())
<div class="pagination">
    @if ($paginator->onFirstPage())
        <span class="disabled">←</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">←</a>
    @endif

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">→</a>
    @else
        <span class="disabled">→</span>
    @endif
</div>
@endif
