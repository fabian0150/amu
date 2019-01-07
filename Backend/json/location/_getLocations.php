<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	$query = "SELECT * FROM TBL_LOCATIONS;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
			$row_array['code'] =  1;
		    $row_array['ID'] = $row['ID'];
		    $row_array['name'] = $row['name'];
		    $row_array['address'] = cleanString($row['address']);
			$row_array['contact_person_id'] = $row['contact_person_id'];
			$row_array['record_date'] = $row['record_date'];
						
		    array_push($return_arr, $row_array);
	   }
	    if(mysqli_num_rows($result) == 0) {
				$row_array['code'] =  6;
			    $row_array['error'] = $locations_no_exist;
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
	logData("load Locations" , "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>