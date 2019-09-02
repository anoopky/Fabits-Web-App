<nav class="navbar navbar-fixed-top navbar-dark bg-primary px-0 sd-1">
    <div class="container">
        <div class="row ">
            <a class="navbar-brand col-lg-1 pl-0 col-xs-12 text-xs-center " href="#">
                <img src="/img/fabits.png" alt="fabits.in" style="width:100px;"
                alt="fabits.in">
            </a>
            <form class="form-inline col-lg-5 col-xs-8 col-sm-7 p-0 hidden-md-down">
                <input class="form-control w-100" type="text" placeholder="Search">
            </form>
            <ul class="nav navbar-nav float-xs-right hidden-md-down col-md-4 ">
                <li class="nav-item">
                  <a class="nav-link p-0" href="#"><img class="img-fluid mr-1 pp-40 rounded-circle " src="{{  Cloudder::show($sentinel_user->profile_picture_small, array()) }}" alt="{{ $sentinel_user->name }}">{{ $sentinel_user->name }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
