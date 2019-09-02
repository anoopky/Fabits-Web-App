{{ Form::open(array('url' => '/settings/phone', 'method' => 'POST')) }}
<div class="pt-1 align-middle">
    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Phone no.</label>
        <div class="col-md-10">
            <input type="text" class="form-control" name="phone" value="{{$phone}}" id="exampleInputPassword1" placeholder="Phone number">
        </div>
    </div>
    {{ Form::hidden('success', 'otp1') }}



<div class="col-xs-12 text-xs-right pt-2 pb-1 " style="position:absolute; bottom:0; right:0;">
    <div class="offset-md-9 col-md-3 px-0">

        <button class="btn btn-primary btn-block px-2">Save</button>
    </div>
</div>
</div>
{{ Form::close() }}