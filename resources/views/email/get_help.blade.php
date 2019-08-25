
<?php
$logo   = $message->embed(public_path() . '/images/logo.png');
//$emaillogo   = $message->embed(public_path() . '/img/email.png');
//$facebook   = $message->embed(public_path() . '/img/facebook.png');
//$logo="http://sk.uploads.im/t/xp0bI.png";
//$emaillogo="http://sk.uploads.im/t/DMtVy.png";
//$facebook="http://sk.uploads.im/t/gmFsb.png";
$projectname=Config::get('constants.settings.projectname');
$domain=Config::get('constants.settings.domainpath');
$facebook_url   = "";
echo $msg='
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Confirmation Mail </title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  </head>
  <body style="background:#fff; font-family:Raleway, sans-serif;margin: 0px;">
    <div class="header-top-bg" style="/*background: #639*/;height: 50px;">

    </div>
    <div class="warp" style=" width: 600px; background: #fff; margin: 10px auto; display: block;">
        <div class="wrapper-inner">
          <div class="wrapper-header-bottom" style="background: #fff; padding: 10px; text-align: center;">
            <img src="'.$logo.'" alt="" width="130">
            <!-- <h1 style="color: #58c4c4; font-family: Roboto, sans-serif; font-weight: 400; font-size: 16px">Welcome </h1> -->
          </div>
          <div class="wrapper-body" style="background: #fff; padding: 40px; ">
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">Hello '.$fullname.'., </p>
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">Congratulations! Your User id '.$userid.' has received a  Get help link of $'.$amount.'. <a href="'.$domain.'" style="">Click here </a> to view the sender\'s details.</p>
            
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">Note:48 hours is the time limit to send help once you receive the provide link. It is necessary to attach payment proof Eg.transaction hash or screen capture of payment sent. In the case if the help receiver does not confirm your Provide help link,please contact the the administrator then and there at enquiry@nexahelp.com</p>
            <p style="color: #717171; margin: 10px 0; line-height: 1.6;">Regards </p>
             <p style="color: #717171; margin: 10px 0; line-height: 1.6;">'.$projectname.'</p>

        </div>
      </div>
      <div class="wrapper-footer" style="background: #fff; padding: 10px; text-align: center;">
        <div class="copyright">
          <p style=" color: #8e8e8e; margin: 0; margin-top: 10px; line-height: 1.6;"> Email send by <a href="'.$domain.'" style="">'.$projectname.'</a></p>
          <p style=" color: #8e8e8e; margin: 0; margin: 0px; line-height: 1.6;"> Copyright &copy; '.date('Y').', All rights reserved.</p>
          <p>'.$domain.'</p>
        </div>
      </div>
    </div>
  </body>
</html>
  ';


                       


       

       
           