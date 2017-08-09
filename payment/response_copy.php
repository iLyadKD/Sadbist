<?php

require_once("./lib/nusoap.php");
echo '<pre>',print_r($_REQUEST);
$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "ResponseCode: ".$_REQUEST['ResCode'].PHP_EOL.
            "Data: ".json_encode($_REQUEST).PHP_EOL.
            "-------------------------".PHP_EOL;

file_put_contents('paylogs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

		
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//$page = curl_exec ($ch);

$client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
// echo '<pre>',print_r($client->__getFunctions());exit; 
$namespace= 'http://interfaces.core.sw.bps.com/';

if ($_REQUEST['ResCode'] == 0){ 

	$terminalId = '2108481';
    $userName = 'ir100';
    $userPassword = '29961660';
	$orderId = $_REQUEST['SaleOrderId'];
	$verifySaleOrderId = $_REQUEST['SaleOrderId'];
	$verifySaleReferenceId = $_REQUEST['SaleReferenceId'];

	// Check for an error
	$err = $client->getError();
	if ($err) {
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		die();
	}
  	  
	$parameters = array(
		'terminalId' => $terminalId,
		'userName' => $userName,
		'userPassword' => $userPassword,
		'orderId' => $orderId,
		'saleOrderId' => $verifySaleOrderId,
		'saleReferenceId' => $verifySaleReferenceId);

	// Call the SOAP method
	$result = $client->call('bpVerifyRequest', $parameters, $namespace);

	$log_verify  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "Data: ".json_encode($result).PHP_EOL.
            "-------------------------".PHP_EOL;

	file_put_contents('paylogs/verifylog_'.date("j.n.Y").'.txt', $log_verify, FILE_APPEND);

	// Check for a fault
	if ($client->fault) {
		echo '<h2>Fault</h2><pre>';
		print_r($result);
		echo '</pre>';
		die();
	} 
	else {

		$resultStr = $result;
		
		$err = $client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Error</h2><pre>' . $err . '</pre>';
			die();
		} 
		else {
			// Display the result
			// Update Table, Save Verify Status 
			// Note: Successful Verify means complete successful sale was done.
			// echo "<script>alert('Verify Response is : " . $resultStr . "');</script>";
			echo "Verify Response is : " . $resultStr;

		$orderId = $orderId;
		$settleSaleOrderId = $orderId;
		$settleSaleReferenceId = $verifySaleReferenceId;

		// Check for an error
		$err = $client->getError();
		if ($err) {
			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
			die();
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
            "-------------------------".PHP_EOL;

	file_put_contents('paylogs/settlelog_'.date("j.n.Y").'.txt', $log_settle, FILE_APPEND);

		// Check for a fault
		if ($client->fault) {
			echo '<h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre>';
			die();
		} 
		else {
			$resultStr = $result;
			
			$err = $client->getError();
			if ($err) {
				// Display the error
				echo '<h2>Error</h2><pre>' . $err . '</pre>';
				die();
			} 
			else {
				// Update Table, Save Settle Status 
				// Note: Successful Settle means that sale is settled.
				echo "<script>alert('Settle Response is : " . $resultStr . "');</script>";
				echo "Settle Response is : " . $resultStr;
			}// end Display the result
		}// end Check for errors

		}// end Display the result
	}// end Check for errors
}


?>