@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.min.css" />
@stop

@section('content')
    {{-- Setup for datatables --}}
    <section class="mx-3 px-2">
        <table id="model_list" class="table table-striped nowrap" width="100%">
            <thead>
                <tr>
                {{--
                    <th></th>
                    --}}
                    @foreach ($columns as $c)
                        <th>{{ $c->comment }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="/DataTables/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var dt_options = {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/admin/list/",
                    dataType: "json",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        model: "{{ $model }}"
                    }
                },
                columns: [
                    /*
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let edit = '/admin/edit/{{ $model }}/' + row.id;
                            return "<a href='{0}' title='编辑'><span class='fas fa-edit glyphicon glyphicon-edit'></span></a>"
                                .replace('{0}', edit);
                        }
                    },
                    */
                    @foreach ($columns as $c)
                    {
                        data: "{{ $c->name }}",
                        render: function(data, type, row) {
                            @if (0 === strpos($c->name,'is_'))
                                return data == 1? '<span class="fa fa-check"/>' : '<span class="fa fa-times"/>';
                            @endif

                            if(!data || data == 0){ return '-';}
                            @if( $c->name == 'website')
                                return '<a href="{0}" target="_blank">{0}</a>'.replaceAll('{0}', data);
                            @elseif( $c->name == 'mqf_level')
                                return [0,1,2,3,'大专',5,'本科','硕士','博士'][data];
                            @elseif( $c->name == 'ielts')
                                return Number(data).toFixed(1);
                            @elseif (in_array($c->type, ['int','decimal']))
                                return Number(data).toLocaleString("en-US");
                            @else
                                return data == 0? '-':data;
                            @endif
                        }
                    },
                    @endforeach
                ],
                paging: true,
                ordering: true,
                info: true,
                lengthChange: true,
                searching: true,
                responsive: false,
                columnDefs: [{
                    targets: '_all',
                    className: 'dt-head-left'
                },],
                dom: "Bfrtip",
                scrollX: true,
                scrollCollapse: true,
                buttons: ['colvis'],
                fixedColumns: { left: 1 },
                language: {
                    url: '/DataTables/zh.json'
                }
            };

            var eventAjax = function(json, table) {
                console.log('data loaded:' + table.attr('id'));
                // $('#'+table.attr('id')+'_count').html('('+json.length+')');
            };

            dt_options.ajax.data.search = $("#search").val();
            dt_options.ajax.data.level = 6;

            var table = $('#model_list').DataTable(dt_options)
                .on('xhr', function(e, settings, json) {
                    eventAjax(json, $(this));
                });
        });
    </script>
@endsection
