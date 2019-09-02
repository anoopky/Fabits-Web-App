<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    @yield('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="google-site-verification" content="mQW8_fPfBqwTvrAmajWqFtJPkUlP5hbKeKj31GqFcsw" />
    <meta name="description" content="Fabits.in is customized social networking site which connect colleges, friends, and students with each other."/>
    <meta name="keywords" content="fabits, social network, fabits.in">
    <link rel="apple-touch-icon" sizes="120x120" href="{{Cloudder::show("fabits/apple-touch-icon", array())}}">
    <link rel="icon" type="image/png" href="{{Cloudder::show("fabits/favicon-32x32", array())}}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{Cloudder::show("fabits/favicon-16x16", array())}}" sizes="16x16">
    <link rel="manifest" href="js/manifest.json">
    <link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#0275d8">
    <meta name="theme-color" content="#0275d8">
    <link rel="stylesheet" href="/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/main.min.css">
    {{--<link rel="stylesheet" href="/css/main.css">--}}

</head>
<body>
    @yield('content')

    <script src="/js/jquery.min.js"></script>
    <script src="/js/tether.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script  src="/js/main.slim.min.js"></script>
    {{--<script  src="/js/main.slim.js"></script>--}}
</body>
</html>
