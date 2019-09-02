{{ Form::open(array('url' => '/settings/account', 'method' => 'POST')) }}
<div class="pt-1 ">
    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Username* </label>
        <div class="col-md-10">
            <div class="input-group">
                <div class="input-group-addon">
                    @
                </div>
                <input class="form-control" type="text" name="username" required="required" maxlength="20" value="{{$sentinel_user->username}}" id="example-text-input" placeholder="Username">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Email</label>
        <div class="col-md-10">
            <input class="form-control" type="text" name="email" value="{{$email}}" id="example-text-input" placeholder="Email Address">
        </div>
    </div>


    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-xs-12 col-form-label">Facebook</label>

        <div class="col-md-10">
            <input class="form-control" type="text" name="facebook" value="{{$facebook}}" id="example-text-input" placeholder="https://www.facebook.com/username">
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Whatsapp</label>


        <div class="col-md-10">
            <input class="form-control" type="text" name="whatsapp" value="{{$whatsapp}}" id="example-text-input" placeholder="Whatsapp number">
        </div>
    </div>


    <div class="col-md-9 text-xs-right p-2 " style="position:absolute; bottom:0; right:0;">
        <button type="submit" class="btn btn-primary px-2">Save</button>
    </div>
</div>
{{ Form::close() }}