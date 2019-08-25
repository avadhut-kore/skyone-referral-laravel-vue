<?php
$logo = $message->embed(public_path() . '/images/logo.png');
$projectname = Config::get('constants.settings.projectname');
$domain = Config::get('constants.settings.domainpath');
echo $msg = '<html>
  <head>
    <meta charset="utf-8">
    <title>Confirmation Mail </title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>
  <body style="background:#f4f3f3; font-family:"Raleway", sans-serif;">
    <div class="warp" style=" width: 400px; background: #ffe0; margin: 30px auto; display: block;">
        <div class="wrapper-header" style=" padding: 20px 0; text-align: center; background: white;">
          <img src=' . $logo . ' alt="" width="200">
        </div>
        <div class="wrapper-inner" style=" box-shadow: 0px 0px 5px 1px #38383840;">
                    
            <div class="wrapper-body" style="background: #fff; padding: 37px; ">
              <p style="color: #717171; margin: 10px 0; line-height: 1.6;"> Thank you for using password recovery option.</p>
              <p style="color: #717171; margin: 10px 0; line-height: 1.6;"> Your password reset successfully.</p>

        </div>
      </div>
      <div class="wrapper-footer" style="background: #fff0; padding: 10px; text-align: center;">
         
        <div class="copyright">
        <p style=" color: #8e8e8e; margin: 0; margin-top: 10px; line-height: 1.6;"> Email send by '. $projectname.'</p>
          <p style=" color: #8e8e8e; margin: 0; margin: 0px; line-height: 1.6;"> Copyright &copy; ' . date('Y') . ', All rights reserved.</p>
          <p>'.$domain.'</p>
        </div>
      </div>
    </div>
  </body>
</html>';