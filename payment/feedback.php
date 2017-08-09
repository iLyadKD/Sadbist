<?php
error_reporting(0);
require_once("Functions.php");
$id = json_decode(base64_decode($_REQUEST['success']));?>

<style type="text/css">
	div,h2 {
    direction: rtl;
    text-align: center;
}
</style>
<?php 
if($id != ''){
	$func = new Functions();

	$data = $func->getData($id);

	$merchantmail = 'jfathi@10020.ir';
	$status = $data['payment_status'];
	$amount = $data['amount'];
	$refid = $data['sale_ref_id'];
	$subject = "10020.ir Payment";
	$email = $data['user_email'] != "" ? $data['user_email'] : $merchantmail;
	$name = $data['user_name'];
	$phone = $data['user_phone'];

	switch ($status) {
		case 'PENDING':
		case 'VERIFIED':
			echo $message = "<html>
						<head>
						<title>$subject</title>
						</head>
						<body>
						<div style='direction:rtl'>
						<p>عزیز $name</p>
						<p>پرداخت <b>$amount ریال</b> شما با انتظار انجام شد. شماه مرجع شناسه <b>$refid</b>است.</p>
						<p>با تشکر</p>
						<p>  مدیریت 10020.ir</p>
						</div>
						</body>
						</html>
						";
			break;
		case 'FAILED':
			echo $message = "<html>
						<head>
						<title>$subject</title>
						</head>
						<body>
						<div style='direction:rtl'>
						<p>عزیز $name</p>
						<p>ترکنش به مبلع<b>$amount ریالی</b> شما  ناموفق میباشد. لطفا برای پشتیبانی بیشتر با ما تماس بگیرید.</p>
						<p>با تشکر</p>
						<p>  مدیریت 10020.ir</p>
						</div>
						</body>
						</html>
						";
			break;
		case 'SETTLED':
			echo $message = "<html>
						<head>
						<title>$subject</title>
						</head>
						<body>
						<div style='direction:rtl'>
						<p>عزیز $name</p>
						<p>پرداخت <b>$amount ریال</b> شما با موفقت انجام شد. شماه مرجع شناسه <b>$refid</b>است.</p>
						<p>با تشکر</p>
						<p>  مدیریت 10020.ir</p>
						</div>
						</body>
						</html>
						";
			break;			
		
		default:
			echo "<h2>معامله خود را شکست خورده است / لغو</h2>";
			break;
	}


	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <support@10020.ir>' . "\r\n";
	$headers .= 'Bcc: riyas.provab@gmail.com' . "\r\n";
	$headers .= 'Bcc: jfathi@10020.ir' . "\r\n";
	$headers .= 'Bcc: rajneesh.bajpai@provabtechnosoft.com' . "\r\n";

	mail($email,$subject,$message,$headers);
	exit;
}
// echo "<h2>هدایت به صفحه پرداخت .....</h2>";
header("location:index.php");
?>