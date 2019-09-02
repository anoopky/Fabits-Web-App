@extends('template.master')
@section('head')
    <title>Profile Picture | fabits.in</title>
@endsection
@section('content')
    @include('template.notification')
    @include('template.topmenu')
<div class="container-fluid  " >
<div class="row mt-4 ">
<div class="col-xs-12 col-md-8  offset-md-2  col-lg-7  offset-lg-3  col-xl-6  mt-3 px-2 pb-3 sd-1 b-white ba-1">
    <div class="row my-2 text-xs-center p-0">
        <div class="btn-group btn-breadcrumb p-0 ">
            <div  class="btn btn-lg btn-primary"><i class="fa fa-lock" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Password</span></div>
            <div class="btn btn-lg btn-primary "> <i class="fa fa-phone" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Phone</span></div>
            <div class="btn btn-lg btn-primary"><i class="fa fa-user" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Info</span></div>
            <div class="btn btn-lg  btn-primary"><i class="fa fa-camera" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Profile</span></div>
        </div>
    </div>
    <div class="col-xs-12 pt-1 ">
        <div class=" col-xs-12 col-md-6 text-xs-center" id="profile_pic_upload" >
          <img src="{{  Cloudder::show($sentinel_user->profile_picture_big, array()) }}" class="pp-190 rounded-circle"
            alt="{{$sentinel_user->name}}">
        </div>
          {{ Form::open(array('url' => '/profile', 'method' => 'POST','enctype' => 'multipart/form-data')) }}
        <div class=" mt-3 col-xs-12 pt-1 col-md-6 text-xs-center align-middle" style="">
            {{ Form::hidden('success', 'load_picture') }}

            <label class="custom-file w-100 ">
              {{ Form::file('profile_picture', array('class' => 'hidden-xs-up',
                                          'accept'=>'image/*',

                                          'onchange' => '$(this).submit();')) }}

            </label>

            <div class="cursor-pointer custom-file-control text-xs-left w-100"  onclick="$(this).prev().children().first().trigger('click');"> </div>


        </div>
          {{ Form::close() }}
    </div>
    <div class="row">
        <div class="col-xs-12  pt-1 p-0 ">
          {{ Form::open(array('url' => '/profile', 'method' => 'PUT')) }}
          {{ Form::hidden('location', '/home') }}
            <div class="col-xs-12  pl-0 offset-md-8 col-md-2 push-md-2  ">
                {{Form::submit('Next',array('class' => 'btn btn-primary mt-1 w-100'))}}
            </div>
            {{ Form::close() }}
            {{ Form::open(array('url' => '/profile', 'method' => 'DELETE')) }}
            {{ Form::hidden('location', '/home') }}
            <div class="col-xs-12 pl-0 col-md-2 pull-md-2 ">
                {{Form::submit('Skip',array('class' => 'btn btn-secondary mt-1 w-100'))}}
            </div>
            {{ Form::close() }}
          </div>
    </div>
</div>
</div>
</div>
@endsection
