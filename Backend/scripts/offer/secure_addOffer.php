<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	

	$offer_date = "NULL";
	$location_id = "NULL";
	$user_id = "NULL";

	$text_head = "NULL";
	$text_foot = "NULL";
	

	
	if(isset($_POST['offer_date'])) {
		
		$offer_date = $_POST['offer_date'];
		$offer_date = mysqli_real_escape_string($db, $offer_date);
		$offer_date = "'$offer_date'";
		if($offer_date == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $offer_not_created;
			$row_array['error'] = $appointment_date_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $offer_not_created;
		$row_array['error'] = $appointment_date_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	if(isset($_POST['text_head'])) {
		
		$text_head = $_POST['text_head'];
		$text_head = mysqli_real_escape_string($db, $text_head);
		$text_head = "'$text_head'";
		if($text_head == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $offer_not_created;
			$row_array['error'] = $text_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $offer_not_created;
		$row_array['error'] = $text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	if(isset($_POST['text_foot'])) {
		
		$text_foot = $_POST['text_foot'];
		$text_foot = mysqli_real_escape_string($db, $text_foot);
		$text_foot = "'$text_foot'";
		if($text_foot == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $offer_not_created;
			$row_array['error'] = $text_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $offer_not_created;
		$row_array['error'] = $text_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	if(isset($_POST['location_id'])) {
		$location_id = $_POST['location_id'];
		$location_id = mysqli_real_escape_string($db, $location_id);
		
		$query = "SELECT ID FROM TBL_LOCATIONS WHERE ID=" . $location_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $offer_not_created;
			$row_array['error'] = $location_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	}

	if(isset($_POST['user_id'])) {
		$user_id = $_POST['user_id'];
		$user_id = mysqli_real_escape_string($db, $user_id);
		
		$query = "SELECT ID FROM TBL_USERS WHERE ID=" . $user_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $offer_not_created;
			$row_array['error'] = $user_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	}
	
	$sql = "INSERT INTO TBL_OFFER (location_id, user_id, offer_date, text_head, text_foot) 
			VALUES (" . $location_id . ", " . $user_id . ", " . $offer_date . ", " . $text_head . ", " . $text_foot . ")";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $offer_created;
	    $row_array['offer_id'] =  intval($db->insert_id);
		logData("inserted offer ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , 0);

	   
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $offer_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>