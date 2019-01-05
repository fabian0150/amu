<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$name = "NULL";
	$address = "NULL";
	$contact_person_id = "NULL";
	
	if(isset($_GET['name'])) {	
		$name = $_GET['name'];
		$name = mysqli_real_escape_string($db, $name);
		$name = "'$name'";
	}
	
	if(isset($_GET['address'])) {
		$address = $_GET['address'];
		$address = mysqli_real_escape_string($db, $address);
		$address = "'$address'";
	}
	if(isset($_GET['contact_person_id'])) {
		$contact_person_id = $_GET['contact_person_id'];
		$contact_person_id = mysqli_real_escape_string($db, $contact_person_id);
		$contact_person_id = "'$contact_person_id'";		
	}
	
	
	$sql = "INSERT INTO TBL_LOCATIONS (name, address, contact_person_id) 
			VALUES (" . $name . ", " . $address . ", " . $contact_person_id . ")";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $location_created;
	    $row_array['location_id'] =  $db->insert_id;
	     logData("inserted Location ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $location_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);	
?>