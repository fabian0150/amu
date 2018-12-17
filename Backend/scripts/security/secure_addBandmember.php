<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$user_id = "NULL";
	$band_id = "NULL";
	
	if(isset($_GET['user_id'])) {
		
		$user_id = $_GET['user_id'];
		$user_id = mysqli_real_escape_string($db, $user_id);
		if($user_id == ""){
			$row_array['message'] =  "Bandmember not added";
			$row_array['error'] = "User ID not given";
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['message'] =  "Bandmember not added";
		$row_array['error'] = "Band ID not given";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	if(isset($_GET['band_id'])) {
		
		$band_id = $_GET['band_id'];
		$band_id = mysqli_real_escape_string($db, $band_id);
		if($band_id == ""){
			$row_array['message'] =  "Bandmember not added";
			$row_array['error'] = "Band ID not given";
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['message'] =  "Bandmember not added";
		$row_array['error'] = "Band ID not given";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	$query = "SELECT ID FROM TBL_USERS WHERE ID=" . $user_id . " LIMIT 1;";
		
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['message'] =  "Bandmember not added";
		$row_array['error'] = "User ID not found";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	} 
	
	$query = "SELECT ID FROM TBL_BANDINFO WHERE ID=" . $band_id . " LIMIT 1;";
		
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['message'] =  "Bandmember not added";
		$row_array['error'] = "Band ID not found";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	$query = "SELECT ID FROM TBL_BANDMEMBERS WHERE user_id=" . $user_id . " AND band_id = " . $band_id . " LIMIT 1;";
		
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['message'] =  "Bandmember not added";
		$row_array['error'] = "Member already in band";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	} 
	
	
	
	
	$sql = "INSERT INTO TBL_BANDMEMBERS (user_id, band_id) 
			VALUES (" . $user_id . ", " . $band_id . ");";

	if ($db->query($sql) === TRUE) {
	    $row_array['message'] =  "Bandmember added";
	    $row_array['member_id'] =  intval($db->insert_id);
	    logData("inserted Bandmember ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);

	} else {
		$row_array['message'] =  "Bandmember not added";
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>