@extends('adminlte::page')

@section('title', 'MyUniDB')

@section('content_header')
    <h1>数据导入</h1>
@stop

@section('css')
@stop

@section('content')
    <section class="mx-3 px-2 py-5">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong><br/>
                            <a href="/uploads/{{ Session::get('file') }}">您导入的文件</a>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>导入失败</strong>
                            <ul>
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="/admin/import" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label class="btn btn-default">
                                <input id="file" name="file" type="file" class="form-control-lg" accept=".xls,.xlsx" >
                                <button type="submit" class="btn btn-success">导入</button>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
@endsection
