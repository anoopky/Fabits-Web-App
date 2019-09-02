@extends($ajax ? 'home.blank' :'home.master')

@section('head')
    <title>{{$userprofile->name}} | fabits.in</title>
@endsection

@section('body')
    <div id="ajax-title" class="hidden-xs-up" data-title="{{$userprofile->name}} | fabits.in"></div>
    <div class="container-fluid  px-0">
        @include('home.profileheader')
    </div>
    <div class="container-fluid pt-3">

        <div class="row">

            <div class=" offset-lg-1 col-lg-8 col-md-12  px-0">

                <div class="grid02">
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

        @if(!$ajax)
                window.onload = function () {
            @endif
                $(document).ready(function () {


                $('#floatPostButton').show();
                var $grid = $('.grid02').masonry({
                    percentPosition: true,
                    gutter: '.gutter-sizer',
                    itemSelector: '.grid-item1',
                    columnWidth: 80,
                });
                var sync = 1;
                var page = 2;

                $.getJSON("/post/{{$userprofile->id}}", function (data) {

                    $.each(data, function (key, val) {
                        var $elems = $(postTemplate(val));
                        $grid.append($elems).masonry('appended', $elems);
                    });

                    $grid.masonry();
                }).done(function () {
                    $(".commentBox").emojioneArea({
                        template: '<div class="emojionearea-editor comment-editor" contenteditable="true" placeholder="Comment" tabindex="0" dir="ltr" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off">' +
                        '</div><filters/><tabs/>',

                        autoHideFilters: true,


                    });
                    $(window).scroll(function () {
                        if (($(document).height() - ($(window).scrollTop() + $(window).height())) < 500) {
                            if (sync) {
                                sync = 0;
                                var allow = 0;

                                $.getJSON("/post/{{$userprofile->id}}?page=" + page, function (data) {

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
