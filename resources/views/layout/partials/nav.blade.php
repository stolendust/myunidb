<section class="toolbar">
    <div class="container">
        <form method="POST" action="/search">
            @csrf
            <div class="d-flex flex-row justify-content-end align-items-center">
                <div>
                    <div class="input-group">
                        <input type="search" name="search" value="{{$search}}" class="form-control rounded" placeholder="搜索专业"
                            aria-label="Search" aria-describedby="search-addon" />
                        <button type="submit" class="btn btn-outline-primary">搜索</button>
                        <button type="button" class="btn btn-outline-primary"><a href="/">清除</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
