<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$return_arr = array();
	
	// variable declaration

	$id = "";
	$name = "";
	$phone_number = "";
	$address = "";
	$mail = "";
	$notes = "";
	$user_type = "";
	$user_description = "";
	$username = "";
	
	
	$id = mysqli_real_escape_string($db, $_POST['id']);
	$name = mysqli_real_escape_string($db, $_POST['name']);
	$phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
	$address = mysqli_real_escape_string($db, $_POST['address']);
	$mail = mysqli_real_escape_string($db, $_POST['mail']);
	$notes = mysqli_real_escape_string($db, $_POST['notes']);
	$user_type = mysqli_real_escape_string($db, $_POST['user_type']);
	$user_description =  mysqli_real_escape_string($db, $_POST['user_description']);
	$username = mysqli_real_escape_string($db, $_POST['username']);


	if (empty($id) || empty($name) || empty($phone_number) || empty($address) || empty($mail) || empty($notes) || empty($user_type) || empty($user_description) || empty($username)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $data_not_given;
		array_push($return_arr, $row_array);
		logData("update User FAIL", "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		echo json_encode($return_arr);
		exit();
	}

	
	
		
	$query = "UPDATE TBL_USERS 
			  (name, phone_number, address, mail, notes, user_type, user_description, username) 
			  VALUES 
			  ('" . $username . "', '" . $phone_number . "', '" . $address . "', '" . $mail . "', '" . $notes . "', " . intval($user_type) . ", '" . $user_description . "', '" . $username . "')
			  WHERE ID = " . intval($id) . ";";
	
	
	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
		$row_array['status'] =  $data_updated;
		array_push($return_arr, $row_array);
		logData("Updated user" . $username , "UPDATE ACTION", basename(__FILE__, '.php') , intval($db->insert_id));
	} else {
		$row_array['code'] =  3;
		$row_array['message'] =   $data_no_updated;
		$row_array['error'] =  $db->error;
		logData("Update User failed " . $username, "UPDATE ACTION", basename(__FILE__, '.php') , 0);
		array_push($return_arr, $row_array);
	}

	$db->close();
	
	echo json_encode($return_arr);
	
?>