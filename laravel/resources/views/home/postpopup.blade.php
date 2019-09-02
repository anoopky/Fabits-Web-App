<div class="modal fade bd-example-modal-lg" id="myModal"   data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">New Post</h4>
            </div>
            {{ Form::open(array('url' => '/post', 'method' => 'POST')) }}
            {{ Form::hidden('success', 'post') }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6 ">
                        <div class="form-group">
                            {{Form::textarea('postText', '', array('class' => 'border-light form-control ba-1','placeholder' => 'What\'s Up !', 'rows' => '7', 'id'=>'post-editor'))}}
                            <div id="people-result" class="hidden-xs-up b-white sd-min ba-1 border-light">

                            </div>
                        </div>
                        <div class="mt-3 pt-1">
                            <ul id="sortable">
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="card square-corner sd-min " >
                            <div class="container-fluid">
                                <div class="row px-0_5 mt-1">
                                    <ul class="nav  nav-inline">
                                        <li class="nav-item align-top">
                                            <a class="" href="{{ '@'.$sentinel_user->username }}">
                                                <img class="rounded-circle pp-50"
                                                     src="{{  Cloudder::show($sentinel_user->profile_picture_small, array()) }}"
                                                     alt="{{ $sentinel_user->name }}"></a>
                                        </li>
                                        <li class="nav-item mx-0 dummy_width">
                                            <div class="lh-1 pl-0_5 "><p class="p-0 m-0 post_user_link opensanN">
                                                    <a class=""
                                                       href="{{ '@'.$sentinel_user->username }}">{{ $sentinel_user->name }}</a>
                                                </p>
                                                <small class="text-muted" id="dummy_time">1 s ago</small>
                                            </div>
                                        </li>
                                        <li class="nav-item px-0 mx-0 float-xs-right">
                                            <div class="nav-item dropdown">
                                                <a class="nav-link fa fa-angle-down text-muted" href="#"
                                                   data-toggle="dropdown" aria-haspopup="true"
                                                   aria-expanded="false"></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="dummy-image" class="pt-0_5"></div>
                            <div class="col-xs-12 pl-c9p pt-1 breakit"><p id="dummy-post">...</p></div>
                            <div class="container-fluid">
                                <div class="row px-1">
                                    <ul class="nav  nav-inline">
                                        <li class="nav-item">
                                            <div class="form-group">
                                                <div class="post-feature-common like">
                                                    <i class="fa fa-thumbs-o-up fa-lg post-icons"
                                                       aria-hidden="true"></i>
                                                    <small class="pl-0_5" id="dummy_like">0</small>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item">
                                            <div class=" post-feature-common dislike">
                                                <i class="fa fa-thumbs-o-down fa-lg post-icons" aria-hidden="true"></i>
                                                <small class="pl-0_5" id="dummy_dislike">0</small>
                                            </div>
                                        </li>
                                        <li class="nav-item float-xs-right">
                                            <div class="post-feature-common comment">
                                                <i class="fa fa-comment-o fa-lg post-icons" aria-hidden="true"></i>
                                                <small class="pl-0_5" id="dummy_comment">0</small>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{Form::submit('Post', array('class' => 'btn btn-primary'))}}
            </div>
            {{ Form::close() }}
            {{ Form::open(array('url' => '/post_upload', 'method' => 'POST','class'=>'pl-1 post_upload','style'=>'position:absolute; bottom:15px;')) }}
            {{ Form::hidden('success', 'post_upload') }}
            {{ Form::file('post_picture', array('class' => 'hidden-xs-up', 'onchange'=>'$(this).submit();' , 'id'=>'post_file'))}}
            <div class="btn-group float-xs-left" role="group" aria-label="Basic example">
                <button type="button" class="btn form-control btn-secondary" onclick="$(this).parent().prev().click();">
                    <i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i>
                </button>
                {{ Form::close() }}
            </div>

        </div>
    </div>
</div>



