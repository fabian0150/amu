<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$return_arr = array();
	
	// variable declaration

	$id = "";
	$location_id = ""; 
	$user_id = ""; 
	$offer_state = ""; 
	$offer_date = ""; 
	$invoice_number = ""; 
	$invoice_date = ""; 
	$record_date = ""; 
	$text_gage = ""; 
	$text_paytype = ""; 
	$text_more_hours = ""; 
	$text_breakfast = ""; 
	$text_food = ""; 
	$text_punitive = ""; 
	$text_fees = ""; 
	$text_replacement = ""; 
	$text_other = "";
	
	
	$id = mysqli_real_escape_string($db, $_POST['id']);
	$location_id = mysqli_real_escape_string($db, $_POST['location_id']);
	$user_id = mysqli_real_escape_string($db, $_POST['user_id']);
	$offer_state = mysqli_real_escape_string($db, $_POST['offer_state']);
	$offer_date = mysqli_real_escape_string($db, $_POST['offer_date']);
	$invoice_number = mysqli_real_escape_string($db, $_POST['invoice_number']);
	$invoice_date = mysqli_real_escape_string($db, $_POST['invoice_date']);
	$record_date = mysqli_real_escape_string($db, $_POST['record_date']);
	$text_gage = mysqli_real_escape_string($db, $_POST['text_gage']);
	$text_paytype = mysqli_real_escape_string($db, $_POST['text_paytype']);
	$text_more_hours = mysqli_real_escape_string($db, $_POST['text_more_hours']);
	$text_breakfast = mysqli_real_escape_string($db, $_POST['text_breakfast']);
	$text_food = mysqli_real_escape_string($db, $_POST['text_food']);
	$text_punitive = mysqli_real_escape_string($db, $_POST['text_punitive']);
	$text_fees = mysqli_real_escape_string($db, $_POST['text_fees']);
	$text_replacement = mysqli_real_escape_string($db, $_POST['text_replacement']);
	$text_other = mysqli_real_escape_string($db, $_POST['text_other']);

	$text_head = mysqli_real_escape_string($db, $_POST['text_head']);
	$text_foot = mysqli_real_escape_string($db, $_POST['text_foot']);

	

	if (empty($id) || 
		empty($location_id) || 
		empty($user_id)) { 

		$row_array['code'] =  2;
		$row_array['error'] =  $data_not_given;
		array_push($return_arr, $row_array);
		logData("update Offer FAIL", "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		echo json_encode($return_arr);
		exit();
		}

	
	$query = "SET FOREIGN_KEY_CHECKS=0;";
	$db->query($query);
		
	$query = "UPDATE TBL_OFFER SET
			  	location_id= '" . intval($location_id) . "', 
				user_id= '" . intval($user_id) . "', 
				offer_state= " . intval($offer_state) . ", 
				offer_date= '" . $offer_date . "', 
				invoice_number= '" . $invoice_number . "', 
				invoice_date= '" . $invoice_date . "', 
				record_date= '" . $record_date . "', 
				text_gage= '" . $text_gage . "', 
				text_paytype=  '" . $text_paytype . "', 
				text_more_hours= '" . $text_more_hours . "', 
				text_breakfast= '" . $text_breakfast . "', 
				text_food= '" . $text_food . "', 
				text_punitive= '" . $text_punitive . "', 
				text_fees=  '" . $text_fees . "', 
				text_replacement= '" . $text_replacement . "',
				text_other= '" . $text_other . "', 
				text_head= '" . $text_head . "', 
				text_foot= '" . $text_foot . "'
			  WHERE ID = " . intval($id) . ";";

	
	if ($db->query($query) === TRUE) {
		$row_array['code'] =  1;
		$row_array['status'] =  $data_updated;
		array_push($return_arr, $row_array);
		logData("Updated offer" . $id , "UPDATE ACTION", basename(__FILE__, '.php') , intval($db->insert_id));
	} else {
		$row_array['code'] =  3;
		$row_array['message'] =   $data_no_updated;
		$row_array['error'] =  $db->error;
		logData("Update offer failed " . $id, "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);
	}
	$query = "SET FOREIGN_KEY_CHECKS=1;";
	$db->query($query);
	$db->close();
	
	echo json_encode($return_arr);
	
?>