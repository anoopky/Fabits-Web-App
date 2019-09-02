@extends($ajax ? 'home.blank' :'home.master')
@section('head')
    <title>fabits.in</title>
@endsection
@section('body')
    <div id="ajax-title" class="hidden-xs-up" data-title="fabits.in"></div>
    <div class="container-fluid pt-5">

        <div class="row">

            <div class=" offset-lg-1 col-lg-8 col-md-12  px-0">
                <div class=" w-100 text-xs-center ">
                    <h5>
                        {{ Form::open(array('url' => '/post_new', 'method' => 'POST')) }}
                        {{ Form::hidden('success', 'newPost') }}
                        {{ Form::hidden('id', '0') }}
                        <div onclick="$(this).submit();" class="tag tag-warning tag-pill hidden-xs-up cursor-pointer"
                             id="new_posts">0
                        </div>
                        {{ Form::close() }}
                    </h5>
                </div>
                <div class="grid1">
                    <div class="gutter-sizer"></div>
                    <div class="grid-item1"></div>

                </div>
                <div class=" w-100 text-xs-center " id="loadPost">
                    <i class="text-xs-center text-primary fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var page = 2;
        var newPostLimit = 0;
        @if(!$ajax)
                window.onload = function () {
            @endif
                $(document).ready(function () {


                $('#floatPostButton').show();

                if (!$("#new_posts").hasClass("hidden-xs-up"))
                    $("#new_posts").addClass("hidden-xs-up");
                if (!$("#homecountTop").hasClass("hidden-xs-up"))
                    $("#homecountTop").addClass("hidden-xs-up");
                if (!$("#homecount").hasClass("hidden-xs-up"))
                    $("#homecount").addClass("hidden-xs-up");

                $('#new_posts').html("0");
                $('#homecountTop').html("0");
                $('#homecount').html("0");


                var $grid = $('.grid1').masonry({
                    percentPosition: true,
                    gutter: '.gutter-sizer',
                    itemSelector: '.grid-item1',
                    columnWidth: 80,
                });
                var template1 = '';
                $.getJSON("/topusers", function (data) {

                    template1 += '<div class="card grid-item1 bg-faded  square-corner sd-min mb-2 p-0_5 pt-1 pb-1" >' +
                            '<h4 class="">Popular Users</h4>' +
                            '<div class="container-fluid">' +
                            '<div class="row px-0_5 mt-1">' ;
                    $.each(data, function (key, val) {
                        template1 += topPeopleTemplate(val);
                    });
                    template1 += "</div></div></div>";
                });

                var template = '';
                $.getJSON("/recommended", function (data) {
                    var i1 = 0;
                    template += '<div id="carouselExampleControls" class="card grid-item1 carousel slide" data-ride="carousel">' +
                            ' <div class="carousel-inner" role="listbox"> ';
                    $.each(data, function (key, val) {
                        if (i1 == 0)
                            template += recomendedPeopleTemplate(val, 1);
                        else
                            template += recomendedPeopleTemplate(val, 0);
                        i1++;

                    });
                    template += "</div></div>";
                });





                var template2 = '';
//                $.getJSON("/toptags", function (data) {
//
//                    template2 += '<div class="card grid-item1 bg-faded  square-corner sd-min mb-2 p-0_5 pt-1 pb-1" >' +
//                            '<h4 class="">Top Hashtags</h4>' +
//                            '<div class="container-fluid">' +
//                            '<div class="row px-0_5 mt-1">' ;
//                    $.each(data, function (key, val) {
//                        template2 += topHashtag(val);
//                    });
//                    template2 += "</div></div></div>";
//                });

                var sync = 1;
                $.getJSON("/post", function (data) {
                    var i = 0;
                    $.each(data, function (key, val) {

                        if (i == 0) {

                            $('#new_posts').prev().val(val.post_id);
                        }

                        if (i == 2) {
                            var $elems1 = $(template);
                            try {
                                $grid.append($elems1).masonry('appended', $elems1);
                                $('.carousel').carousel();
                            }
                            catch (e) {

                            }
                        }
//                        if (i == 5) {
//                            var $elems1 = $(template2);
//                            try {
//                                $grid.append($elems1).masonry('appended', $elems1);
//                                $('.carousel').carousel();
//                            }
//                            catch (e) {
//
//                            }
//                        }



                        var $elems = $(postTemplate(val));
                        try {
                            $grid.append($elems).masonry('appended', $elems);
                        }
                        catch (e) {

                        }
                        i++;
                    });

                }).done(function () {

                    var $elems1 = $(template1);
                    try {
                        $grid.append($elems1).masonry('appended', $elems1);
                    }
                    catch (e) {
                    }


                    commentInit();
                    $(window).scroll(function () {
                        if (($(document).height() - ($(window).scrollTop() + $(window).height())) < 500) {
                            if (sync) {
                                sync = 0;
                                var allow = 0;
                                $.getJSON("/post?page=" + page, function (data) {

                                    if (data.length) {
                                        page++;
                                        allow = 1;
                                    }
                                    else
                                    {
                                        $('#loadPost').remove();

                                    }
                                    $.each(data, function (key, val) {

                                        var $elems = $(postTemplate(val));
                                        try {
                                            $grid.append($elems).masonry('appended', $elems);
                                        }
                                        catch (e) {

                                        }

                                    });

                                }).done(function () {
                                    commentInit();
                                    if(allow==1)
                                    sync = 1;
                                });

                            }

                        }
                    });

                });


            });

            @if(!$ajax)
        };
        @endif
    </script>

@endsection




