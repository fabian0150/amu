<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$return_arr = array();
	
	// variable declaration

	$id = "";
	$appointment_date = "";
	$band_id = "";
	$location_id = "";
	
	$id = mysqli_real_escape_string($db, $_POST['id']);
	$appointment_date = mysqli_real_escape_string($db, $_POST['appointment_date']);
	$band_id = mysqli_real_escape_string($db, $_POST['band_id']);
	$location_id = mysqli_real_escape_string($db, $_POST['location_id']);
	

	if (empty($id) || empty($appointment_date) || empty($band_id) || empty($location_id)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $data_not_given;
		array_push($return_arr, $row_array);
		logData("update location FAIL", "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		echo json_encode($return_arr);
		exit();
	}

	
	
		
	$query = "UPDATE TBL_APPOINTMENTS 
			  (band_id, location_id, appointment_date) 
			  VALUES 
			  (" . intval($band_id) . ", " . intval($location_id) . ", '" . $appointment_date . "',)
			  WHERE ID = " . intval($id) . ";";
	
	
	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
		$row_array['status'] =  $data_updated;
		array_push($return_arr, $row_array);
		logData("Updated appointment " . $id , "UPDATE ACTION", basename(__FILE__, '.php') , intval($db->insert_id));
	} else {
		$row_array['code'] =  3;
		$row_array['message'] =   $data_no_updated;
		$row_array['error'] =  $db->error;
		logData("Update appointment failed " . $id, "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);
	}

	$db->close();
	
	echo json_encode($return_arr);
	
?>