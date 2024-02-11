<div class="row text-center justify-content-center mb-3 dataDirection">
    <form class="select-pagination" id="select-pagination" action="">
        <div class="row dataDirection mb-2">
        @if ($paginator->onFirstPage())
            <span class="disabled bg-secondary p-1 pl-3 pr-3" title="@lang('site.prev')">&lsaquo;&lsaquo;</span>
        @else
            <a href="#" id="prev-pagination" class="bg-success p-1 pl-3 pr-3" rel="prev" title="@lang('site.prev')">&lsaquo;&lsaquo;</a>
        @endif
            {{-- <div class="col"> --}}
                <span class="bg-light p-1 pl-3 pr-3">@lang('site.page')</span>
            {{-- </div> --}}
            <div class="col">
                <select class="form-control form-control-sm d-inline-block" name="pageNo" id="page-no">
                    @php $selectedvalue = $paginator->currentPage() @endphp
                    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page=>$url)
                        @if ($page == $paginator->currentPage())
                            <option selected>{{ $page }}</option>
                        @else
                            <option  value="{{ $page }}" {{ $selectedvalue == $page ? 'selected="selected"' : '' }}>{{ $page }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            {{-- <div class="col"> --}}
                <span class=" bg-light p-1 pl-3 pr-3"> @lang('site.of') {{ $paginator->lastPage() }}</span>
            {{-- </div> --}}
        @if ( $paginator->hasMorePages())
            <a href="" id="next-pagination" class="bg-success p-1 pl-3 pr-3" rel="next" title="@lang('site.next')">&rsaquo;&rsaquo;</a>
        @else
            <li class="bg-secondary p-1 pl-3 pr-3 disabled" title="@lang('site.next')">&rsaquo;&rsaquo;</li>
        @endif
        </div>

    </form>
</div>
