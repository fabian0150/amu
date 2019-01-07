<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$name = "NULL";
	$logo_path = "NULL";
	$website_url = "NULL";
	$notes = "NULL";
	$leader_id = "NULL";
	

	
	if(isset($_POST['name'])) {
		
		$name = $_POST['name'];
		$name = mysqli_real_escape_string($db, $name);
		$name = "'$name'";
		if($name == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $band_not_created;
			$row_array['error'] = $band_name_not_given;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $band_not_created;
		$row_array['error'] = $band_name_not_given;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	if(isset($_POST['logo_path'])) {
		$logo_path = $_POST['logo_path'];
		$logo_path = mysqli_real_escape_string($db, $logo_path);
		$logo_path = "'$logo_path'";
	}
	
	if(isset($_POST['website_url'])) {
		$website_url = $_POST['website_url'];
		$website_url = mysqli_real_escape_string($db, $website_url);
		$website_url = "'$website_url'";
	}
	
	if(isset($_POST['notes'])) {
		$notes = $_POST['notes'];
		$notes = mysqli_real_escape_string($db, $notes);
		$notes = "'$notes'";
	}
	
	if(isset($_POST['leader_id'])) {
		$leader_id = $_POST['leader_id'];
		$leader_id = mysqli_real_escape_string($db, $leader_id);
		
		$query = "SELECT ID FROM TBL_USERS WHERE ID=" . $leader_id . " LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) { } else {
			$row_array['code'] =  2;
			$row_array['message'] =  $band_not_created;
			$row_array['error'] = $band_leader_no_exist;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 

	}
	
	$sql = "INSERT INTO TBL_BANDINFO (name, logo_path, website_url, notes, leader_id) 
			VALUES (" . $name . ", " . $logo_path . ", " . $website_url . ", " . $notes . ", " . $leader_id . ")";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $band_created;
	    $row_array['band_id'] =  intval($db->insert_id);
		logData("inserted Band ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , 0);
		 
		if(isset($_POST['leader_id'])) {
			$sql = "INSERT INTO TBL_BANDMEMBERS (user_id, band_id) 
			VALUES (" . $leader_id . ", " . $row_array['band_id'] . ");";

			if ($db->query($sql) === TRUE) {
				$row_array['code'] =  1;
				$row_array['message'] =  $bandmember_created;
				$row_array['member_id'] =  intval($db->insert_id);
				$log_user_id = 0;
				if(isset($_SESSION['session_user'])) {
					$log_user_id = $_SESSION['session_user'];
				}
				logData("inserted Bandmember ID: " . $db->insert_id, "ADDED", basename(__FILE__, '.php') , $log_user_id);
				array_push($return_arr, $row_array);
			}
		}
		
		
	   
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $band_not_created;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>