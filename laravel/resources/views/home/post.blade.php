@extends($ajax ? 'home.blank' :'home.master')
@section('body')
    <div class="container-fluid pt-5">

        <div class="row">

            <div class=" offset-lg-1 col-lg-8 col-md-12  px-0">

                <div class="card-columns">

                    <div id="posts-custom"></div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        @if(!$ajax)
                window.onload = function () {
            @endif

                    $(document).ready(function () {

                $.getJSON("/post/one/{{$id}}", function (data) {
                    // var items = [];
                    $.each(data, function (key, val) {
                        // console.log(key, val);
                        $("#posts-custom").append(postTemplate(val));
                    });

                });
            });
            @if(!$ajax)
        };
        @endif
    </script>


@endsection







