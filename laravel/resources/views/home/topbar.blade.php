<nav class="navbar navbar-fixed-top navbar-dark bg-primary px-0 sd-1" id="blueTopbar">
    <div class="container">
        <div class="row ">
            <div class ="col-xs-12 ">
            <button class=" navbar-toggler col-xs-1 col-sd-1 hidden-lg-up cursor-pointer ba-0"  id="sliderMenu"></button>

            <a class="navbar-brand col-md-1  col-sd-1 hidden-md-down pl-0" href="/home" data-loc="page">
                <img src="/img/fabits.png" style="width:100px;" alt="fabits.in">
            </a>

                <a class="navbar-brand col-md-1  col-sd-1 hidden-sm-down hidden-lg-up pl-0 mr-3" href="/home" data-loc="page">
                    <img src="/img/fabits-m-s.png" style="width:100px;" alt="fabits.in">
                </a>

                <div class="form-inline col-lg-5 col-xs-12  col-sm-10  hidden-md-down" id="searchDiv">
                <input class="form-control w-100" type="text" autocomplete="off" placeholder="Search" id="u-search">
                <i class="fa fa-times fa-lg text-muted cursor-pointer hidden-lg-up" id="closeSearch" aria-hidden="true"
                   style="position: absolute; right:10%; top:30%;"></i>
                <div id="search-result" class=" b-white sd-1 ba-1 border-light hidden-xs-up"
                     style="position: absolute; padding: .5rem .75rem;">
                </div>
            </div>


            <div  class="col-xs-2 px-0 mr-1 hidden-lg-up"  id="topbarHome">
                <span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" style="position: absolute;" id="homecountTop">0</span>
                <a href="/home" data-loc="page-menu" class="btn white ba-0" >
                    <i class="fa fa-home fa-lg" aria-hidden="true"></i>
                </a>

            </div>

            <div class="col-xs-2 mr-1 px-0 hidden-lg-up" id="topbarNotification">
                {{ Form::open(array('url' => '/notificationRead', 'method' => 'POST')) }}
                <span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" style="position: absolute;" id="notificationTop">0</span>

                <button  onclick="$(this).submit();" data-target="#notificationModal"
                         data-toggle="modal"  class="btn white ba-0"  style="background-color: transparent !important;">
                    <i class="fa fa-globe fa-lg" aria-hidden="true"></i>
                </button>
                {{ Form::close() }}

            </div>

            {{--<div  class="col-xs-2 px-0 mr-1 hidden-lg-up"  id="topbarMessage">--}}
                {{--<span class="tag tag-danger tag-pill float-xs-right hidden-xs-up" style="position: absolute;" id="messagecountTop">0</span>--}}
                {{--<a href="/messages_all" data-loc="page-full" class="btn white ba-0 " >--}}
                    {{--<i class="fa fa-commenting fa-lg" aria-hidden="true"></i>--}}
                {{--</a>--}}

            {{--</div>--}}

            <div class="col-xs-1 mr-1 hidden-lg-up float-xs-right"  id="topbarSearch">
                <button id="openSearch" class="btn white ba-0"
                        style="background-color: transparent !important;" >
                    <i class="fa fa-search fa-lg" aria-hidden="true"></i>
                </button>

            </div>


            <div class="col-xs-1  pl-0 hidden-md-down">
                {{ Form::open(array('url' => '/post_init', 'method' => 'POST')) }}
                {{ Form::hidden('success', 'post_init') }}
                <button type="submit" class="btn outline " data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                    <span class="hidden-sm-down">Post</span>
                </button>
                {{ Form::close() }}

            </div>



            <div id="floatPostButton" class="pl-0 hidden-md-up " style="display:none; position:fixed; border-radius:100%;   bottom:30px; right:30px;">
                {{ Form::open(array('url' => '/post_init', 'method' => 'POST')) }}
                {{ Form::hidden('success', 'post_init') }}
                <button type="submit" class="btn btn-primary sd-3 " style="border-radius:100%;  width: 55px;height: 55px;" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                    <span class="hidden-sm-down">Post</span>
                </button>
                {{ Form::close() }}

            </div>



            <ul class="nav navbar-nav float-xs-right hidden-md-down col-md-4 ">
                <li class="nav-item">
                    <a class="nav-link  p-0" id="userinfo" data-loc="page" href="{{ '/@'.$sentinel_user->username }}"
                       data-csrf="{{ csrf_token() }}" data-id="{{ $sentinel_user->id }}">
                        <img class="img-fluid mr-1 pp-40 rounded-circle "
                             src="{{  Cloudder::show($sentinel_user->profile_picture_small, array()) }}"
                             alt="{{ $sentinel_user->name }}">
                        {{ $sentinel_user->name }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#" data-toggle="dropdown" > <i class="fa fa-cog" aria-hidden="true"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" >
                        <a class="dropdown-item" href="/settings" data-loc="page">
                            <small><i class="fa fa-cog" aria-hidden="true"></i> Settings</small>
                        </a>
                        {{ Form::open(array('url' => '/logout', 'method' => 'POST')) }}
                        {{ Form::hidden('location', '/') }}
                        <a class="dropdown-item" href="#" onclick="$(this).submit();">
                            <small><i class="fa fa-sign-out fa-rotate-180" aria-hidden="true"></i> Logout</small>
                        </a>
                        {{ Form::close() }}
                        {{--<a class="dropdown-item" href="#">--}}
                            {{--<small>Help</small>--}}
                        {{--</a>--}}
                        {{--<a class="dropdown-item" href="#">--}}
                            {{--<small>Support</small>--}}
                        {{--</a>--}}
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-type="fabits" data-toggle="modal" data-target="#reportModal">
                            <small><i class="fa fa-flag" aria-hidden="true"></i> Report a Problem</small>
                        </a>
                    </div>
                </li>
            </ul>

            </div>

        </div>
    </div>
</nav>

<div class="modal fade facematch-modal" id="facematchModal" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog  " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Whom you like more ?</h4>
            </div>
            <div class="modal-body p-0 m-0">
                <div class="container-fluid p-0 m-0">
                    <div class="col-xs-12 py-1 px-0 text-xs-center ">
                        {{ Form::open(array('url' => '/facematch', 'method' => 'POST' , 'id'=>"F_user_1_form")) }}
                        {{ Form::hidden('facematch_id', '-1') }}
                        {{ Form::hidden('face_id', '-1') }}
                        {{ Form::hidden('success', 'facematch') }}
                        <div class=" col-xs-6 text-xs-center m-0  ">
                            <img src="" id="F_user_1_photo" class=" cursor-pointer face_cust_border pp-130"
                                 onclick="$(this).submit();" alt="">
                            <p class="pt-1 mb-0 post_user_link opensanN" id="F_user_1_name"><a href="#"></a></p>
                            <small class="text-muted" id="F_user_1_college"></small>
                            <br>
                            <small class="text-muted" id="F_user_1_detail"></small>
                        </div>
                        {{ Form::close() }}
                        {{ Form::open(array('url' => '/facematch', 'method' => 'POST', 'id'=>"F_user_2_form")) }}
                        {{ Form::hidden('facematch_id', '-1') }}
                        {{ Form::hidden('face_id', '-1') }}
                        {{ Form::hidden('success', 'facematch') }}
                        <div class="col-xs-6  text-xs-center  m-0 ">
                            <img src="" id="F_user_2_photo" class="cursor-pointer face_cust_border pp-130"
                                 onclick="$(this).submit();" alt="">
                            <p class="pt-1 mb-0 post_user_link opensanN" id="F_user_2_name"><a href="#"></a></p>
                            <small class="text-muted" id="F_user_2_college"></small>
                            <br>
                            <small class="text-muted" id="F_user_2_detail"></small>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade report-modal" id="reportModal" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Report a Problem</h4>
            </div>
            {{ Form::open(array('url' => '/report', 'method' => 'POST')) }}
            {{ Form::hidden('success', 'report') }}
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="col-xs-12 py-1 px-0  ">

                            <fieldset class="form-group post-type row hidden-xs-up">
                                <input type="hidden" name="post_id">
                                <legend class="col-form-legend col-xs-12">What bother you about this post?</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="Abusive" >
                                            Abusive
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="Illegal Content">
                                            Illegal Content
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="Sexual Content">
                                            Sexual Content
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-group fabits-type row hidden-xs-up">
                                <legend class="col-form-legend col-xs-12">What you want to report?</legend>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="Hack or bug">
                                            Hack or Bug
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="Update or improvement">
                                            Update or Improvement
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="New Idea">
                                            New Idea
                                        </label>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="form-group">
                                <label for="exampleTextarea">Comment (Explain it a little bit.)</label>
                                <textarea class="form-control" name="comment" rows="3"></textarea>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{Form::submit('Send', array('class' => 'btn btn-primary'))}}
            </div>
            {{ Form::close() }}

        </div>
    </div>
</div>



<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg" role="document">

        <button type="button" class="close imageClose" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <img src="#" class="img-fluid w-100" alt="image">
    </div>
</div>



<div class="modal fade z-1200" id="likeModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content px-0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Liked by</h4>
            </div>
            <div class="modal-body px-0">
                <div class="container-fluid px-0 ">
                    <div class="col-xs-12  px-0 " id="likedby">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="profilepicModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content px-0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update your profile picture.</h4>
            </div>
            <div class="modal-body px-0">
                <div class="container-fluid px-0 ">
                    <div class="col-xs-12 pt-1 ">
                        <div class=" col-xs-12 col-md-6 text-xs-center" id="profile_pic_upload">
                            <img src="{{  Cloudder::show($sentinel_user->profile_picture_big, array()) }}" class="pp-190 rounded-circle"
                                 alt="{{$sentinel_user->name}}">
                        </div>
                        {{ Form::open(array('url' => '/update_profile', 'method' => 'POST','enctype' => 'multipart/form-data')) }}
                        <div class=" mt-3 col-xs-12 pt-1 col-md-6 text-xs-center align-middle" style="">
                            <label class="custom-file w-100 ">
                                {{ Form::hidden('success', 'load_picture') }}
                                {{ Form::file('profile_picture', array('class' => 'hidden-xs-up',
                                                            'accept'=>'image/*',
                                                            'onchange' => '$(this).submit();')) }}

                            </label>
                            <div class="cursor-pointer custom-file-control text-xs-left w-100"  onclick="$(this).prev().children().first().trigger('click');"> </div>


                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::open(array('url' => '/update_profile', 'method' => 'DELETE','class' => 'd-inline')) }}

                <button type="submit" class="btn btn-secondary " data-dismiss="modal">Close</button>

                {{ Form::close() }}


                {{ Form::open(array('url' => '/update_profile', 'method' => 'PUT','class' => 'd-inline')) }}
                {{ Form::hidden('success', 'upload_profile_picture') }}
                {{Form::submit('Save', array('class' => 'btn btn-primary'))}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>






<div class="modal fade" id="wallModal" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content px-0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update your profile Wall.</h4>
            </div>
            <div class="modal-body px-0">
                <div class="container-fluid px-0 ">
                    <div class="col-xs-12 pt-1 ">
                        <div class=" col-xs-12 text-xs-center"  id="wall_pic_upload">
                            <img src="{{  Cloudder::show($sentinel_user->wall_picture_big, array()) }}" class="w-100"
                                 alt="{{$sentinel_user->name}}">
                        </div>
                        {{ Form::open(array('url' => '/update_wall', 'method' => 'POST','enctype' => 'multipart/form-data')) }}
                        <div class=" mt-1 col-xs-12  text-xs-center align-middle">
                            <label class="custom-file w-100 ">
                                {{ Form::hidden('success', 'load_wall') }}
                                {{ Form::file('wall_picture', array('class' => 'hidden-xs-up',
                                                            'accept'=>'image/*',
                                                            'onchange' => '$(this).submit();')) }}

                            </label>

                            <div class="cursor-pointer custom-file-control text-xs-left w-100"  onclick="$(this).prev().children().first().trigger('click');"> </div>


                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::open(array('url' => '/update_wall', 'method' => 'DELETE','class' => 'd-inline')) }}

                <button type="submit" class="btn btn-secondary " data-dismiss="modal">Close</button>

                {{ Form::close() }}


                {{ Form::open(array('url' => '/update_wall', 'method' => 'PUT','class' => 'd-inline')) }}
                {{ Form::hidden('success', 'upload_wall_picture') }}
                {{Form::submit('Save', array('class' => 'btn btn-primary'))}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="profileListModal" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content px-0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body px-0">
                <div class="container-fluid px-0 ">
                    <div class="col-xs-12  " id="profileList">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
