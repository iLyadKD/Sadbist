<!DOCTYPE html>
<!-- saved from url=(0028)http://10020.ir/b2c/register -->
<html class="no-js"><head data-id="" data-base-url="http://10020.ir/" data-language="fa" asset-url="http://10020.ir/assets/" front-url="http://100" controller="b2c" method="register" data-file-ext=".html" data-controller="b2c" data-method="register" data-hash=""><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style type="text/css">@charset "UTF-8";[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak,.ng-hide:not(.ng-hide-animate){display:none !important;}ng\:form{display:block;}.ng-animate-shim{visibility:hidden;}.ng-anchor{position:absolute;}</style>
    <!--  Link JavaScrript For Modal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
@font-face {
    font-family: "IRANSans";
src: url(Font/IRANSans.eot);
src: url(Font/IRANSans.eot?#iefix) format("embedded-opentype"), url(Font/IRANSans.woff)
        format("woff"), url(Font/IRANSans.ttf) format("truetype"), url(Font/IRANSans.svg#BYekan) format("svg");
font-weight: normal;
font-style: normal
}
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    background: transparent;
}
ol, ul {
    list-style: none;
}
table {
    border-collapse:collapse;
    border-spacing:0;
}
h1,h2,h3,h4,h5,h6 {
    font-size:100%;
}
.title{
    font-family: IRANSans; font-size:22px;
}
#bl, #bl2 , #bl3, #bl4{ position: relative; color: #fff; font-weight: bold; float: left; margin-right: 0px; }
.flip-top { height: 39px; line-height: 75px; font-size: 55px; background: url('img/flip-top.png') no-repeat; text-align: center; }

.flip-btm { height: 40px; background: url('img/flip-btm.png') no-repeat; text-align: center; }

@media (min-width: 472px) and (max-width: 768px) {
    /*.flip-top { font-size: 30px;}*/
    /*#beginnerH3{font-size: 15px}*/

}
@media (max-width: 472px) {
    /*.flip-top { font-size: 30px;}*/
    .title{
        font-size: 18px;
    }
    .registerForm{
     padding-right: 20px;
        padding-left: 5px;

    }
    /*#beginnerH3{font-size: 15px}*/

}

</style>
	<title>10020.ir</title>
    <head>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<meta charset="utf-8">
<link type="text/css" rel="stylesheet" href="clock_assets/flipclock.css" />

<link rel="stylesheet" type="text/css" href="style.css">
  <script type="text/javascript" language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<link href="http://10020.ir/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<link href="./Home - 10020.ir_files/style_fa.css" media="all" rel="stylesheet" type="text/css">
<link href="./Home - 10020.ir_files/bootstrap_fa.css" media="all" rel="stylesheet" type="text/css">
<style>
body {
    background-color: #ff6633;
}
</style>
    </head>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name=$phone=$msg=$msg1=$success="";
    $description= $_POST["description"];
    if(empty($_POST['phone'])) {
        $msg = ' لطفا شماره تلفن را وارد نمایید';
    } else if(!is_numeric($_POST['phone'])) {
        $msg = 'فرمت درست نیست';
    } else if(strlen($_POST['phone']) < 10 || strlen($_POST['phone']) > 16 ) {
        $msg = 'شماره تلفن را درست وارد کنید';
    } else {
        $phone = $_POST['phone'];
    }
    if(empty($_POST['name'])) {
        $msg1 = ' Please enter a value';
    }
    else if(is_numeric($_POST['name'])) {
        $msg1 = ' Data entered should not numeric';
    }
    else{
        $name= $_POST['name'];
    }

    $con = mysqli_connect('localhost','ir123138_10020','ir10020','ir123138_10020');
   // $con = mysqli_connect('localhost','root','','10020');
    if(!$con)
    {
        exit("s");
    }
    mysqli_query($con ,"SET NAMES UTF8");
    if($phone != "") {
        $result = mysqli_query($con, "INSERT INTO `special_deal_request`( `email`, `phone`,`description`,`created_date`) VALUES ('$name', '$phone','$description',CURRENT_TIMESTAMP );");
        if ($result) {
          $success="ثبت نام با موفقیت انجام شد";
        } else {
            $msg="مشکلی در ثبت نام";
        }
    }
}
else
{
    $msg="";
    $success="";

}
?>
<script type="text/javascript" async="" src="./Home - 10020.ir_files/js"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="config" src="./Home - 10020.ir_files/config.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jquery" src="./Home - 10020.ir_files/jquery.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="social_media_share" src="./Home - 10020.ir_files/social_media_share.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="lazy" src="./Home - 10020.ir_files/jquery.lazyload.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="select2" src="./Home - 10020.ir_files/select2.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="backslider" src="./Home - 10020.ir_files/backslider.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jq_bxslider" src="./Home - 10020.ir_files/jquery.bxslider.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="owl_carousel" src="./Home - 10020.ir_files/owl.carousel.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="tiny_carousel" src="./Home - 10020.ir_files/jquery.tinycarousel.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jssor" src="./Home - 10020.ir_files/jssor.slider.mini.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="sticky" src="./Home - 10020.ir_files/jquery.sticky.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="underscore_min" src="./Home - 10020.ir_files/underscore-min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="authentication" src="./Home - 10020.ir_files/authentication.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="validation" src="./Home - 10020.ir_files/jquery.validate.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="datatables" src="./Home - 10020.ir_files/jquery.dataTables.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="bootstrap" src="./Home - 10020.ir_files/bootstrap.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="angular" src="./Home - 10020.ir_files/angular.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="additional_methods" src="./Home - 10020.ir_files/additional-methods.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="popupoverlay" src="./Home - 10020.ir_files/jquery.popupoverlay.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="pop_modal" src="./Home - 10020.ir_files/popModal.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jquery_ui" src="./Home - 10020.ir_files/jquery-ui.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="dt_extn" src="./Home - 10020.ir_files/dataTables.extension.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="dt_tabletools" src="./Home - 10020.ir_files/dataTables.tableTools.min.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="angular_slider" src="./Home - 10020.ir_files/angular-slider.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="angular_pagination" src="./Home - 10020.ir_files/angular-pagination.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="angular_sanitize" src="./Home - 10020.ir_files/angular-sanitize.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jquery_plugin" src="./Home - 10020.ir_files/jquery.plugin.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="custom_func" src="./Home - 10020.ir_files/custom_func.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="calendars" src="./Home - 10020.ir_files/jquery.calendars.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="calendars_persian" src="./Home - 10020.ir_files/jquery.calendars.persian.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="calendars_plus" src="./Home - 10020.ir_files/jquery.calendars.plus.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="calendars_picker" src="./Home - 10020.ir_files/jquery.calendars.picker.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="custom" src="./Home - 10020.ir_files/custom.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="tour" src="./Home - 10020.ir_files/tour.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="angular_app" src="./Home - 10020.ir_files/angular-app.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="flight" src="./Home - 10020.ir_files/flight.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="simplePagination" src="./Home - 10020.ir_files/simplePagination.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="async" src="./Home - 10020.ir_files/async.js.download"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="hotel" src="./Home - 10020.ir_files/hotel.js.download"></script><script type="text/javascript" charset="UTF-8" src="./Home - 10020.ir_files/common.js.download"></script><script type="text/javascript" charset="UTF-8" src="./Home - 10020.ir_files/util.js.download"></script><script type="text/javascript" charset="UTF-8" src="./Home - 10020.ir_files/stats.js.download"></script></head>
<body>
<div id="page-wrapper">
    <div class="row">

        <div  align="center">
                <div style="margin-top:20px;"><img src="pic/logo_farsi4.png" width=400px height="75"></div>
            <div><img src="pic/coming_soon.png" width=400px height="120"></div>
        </div>
        <div >

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-4 col-sm-3">

        </div>
        <div class="col-sm-6 col-md-4 col-xs-12">
            <h3 style="margin-top:20px;" align="center" id="beginnerH3"><span style=" font-family: IRANSans; color : white;"> افتتاح سایت ۱۹ مرداد ۱۳۹۶</span></h3>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-3 ">

        </div>
    </div>
        <div class="row">
            <div class="col-md-4 col-sm-3 ">

            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div id="clock-ticker" class="clearfix col-md-12 col-sm-12 col-xs-12 " style=" margin-top:10%; " >
                    <div class=" col-sm-3 col-md-3 col-xs-3" id="bl">
                        <span class="flip-top" id="numdays"> </span>
                        <span class="flip-btm" style=" "></span>
                        <footer style="font-family: IRANSans;display: block" class="label">روز</footer>
                    </div>

                    <div class=" col-sm-3 col-md-3 col-xs-3" id="bl2">
                        <span class="flip-top" id="numhours"></span>
                        <span class="flip-btm"></span>
                        <footer style="font-family: IRANSans;display: block"class="label">ساعت</footer>
                    </div>

                    <div class=" col-sm-3 col-md-3 col-xs-3" id="bl3">
                        <span class="flip-top" id="nummins"></span>
                        <span class="flip-btm"></span>
                        <footer style="font-family: IRANSans;display: block"class="label">دقیقه</footer>
                    </div>

                    <div class=" col-sm-3 col-md-3 col-xs-3" id="bl4">
                        <span class="flip-top" id="numsecs"></span>
                        <span class="flip-btm"></span>
                        <footer style="font-family: IRANSans ; display: block" class="label">ثانیه</footer><br>
                    </div>
            </div>
            </div>
            <div class="col-md-4 col-sm-3  ">

            </div>
        </div>
            <div class="row">
                <div class="col-md-4 col-sm-3 ">

                </div>
                <div class="col-md-4 col-sm-6 col-xs-12 registerForm">
                    <form action="" method="post">
                        <div style="" align="center">
                            <div class="deals1">
                                <!--<div class="col-md-8 nopadding">-->
                                <h3><span  class= "title" style=" ">ثبت نام در سفر کیش</span></h3>
                                <div class="insigndiv">
                                    <label class="error spcl_req_label" style="color:green"></label>
                                    <form class="special_deal_request_form" method="post" novalidate="novalidate">
                                        <div class="ritpul">
                                            <div class="rowput col-md-5 nopadding"  style="float: left">
                                                <span class="error" style="color: green; font-family: IRANSans"><?php echo $success;?></span>
                                            </div>
                                            <div class="rowput col-md-7 nopadding">
                                                <span style=" font-family: IRANSans"><input class="form-control logpadding " type="text" name="name" placeholder="نام خانوادگی" data-rule-required="true" data-msg-required="نام خانوادگی را وارد نمایید" aria-required="true"></span>

                                            </div>
                                            <div class="rowput col-md-7 nopadding">
                                                <span style=" font-family: IRANSans"><input class="form-control logpadding " type="text" name="phone" placeholder="شماره تماس" data-rule-required="true" data-msg-required="شماره تلفن را وارد نمایید" aria-required="true"></span>
                                            </div>
                                            <div class="rowput col-md-5 nopadding">
                                                <span class="error" style="color: #ff0000; font-family: IRANSans"><?php echo $msg;?></span>
                                            </div>

<!--                                            <div class="rowput col-md-7 nopadding">-->
<!--                                                <span style=" font-family: IRANSans"><input class="form-control logpadding " type="text" name="phone" placeholder="شماره تماس" data-rule-required="true" data-msg-required="شماره تلفن را وارد نمایید" aria-required="true"></span>-->
<!--                                            </div>-->

                                            <div class="rowput col-md-7 nopadding">
                                                <span style=" font-family: IRANSans"><textarea style="resize: none" rows="4" cols="5"  class="form-control logpadding " name="description"  placeholder=" حدس بزنید ما چکاره هستیم" data-rule-required="true" aria-required="true"></textarea></span>
                                            </div>
                                            <div class="rowput col-md-4 nopadding_right">
                                                <input  type="button" data-toggle="modal" data-target="#myModal" name="ghavanin" class="submitlogin " style="font-family: IRANSans;" value="مشاهده قوانین">
                                                <input  type="submit"  name="sabtenam" class="submitlogin " style="font-family: IRANSans;margin-top: 15px" value="ارسال">
                                                <div class='modal fade' id='myModal' role='dialog' >
                                                    <div class='modal-dialog'>

                                                        <!-- Modal content-->
                                                        <div class='modal-content' style='direction: rtl; font-family: IRANSans'>
                                                        <div class='modal-header'>
                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                            <h4 class='modal-title'> قوانین سفر به کیش</h4>
                                                        </div>
                                                        <div class='modal-body'>
                                                            <p>با ثبت نام در سایت 10020 میتوانید شانس خود را برای یک سفر هیجانی امتحان کنید.</p><br>
                                                            <p>حدس بزنید ما چکاره هستیم و برنده خوش شانس ما باشید.</p><br>
                                                            <p>برنده قرعه کشی قادر به همراهی یک نفر دراین سفر می باشد.</p>
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-info' data-dismiss='modal'>بستن</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-md-4 col-sm-3 ">

                </div>
            </div>
    </div>


<script type="text/javascript">
    $(document).ready(function(){

            var deadline = new Date ("2017-08-10");
            //var deadline = new Date (2017,7,22);
            var d = new Date();
            var t = deadline - d;
            var seconds = Math.floor( (t/1000) % 60 );
            var minutes = Math.floor( (t/1000/60) % 60 );
            var hours = Math.floor( (t/(1000*60*60)) % 24 );
            var days = Math.floor( t/(1000*60*60*24) );
       // )
//            function updateClock(){
//                var t = getTimeRemaining(endtime);
//
//                daysSpan.innerHTML = t.days;
//                hoursSpan.innerHTML = t.hours;
//                minutesSpan.innerHTML = t.minutes;
//                secondsSpan.innerHTML = t.seconds;
//
//            }

            var theDaysBox  = $("#numdays");
            var theHoursBox = $("#numhours");
            var theMinsBox  = $("#nummins");
            var theSecsBox  = $("#numsecs");
            theDaysBox.html(days);
            theHoursBox.html(hours);
            theMinsBox.html(minutes);
            theSecsBox.html(seconds);
//            document.getElementById("numdays").value = days;
        var refreshId = setInterval(function() {
            var currentSeconds = theSecsBox.text();
            var currentMins    = theMinsBox.text();
            var currentHours   = theHoursBox.text();
            var currentDays    = theDaysBox.text();

            if(currentSeconds == 0 && currentMins == 0 && currentHours == 0 && currentDays == 0) {
                // if everything rusn out our timer is done!!
                // do some exciting code in here when your countdown timer finishes

            } else if(currentSeconds == 0 && currentMins == 0 && currentHours == 0) {
                // if the seconds and minutes and hours run out we subtract 1 day
                theDaysBox.html(currentDays-1);
                theHoursBox.html("23");
                theMinsBox.html("59");
                theSecsBox.html("59");
            } else if(currentSeconds == 0 && currentMins == 0) {
                // if the seconds and minutes run out we need to subtract 1 hour
                theHoursBox.html(currentHours-1);
                theMinsBox.html("59");
                theSecsBox.html("59");
            } else if(currentSeconds == 0) {
                // if the seconds run out we need to subtract 1 minute
                theMinsBox.html(currentMins-1);
                theSecsBox.html("59");
            } else {
                theSecsBox.html(currentSeconds-1);
            }
        }, 1000);
    }
    );

</script>



<!--
                                           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-104132437-1', 'auto');
  ga('send', 'pageview');

</script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-104119777-1', 'auto');
  ga('send', 'pageview');

</script>
</body></html>