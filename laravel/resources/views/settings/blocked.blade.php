<div class="pt-1">

    <h5 class="text-xs-center">You have been blocked by <span class="tag tag-danger tag-pill" >{{$blockme}}</span> people.</h5>
    @foreach($blocksChat as $block)
        {{ Form::open(array('url' => '/settings/blockedChat', 'method' => 'POST')) }}
        <div class="col-xs-12 bb-1 border-light pt-0_5">
            <div class="container-fluid">
                <div class="row pb-0_5  px-0_5 ">
                    <ul class="nav  nav-inline ">
                        <li class="nav-item align-top ">
                            <a class="" href="/{{'@'.$block["username"]}}">
                                <img class="rounded-circle pp-60" src=" {{  Cloudder::show($block["picture"], array()) }} " alt="{{$block["name"]}}">
                            </a></li>
                        <li class="nav-item mx-0 comment_width">
                            <div class="lh-1 pl-0_5 pt-0_5 ">
                                <p class="m-0 p-0 setting_link opensanN">
                                    <a class="" href="/{{'@'.$block["username"]}}"> {{$block["name"]}}
                                        <span class="tag tag-danger tag-pill">Chat</span></a></p>

                                <small class="text-muted pt-1 opensanN">{{$block["college"]}} - {{$block["branch"]}} - {{$block["year"]}}  </small>
                            </div>
                        </li>
                        <li class="nav-item float-xs-right  pt-1">
                            {{ Form::hidden('id', $block["id"]) }}
                            {{ Form::hidden('success', 'unblock') }}

                            <button type="submit" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </li>
                    </ul>
                </div>
            </div>

        </div>
        {{ Form::close() }}
    @endforeach

    @foreach($blocks as $block)
        {{ Form::open(array('url' => '/settings/blocked', 'method' => 'POST')) }}
        <div class="col-xs-12 bb-1 border-light pt-0_5">
            <div class="container-fluid">
                <div class="row pb-0_5  px-0_5 ">
                    <ul class="nav  nav-inline ">
                        <li class="nav-item align-top ">
                            <a class="" href="/{{'@'.$block["username"]}}">
                                <img class="rounded-circle pp-60" src=" {{  Cloudder::show($block["picture"], array()) }} " alt="{{$block["name"]}}">
                            </a></li>
                        <li class="nav-item mx-0 comment_width">
                            <div class="lh-1 pl-0_5 pt-0_5 ">
                                <p class="m-0 p-0 setting_link opensanN">
                                    <a class="" href="/{{'@'.$block["username"]}}"> {{$block["name"]}}
                                        <span class="tag tag-danger tag-pill">Profile</span></a></p>

                                <small class="text-muted pt-1 opensanN">{{$block["college"]}} - {{$block["branch"]}} - {{$block["year"]}}  </small>
                            </div>
                        </li>
                        <li class="nav-item float-xs-right  pt-1">
                            {{ Form::hidden('userid', $block["id"]) }}
                            {{ Form::hidden('success', 'unblock') }}

                            <button type="submit" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </li>
                    </ul>
                </div>
            </div>

        </div>
        {{ Form::close() }}
    @endforeach
</div>
