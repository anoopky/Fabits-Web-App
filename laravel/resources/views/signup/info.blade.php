@extends('template.master')
@section('head')
    <title>Basic Information | fabits.in</title>
@endsection
@section('content')
    @include('template.notification')
    @include('template.topmenu')
<div class="container-fluid  " >
<div class="row mt-4 ">
<div class="col-xs-12 col-md-8  offset-md-2  col-lg-7  offset-lg-3  col-xl-6  mt-3 px-2 pb-3 sd-1 b-white ba-1">
    <div class="row my-2 text-xs-center p-0">
        <div class="btn-group btn-breadcrumb p-0 ">
            <div  class="btn btn-lg btn-primary"><i class="fa fa-lock" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Password</span></div>
            <div class="btn btn-lg btn-primary "> <i class="fa fa-phone" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Phone</span></div>
            <div class="btn btn-lg btn-primary"><i class="fa fa-user" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Info</span></div>
            <div class="btn btn-lg  btn-default"><i class="fa fa-camera" aria-hidden="true"></i>
                <span class="hidden-sm-down thin">Profile</span></div>
        </div>
    </div>
    {{ Form::open(array('url' => '/info', 'method' => 'PUT')) }}
    {{ Form::hidden('location', '/profile') }}
    <div class="col-xs-12 pt-1 px-0  ">
        <div class="col-xs-6 offset-md-2 col-md-4  p-0 text-xs-center  ">
            <img src="{{  Cloudder::show('fabits/male', array()) }}" id="male" class="cust_border"
                alt="Male">
        </div>
        <div class="col-xs-6 col-md-4 text-xs-center p-0 ">
            <img src="{{  Cloudder::show('fabits/female', array()) }}" id="female" class="cust_border"
                alt="Female">
    </div></div>

    <input type="hidden" id="Gender-val" name="gender" value="-1">

<div class="col-xs-12 pt-2 p-0">
    <div class="col-xs-4 ">
        <select name="birthday_day"  title="Day" class="form-control  ">
        <option value="0" selected>Day</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        <option value="13">13</option>
        <option value="14">14</option>
        <option value="15">15</option>
        <option value="16">16</option>
        <option value="17">17</option>
        <option value="18">18</option>
        <option value="19">19</option>
        <option value="20">20</option>
        <option value="21">21</option>
        <option value="22">22</option>
        <option value="23">23</option>
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
        <option value="29">29</option>
        <option value="30">30</option>
        <option value="31">31</option>

    </select>
    </div>
    <div class="col-xs-4">
        <select name="birthday_month" title="Month" class="form-control ">
        <option value="0" selected>Month</option>
        <option value="1">Jan</option>
        <option value="2">Feb</option>
        <option value="3">Mar</option>
        <option value="4">Apr</option>
        <option value="5">May</option>
        <option value="6">Jun</option>
        <option value="7">Jul</option>
        <option value="8">Aug</option>
        <option value="9">Sept</option>
        <option value="10">Oct</option>
        <option value="11">Nov</option>
        <option value="12">Dec</option>
    </select>
    </div>
    <div class="col-xs-4">
        <select  name="birthday_year" title="Year" class="form-control ">
        <option value="0" selected>Year</option>
        <option value="2016">2016</option>
        <option value="2015">2015</option>
        <option value="2014">2014</option>
        <option value="2013">2013</option>
        <option value="2012">2012</option>
        <option value="2011">2011</option>
        <option value="2010">2010</option>
        <option value="2009">2009</option>
        <option value="2008">2008</option>
        <option value="2007">2007</option>
        <option value="2006">2006</option>
        <option value="2005">2005</option>
        <option value="2004">2004</option>
        <option value="2003">2003</option>
        <option value="2002">2002</option>
        <option value="2001">2001</option>
        <option value="2000">2000</option>
        <option value="1999">1999</option>
        <option value="1998">1998</option>
        <option value="1997">1997</option>
        <option value="1996">1996</option>
        <option value="1995">1995</option>
        <option value="1994">1994</option>
        <option value="1993">1993</option>
        <option value="1992">1992</option>
        <option value="1991">1991</option>
        <option value="1990">1990</option>
        <option value="1989">1989</option>
        <option value="1988">1988</option>
        <option value="1987">1987</option>
        <option value="1986">1986</option>
        <option value="1985">1985</option>
        <option value="1984">1984</option>
        <option value="1983">1983</option>
        <option value="1982">1982</option>
        <option value="1981">1981</option>
        <option value="1980">1980</option>
        <option value="1979">1979</option>
        <option value="1978">1978</option>
        <option value="1977">1977</option>
        <option value="1976">1976</option>
        <option value="1975">1975</option>
        <option value="1974">1974</option>
        <option value="1973">1973</option>
        <option value="1972">1972</option>
        <option value="1971">1971</option>
    </select>
    </div>
</div>
    <div class="row">
        <div class="col-xs-12  pt-2 p-0 ">
            <div class="col-xs-12  pl-0 offset-md-10 col-md-2">
                {{Form::submit('Next',array('class' => 'btn btn-primary mt-1 w-100'))}}
            </div>

          </div>
    </div>
{{ Form::close() }}
</div>
</div>
</div>
@endsection
