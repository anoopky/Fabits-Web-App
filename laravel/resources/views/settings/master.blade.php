@extends($ajax ? 'home.blank' :'home.master')
@section('head')
    <title>Settings | fabits.in</title>
@endsection
@section('body')
    <div id="ajax-title" class="hidden-xs-up" data-title="Settings | fabits.in"></div>

    <div class="container-fluid pt-5">
        <div class="row ">
            <div class="offset-lg-1 col-lg-8 col-md-12 sd-1 b-white  ba-1 px-0  ">
                <div class="col-md-3 px-0 br-1 border-light h-100 pb-3 text-xs-center b-white z-11 " id="settings-list">

                    <img class="img-fluid m-1  pp-100 rounded-circle "
                         src="{{  Cloudder::show($sentinel_user->profile_picture_small, array()) }}"
                         alt="{{ $sentinel_user->name }}">

                    <div class="list-group  ">
                        <a href="/settings/account" data-loc="settings"
                           class="list-group-item   pl-1 bx-0 square-corner list-group-item-action">
                            Account
                        </a>

                        <a href="/settings/info" data-loc="settings"
                           class="list-group-item  pl-1  bx-0 square-corner list-group-item-action">
                            User Info.
                        </a>

                        <a href="/settings/phone" data-loc="settings"
                           class="list-group-item  pl-1 bx-0  square-corner list-group-item-action">
                            Phone
                        </a>

                        <a href="/settings/password" data-loc="settings"
                           class="list-group-item  pl-1 bx-0 square-corner list-group-item-action">
                            Password
                        </a>
                        <a href="/settings/privacy" data-loc="settings"
                           class="list-group-item  pl-1 bx-0 square-corner list-group-item-action">
                            Privacy
                        </a>

                        <a href="/settings/notification" data-loc="settings"
                           class="list-group-item  pl-1 bx-0 square-corner list-group-item-action">
                            Alerts
                        </a>

                        <a href="/settings/blocked" data-loc="settings"
                           class="list-group-item  pl-1 bx-0 square-corner list-group-item-action">
                            Blocked Users
                        </a>


                    </div>

                </div>
                <div class="col-md-9  pl-1 b-white h-100 col-xs-12 z-10 " style="position:absolute; top:0; right:0;">
                    <a href="#" data-loc="settings-back" class="hidden-md-up ">
                        <i class="fa fa-lg fa-arrow-left mt-1" aria-hidden="true"></i>
                    </a>
                    <div id="settings-content">

                        @if ($pageid == 'account')
                            @include('settings.account', array(
                            'email'=>$email,
                            'facebook'=>$facebook,
                            'whatsapp'=>$whatsapp,
                            ))
                        @elseif ($pageid == 'info')
                            @include('settings.info', array(
                            'intro'=>$intro,
                            'location'=>$location,
                            'relationship'=>$relationship
                            ))
                        @elseif ($pageid == 'phone')
                            @include('settings.phone', array(
                            'phone'=>$phone,
                            ))
                        @elseif ($pageid == 'password')
                            @include('settings.password')
                        @elseif ($pageid == 'privacy')
                            @include('settings.privacy', array(
                            'privacy'=>$privacy,
                            ))
                        @elseif ($pageid == 'notification')
                            @include('settings.notification', array(
                            'notification'=>$notification,
                            ))
                        @elseif ($pageid == 'blocked')
                            @include('settings.blocked', array(
                            'blocks'=>$blocks,
                            ))
                        @else
                            @include('settings.account', array(
                            'email'=>$email,
                            'facebook'=>$facebook,
                            'whatsapp'=>$whatsapp,
                            ))
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Enter OTP</h4>
                </div>
                {{ Form::open(array('url' => '/phoneOTP', 'method' => 'POST')) }}
                <div class="modal-body">
                    {{ Form::hidden('success', 'otp2') }}
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

    <script type="text/javascript">

        @if(!$ajax)
                window.onload = function () {
            @endif
                $(document).ready(function () {
                $('#floatPostButton').hide();
            });
            @if(!$ajax)
        };
        @endif
    </script>

@endsection