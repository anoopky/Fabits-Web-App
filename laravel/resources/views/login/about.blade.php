@extends('template.master')
@section('head')
    <title>fabits.in | About</title>
@endsection
@section('content')
    <style>
        body {
            background: url("{{  Cloudder::show('fabits/background_image1', array()) }}") no-repeat fixed;
            background-size: cover;
        }
    </style>
    <!--#2196F3 !important blue-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12  pt-1 text-white lead text-xs-center" style="height: 50px;">
                <a href="/" data-loc="page-full"><img src="/img/fabits.png" alt="fabits.in" style="width: 180px;" ></a>

            </div>
        </div>
        @include('template.notification')
        <div class="row mt-8per ">
            <div class="offset-md-1 col-xs-12 col-md-10 b-white">
                <h1 class="display-4 text-xs-center p-2">About</h1>
                <P class="lead text-black text-xs-center">
                    Founded on 14 February 2017
                    <br>
                    Fabits.in is customized social networking site which connect colleges, friends, and students with each other.
                    <br>
                    <b>CONTACT INFO::</b>
                    <br>
                    <a href="mailto:fabitsmail@gmail.com?Subject=Inquiry" data-loc="page-full">fabitsmail@gmail.com</a>
                <p>

            </div>

        </div>
        <div class="row ">
            <div class="col-md-12 p-2 pr-3 text-xs-center text-md-right mr-1 " >

                <ul class="nav nav-inline ">
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/terms">Terms & Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/privacy">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/loginpolicy">Login Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link white-a" data-loc="page-full" href="/about">About</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
@endsection
