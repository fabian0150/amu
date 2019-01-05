<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	
	
	$return_arr = array();
	
	$sql = "UPDATE TBL_USERS SET session_key=NULL, session_date=NULL WHERE ID=" . $_SESSION['session_user'] . ";";

	if ($db->query($sql) === TRUE) {
		
		$_SESSION['session_key'] = null;
		$_SESSION['session_loggedin'] = false;
		$_SESSION['session_user'] = null;
		session_destroy();
		$row_array['code'] =  1;
		$row_array['message'] =  $user_logout;
		array_push($return_arr, $row_array);
	} else {
		$row_array['code'] =  3;
		$row_array['error'] =  $user_no_logout;
		array_push($return_arr, $row_array);
	}
		
	
	echo json_encode($return_arr);
?>