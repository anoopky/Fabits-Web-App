{{ Form::open(array('url' => '/settings/password', 'method' => 'POST')) }}

<div class="pt-1 ">
    <div class="form-group row">
        <label for="example-text-input" class="col-md-3 col-form-label">Current Password</label>
        <div class="col-md-9">
            <input type="password" name="oldPassword" class="form-control" id="exampleInputPassword1" placeholder="Current Password">
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-3 col-form-label">New Password</label>
        <div class="col-md-9">
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="New Password">
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-3 col-form-label">Confirm Password</label>
        <div class="col-md-9">
            <input type="password" name="passwordConf" class="form-control" id="exampleInputPassword1" placeholder="Confirm New Password">
        </div>
    </div>



    <div class="col-xs-12 text-xs-right pt-2 pb-1 " style="position:absolute; bottom:0; right:0;">
        <div class="offset-md-9 col-md-3 px-0">

            <button type="submit" class="btn btn-primary btn-block px-2">Save</button>
        </div>
    </div>
</div>
{{ Form::close() }}