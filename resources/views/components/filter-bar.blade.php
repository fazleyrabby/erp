@props([
    'route' => '#',
    'searchPlaceholder' => 'Search...',
    'sortOptions' => [],
    'showLimit' => true,
    'showSort' => true,
    'showSearch' => true,
    'limits' => [10, 25, 50, 100],
    'defaultLimit' => 10,
    'defaultSort' => 'id',
    'defaultDirection' => 'DESC',
])

<div class="card mb-3">
    <div class="card-body py-2">
        <form action="{{ $route }}" method="GET" class="row g-2 align-items-center">
            @if ($showLimit)
                <div class="col-auto">
                    <label class="form-label d-inline me-1 text-muted small">Show</label>
                    <select name="limit" class="form-select form-select-sm d-inline auto-submit" style="width: auto;">
                        @foreach ($limits as $n)
                            <option value="{{ $n }}" {{ request('limit', $defaultLimit) == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($showSort && count($sortOptions) > 0)
                <div class="col-auto">
                    <label class="form-label d-inline me-1 text-muted small">Sort</label>
                    <select name="sort_by" class="form-select form-select-sm d-inline auto-submit" style="width: auto;">
                        @foreach ($sortOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('sort_by', $defaultSort) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="sort_direction" class="form-select form-select-sm d-inline auto-submit" style="width: auto;">
                        <option value="ASC" {{ request('sort_direction', $defaultDirection) == 'ASC' ? 'selected' : '' }}>Asc</option>
                        <option value="DESC" {{ request('sort_direction', $defaultDirection) == 'DESC' ? 'selected' : '' }}>Desc</option>
                    </select>
                </div>
            @endif

            @if ($showSearch)
                <div class="col-auto ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ $searchPlaceholder }}" style="min-width: 180px;">
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request()->anyFilled(['q', 'sort_by', 'sort_direction']) || request('limit') != $defaultLimit)
                            <a href="{{ $route }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
        </form>
    </div>
</div>

@push('javascript')
<script>
$(function() {
    $('.auto-submit').change(function() {
        this.form.submit();
    });
});
</script>
@endpush
