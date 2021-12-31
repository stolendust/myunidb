@extends('layout.mainlayout')

@section("head_ext")
<link href="https://cdn.bootcdn.net/ajax/libs/datatables/1.9.4/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <section class="school_info rounded">
        <div class="text-center mt-2 mb-4"><h2>{{$school->name}}</h2></div>
        <div class="container">
            <div class="row text-center">
                <div class="col-4 col-sm-2 fw-bold">校名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->name }}</div>
                <div class="col-4 col-sm-2 fw-bold">是否公立</div>
                <div class="col-8 col-sm-4 text-start">@if ($school->is_public) 公立 @else 私立 @endif</div>
                <div class="col-4 col-sm-2 fw-bold">英文名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->en_name }}</div>
                <div class="col-4 col-sm-2 fw-bold">简称</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->short_name }}</div>
                <div class="col-4 col-sm-2 fw-bold">创建时间</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->create_time}}</div>
                <div class="col-4 col-sm-2 fw-bold">学校官网</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->website}}</div>
                <div class="col-4 col-sm-2 fw-bold">全球排名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->global_sort}}</div>
                <div class="col-4 col-sm-2 fw-bold">亚洲排名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->asia_sort}}</div>
                <div class="col-4 col-sm-2 fw-bold">老师数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->teacher_count)}}</div>
                <div class="col-4 col-sm-2 fw-bold">学生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->student_count)}}</div>
                <div class="col-4 col-sm-2 fw-bold">本科生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->undergraduate_count) }}</div>
                <div class="col-4 col-sm-2 fw-bold">研究生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->postgraduate_count)}}</div>
                <div class="col-4 col-sm-2 fw-bold">校长</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->principal}}</div>
                <div class="col-4 col-sm-2 fw-bold">副校长</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->vice_principal}}</div>
            </div>
        </div>
    </section>
@endsection
<b></b>
@section('script')
<script src="https://cdn.bootcdn.net/ajax/libs/datatables/1.9.4/jquery.dataTables.min.js"></script>
@endsection
