@extends('template.master')
@section('head')
    <title>fabits.in | Share moments, Share Fabits </title>
@endsection
@section('content')
    <style>
        body {
            background: url("{{  Cloudder::show('fabits/background_image1', array()) }}") no-repeat fixed;
            background-size: cover;
        }
    </style>
    <!--#2196F3 !important blue-->
    <div class="modal fade" id="forgetPasswordModal" tabindex="-1" data-backdrop="static" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" data-backdrop="static" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Enter User name and phone number</h4>
                </div>
                {{ Form::open(array('url' => '/resetInit', 'method' => 'POST')) }}
                {{ Form::hidden('success', 'resetInit') }}
                <div class="modal-body">
                    <div class="form-group pt-1">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <b>@</b>
                            </div>
                            {{ Form::text('username' , '', array('class' => 'form-control','placeholder'=>'UserName','required') ) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </div>
                            {{ Form::text('phone' , '', array('class' => 'form-control','placeholder'=>'Phone Number') ) }}
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

    <div class="modal fade" id="OtpModal" tabindex="-1" data-backdrop="static" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" data-backdrop="static" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Enter User name and phone number</h4>
                </div>
                {{ Form::open(array('url' => '/resetOTP', 'method' => 'POST')) }}
                {{ Form::hidden('success', 'resetOTP') }}

                <div class="modal-body">
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

    <div class="modal fade" id="resetPasswordModal" tabindex="-1" data-backdrop="static" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" data-backdrop="static" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Enter User name and phone number</h4>
                </div>
                {{ Form::open(array('url' => '/resetPassword', 'method' => 'POST')) }}
                {{ Form::hidden('location', '/home') }}

                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{Form::submit('Confirm',array('class' => 'btn btn-primary'))}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12  pt-1 text-white lead text-xs-center" style="height: 60px;">
                <a href="https://play.google.com/store/apps/details?id=in.fabits.fabits" data-loc="page-full"><img src="/img/app.png" alt="fabits.in" style="width: 200px;"></a>

            </div>
        </div>
        @include('template.notification')
        <div class="row mt-8per ">
            <div class="offset-md-1 col-md-6 text-white hidden-md-down">
                {{--<h1 class="display-4 ">No Signup</h1>--}}
                <h1 class="display-4 ">Just Login</h1>
                <p class="lead ">Enter your University Rollno and College Password to login</p>
                {{--<p class="lead ">Share moments, Share Fabits</p>--}}

                <h5 class="mt-3">Users Online</h5>

                @foreach($online_data as $user)

                    {{--<div class="col-xs-2 p-0 text-xs-center">--}}

                    <img src="{{$user["user_picture"]}}" class="img-fluid rounded-circle pp-100 b-w-2 sd-1"
                         alt="{{$user["user_name"]}}" data-toggle="tooltip" data-placement="top"
                         title="{{$user["user_name"]}}">

                    {{--<p>{{$user["user_name"]}}</p>--}}
                    {{--</div>--}}
                @endforeach

            </div>
            <div class="col-lg-4 offset-md-2 offset-lg-0  col-md-8">
                <div class="p-1 sd-3 rounded b-white-a ba-1">
                    {{ Form::open(array('url' => '/', 'method' => 'POST')) }}
                    <div class="form-group pt-2">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            </div>
                            {{ Form::hidden('location', '/home') }}
                            {{ Form::text('username', '' , array('class' => 'form-control','placeholder'=>'University rollno or Phone','required','maxlength'=>"20") ) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </div>
                            {{ Form::password('password' , array('class' => 'form-control','placeholder'=>'college password','required','maxlength'=>"20") ) }}
                        </div>
                    </div>
                    {{Form::submit('Login',array('class' => 'btn btn-primary btn-block '))}}
                    <div class="row pt-1">
                        <div class="col-xs-6">
                            <div class="form-check">
                                <label class="form-check-label">
                                    {{Form::checkbox('remember', null , false , array('class' => 'form-check-input'))}}
                                    Remember Me!
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6 text-xs-right">
                            <a href="#" class="" id="forget">Forget password?</a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-md-12 p-2 pr-3 text-xs-center text-md-right l_footer">

                <ul class="nav nav-inline ">
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/terms">Terms & Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/privacy">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/loginpolicy">Login Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/about">About</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
@endsection
