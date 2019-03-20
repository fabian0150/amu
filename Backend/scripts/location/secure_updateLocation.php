<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$return_arr = array();
	
	// variable declaration

	$id = "";
	$name = "";
	$address = "";
	$contact_person_id = "";
	
	$id = mysqli_real_escape_string($db, $_POST['id']);
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$address = mysqli_real_escape_string($db, $_POST['address']);
	$contact_person_id = mysqli_real_escape_string($db, $_POST['contact_person_id']);
	

	if (empty($id) || empty($name) || empty($address) || empty($contact_person_id)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $data_not_given;
		array_push($return_arr, $row_array);
		logData("update location FAIL", "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		echo json_encode($return_arr);
		exit();
	}
		
	$query = "UPDATE TBL_LOCATIONS SET
			  name = '" . $name . "', 
			  address = '" . $address . "', 
			  contact_person_id = " . intval($contact_person_id) . " 
			  WHERE ID = " . intval($id) . ";";
	
	
	if ($db->query($query) === TRUE) {
		$row_array['code'] =  1;
		$row_array['status'] =  $data_updated;
		array_push($return_arr, $row_array);
		logData("Updated location " . $id , "UPDATE ACTION", basename(__FILE__, '.php') , intval($db->insert_id));
	} else {
		$row_array['code'] =  3;
		$row_array['message'] =   $data_no_updated;
		$row_array['error'] =  $db->error;
		logData("Update location failed " . $id, "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);
	}

	$db->close();
	
	echo json_encode($return_arr);
	
?>