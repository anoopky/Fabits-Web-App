<div class="collapse in " id="collapse">
    <div class="z-101 fixed-left h-100 mt-53  sd-min ">
        <div class="list-group ">
            <a href="/home" data-loc="page-menu" class="list-group-item br-0 bl-0 square-corner  list-group-item-action"
               data-toggle="tooltip_custom" data-placement="right" title="Home">
                <span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" id="homecount">0</span>
                <i class="fa fa-home fa-lg" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">Home</span>
            </a>

            <a href="/trending" data-loc="page-menu" class="list-group-item br-0 bl-0 square-corner list-group-item-action"
               data-toggle="tooltip_custom" data-placement="right" title="Trending">
                <i class="fa fa-rocket fa-lg" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">Trending</span>
            </a>

            <a href="/{{'@'.$sentinel_user->username }}" data-loc="page-menu" class="list-group-item br-0 bl-0 square-corner list-group-item-action"
               data-toggle="tooltip_custom" data-placement="right" title="Profile">
                <i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">My Profile</span>
            </a>


            {{--<a href="/messages_all" data-loc="page-full" class="list-group-item br-0 bl-0 square-corner list-group-item-action"--}}
               {{--id="message-icon" title="Message"  data-toggle="tooltip_custom" data-placement="right">--}}
                {{--<span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" id="messagecount">0</span>--}}
                {{--<i class="fa fa-commenting fa-lg" aria-hidden="true"></i>--}}
                {{--<span class="hidden-lg-up pl-0_5">Messages</span>--}}
            {{--</a>--}}

            {{ Form::open(array('url' => '/notificationRead', 'method' => 'POST')) }}
            <a href="#" onclick="$(this).submit();" class="list-group-item br-0 bl-0 bb-0 square-corner list-group-item-action"
               id="notification-icon" data-target="#notificationModal" data-keyboard="false" data-toggle="modal"  title="Notification">
                <span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" id="notificationcount">0</span>
                <i class="fa fa-globe fa-lg" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">Notification</span>
            </a>
            {{ Form::close() }}


            <a href="/settings" data-loc="page-menu" class="list-group-item br-0 bl-0 square-corner list-group-item-action"
               data-toggle="tooltip_custom" data-placement="right" title="Settings">
                <i class="fa fa-cogs fa-lg" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">Settings</span>

            </a>

            {{ Form::open(array('url' => '/logout', 'method' => 'POST')) }}
            {{ Form::hidden('location', '/') }}
            <a href="#" onclick="$(this).submit();" data-loc="page-menu" class="list-group-item br-0 bl-0 square-corner list-group-item-action"
               data-toggle="tooltip_custom" data-placement="right" title="Logout">
                <i class="fa fa-sign-out fa-lg fa-rotate-180" aria-hidden="true"></i>
                <span class="hidden-lg-up pl-0_5">Logout</span>
            </a>
            {{ Form::close() }}

        </div>
    </div>
</div>

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content px-0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Notification</h4>
            </div>
            <div class="modal-body px-0">
                 <div class="container-fluid px-0 ">
                     <div class="scrollbar-inner" id="notification-scroll">
                     <div class="notification-scroll">

                    <div class="carousel slide " id="notificationSlide" data-ride="carousel" data-interval="false" data-keyboard="false">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <div class="col-xs-12  px-0 " id="notifications">

                                </div>
                            </div>
                            <div class="carousel-item">
                                <button type="button" class="btn btn-secondary ml-1 mb-0_5" onclick=" $('#notificationSlide').carousel(0);"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                                <div id="notificationSliderPost" class="px-1"></div>
                            </div>

                        </div>
                        </div>
                        </div>

                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
