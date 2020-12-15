<div class="table-filters">
    <div class="pagination-info">
        Page {{ $page }} of {{ $totalPages }} ({{ $totalRecords }} links tracked)
    </div>
    <div class="pagination-action">
        <div class="action">
            @if($page - 1 > 0)
                <a href='{{$url}}/{{ $page - 1 }}'><i class="fas fa-arrow-left"></i></a>
            @endif
        </div>
        <span class="current-page">{{ $page }}</span>
        <div class="action">
            @if($page + 1 <= $totalPages)
                <a href='{{$url}}/{{ $page + 1 }}'><i class="fas fa-arrow-right"></i></a>
            @endif
        </div>
    </div>
</div>