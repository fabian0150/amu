<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$return_arr = array();
	
	// variable declaration

	$id = "NULL";
	$name = "NULL";
	$logo_path = "NULL";
	$website_url = "NULL";
	$notes = "NULL";
	$leader_id = "NULL";
	
	$id = mysqli_real_escape_string($db, $_POST['id']);
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$logo_path = mysqli_real_escape_string($db, $_POST['logo_path']);
	$website_url = mysqli_real_escape_string($db, $_POST['website_url']);
	$notes = mysqli_real_escape_string($db, $_POST['notes']);
	$leader_id = mysqli_real_escape_string($db, $_POST['leader_id']);
	

	if (empty($id) || empty($name) || empty($logo_path)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $data_not_given;
		array_push($return_arr, $row_array);
		logData("update band FAIL", "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		echo json_encode($return_arr);
		exit();
	}

	
	
		
	$query = "UPDATE TBL_BANDINFO 
			  (name, logo_path, website_url, notes, leader_id) 
			  VALUES 
			  ('" . $name . "', '" . $logo_path . "',, '" . $website_url . "', '" . $notes . "', " . intval($leader_id) . ")
			  WHERE ID = " . intval($id) . ";";
	
	
	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
		$row_array['status'] =  $data_updated;
		array_push($return_arr, $row_array);
		logData("Updated band " . $id , "UPDATE ACTION", basename(__FILE__, '.php') , intval($db->insert_id));
	} else {
		$row_array['code'] =  3;
		$row_array['message'] =   $data_no_updated;
		$row_array['error'] =  $db->error;
		logData("Update band failed " . $id, "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);
	}

	$db->close();
	
	echo json_encode($return_arr);
	
?>