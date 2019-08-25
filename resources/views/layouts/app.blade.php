<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<style type="text/css">
    .disabledbutton 
    {
        cursor: not-allowed;
        background-color: rgb(229, 229, 229) !important;
    }
   
</style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <input type = "hidden" id="testtoken"> 
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>

                    
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php 
                        if(isset($_POST['data1'])){
                               echo "helllo ";die;
                           }
                        ?>
                        @guest
                            <router-link tag="li" :to="{ name: 'login' }">
                            <a>Login</a>
                        </router-link>
                         
                        @else
                            <li><a href="{{ route('admin.companies.index') }}">Companies</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                   <!--  <ul class="nav navbar-nav navbar-right">
                       
                        <router-link tag="li" :to="{ name: 'login' }">
                            <a>Login</a>
                        </router-link>
                    </ul> -->
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
   
    <script>
        console.log("app.blade"+localStorage.getItem('token'));
        value = localStorage.getItem('token');
        $.ajax({
     method: "POST",
     url: "app.blade.php",
     data: { data1: value}
                    }).done(function(html){                      //function block runs if Ajax request was successful
                    }).fail(function(html){
    // function block runs if Ajax request failed
});
    </script>
    
    <!-- Scripts -->
   <!--  <script src="{{ asset('js/interceptor.js') }}"></script> -->
    <!-- Common JS -->
    <script src="{{ asset('js/common.min.js') }}"></script>
    <!-- Custom script -->
    <script src="{{ asset('js/custom.min.js') }}"></script>
</body>
</html>