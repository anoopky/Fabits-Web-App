<div class="pt-1 offset-xs-1">


    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Followers</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}
            @if($privacy["followers"]==1)

                <input type="checkbox" name="followers" checked data-width="120" onchange="$(this).submit();" data-toggle="toggle" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @else
                <input type="checkbox" name="followers" data-width="120" data-toggle="toggle" onchange="$(this).submit();" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>


    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Following</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}
            @if($privacy["following"]==1)

                <input type="checkbox" name="following" checked data-width="120" onchange="$(this).submit();" data-toggle="toggle" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @else
                <input type="checkbox" name="following" data-width="120" data-toggle="toggle" onchange="$(this).submit();" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>


    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Facematch</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}
            @if($privacy["facematch"]==1)

                <input type="checkbox" name="facematch" checked data-width="120" onchange="$(this).submit();" data-toggle="toggle" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @else
                <input type="checkbox" name="facematch" data-width="120" data-toggle="toggle" onchange="$(this).submit();" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>



    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Phone Number</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}
            @if($privacy["phone"]==1)

                <input type="checkbox" name="phone" checked data-width="120" onchange="$(this).submit();" data-toggle="toggle" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @else
                <input type="checkbox" name="phone" data-width="120" data-toggle="toggle" onchange="$(this).submit();" data-on="Public"
                       data-off="Private" data-onstyle="success" data-offstyle="warning" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>

    {{--<div class="form-group row">--}}
        {{--<label for="example-text-input" class="col-xs-5 col-form-label">Message From</label>--}}
        {{--<div class="col-xs-7">--}}
            {{--{{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}--}}
            {{--@if($privacy["message"]==1)--}}

                {{--<input type="checkbox" name="message" checked data-width="120" onchange="$(this).submit();" data-onstyle="success"--}}
                       {{--data-offstyle="warning" data-toggle="toggle" data-on="Everyone" data-off="Friends"--}}
                       {{--class="toggle-two2">--}}
            {{--@else--}}
                {{--<input type="checkbox" name="message" data-width="120" onchange="$(this).submit();" data-onstyle="success" data-offstyle="warning"--}}
                       {{--data-toggle="toggle" data-on="Everyone" data-off="Friends" class="toggle-two2">--}}
            {{--@endif--}}
            {{--{{ Form::close() }}--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group row">--}}
        {{--<label for="example-text-input" class="col-xs-5 col-form-label">Tags</label>--}}
        {{--<div class="col-xs-7">--}}
            {{--{{ Form::open(array('url' => '/settings/privacy', 'method' => 'POST')) }}--}}
            {{--@if($privacy["tags"]==1)--}}

                {{--<input type="checkbox" name="tags" onchange="$(this).submit();" checked data-width="120" data-onstyle="success"--}}
                       {{--data-offstyle="warning" data-toggle="toggle" data-on="Allow" data-off="Block"--}}
                       {{--class="toggle-two1">--}}
            {{--@else--}}
                {{--<input type="checkbox" name="tags" onchange="$(this).submit();" data-width="120" data-onstyle="success" data-offstyle="warning"--}}
                       {{--data-toggle="toggle" data-on="Allow" data-off="Block" class="toggle-two1">--}}
            {{--@endif--}}
            {{--{{ Form::close() }}--}}
        {{--</div>--}}
    {{--</div>--}}




</div>

<script>
    $(function () {
        $('.toggle-two').bootstrapToggle({
            on: 'Public',
            off: 'Private'
        });

        $('.toggle-two1').bootstrapToggle({
            on: 'Allow',
            off: 'Block'
        });
        $('.toggle-two2').bootstrapToggle({
            on: 'Everyone',
            off: 'Friends'
        });
    })
</script>