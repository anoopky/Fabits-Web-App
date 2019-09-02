<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    @yield('head')
    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <meta name="google-site-verification" content="mQW8_fPfBqwTvrAmajWqFtJPkUlP5hbKeKj31GqFcsw"/>
    <meta name="description"
          content="Fabits.in is customized social networking site which connect colleges, friends, and students with each other."/>
    <link rel="apple-touch-icon" sizes="120x120" href="{{Cloudder::show("fabits/apple-touch-icon", array())}}">
    <link rel="icon" type="image/png" href="{{Cloudder::show("fabits/favicon-32x32", array())}}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{Cloudder::show("fabits/favicon-16x16", array())}}" sizes="16x16">
    <link rel="manifest" href="js/manifest.json">
    <link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#0275d8">
    <meta name="theme-color" content="#0275d8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user_id" content="{{ $sentinel_user->id }}">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/emojionearea.min.css">
     <link rel="stylesheet" href="/css/main.min.css">
     {{--<link rel="stylesheet" href="/css/main.css">--}}
    <link rel="stylesheet" type="text/css" href="/css/csshake.min.css">

</head>
<body>
<!--#2196F3 !important blue-->
@include('template.notification')
@include('home.postpopup')
@include('home.topbar')
@include('home.leftbar')
@include('home.rightbar')

<div id="page">
    @yield('body')
</div>

<div id="chatmanager"></div>
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/tether.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/masonry.pkgd.min.js"></script>
<script src="/js/jquery.scrollbar.min.js"></script>
<script src="/js/emojionearea.min.js"></script>
<script src="/js/pusher.min.js"></script>
<script src="/js/push.min.js"></script>
{{--<script src="/js/templates.js"></script>--}}
{{--<script src="/js/realtime.js"></script>--}}
{{--<script src="/js/main.js"></script>--}}
<script src="/js/templates.min.js"></script>
<script src="/js/realtime.min.js"></script>
<script src="/js/main.min.js"></script>
<script>chat_app_conversation_id = null; </script>
</body>
</html>
