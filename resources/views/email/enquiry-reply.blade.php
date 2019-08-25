<html>
    <head>
        <meta charset="utf-8">
        <title>Enquiry Reply </title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>
    <body style="background:#fff; font-family:"Raleway", sans-serif;">
        <div class="warp" style=" width: 400px; background: #fff; margin: 30px auto; display: block;">
            <div class="wrapper-header" style=" padding: 20px 0; text-align: center; background: white;">
                <img src="{{asset('images/logo.png')}}" alt="Logo" width="130">
            </div>
            <div class="wrapper-inner" style=" box-shadow: 0px 0px 5px 1px #38383840;">
                <div class="wrapper-body" style="background: #fff; padding: 40px; text-align: center;">
                    @if(isset($msg) && (!empty($msg)))
                        <p style="color: #717171; margin: 10px 0; line-height: 1.6; text-align: left">Message:
                    {{$msg}} </p>
                    @endif
                </div>
            </div>
            <div class="wrapper-footer" style="background: #fff; padding: 10px; text-align: center;">
                <div class="copyright">
                    <p style=" color: #8e8e8e; margin: 0; margin-top: 10px; line-height: 1.6;"> Email send by {{Config::get('constants.settings.projectname')}}</p>
                    <p style=" color: #8e8e8e; margin: 0; margin: 0px; line-height: 1.6;"> Copyright &copy; {{date('Y')}}, All rights reserved.</p>
                </div>
            </div>
        </div>
    </body>
</html>