<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	

	$offer_id = "NULL";
	$offer_band_id = "NULL";
	$price = "NULL";
	
	$text_gage = "NULL";
	$text_paytype = "NULL";
	$text_more_hours = "NULL";
	$text_breakfast = "NULL";
	$text_food = "NULL";
	$text_punitive = "NULL";
	$text_fees = "NULL";
	$text_replacement = "NULL";
	$text_other = "NULL";

	if(isset($_POST['text_gage'])) { 
		$text_gage = $_POST['text_gage'];
		$text_gage = mysqli_real_escape_string($db, $text_gage);
		$text_gage = "'$text_gage'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_paytype'])) { 
		$text_paytype = $_POST['text_paytype'];
		$text_paytype = mysqli_real_escape_string($db, $text_paytype);
		$text_paytype = "'$text_paytype'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_more_hours'])) { 
		$text_more_hours = $_POST['text_more_hours'];
		$text_more_hours = mysqli_real_escape_string($db, $text_more_hours);
		$text_more_hours = "'$text_more_hours'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_breakfast'])) { 
		$text_breakfast = $_POST['text_breakfast'];
		$text_breakfast = mysqli_real_escape_string($db, $text_breakfast);
		$text_breakfast = "'$text_breakfast'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_food'])) { 
		$text_food = $_POST['text_food'];
		$text_food = mysqli_real_escape_string($db, $text_food);
		$text_food = "'$text_food'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_punitive'])) { 
		$text_punitive = $_POST['text_punitive'];
		$text_punitive = mysqli_real_escape_string($db, $text_punitive);
		$text_punitive = "'$text_punitive'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_fees'])) { 
		$text_fees = $_POST['text_fees'];
		$text_fees = mysqli_real_escape_string($db, $text_fees);
		$text_fees = "'$text_fees'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_replacement'])) { 
		$text_replacement = $_POST['text_replacement'];
		$text_replacement = mysqli_real_escape_string($db, $text_replacement);
		$text_replacement = "'$text_replacement'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_POST['text_other'])) { 
		$text_other = $_POST['text_food'];
		$text_other = mysqli_real_escape_string($db, $text_other);
		$text_other = "'$text_other'";
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $contract_not_created;
		$row_array['error'] = $contract_text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}



	
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
	$sql = "UPDATE TBL_OFFER SET offer_state = 1, text_gage = " . $text_gage . ",
	text_paytype = " . $text_paytype . ",
	text_more_hours = " . $text_more_hours . ",
	text_breakfast = " . $text_breakfast . ",
	text_food = " . $text_food . ",
	text_punitive = " . $text_punitive . ",
	text_fees = " . $text_fees . ",
	text_replacement = " . $text_replacement . ",
	text_other = " . $text_other . " WHERE ID = " . $offer_id . ";";
	if ($db->query($sql) === TRUE) {

	} else {
		$error = true;
	}
	$sql = "UPDATE TBL_OFFER_BANDS SET price = " . $price . ", offer_band_chosen = 1
			
			WHERE offer_id = " . $offer_id . " AND band_id = " . $offer_band_id . ";";
			echo $sql;
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