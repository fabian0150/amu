<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	$query = "SELECT * FROM TBL_USERS WHERE user_type != 3;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
			$row_array['code'] =  1;
		    $row_array['ID'] = $row['ID'];
		    $row_array['name'] = $row['name'];
		    $row_array['phone_number'] = $row['phone_number'];
		    $row_array['address'] = cleanString($row['address']);
			$row_array['mail'] = $row['mail'];
			$row_array['notes'] = cleanString($row['notes']);
			$row_array['user_type'] = $row['user_type'];
			$row_array['user_description'] = $row['user_description'];
			$row_array['username'] = $row['username'];
			$row_array['record_date'] = $row['record_date'];
						
		    array_push($return_arr, $row_array);
	   }
	   if(mysqli_num_rows($result) == 0) {
		   $row_array['code'] =  6;
			    $row_array['error'] = $users_no_exist;
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
	logData("load Users" , "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>