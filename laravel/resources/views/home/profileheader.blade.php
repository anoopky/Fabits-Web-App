<div class="row m-0">
    <div class=" offset-lg-1 col-lg-8 col-md-12 p-0 profile-wall"
         style="background:  url('{{ Cloudder::show($userprofile->wall_picture_big, array())  }}');background-size:cover;">
        <div class="p-0 profile-image-cover">

        </div>
        <div class="jumbotron p-0 pb-1 mb-0 profile-jumbo">
            <div class="container-fluid">
                <div class="row p-0 profile-row">

                    <div class="col-xs-12  text-xs-center lh-1">
                        @if ($sentinel_user->username == $userprofile->username)
                        <i class="fa fa-camera fa-lg cursor-pointer profile-wall-change" data-toggle="modal" data-target="#wallModal" aria-hidden="true"></i>
                        @endif
                        <img src="{{ Cloudder::show($userprofile->profile_picture_big, array())  }}"
                             class="rounded-circle pp-150 sd-1 myprofile"
                             alt="{{ $userprofile->name }}">
                            @if ($sentinel_user->username == $userprofile->username)
                        <i class="fa fa-camera fa-lg cursor-pointer profile-pic-change" data-toggle="modal" data-target="#profilepicModal" aria-hidden="true"></i>
                            @endif
                        <p class="lead mt-1 opensanL"><b>{{ $userprofile->name }}</b></p>
                        <p>{{$profiledata["college"]}} - {{$profiledata["branch"]}} - {{$profiledata["year"]}}</p>
                        <p class="breakit">{{$userprofile->intro}}
                            @if ($sentinel_user->username == $userprofile->username)
                                <span><a href="/settings/info" class="white" data-loc="page-full"> <i class="fa fa-pencil fa-lg cursor-pointer" aria-hidden="true"></i></a></span>
                            @endif
                        </p>
                    </div>

                    <nav class="nav nav-inline ml-1 mt-0 mb-2  text-xs-center   ">
                        @if ($sentinel_user->username != $userprofile->username)
                            {{ Form::open(array('url' => '/follow', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                            {{ Form::hidden('success', 'follow') }}
                            {{ Form::hidden('user_id', $userprofile->id ) }}

                            @if($profiledata["isfollow"])

                                <button type="submit" class="nav-link btn btn-secondary unfollow ">
                                    <i class="fa fa-user-plus  " aria-hidden="true"></i> Following
                                </button>
                            @else
                                <button type="submit" class="nav-link btn btn-primary   ">
                                    <i class="fa fa-user-plus  " aria-hidden="true"></i> Follow
                                </button>
                            @endif
                            {{ Form::close() }}

                            {{ Form::open(array('url' => '/conversation', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                            {{ Form::hidden('success', 'chat') }}
                            {{ Form::hidden('user_id', $userprofile->id ) }}

                            <div class="btn-group" role="group">
                                <button  class="nav-link btn btn-secondary   dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-comments " aria-hidden="true"></i> Message
                                </button>
                                <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">
                                    <button class="dropdown-item" type="submit" value="true" name="chat_n" id="chat-n">Chat</button>
                                    <button class="dropdown-item" type="submit" value="false" name="chat_a" id="chat-a">Anonymous Chat</button>
                                </div>
                            </div>

                            {{ Form::close() }}

                            {{ Form::open(array('url' => '/block', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                            {{ Form::hidden('success', 'block') }}
                            {{ Form::hidden('user_id', $userprofile->id ) }}
                            <div class="btn-group" role="group">
                                <button  class="nav-link btn btn-secondary   dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    More
                                </button>
                                <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">
                                    <button class="dropdown-item" type="submit" value="true" name="chat_n" id="chat-n">
                                        @if($profiledata["isblock"] > 0 )
                                        <i class="fa fa-ban" aria-hidden="true"></i> Unblock
                                        @else
                                        <i class="fa fa-ban" aria-hidden="true"></i> Block
                                        @endif
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}


                        @else
                            <a href="/settings" data-loc="page" class="nav-link btn btn-secondary">
                                <i class="fa fa-cog " aria-hidden="true"></i> Setting
                            </a>

                        @endif
                    </nav>
                    <nav class="nav nav-inline ml-0 user-stats text-xs-center ">
                        {{ Form::open(array('url' => '/followers', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                        {{ Form::hidden('success', 'followers') }}
                        {{ Form::hidden('user_id', $userprofile->id ) }}
                        <a class=" nav-link ml-1 px-1" href="#"  onclick="$(this).submit();">
                            <p class="h2 text-primary opensanL" id="user_followers">
                                <i class="fa fa-users " aria-hidden="true"></i> {{ $profiledata["followers"] }}</p>
                            <p class="lead opensanL">Followers</p>
                        </a>
                        {{ Form::close() }}

                        {{ Form::open(array('url' => '/following', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                        {{ Form::hidden('success', 'following') }}
                        {{ Form::hidden('user_id', $userprofile->id ) }}
                        <a class="nav-link  px-1" href="#" onclick="$(this).submit();">
                            <p class="h2  pumpkin opensanL" id="user_following">
                                <i class="fa fa-user " aria-hidden="true"></i> {{ $profiledata["following"] }}</p>
                            <p class="lead opensanL">Following</p></a>
                        {{ Form::close() }}
                        <a class="nav-link  px-1" href="#">
                            <p class="h2 text-muted opensanL" id="user_views">
                                <i class="fa fa-user-secret" aria-hidden="true"></i> {{ $profiledata["profile_views"] }}
                            </p>
                            <p class="lead">Profile views</p></a>

                        <a class="nav-link px-1" href="#">
                            <p class="h2 neptritius opensanL" id="user_posts">
                                <i class="fa fa-pencil" aria-hidden="true"></i> {{ $profiledata["posts"] }}</p>
                            <p class="lead">Posts</p></a>

                        {{ Form::open(array('url' => '/facematches', 'method' => 'POST', 'class' => 'd-inline form-inline')) }}
                        {{ Form::hidden('success', 'facematches') }}
                        {{ Form::hidden('user_id', $userprofile->id ) }}
                        <a class="nav-link px-1" href="#" onclick="$(this).submit();">
                            <p class="h2  pomegrnate opensanL" id="user_facematch">
                                <i class="fa fa-heart" aria-hidden="true"></i> {{ $profiledata["faceMatch_Rating"] }}
                            </p>
                            <p class="lead opensanL">FaceMatch Rating</p></a>
                        {{ Form::close() }}

                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row m-0">
    <div class="offset-lg-1 col-lg-8 col-md-12 p-0 mb-0 bt-0 profile-user-info ">

        <div class="col-md-3 br-1  bb-1  border-light">

            <div class="row m-0">

                <div class="col-xs-12 text-xs-center p-1 opensanN">
                    <i class="fa fa-2x fa-map-marker" aria-hidden="true"></i>
                    <p class="lead"> {{ $userprofile["hometown"] }}</p>
                </div>
            </div>
        </div>


        <div class="col-md-3 br-1  bb-1  border-light">

            <div class="row m-0">

                <div class="col-xs-12 text-xs-center p-1 opensanN">
                    <i class="fa fa-2x fa-phone" aria-hidden="true"></i>

                    <p class="lead">

                        @if($userprofile["phone"] == null)
                            -
                            @else
                            @if($Privacy["phone"] == "0")

                                @if($sentinel_user->username != $userprofile->username)
                                <i class="fa fa-lock fa-lg" aria-hidden="true"></i>
                                @else
                                {{ $userprofile["phone"] }}
                            @endif

                            @elseif($Privacy["phone"] == 1)

                                {{ $userprofile["phone"] }}


                      @else
                          -
                        @endif
                        @endif
                    </p>
                </div>
            </div>
        </div>


        <div class="col-md-3 br-1  bb-1  border-light">

            <div class="row m-0">

                <div class="col-xs-12 text-xs-center p-1 opensanN">
                    <i class="fa fa-2x fa-heart" aria-hidden="true"></i>
                    <p class="lead"> {{ $userprofile["relationship"]["name"] }}</p>
                </div>
            </div>
        </div>


        <div class="col-md-3 br-1  bb-1  border-light">

            <div class="row m-0">
                <div class="col-xs-12 text-xs-center p-1 opensanN">
                    <i class="fa fa-2x fa-birthday-cake" aria-hidden="true"></i>
                    <p class="lead"> {{ $dob }} </p>
                </div>
            </div>
        </div>


    </div>
</div>