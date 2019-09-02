@extends($ajax ? 'home.blank' :'home.master')
@section('head')
    <title>Search | fabits.in</title>
@endsection
@section('body')
    <div id="ajax-title" class="hidden-xs-up" data-title="Search | fabits.in"></div>
    <div class="container-fluid pt-5">

        <div class="row">

            <div class=" offset-lg-1 col-lg-8 col-md-12  px-0">
                <div id="search" class="grid2">
                    <div class="gutter-sizer"></div>
                    <div class="grid-item1"></div>

                    @if(count($searchs)>0)
                @foreach($searchs as $search)
                        <div class="card grid-item1 square-corner sd-min ">
                            <div class="container-fluid">
                                <div class="row px-0_5 my-1">
                                    <ul class="nav text-xs-center">
                                        <li class="nav-item align-top">
                                            <a class="" href="{{'/@'.$search["username"]}}" data-loc="page">
                                                <img class="rounded-circle pp-100" src="{{$search["image"]}}"
                                                     alt="{{$search["name"]}}">
                                            </a></li>
                                        <li class="nav-item mx-0 ">
                                            <div class="lh-1 pl-0_5 ">
                                                <p class="p-0 m-0 post_user_link opensanN">
                                                    <a class="" href="{{'/@'.$search["username"]}}" data-loc="page">
                                                        {{$search["name"]}}</a></p>
                                                <small class="text-muted"> {{'@'.$search["username"]}}</small>
                                                <br>
                                                <small class="text-muted"> Followers {{$search["followers"]}} . Profile
                                                    views {{$search["profileviews"]}} .
                                                    FaceMatch {{$search["facematch"]}}</small>
                                            </div>
                                        </li>
                                        <li class="nav-item px-0 mx-0 ">
                                            {{ Form::open(array('url' => '/follow', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                                            {{ Form::hidden('success', 'follow') }}
                                            {{ Form::hidden('user_id', $search["id"] ) }}

                                            @if($search["isfollow"])

                                                <button type="submit" class="nav-link btn btn-secondary unfollow m-1 ">
                                                    <i class="fa fa-user-plus  " aria-hidden="true"></i> Following
                                                </button>
                                            @else
                                                <button type="submit" class="nav-link btn btn-primary m-1">
                                                    <i class="fa fa-user-plus  " aria-hidden="true"></i> Follow
                                                </button>
                                            @endif
                                            {{ Form::close() }}

                                            {{ Form::open(array('url' => '/conversation', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                                            {{ Form::hidden('success', 'chat') }}
                                            {{ Form::hidden('user_id', $search["id"] ) }}
                                            <button type="submit" class="nav-link btn btn-outline-secondary m-1 ">
                                                <i class="fa fa-comments " aria-hidden="true"></i> Message
                                            </button>
                                            {{ Form::close() }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @else
                        <h1 class="text-xs-center hidden-xs-up">NO Result Found!</h1>

                    @endif

                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">

        @if(!$ajax)
                window.onload = function () {
            @endif
                $(document).ready(function () {

                var pathname = window.location.href;
                pathname = pathname.substring(pathname.lastIndexOf("/")+1, pathname.length);

                if(pathname.substring(0, 2) == '!#'){

                    var search = encodeURIComponent(pathname);
                    var isData =0;
                    $.getJSON(search, function (data) {

                        $.each(data, function (key, val) {
                            isData =1;
                            var $elems = $(postTemplate(val));
                            $('.grid2').append($elems).masonry('appended', $elems);

                        });

                    }).done(function () {

                        if(isData ==1) {
                            commentInit();

                        }
                        else{
                            $('h1').removeClass('hidden-xs-up');

                        }



                    });
                }
                else{

                    $('h1').removeClass('hidden-xs-up');

                }

                $('#floatPostButton').show();
                $('.grid2').masonry({
                    percentPosition: true,
                    gutter: '.gutter-sizer',
                    itemSelector: '.grid-item1',
                    columnWidth: 80,
                });
            });
            @if(!$ajax)
        };
        @endif
    </script>
@endsection