@extends('layout.mainlayout')
@section('content')
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
                        <button type="button" class="btn btn-outline-primary"><a href="/">重置</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<section class="school_info">
<div class="container">
    <div class="row">
        <div class="col-sm-3">校名</div>
        <div class="col-sm-3">{{$school->name}}</div>
        <div class="col-sm-3">是否公立</div>
        <div class="col-sm-3">@if($school->is_public) 公立 @else 私立 @endif</div>
    </div>
    <div class="row">
        <div class="col-sm-3">英文名</div>
        <div class="col-sm-3">{{$school->en_name}}</div>
        <div class="col-sm-3">简称</div>
        <div class="col-sm-3">{{$school->short_name}}</div>
    </div>
</div>

</section>
@endsection
<b></b>
