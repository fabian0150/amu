<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	

	$offer_id = "NULL";
	$offer_band_id = "NULL";
	$price = "NULL";
	

	
	if(isset($_POST['price'])) {
		
		$price = $_POST['price'];
		$price = mysqli_real_escape_string($db, $price);
		$price = "'$price'";
		if($price == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $contract_not_created;
			$row_array['error'] = $contract_price_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_price_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	if(isset($_POST['offer_id'])) {
		$offer_id = $_POST['offer_id'];
		$offer_id = mysqli_real_escape_string($db, $offer_id);
		
		$query = "SELECT ID FROM TBL_OFFER WHERE ID=" . $offer_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $contract_not_created;
			$row_array['error'] = $offer_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $offer_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['offer_band_id'])) {
		$offer_band_id = $_POST['offer_band_id'];
		$offer_band_id = mysqli_real_escape_string($db, $offer_band_id);
		
		$query = "SELECT ID FROM TBL_OFFER_BANDS WHERE offer_id=" . $offer_id . " AND band_id=" . $offer_band_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $contract_not_created;
			$row_array['error'] = $contract_band_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_band_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	$error = false;
	$sql = "UPDATE TBL_OFFER SET offer_state = 1 WHERE ID = " . $offer_id . ";";
	if ($db->query($sql) === TRUE) {

	} else {
		$error = true;
	}
	$sql = "UPDATE TBL_OFFER_BANDS SET price = " . $price . ", offer_band_chosen = 1 WHERE offer_id = " . $offer_id . " AND band_id = " . $offer_band_id . ";";
	if ($db->query($sql) === TRUE) {

	} else {
		$error = true;
	}



	if ($error == $false) {

		$row_array['code'] =  1;
	    $row_array['message'] =  $contract_created;
	    $row_array['contract_id'] =  intval($offer_id);
		logData("updated offer to contract ID: " . $offer_id, "UPDATED", basename(__FILE__, '.php') , 0);

	   
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $contract_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>