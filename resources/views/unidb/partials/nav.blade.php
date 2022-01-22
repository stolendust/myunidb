<section class="bg-light pt-1 pb-0 sticky-top">
    <div class="container toolbar">
        <form id="form_search" method="get" action="/search">
            <div class="d-flex align-items-center flex-wrap pb-1">
                <div class="col-md-4 pe-2">
                    <span> <a href="/">HOME</a> </span>
                    @isset($school)
                        <span> / {{ $school->short_name }}</span>
                    @endisset
                </div>
                <div class="col-md-4 offset-md-4 pe-2">
                    <div class="input-group">
                        <input id="search" type="search" name="search" value="{{ $search }}"
                            class="form-control rounded" placeholder="搜索专业" aria-label="Search"
                            aria-describedby="search-addon" />
                        <button type="submit" class="btn btn-outline-primary">过滤</button>
                        <button type="button" id="clear" class="btn btn-outline-primary"><a href="/">全部</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
