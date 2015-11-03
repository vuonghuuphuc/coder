@extends('templates.default')

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="row">
            @yield('left-content')
        </div>
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class='col-lg-12'>
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">Skills</div>
                    <div class="panel-body">
                        <p>
                            @foreach(Coder\Models\Skill::whereHas('posts', function($query){
                                $query->where('active', true);
                            })->with(['posts' => function ($query) {
                                $query->where('active', true);
                            }])->get() as $skill)
                            <a href="{{ route('search.tag', ['tag_id' => $skill->id, 'tag_name' => $skill->text]) }}">
                                <span class="label label-primary">{{ $skill->text }} ({{ $skill->posts->count() }})</span>
                            </a>
                            @endforeach
                        </p>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <blockquote>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">Quảng cáo & Liên kết</div>
                    <div class="panel-body">
                        <p class='text-center'>
                            <a href="{{ route('contact') }}"><i class='glyphicon glyphicon-link' style='font-size: 100px'></i></a>
                        </p>
                        <p>
                            Liên hệ đặt quảng cáo hoặc liên kết tại <a href="{{ route('contact') }}">đây.</a>
                        </p>
                    </div>
                </div>
                <a href="#" class='btn btn-primary btn-block btn-lg'>Facebook</a>
                <a href="#" class='btn btn-danger btn-block btn-lg'>Google</a>
                <a href="#" class='btn btn-info btn-block btn-lg'>Linkedin</a>
            </div>

        </div>
    </div>
</div>
@stop
