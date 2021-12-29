@extends('layout.mainlayout')
@section('content')
    <section class="toolbar">
        <div class="container">
            <form method="POST" action="/search">
                @csrf
                <div class="d-flex flex-row justify-content-end align-items-center">
                    <div>
                        <div class="input-group">
                            <input id="search" type="search" name="search" value="{{ $search }}"
                                class="form-control rounded" placeholder="搜索专业" aria-label="Search"
                                aria-describedby="search-addon" />
                            <button type="submit" class="btn btn-outline-primary">搜索</button>
                            <button type="button" class="btn btn-outline-primary"><a href="/">重置</a></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section id="team" class="team_member section-padding">
        <div class="container">
            <div class="row text-center">
                @foreach ($schools as $s)
                    <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s"
                        data-wow-offset="0">
                        <div class="our-team" id="{{ $s->id }}">
                            <div class="team_img">
                                <img src="images/schools/{{ strtolower($s->short_name) }}.png" alt="team-image">
                            </div>
                            <div class="team-content">
                                <h3 class="title">{{ $s->name }}</h3>
                                <span class="post">{{ $s->en_name }}</span>
                            </div>
                            <div class="team-numbers">
                                <div class="d-flex p-1 fs-12 justify-content-around">
                                    <div class=""> 本科专业 </div>
                                    <div class=""> {{ $s->program_degree }} </div>
                                </div>
                                <div class="d-flex p-1 fs-12 justify-content-around">
                                    <div class=""> 硕士方向 </div>
                                    <div class=""> {{ $s->program_master }} </div>
                                </div>
                                <div class="d-flex p-1 fs-12 justify-content-around">
                                    <div class=""> 博士方向 </div>
                                    <div class=""> {{ $s->program_doctor }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        // jquery extend function
        $.extend({
            redirectPost: function(location, args) {
                var form = '';
                $.each(args, function(key, value) {
                    value = value.split('"').join('\"')
                    form += '<input type="hidden" name="' + key + '" value="' + value + '">';
                });
                form += '@csrf';
                $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document
                    .body)).submit();
            }
        });

        $(document).ready(function() {
            $('.our-team').click(function() {
                $.redirectPost("school/search", {
                    search: $('#search').val(),
                    school_id: $(this).attr('id')
                });
            });
        });
    </script>
@endsection
