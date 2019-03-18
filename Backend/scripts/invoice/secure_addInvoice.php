<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	

	$contract_id = "NULL";
	$invoice_date = "NULL";
	$invoice_number = "NULL";
	

	
	if(isset($_POST['invoice_number'])) {
		
		$invoice_number = $_POST['invoice_number'];
		$invoice_number = mysqli_real_escape_string($db, $invoice_number);
		$invoice_number = "'$invoice_number'";
		if($invoice_number == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $invoice_not_created;
			$row_array['error'] = $invoice_number_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $invoice_not_created;
		$row_array['error'] = $invoice_number_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['invoice_date'])) {
		
		$invoice_date = $_POST['invoice_date'];
		$invoice_date = mysqli_real_escape_string($db, $invoice_date);
		$invoice_date = "'$invoice_date'";
		if($invoice_date == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $invoice_not_created;
			$row_array['error'] = $invoice_date_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $invoice_not_created;
		$row_array['error'] = $invoice_date_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	if(isset($_POST['contract_id'])) {
		$contract_id = $_POST['contract_id'];
		$contract_id = mysqli_real_escape_string($db, $contract_id);
		
		$query = "SELECT ID FROM TBL_OFFER WHERE ID=" . $contract_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $invoice_not_created;
			$row_array['error'] = $contract_not_found;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $invoice_not_created;
		$row_array['error'] = $offer_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	
	
	
	$sql = "UPDATE TBL_OFFER SET invoice_number = " . $invoice_number . ", invoice_date = " . $invoice_date . " WHERE ID = " . $contract_id . ";";
	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $invoice_created;
	    $row_array['invoice_id'] =  intval($contract_id);
		logData("updated contract to invoice ID: " . $contract_id, "UPDATED", basename(__FILE__, '.php') , 0);

	   
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $invoice_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}

	
	$db->close();
	
	echo json_encode($return_arr);
	
?>