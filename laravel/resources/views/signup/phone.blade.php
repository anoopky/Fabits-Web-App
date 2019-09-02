@extends('template.master')
@section('head')
    <title>Phone | fabits.in</title>
@endsection
@section('content')
    @include('template.notification')
    @include('template.topmenu')
<div class="modal fade" id="myModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" data-backdrop="static" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Enter OTP</h4>
      </div>
      {{ Form::open(array('url' => '/phone', 'method' => 'PUT')) }}
      <div class="modal-body">
        {{ Form::hidden('location', '/info') }}
        <div class="form-group pt-1">
          <div class="input-group">
          <div class="input-group-addon">
            <i class="fa fa-lock" aria-hidden="true"></i>
          </div>
          {{ Form::text('otp' , '', array('class' => 'form-control','placeholder'=>'4 digit  OTP','required') ) }}
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{Form::submit('Confirm',array('class' => 'btn btn-primary'))}}
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
<div class="container-fluid  " >
<div class="row mt-4 ">
<div class="col-xs-12 col-md-8  offset-md-2  col-lg-7  offset-lg-3  col-xl-6  mt-3 px-2 pb-3 sd-1 b-white ba-1">
    <div class="row my-2 text-xs-center p-0">
        <div class="btn-group btn-breadcrumb p-0 ">
            <div  class="btn btn-lg btn-primary"><i class="fa fa-lock" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Password</span></div>
            <div class="btn btn-lg btn-primary "> <i class="fa fa-phone" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Phone</span></div>
            <div class="btn btn-lg btn-default"><i class="fa fa-user" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Info</span></div>
            <div class="btn btn-lg  btn-default"><i class="fa fa-camera" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Profile</span></div>
        </div>
    </div>
    {{ Form::open(array('url' => '/phone', 'method' => 'POST')) }}
    {{ Form::hidden('success', 'otp') }}
    <div class="form-group pt-1">
      <div class="input-group">
      <div class="input-group-addon">
        <i class="fa fa-mobile" aria-hidden="true"></i>
      </div>
      {{ Form::text('phone' , '', array('class' => 'form-control','placeholder'=>'Phone number','required') ) }}
      </div>
    </div>
    <div class="row">
        <div class="col-xs-12  pt-1 p-0 ">
            <div class="col-xs-12  pl-0 offset-md-10 col-md-2">
                {{Form::submit('Next',array('class' => 'btn btn-primary mt-1 w-100'))}}
            </div>
          </div>
    </div>
{{ Form::close() }}
</div>
</div>
</div>
@endsection
