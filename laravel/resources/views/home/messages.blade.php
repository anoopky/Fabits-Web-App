@extends($ajax ? 'home.blank' :'home.master')
@section('head')
    <title>Messages | fabits.in</title>
@endsection
@section('body')
    <div id="ajax-title" class="hidden-xs-up" data-title="Messages | fabits.in"></div>
    <div class="container-fluid pt-5">

        <div class="row ">

            <div class="offset-lg-1 col-lg-8 col-md-12 sd-1 b-white ba-1 px-0 ">
                <div class="col-md-4 px-0 ba-0 b-white " id="chats-list">

                    <div class="p-0_5 b-lightgrey bb-1">
                        <form class="form-inline ">
                            <input class="form-control  w-100" type="text" id="filter1" placeholder="Search">
                        </form>
                    </div>


                    <ul class="nav nav-tabs ba-0 px-0 text-xs-center debug" role="tablist">
                        <li class="nav-item col-xs-4 px-0 mx-0">
                            @if(count($conversations)>0)
                            <a class="nav-link ba-0 opensanL active" data-toggle="tab" href="#recent-app"
                               role="tab">Recent </a>
                            @else
                                <a class="nav-link ba-0 opensanL" data-toggle="tab" href="#recent-app"
                                   role="tab">Recent </a>
                            @endif
                        </li>
                        <li class="nav-item col-xs-4 px-0 mx-0 ">
                            @if(count($conversations)>0)
                            <a class="nav-link ba-0 opensanL " data-toggle="tab" href="#people-app"
                               role="tab">People</a>

                            @else
                                <a class="nav-link ba-0 opensanL active" data-toggle="tab" href="#people-app"
                                   role="tab">People</a>
                            @endif

                        </li>
                    </ul>

                    <div class="tab-content mt-0_5 ">
                        @if(count($conversations)>0)
                            <div class="tab-pane active " id="recent-app" role="tabpanel">
                                @else
                                    <div class="tab-pane" id="recent-app" role="tabpanel">
                                        @endif
                                        <div class="scrollbar-inner app-scroll1" id="chat-app-scroll1">
                                            {{--<div class="app-scroll1" id="">--}}
                                                <div class="list-group " style="margin-right:5px">
                                                    @foreach($conversations as $conversation)
                                                        <div class="message_app_users cursor-pointer list-group-item p-0 bb-1 border-light square-corner list-group-item-action"
                                                             conversation-id="{{ $conversation["conversation_id"] }}"
                                                             data-filter="{{$conversation["name"]}}"
                                                             conversation-auth="{{ $conversation["auth"] }}"
                                                             user-id="{{ $conversation["username"]}}">
                                                            <ul class="nav  nav-inline p-0_5 pr-0">
                                                                <li class="nav-item align-top ">
                                                                    <img class="rounded-circle pp-40"
                                                                         src="{{$conversation["image"]}}"
                                                                         alt="{{$conversation["name"]}}">
                                                                </li>
                                                                <li class="nav-item mx-0 online_width-s1">
                                                                    <div class="lh-1 pl-0_5 ">
                                                                        <p class="m-0 p-0 comment_link ">
                                                                            @if(strpos( $conversation["name"], 'Anonymous' ) !== false )
                                                                                <?php
                                                                                $name2 = substr ( $conversation["name"] , strpos( $conversation["name"], 'Anonymous'), strlen($conversation["name"] ));
                                                                                $name1 =substr ( $conversation["name"] , 0 , strpos( $conversation["name"], 'Anonymous' ));
                                                                                echo "$name1<span class='tag tag-danger tag-pill'>$name2</span>";
                                                                                 ?>
                                                                            @else
                                                                                {{$conversation["name"]}}

                                                                            @endif

                                                                        </p>
                                                                        <small class="text-muted opensanN"><?php echo $conversation["message"];?></small>
                                                                    </div>
                                                                </li>
                                                                <li class="nav-item float-xs-right pl-0 pr-0">
                                                                    @if($conversation["count"]>0)
                                                                        <span class="tag tag-primary tag-pill">{{$conversation["count"]}}</span>
                                                                    @else
                                                                        <span class="tag tag-primary tag-pill hidden-xs-up">{{$conversation["count"]}}</span>
                                                                    @endif
                                                                    <small class="text-muted opensanL"> {{$conversation["time"]}}</small>

                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                {{--</div>--}}
                                            </div>
                                        </div>

                                    </div>
                                    @if(count($conversations)>0)
                                        <div class="tab-pane " id="people-app" role="tabpanel">
                                            @else
                                                <div class="tab-pane active" id="people-app" role="tabpanel">
                                                    @endif
                                                    <div class="scrollbar-inner" id="people2">
                                                        <div class="app-scroll1" id="online1"></div>
                                                    </div>

                                                </div>
                                        </div>
                            </div>
                            <div class="col-md-8 bl-1 b-white h-100 col-xs-12 hidden-sm-down px-0 " id="chats-content">
                                <div class="p-0_5  b-lightgrey  bb-1 " id="chat-app">
                                    <a href="#" data-loc="chats-back" class="hidden-md-up ">
                                        <i class="fa fa-lg fa-arrow-left " aria-hidden="true"> </i>
                                    </a>
                                    <img class="rounded-circle pp-40"
                                         src="{{  Cloudder::show('fabits/blank', array()) }}" alt="">
                                    <a href="#" data-loc="page"><span></span>
                                        <small class="tag tag-success tag-pill"></small>

                                    </a>

                                    <div class="nav-item dropdown  float-xs-right pr-1 pt-0_5 hidden-xs-up">
                                        <form method='POST' action='/block_message' accept-charset='UTF-8' class ='d-inline form-inline' >
                                            <input name='_token' type='hidden' value='{{csrf_token()}}'>
                                            <input name='success' type='hidden' value='chat_block'>
                                            <input name='id' type='hidden' value='-1'>
                                            <a class="nav-link fa fa-ellipsis-v fa-lg text-muted" href="#" data-toggle="dropdown" ></a>
                                            <div class="dropdown-menu dropdown-menu-right" >
                                                <a class="dropdown-item" onclick="$(this).submit();"  href="#">
                                                    <small>Block</small></a>
                                                </div>
                                            </form>
                                        </div>
                                </div>
                                <div class="scrollbar-inner" id="chat-app-scroll" data-load="1" data-limit="0">
                                    <div class="chat-content app-scroll pr-0_5 pt-0_5"></div>
                                </div>

                                <div class="col-xs-12 p-1 b-lightgrey msgbox ">

                                    <form class="form-inline " method="POST" action="/message" accept-charset="UTF-8">
                                        <input name="_token" type="hidden" value="{{csrf_token()}}">
                                        <input name="conversation_id" type="hidden" value="-1">
                                        <input name="success" type="hidden" value="chatting">
                                        <input class="form-control w-100 chatBox "disabled id="chatBoxText" disabled name="message" autocomplete="off" type="text"
                                               placeholder="Message">
                                    </form>
                                </div>

                            </div>
                    </div>

                </div>
            </div>

            <script type="text/javascript">

                @if(!$ajax)
                        window.onload = function () {
                    @endif
                        $(document).ready(function () {
                        $('#chat-app-scroll1').scrollbar();

                        $('#floatPostButton').hide();
                        getOnline1();

                        $("#chatBoxText").emojioneArea({
                            pickerPosition: "top",
                            autocomplete: false,
                            inline: true,
                        });

                    });

                    @if(!$ajax)
                };
                @endif
            </script>


@endsection
