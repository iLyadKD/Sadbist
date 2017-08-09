<?php
error_reporting(E_ALL); 
?>
<html>
<head>
	<title>10020.ir Payment</title>
	<link href="Css/Style.css" rel="stylesheet" type="text/css" />

	<script language="javascript" type="text/javascript">    
		function postRefId (refIdValue) {
			var form = document.createElement("form");
			form.setAttribute("method", "POST");
			form.setAttribute("action", "https://bpm.shaparak.ir/pgwchannel/startpay.mellat");         
			form.setAttribute("target", "_self");
			var hiddenField = document.createElement("input");              
			hiddenField.setAttribute("name", "RefId");
			hiddenField.setAttribute("value", refIdValue);
			form.appendChild(hiddenField);

			document.body.appendChild(form);         
			form.submit();
			document.body.removeChild(form);
		}
		
		function initData()
		{
			// document.getElementById("PayDate").value = <?php echo date("Ymd");?>;
			// document.getElementById("PayTime").value = <?php echo date("His");?>;
			// document.getElementById("PayAmount").value = "";
			// document.getElementById("PayOrderId").value = "";
			// document.getElementById("PayAdditionalData").value = "";
			// document.getElementById("PayCallBackUrl").value = "http://10020.ir/payment_beta/response.php";
			// document.getElementById("PayPayerId").value = "0";
		}
	</script>
	<style type="text/css">
		.wdt_in{width: 100%;}
	</style>
</head>

<body>
    <form name="form1" method="post" preservedata="true">
    <table width="100%" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td>
                <table class="InputTable" cellspacing="5" cellpadding="1" align="center">
                    <tr>
                        <td>
                            <table class="MainTable" cellspacing="5" cellpadding="1" align="center">
                                <tr class="HeaderTr">
                                    <td colspan="2" align="center" height="25">
                                        <span class="HeaderText">پرداخت شما</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" name="uname" class="wdt_in" id="uname" required>
                                    </td>
                                    <td class="LabelTd">
                                        <span>نام</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" name="uemail" class="wdt_in" id="uemail"  placeholder="user@email.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="آدرس ایمیل نامعتبر">
                                    </td>
                                    <td class="LabelTd">
                                        <span>آدرس ایمیل</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" name="uphone" class="wdt_in" id="uphone"  placeholder="+911234567890" required>
                                    </td>
                                    <td class="LabelTd">
                                        <span>تلفن</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="number" name="PayAmount" class="wdt_in" id="PayAmount" value="1000" required title="لطفا مقدار را وارد کنید" min="1000">
                                    </td>
                                    <td class="LabelTd">
                                        <span>مبلغ</span>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <textarea name="PayAdditionalData" class="wdt_in" id="PayAdditionalData"></textarea>
                                    </td>
                                    <td class="LabelTd">
                                        <span>ملاحظات</span>
                                    </td>
                                </tr>
                                <tr class="HeaderTr">
                                    <td colspan="2" align="center">
                                        <input type="submit" CssClass="PublicButton" name="PayRequestButton" value="پرداخت"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </form>
</body>

<?php
    require_once("./lib/nusoap.php");
	require_once("Functions.php");
		
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//$page = curl_exec ($ch);

	$client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
    // echo '<pre>',print_r($client->__getFunctions());exit; 
	$namespace= 'http://interfaces.core.sw.bps.com/';

	///////////////// PAY REQUEST

	if (isset($_POST['PayRequestButton']) && $_POST['PayAmount'] != false) 
	{ 
		$terminalId = '2108481';
		$userName = 'ir100';
		$userPassword = '29961660';
        $callBackUrl = 'http://10020.ir/payment/response.php';
        // $callBackUrl = 'http://192.168.0.39/10020ir/bp_pay/response.php';
        $orderId = time();
        $payerId = 0;
        
        $amount = $_POST['PayAmount'];
        $localDate = date("Ymd");
        $localTime = date("His");
        $additionalData = $_POST['PayAdditionalData'];
		// Check for an error
		$err = $client->getError();
		if ($err) {
			// header("Refresh:0");
		}

        $userData = array(
                        'name' => $_POST['uname'],
                        'email' => $_POST['uemail'],
                        'phone' => $_POST['uphone']
                    );
	  
		$parameters = array(
			'terminalId' => $terminalId,
			'userName' => $userName,
			'userPassword' => $userPassword,
			'orderId' => $orderId,
			'amount' => $amount,
			'localDate' => $localDate,
			'localTime' => $localTime,
			'additionalData' => $additionalData,
			'callBackUrl' => $callBackUrl,
			'payerId' => $payerId);

        $func = new Functions();
        $pay_trans_id = $func->addPaymentDetails($parameters,$userData);
  
		// Call the SOAP method
		$result = $client->call('bpPayRequest', $parameters, $namespace);
		// echo '<pre>',print_r($result);exit; 
		// Check for a fault
		if ($client->fault) {
			header("Refresh:0");
			// echo '<h2>Fault</h2><pre>';
			// print_r($result);
			// echo '</pre>';
			// die();
		} 
		else {
			// Check for errors
			
			$resultStr  = $result;

			$err = $client->getError();
			if ($err) {
				// Display the error
				// echo '<h2>Error</h2><pre>' . $err . '</pre>';
				// die();
			} 
			else {
				// Display the result

				$res = explode (',',$resultStr);

				// echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
				// echo "Pay Response is : " . $resultStr;

				$ResCode = $res[0];
				
				if ($ResCode == "0") {
					// Update table, Save RefId
					echo "<script language='javascript' type='text/javascript'>postRefId('" . $res[1] . "');</script>";
				} 
				else {
				// log error in app
					// Update table, log the error
					// Show proper message to user
				}
			}// end Display the result
		}// end Check for errors
	}
	else
	{	
		echo "<script>initData();</script>";
	}
	
		
?>



</html>