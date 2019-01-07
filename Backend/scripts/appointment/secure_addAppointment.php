<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$band_id = "NULL";
	$location_id = "NULL";
	$appointment_date = "NULL";
	
	
	if(isset($_POST['band_id'])) {
		
		$band_id = $_POST['band_id'];
		$band_id = mysqli_real_escape_string($db, $band_id);
		$band_id = "$band_id";
		if($band_id == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $appointment_not_created;
			$row_array['error'] = "Band nicht angegben";
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		}
		
		$query = "SELECT ID FROM TBL_BANDINFO WHERE ID=" . $band_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $appointment_not_created;
			$row_array['error'] = $band_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $appointment_not_created;
		$row_array['error'] = $band_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	if(isset($_POST['location_id'])) {
		$location_id = $_POST['location_id'];
		$location_id = mysqli_real_escape_string($db, $location_id);
		$location_id = "$location_id";
		
		$query = "SELECT ID FROM TBL_LOCATIONS WHERE ID=" . $location_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $appointment_not_created;
			$row_array['error'] = $location_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	}
	if(isset($_POST['appointment_date'])) {
		$appointment_date = $_POST['appointment_date'];
		$appointment_date = mysqli_real_escape_string($db, $appointment_date);
		$appointment_date = "'$appointment_date'";		
	}
	
	
		
	$sql = "INSERT INTO TBL_APPOINTMENTS (band_id, location_id, appointment_date) 
			VALUES (" . $band_id . ", " . $location_id . ", " . $appointment_date . ")";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $appointment_created;
	    $row_array['appointment_id'] =  intval($db->insert_id);
		
		$log_user_id = 0;
		if(isset($_SESSION['session_user'])) {
			$log_user_id = $_SESSION['session_user'];
		}
	    logData("inserted Appointment ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , $log_user_id);
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $appointment_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
?>