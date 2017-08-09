<?php
// error_reporting(0);
require_once("./lib/nusoap.php");
require_once("Functions.php");
// $_REQUEST = json_decode('{"RefId":"1739D719904C0581","ResCode":"0","SaleOrderId":"1486022914","SaleReferenceId":"121915830193","CardHolderInfo":"88FE9052519486AE2912B4D1F5AF46968F1AC3AC7D5E622AC359E2DA152FE7FB","CardHolderPan":"610433******5800"}',true);
// echo '<pre>',print_r($_REQUEST);exit; 
$func = new Functions();
// echo '<pre>',print_r($_REQUEST);
$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "ResponseCode: ".$_REQUEST['ResCode'].PHP_EOL.
            "Data: ".json_encode($_REQUEST).PHP_EOL.
            "-------------------------".PHP_EOL;

file_put_contents('paylogs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

$client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl'); 
$namespace= 'http://interfaces.core.sw.bps.com/';
$status = $_REQUEST['ResCode'] == 0 ? 'PENDING' : 'FAILED';
$data = array(
				'status' => $status,
				'res_code' => $_REQUEST['ResCode'],
				'SaleOrderId' => $_REQUEST['SaleOrderId'],
				'SaleReferenceId' => $_REQUEST['SaleReferenceId'],
				'data_res' => json_encode($_REQUEST),
			);


$terminalId = '2108481';
$userName = 'ir100';
$userPassword = '29961660';
$orderId = $_REQUEST['SaleOrderId'];
$verifySaleOrderId = $_REQUEST['SaleOrderId'];
$verifySaleReferenceId = $_REQUEST['SaleReferenceId'];

$key = base64_encode($_REQUEST['SaleOrderId']);
// Check for an error
// $err = $client->getError();
// if ($err) {
// 	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
// 	die();
// }
  	  
$parameters = array(
	'terminalId'      => $terminalId,
	'userName' 	      => $userName,
	'userPassword'    => $userPassword,
	'orderId'         => $orderId,
	'saleOrderId'     => $verifySaleOrderId,
	'saleReferenceId' => $verifySaleReferenceId);

//save data
$saveAction = $func->updatePayRequestAction($data);


if ($_REQUEST['ResCode'] == 0){ 

	
	// Call the SOAP method
	$result = $client->call('bpVerifyRequest', $parameters, $namespace);

	$log_verify  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "Data: ".json_encode($result).PHP_EOL.
            "DataPGRes: ".json_encode($_REQUEST).PHP_EOL.
            "-------------------------".PHP_EOL;

	file_put_contents('paylogs/verifylog_'.date("j.n.Y").'.txt', $log_verify, FILE_APPEND);

	// Check for a fault
	if ($client->fault) {
		// header("location:feedback.php?success='.$key.'");
	} 
	else {

		$resultStr = $result;
		
		$err = $client->getError();
		if ($err) {
			// header("location:feedback.php?success='.$key.'");
		} 
		else {

			$status = $resultStr == 0 ? 'VERIFIED' : $status;
			$data = array(
							'status' => $status,
							'res_code' => $resultStr,
							'SaleOrderId' => $_REQUEST['SaleOrderId'],
							'SaleReferenceId' => $_REQUEST['SaleReferenceId']
						);

			// Update Table, Save Verify Status 
			$verifyAction = $func->verifyUpdateAction($data);

			// Note: Successful Verify means complete successful sale was done.
			// echo "<script>alert('Verify Response is : " . $resultStr . "');</script>";
			// echo "Verify Response is : " . $resultStr;

			$orderId = $orderId;
			$settleSaleOrderId = $orderId;
			$settleSaleReferenceId = $verifySaleReferenceId;

			// Check for an error
			$err = $client->getError();
			if ($err) {
				// header("location:feedback.php?success='.$key.'");
			}
		  	  
			$parameters = array(
				'terminalId' => $terminalId,
				'userName' => $userName,
				'userPassword' => $userPassword,
				'orderId' => $orderId,
				'saleOrderId' => $settleSaleOrderId,
				'saleReferenceId' => $settleSaleReferenceId);

			// Call the SOAP method
			$result = $client->call('bpSettleRequest', $parameters, $namespace);

			$log_settle  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
	            "Data: ".json_encode($result).PHP_EOL.
	            "DataPGRes: ".json_encode($_REQUEST).PHP_EOL.
	            "-------------------------".PHP_EOL;

			file_put_contents('paylogs/settlelog_'.date("j.n.Y").'.txt', $log_settle, FILE_APPEND);

			// Check for a fault
			if ($client->fault) {
				// header("location:feedback.php?success='.$key.'");
			} 
			else {
				$resultStr1 = $result;
				
				$err = $client->getError();
				if ($err) {

					// header("location:feedback.php?success='.$key.'");
				} 
				else {

					$status = $resultStr1 == 0 ? 'SETTLED' : $status;
					$data = array(
									'status' => $status,
									'res_code' => $resultStr,
									'SaleOrderId' => $_REQUEST['SaleOrderId'],
									'SaleReferenceId' => $_REQUEST['SaleReferenceId']
								);

					// Update Table, Save Settle Status 
					$settleAction = $func->settleUpdateAction($data);
					// Note: Successful Settle means that sale is settled.
					// echo "<script>alert('Settle Response is : " . $resultStr . "');</script>";
					// echo "Settle Response is : " . $resultStr;
				}// end Display the result
			}// end Check for errors

			}// end Display the result
	}// end Check for errors
}
else{

}

header("location:feedback.php?success=".$key);
?>