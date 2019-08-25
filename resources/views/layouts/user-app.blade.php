<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (Config::get('constants.settings.projectname'))}}</title>
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/ico" href="{{ asset('public/images/favicon.ico') }}">
    <!-- Custom Stylesheet -->
    
    <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
     <link href="{{ asset('public/css/bootstrap.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'"/>
    <link href="{{ asset('public/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/css/responsive.bootstrap.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'"/>
    <link href="{{ asset('public/css/simple-line-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/css/font-awesome.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'"/>
   
    <link href="{{ asset('public/css/shortcode.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'"/>
    <link href="{{ asset('public/css/metisMenu.min.css') }}" rel="stylesheet" media="none" onload="if(media!='all')media='all'"/>
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet" >
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <script src="{{ asset('public/js/modernizr-3.6.0.min.js') }}"></script>
</head>
<body>
   <!--  <div id="preloader">
        <div class="loader">
            <div class="loader__bar"></div>
            <div class="loader__bar"></div>
            <div class="loader__bar"></div>
            <div class="loader__bar"></div>
            <div class="loader__bar"></div>
            <div class="loader__ball"></div>
        </div>
    </div> -->
    <div id="app"></div>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <!-- Common JS -->
    <script src="{{ asset('public/js/common.min.js') }}"></script>
    <!-- Custom script -->
    <script async src="{{ asset('public/js/custom.min.js') }}"></script>
    <script async src="{{ asset('public/js/jquery.dataTables.min.js') }}"></script>
    <script async src="{{ asset('public/js/dataTables.bootstrap.min.js') }}"></script>
    <script async src="{{ asset('public/js/tables-datatables.js') }}"></script>
    <script async src="{{ asset('public/js/dataTables.responsive.min.js') }}"></script>
    <script async src="{{ asset('public/js/responsive.bootstrap.min.js') }}"></script>
    <script>
        function myFunction() {
            var copyText = document.getElementById("referral-link");
            copyText.select();
            document.execCommand("copy");

            var tooltip = document.getElementById("refcopy");
            tooltip.innerHTML = "Copied: " + copyText.value;
        }

        function outFunc() {
            var tooltip = document.getElementById("refcopy");
            tooltip.innerHTML = "Copy to clipboard";
        }
        $(".fileupicon").click(function (e) {
            e.preventDefault();
            $("input[type='file']").trigger('click');
        });

        $('input[type="file"]').on('change', function() {
            var val = $(this).val();
            $(this).siblings('span').text(val);
        });
        let token= (localStorage.getItem('access_token'));
        
        if(token == null){
            var root = document.getElementsByTagName( 'html' )[0]; // '0' to assign the first (and only `HTML` tag)
            root.setAttribute( 'class', 'h-100' );
            root.setAttribute( 'id', 'login-page1' );
        }
    </script>

</body>
</html>