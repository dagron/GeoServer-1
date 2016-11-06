<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->

<!-- custom styles -->
<style>
.btn-info {
    background-color: #006600;
    border-color: #003300;
}
.btn-info:hover,
.btn-info:focus {
    background-color: #009900 !important;
    border-color: #006600 !important;
}
.btn-info:active {
    background-color: #00b300 !important;
    border-color: #003300 !important;
}
.btn-danger {
    background-color: #b30000;
    border-color: #990000;
}
.btn-danger:hover,
.btn-danger:focus {
    background-color: #e60000 !important;
    border-color: #b30000 !important;
}
.btn-danger:active {
    background-color: #ff0000 !important;
    border-color: #e60000 !important;
}
.dropdown-menu>li>a:hover{
    background-color: #009900 !important;
}
#splashscreen {
  position: fixed; 
  top: -50%; 
  left: -50%; 
  width: 200%; 
  height: 200%;    
  z-index: -10;
}

#splashscreen img {
  position: absolute; 
  top: 0; 
  left: 0; 
  right: 0; 
  bottom: 0; 
  margin: auto; 
  min-width: 50%;
  min-height: 50%;
}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

<div  id="splashscreen">
     <img src="{{ url('img/splash.jpg') }}" alt=""> 
</div>

    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                 <img style="width:40px;height:40px;float:left;margin-right:5px;position:relative;top:-10px;" src="{{ url('img/logo.png')}}" > FarmManager-GeoServer
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">{{trans('general.login') }} </a></li>
                        <li><a href="{{ url('/register') }}">{{trans('general.register') }}</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ trans('general.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?php echo strtoupper( App::getLocale() ); ?><span class="caret"></span>
                            </a>
                        <ul class="dropdown-menu" role="menu">
                            <div style="margin: 0 auto;width: 50%;">
                                <li style="margin-bottom:4px"><div onclick="changeLanguage('en')"><img src="{{url('img/en.png') }}"/> EN</div></li>
                                <li><div onclick="changeLanguage('gr')"><img src="{{url('img/gr.png') }}"/> GR</div></li>
                            </div>
                        </ul>
                        <script>
                            function changeLanguage(locale){
                                    var base_url = window.location.origin;

                                    var http = new XMLHttpRequest();
                                    http.onreadystatechange = function() {
                                        if(http.readyState == 4 && http.status == 200) {
                                              location.reload();
                                        }
                                    }

                                    http.open("GET", base_url + '/api/languages/'+locale);
                                    http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

                                    http.send();
                            }
                        </script>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/jquery-3.1.1.js"></script>
    <script src="/js/geoserver.js"></script>
</body>
</html>
