<?php
//$facebook   = $message->embed(public_path() . '/img/facebook.png');
//$logo="http://sk.uploads.im/t/xp0bI.png";
//$emaillogo="http://sk.uploads.im/t/DMtVy.png";
//$facebook="http://sk.uploads.im/t/gmFsb.png";
//$logo   = $message->embed(public_path() . '/img/logo.png');
//$emaillogo   = $message->embed(public_path() . '/img/email.png');
//$facebook   = $message->embed(public_path() . '/img/facebook.png');
$projectname=Config::get('constants.settings.projectname');
$path=Config::get('constants.settings.domainpath');
$facebook_url   = "";
  echo $msg=
'<html>
  <head>
    <meta charset="utf-8">
    <title>Confirmation Mail </title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>
  <body style="background:#fff; font-family:"Raleway", sans-serif;">
    <div class="warp" style=" width: 400px; background: #ffe0; margin: 30px auto; display: block;">
        <div class="wrapper-header" style=" padding: 20px 0; text-align: center; background: white;">
          <img src='' alt="" width="200">
        </div>
        <div class="wrapper-inner" style=" box-shadow: 0px 0px 5px 1px #38383840;">
          
          <div class="wrapper-body" style="background: #fff; padding: 40px; text-align: center;">
            <h1 style="color: #2d2d2d; margin-top: 0; font-family: "Roboto", sans-serif; font-weight: 400;">Email Confirmation</h1>
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">You are just one step away! </p>
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">Please click the link below to verify your email id and become a Eravea insider! </p>
            <a href="'.$path.'/verifyemailid?verifytoken='.$verify_token.'"  style="background: #3270d0; text-align: center; text-decoration: none; color: #fff; padding: 10px; display: inline-block;">Verify Email Address</a>
        </div>
      </div>
      <div class="wrapper-footer" style="background: #fff; padding: 10px; text-align: center;">
        <div class="copyright">
          <p style=" color: #8e8e8e; margin: 0; margin-top: 10px; line-height: 1.6;"> Email send by '.$projectname.'</p>
          <p style=" color: #8e8e8e; margin: 0; margin: 0px; line-height: 1.6;"> Copyright &copy; '.date('Y').', All rights reserved.</p>
        </div>
      </div>
    </div>
  </body>
</html>
';

