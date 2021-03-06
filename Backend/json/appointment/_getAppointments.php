<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	$query = "SELECT a.*, b.name as 'band_name', l.name, l.address
					FROM TBL_APPOINTMENTS a 
					LEFT JOIN TBL_BANDINFO b ON a.band_id = b.ID
				    LEFT JOIN TBL_LOCATIONS l ON a.location_id = l.ID";
					
	if(isset($_GET['band_id'])) {
		$id = $_GET['band_id'];
		$id = mysqli_real_escape_string($db, $id);
		$query .= " WHERE a.band_id = " . $id . ";";
		
	}
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
			$row_array['code'] =  1;
		    $row_array['ID'] = $row['ID'];
		    $row_array['band_id'] = $row['band_id'];
		    $row_array['band_name'] = $row['band_name'];
		    $row_array['location_id'] = $row['location_id'];
		    $row_array['location_address'] = $row['address'];
		    $row_array['location_name'] = $row['name'];
		    $row_array['appointment_date'] = $row['appointment_date'];
			$row_array['record_date'] = $row['record_date'];
			
						
		    array_push($return_arr, $row_array);
	   }
	    if(mysqli_num_rows($result) == 0) {
				$row_array['code'] =  6;
			    $row_array['error'] = $appointments_no_exist;
			    array_push($return_arr, $row_array);
		   }
	} else {
		$row_array['code'] =  3;
		 $row_array['error'] = $server_error;
		  array_push($return_arr, $row_array);
	}
	$log_user_id = 0;
	if(isset($_SESSION['session_user'])) {
		$log_user_id = $_SESSION['session_user'];
	}
	logData("load Appointments" , "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>