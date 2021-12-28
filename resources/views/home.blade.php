@extends('layout.mainlayout')
@section('content')

    <section id="team" class="team_member section-padding">
        <div class="container">
            <div class="row text-center">
            @foreach($schools as $s)
                <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.1s"
                    data-wow-offset="0">
                    <div class="our-team">
                        <div class="team_img">
                            <img src="images/schools/{{strtolower($s->short_name)}}.png" alt="team-image">
                            <ul class="social">
                            </ul>
                        </div>
                        <div class="team-content">
                            <h3 class="title">{{$s->name}}</h3>
                            <span class="post">{{$s->en_name}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </section>
@endsection
<b></b>
