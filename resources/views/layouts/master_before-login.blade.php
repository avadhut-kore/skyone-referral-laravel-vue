<!DOCTYPE html>
<html lang="en" class="h-100" id="login-page1">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Helping User Panel Login page</title>
    <!-- Favicon icon -->

    <!-- Custom Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/modernizr-3.6.0.min.js') }}"></script>   
   
</head>

<body class="h-100">
<div id="app" class="h-100">
     @yield('content')
    <!-- #/ container -->
</div>    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Common JS -->
    <script src="{{ asset('js/common.min.js') }}"></script>
    <!-- Custom script -->
    <script src="{{ asset('js/custom.min.js') }}"></script>
</body>
</html>