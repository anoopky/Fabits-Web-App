@extends('template.master')
@section('head')
    <title>Change Password | fabits.in</title>
@endsection

@section('content')

    @include('template.notification')
    @include('template.topmenu')
<div class="container-fluid  " >
<div class="row mt-4 ">
<div class="col-xs-12 col-md-8  offset-md-2  col-lg-7  offset-lg-3  col-xl-6  mt-3 px-2 pb-1 sd-1 b-white ba-1">
    <div class="row my-2 text-xs-center p-0">
        <div class="btn-group btn-breadcrumb p-0 ">
            <div  class="btn btn-lg btn-primary"><i class="fa fa-lock" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Password</span></div>
            <div class="btn btn-lg btn-default "> <i class="fa fa-phone" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Phone</span></div>
            <div class="btn btn-lg btn-default"><i class="fa fa-user" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Info</span></div>
            <div class="btn btn-lg  btn-default"><i class="fa fa-camera" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Profile</span></div>
        </div>
    </div>
    {{ Form::open(array('url' => '/changepassword', 'method' => 'PUT')) }}
    {{ Form::hidden('location', '/phone') }}
    <div class="form-group pt-1">
      <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-lock" aria-hidden="true"></i>
      </div>
      {{ Form::password('password' , array('class' => 'form-control','placeholder'=>'New Password','required') ) }}
      </div>
    </div>
    <div class="form-group">
      <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-lock" aria-hidden="true"></i>
      </div>
      {{ Form::password('confirm_password' , array('class' => 'form-control','placeholder'=>'Confirm Password','required') ) }}
      </div>
    </div>
    <div class="row pb-1">
        <div class="col-xs-12  pt-1 p-0 ">
            <div class="col-xs-12  pl-0 offset-md-8 col-md-2 push-md-2  ">
                {{Form::submit('Next',array('class' => 'btn btn-primary mt-1 w-100'))}}
            </div>
            {{ Form::close() }}
            {{ Form::open(array('url' => '/changepassword', 'method' => 'POST')) }}
            <div class="col-xs-12 pl-0 col-md-2 pull-md-2 ">
                {{ Form::hidden('location', '/phone') }}

                {{Form::submit('Skip',array('class' => 'btn btn-secondary mt-1 w-100'))}}
            </div>
            {{ Form::close() }}

          </div>
    </div>

    <small class="text-muted ">It is recommended to change your password.</small>

</div>

</div>

</div>
@endsection
