<div class="pt-1 offset-xs-1">
    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Login Alerts</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/notification', 'method' => 'POST')) }}
            @if($notification["login"]==1)
                <input type="checkbox" name="login" checked data-width="100" onchange="$(this).submit();" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @else
                <input type="checkbox" name="login" onchange="$(this).submit();" data-width="100" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Messages</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/notification', 'method' => 'POST')) }}
            @if($notification["messages"]==1)
                <input type="checkbox" name="messages" checked data-width="100" onchange="$(this).submit();" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @else
                <input type="checkbox" name="messages" onchange="$(this).submit();" data-width="100" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @endif
            {{ Form::close() }}
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Notifications</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/notification', 'method' => 'POST')) }}
            @if($notification["notifications"]==1)
                <input type="checkbox" name="notifications" checked data-width="100" onchange="$(this).submit();" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @else
                <input type="checkbox" name="notifications" onchange="$(this).submit();" data-width="100" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @endif
            {{ Form::close() }}    </div>
    </div>


    <div class="form-group row">
        <label for="example-text-input" class="col-xs-5 col-form-label">Anonymous Messages</label>
        <div class="col-xs-7">
            {{ Form::open(array('url' => '/settings/notification', 'method' => 'POST')) }}
            @if($notification["anonymous"]==1)
                <input type="checkbox" name="anonymous" checked data-width="100" onchange="$(this).submit();" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @else
                <input type="checkbox" name="anonymous" onchange="$(this).submit();" data-width="100" data-onstyle="success"
                       data-offstyle="warning" data-toggle="toggle" data-on="On" data-off="Off" class="toggle-two">
            @endif
            {{ Form::close() }}     </div>
    </div>

</div>

<script>
    $(function () {

        $('.toggle-two').bootstrapToggle({
            on: 'On',
            off: 'Off'
        });


    })
</script>