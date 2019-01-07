<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../scripts/config.php');
	$return_arr = array();

	$query = "SELECT l.*, u.username FROM TBL_AMU_LOG l LEFT JOIN TBL_USERS u ON l.user_id = u.ID;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
		    $row_array['ID'] = $row['ID'];
		    $row_array['user_id'] = $row['user_id'];
		    $row_array['user_name'] = $row['username'];
		    $row_array['log_type'] = cleanString($row['log_type']);
			$row_array['log_file'] = cleanString($row['log_file']);
			$row_array['log_message'] = cleanString($row['log_message']);
			$row_array['record_date'] = $row['record_date'];
						
		    array_push($return_arr, $row_array);
	   }
	   if(mysqli_num_rows($result) == 0) {
			    $row_array['error'] = $log_no_exist;
			    array_push($return_arr, $row_array);
		   }
	} else {
		 $row_array['error'] = $server_error;
		  array_push($return_arr, $row_array);
	}
	$log_user_id = 0;
	if(isset($_SESSION['session_user'])) {
		$log_user_id = $_SESSION['session_user'];
	}
	logData("load Log" , "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>