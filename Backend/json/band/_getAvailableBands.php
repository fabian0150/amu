<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();
	
	$members_cnt = "NULL";
	$available_date = "NULL";

	
	if(isset($_GET['members_cnt'])) {
		
		$members_cnt = $_GET['members_cnt'];
		$members_cnt = mysqli_real_escape_string($db, $members_cnt);
		if($members_cnt == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $search_error;
			$row_array['error'] = $bandmember_count_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $search_error;
		$row_array['error'] = $bandmember_count_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	if(isset($_GET['available_date'])) {
		
		$available_date = $_GET['available_date'];
		$available_date = mysqli_real_escape_string($db, $available_date);
		if($available_date == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $search_error;
			$row_array['error'] = $appointment_date_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $search_error;
		$row_array['error'] = $appointment_date_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}

	$query = "SELECT bm.*, u.username, b.name 
	FROM TBL_BANDMEMBERS bm
	LEFT JOIN TBL_BANDINFO b ON bm.band_id = b.ID
	LEFT JOIN TBL_USERS u ON bm.user_id = u.ID
	WHERE bm.user_id = " . $id . ";";


if ($result = mysqli_query($db, $query)){

	while ($row = mysqli_fetch_assoc($result)) {
		$row_array['code'] =  1;



		array_push($return_arr, $row_array);
	}

	if(mysqli_num_rows($result) == 0) {
	   $row_array['code'] =  6;
		 $row_array['error'] = $band_none_found;
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
	logData("load available Bands(" . $members_cnt . ") on " . $available_date, "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>