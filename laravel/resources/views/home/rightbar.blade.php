<div class="fixed-right h-100 mt-53 sd-min  px-0 hidden-md-down">
    <ul class="nav nav-tabs ba-0 px-0 text-xs-center" role="tablist">
        <li class="nav-item col-xs-4 px-0 mx-0">
            <a class="nav-link ba-0 opensanL" data-toggle="tab" href="#home" role="tab">Feeds</a>
        </li>
        {{--<li class="nav-item col-xs-4 px-0 mx-0">--}}
            {{--<a class="nav-link ba-0 opensanL " data-toggle="tab" href="#messages" role="tab">Recent</a>--}}
        {{--</li>--}}
        <li class="nav-item col-xs-4 px-0 mx-0">
            <a class="nav-link active ba-0 opensanL" data-toggle="tab" href="#people1" role="tab">People</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mt-0_5">
        <div class="tab-pane " id="home" role="tabpanel">

            <div class="scrollbar-inner" id="feed-tab">
                <div class="right-bar-scroll" id="feeds-data"></div>
            </div>

        </div>
        {{--<div class="tab-pane" id="messages" role="tabpanel">--}}
            {{--<div class="scrollbar-inner" id="recent-people">--}}
                {{--<div class="right-bar-scroll" id="recent-chat"></div>--}}
            {{--</div>--}}

        </div>
        <div class="tab-pane active " id="people1" role="tabpanel">
            <div class="scrollbar-inner" id="people">
                    <div class="right-bar-scroll" id="online"></div>
            </div>
        </div>
        <form class="form-inline w-100" style="position:absolute;bottom:56px;">
            <input id="filter" class="form-control form-control-sm b-lightgrey  border-light square-corner  w-100" type="text" placeholder="Search">
        </form>
    </div>

</div>
