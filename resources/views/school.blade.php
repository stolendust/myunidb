@extends('layout.mainlayout')

@section('head_ext')
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css" />
@endsection

@section('content')
    <section class="school_info rounded my-4 mx-3 py-2 bg-light">
        <div class="text-center">
            <h4>{{ $school->name }}</h4>
        </div>
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
                <div class="col-8 col-sm-4 text-start">{{ $school->create_time }}</div>
                <div class="col-4 col-sm-2 fw-bold">学校官网</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->website }}</div>
                <div class="col-4 col-sm-2 fw-bold">全球排名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->global_sort }}</div>
                <div class="col-4 col-sm-2 fw-bold">亚洲排名</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->asia_sort }}</div>
                <div class="col-4 col-sm-2 fw-bold">老师数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->teacher_count) }}</div>
                <div class="col-4 col-sm-2 fw-bold">学生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->student_count) }}</div>
                <div class="col-4 col-sm-2 fw-bold">本科生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->undergraduate_count) }}</div>
                <div class="col-4 col-sm-2 fw-bold">研究生数量</div>
                <div class="col-8 col-sm-4 text-start">{{ number_format($school->postgraduate_count) }}</div>
                <div class="col-4 col-sm-2 fw-bold">校长</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->principal }}</div>
                <div class="col-4 col-sm-2 fw-bold">副校长</div>
                <div class="col-8 col-sm-4 text-start">{{ $school->vice_principal }}</div>
            </div>
        </div>
    </section>
    <section class="school_programs  rounded my-2 mx-3 py-1 px-2 bg-light">
        <div class="text-center pt-1">
            <h5>本科专业</h5>
        </div>
        <table id="programs_degree" class="table table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th>专业方向</th>
                    <th>专业方向</th>
                    <th>模式</th>
                    <th>学制</th>
                    <th>学费</th>
                    <th >雅思</th>
                    <th >入学时间</th>
                    <th>院系</th>
                    <th>院系</th>
                </tr>
            </thead>
        </table>
    </section>
    <section class="school_programs  rounded my-2 mx-3 py-1 px-2 bg-light">
        <div class="text-center pt-1">
            <h5>硕士方向</h5>
        </div>
        <table id="programs_master" class="table table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th>专业方向</th>
                    <th>专业方向</th>
                    <th>模式</th>
                    <th>学制</th>
                    <th>学费</th>
                    <th >雅思</th>
                    <th >入学时间</th>
                    <th>院系</th>
                    <th>院系</th>
                </tr>
            </thead>
        </table>
    </section>
    <section class="school_programs rounded my-2 mx-3 py-1 px-2 bg-light">
        <div class="text-center pt-1">
            <h5>博士方向</h5>
        </div>
        <table id="programs_doctor" class="table table-striped nowrap" width="100%">
            <thead>
                <tr>
                    <th >专业方向</th>
                    <th >专业方向</th>
                    <th >模式</th>
                    <th >学制</th>
                    <th >学费</th>
                    <th >雅思</th>
                    <th >入学时间</th>
                    <th >院系</th>
                    <th >院系</th>
                </tr>
            </thead>
        </table>
    </section>
@endsection
<b></b>
@section('script')
    <script type="text/javascript" src="/DataTables/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#form_search").attr("action", "/school/search");
            $("#clear").click(function(event ){
                event.preventDefault();
                $('#search').val('');
                $('#form_search').submit();
            });

            var dt_options = {
                ajax: {
                    url: "/school/fetch",
                    dataSrc: '',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        school_id: "{{ $school->id }}",
                        search: "",
                        level: "6"
                    }
                },
                columns: [
                    { data: "name" },
                    { data: "en_name" },
                    { data: "mode" },
                    { data: "school_years" },
                    { data: "tuition_total", render: $.fn.dataTable.render.number( ',', '.', 0, '' ) },
                    { data: "ielts" },
                    { data: "intakes" },
                    { data: "college.name" },
                    { data: "college.en_name" }
                ],
                paging: true,
                pagingType: "numbers",
                ordering: true,
                info: false,
                lengthChange: false,
                searching:false,
                responsive: true,
                columnDefs: [
                    { targets: '_all', className: 'dt-head-left' },
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 1000, targets: 1 },
                    { responsivePriority: 200, targets: 2 },
                    { responsivePriority: 300, targets: 3 },
                    { responsivePriority: 800, targets: 4 },
                    { responsivePriority: 100, targets: 5 },
                    { responsivePriority: 700, targets: 6 },
                    { responsivePriority: 900, targets: 7 },
                    { responsivePriority: 1000, targets: 8 }
                ]
            };

            // remove display section when data is empty
            var eventAjax = function(json, table){
                if($.isEmptyObject(json)){
                    table.parents('.school_programs').remove();
                }else if(json.length < 10){
                    $('.dataTables_paginate').hide();
                }
            };

            dt_options.ajax.data.search = $("#search").val();
            dt_options.ajax.data.level = 6;

            $('#programs_degree').DataTable(dt_options)
                .on( 'xhr', function ( e, settings, json ) { eventAjax(json, $(this));});

            dt_options.ajax.data.level = 7;
            $('#programs_master').DataTable(dt_options)
                .on( 'xhr', function ( e, settings, json ) { eventAjax(json, $(this));});

            dt_options.ajax.data.level = 8;
            $('#programs_doctor').DataTable(dt_options)
                .on( 'xhr', function ( e, settings, json ) { eventAjax(json, $(this));});
        });
    </script>
@endsection
