<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	
	
	$return_arr = array();
	$name = "NULL";
	$logo_path = "NULL";
	$website_url = "NULL";
	$leader_id = "NULL";
	

	
	if(isset($_GET['name'])) {
		
		$name = $_GET['name'];
		$name = mysqli_real_escape_string($db, $name);
		$name = "'$name'";
		if($name == ""){
			$row_array['message'] =  "Band not created";
			$row_array['error'] = "Name not given";
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['message'] =  "Band not created";
		$row_array['error'] = "Name not given";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	if(isset($_GET['logo_path'])) {
		$logo_path = $_GET['logo_path'];
		$logo_path = mysqli_real_escape_string($db, $logo_path);
		$logo_path = "'$logo_path'";
	}
	
	if(isset($_GET['website_url'])) {
		$website_url = $_GET['website_url'];
		$website_url = mysqli_real_escape_string($db, $website_url);
		$website_url = "'$website_url'";
	}
	
	if(isset($_GET['notes'])) {
		$notes = $_GET['notes'];
		$notes = mysqli_real_escape_string($db, $notes);
		$notes = "'$notes'";
	}
	
	if(isset($_GET['leader_id'])) {
		$leader_id = $_GET['leader_id'];
		$leader_id = mysqli_real_escape_string($db, $leader_id);
	}
	
	$sql = "INSERT INTO TBL_BANDINFO (name, logo_path, website_url, notes, leader_id) 
			VALUES (" . $name . ", " . $logo_path . ", " . $website_url . ", " . $notes . ", " . $leader_id . ")";

	if ($db->query($sql) === TRUE) {
	    $row_array['message'] =  "Band created";
	    $row_array['band_id'] =  $db->insert_id;
	    logData("inserted Band ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);

	} else {
		$row_array['message'] =  "Band not created";
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>