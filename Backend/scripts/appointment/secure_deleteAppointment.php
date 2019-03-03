<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$appointment_id = "NULL";

	if(isset($_POST['appointment_id'])) {
		
		$appointment_id = $_POST['appointment_id'];
		$appointment_id = mysqli_real_escape_string($db, $appointment_id);
		if($appointment_id == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $appointment_not_deleted;
			$row_array['error'] = $appointment_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $appointment_not_deleted;
		$row_array['error'] = $appointment_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	$query = "SELECT ID FROM TBL_APPOINTMENTS WHERE ID=" . $appointment_id . " LIMIT 1;";
		
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['code'] =  2;
		$row_array['message'] =  $appointment_not_deleted;
		$row_array['error'] = $appointment_no_exist;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	} 
	
	$sql = "DELETE FROM TBL_APPOINTMENTS WHERE ID=" . $appointment_id . " LIMIT 1;";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $appointment_deleted;
	    $row_array['appointment_id'] =  intval($appointment_id);
		$log_user_id = 0;
		if(isset($_SESSION['session_user'])) {
			$log_user_id = $_SESSION['session_user'];
		}
	    logData("deleted Appointment ID: " . $appointment_id, "DELETED", basename(__FILE__, '.php') , $log_user_id);
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $appointment_not_deleted;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>