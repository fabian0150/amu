<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	
	$return_arr = array();
	
	// variable declaration
	$username = "";
	$email    = "";
	$password_1 = "";
	$password_2 = "";
	
	
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $user_name_not_given;
		array_push($return_arr, $row_array);
		logData("register User FAIL", "USER ACTION", basename(__FILE__, '.php') , 0);
	}
	
	if (empty($email)) { 
		$row_array['code'] =  2;
		$row_array['error'] =  $user_mail_not_given;
		array_push($return_arr, $row_array);
		logData("register User FAIL " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);
	}
	if (empty($password_1) || empty($password_2)) {
		$row_array['code'] =  2;
		$row_array['error'] =  $user_pwd_not_given;
		array_push($return_arr, $row_array);
		logData("register User FAIL " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);
	}

	if ($password_1 != $password_2) {
		$row_array['code'] =  2;
		$row_array['error'] =  $user_pwd_different;
		array_push($return_arr, $row_array);
		
		logData("register User FAIL " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);
	}

	// register user if there are no errors in the form
	if (count($row_array) == 0) {
		
		$query = "SELECT ID FROM TBL_USERS WHERE username='" . $username . "' LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) {
			$row_array['code'] =  5;
			$row_array['message'] =  $user_name_used;
			$row_array['error'] =  $user_no_registered;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
			
		}
		
		$query = "SELECT ID FROM TBL_USERS WHERE mail='" . $email . "' LIMIT 1;";
		
		
		$result = $db->query($query);
	
		if ($result->num_rows > 0) {
			$row_array['code'] =  5;
			$row_array['message'] =  $user_no_registered;
			$row_array['error'] = $user_mail_used;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		}
		
		
		
		$password = md5($password_1);//encrypt the password before saving in the database
		$sql = "INSERT INTO TBL_USERS (username, mail, password) 
				  VALUES('$username', '$email', '$password')";
	
		if ($db->query($sql) === TRUE) {
			$row_array['code'] =  1;
			$row_array['user_id'] =  intval($db->insert_id);
			$row_array['username'] =  $username;
			$row_array['status'] =  $user_registered;
	
			array_push($return_arr, $row_array);
			
			logData("register User: " . $username , "USER ACTION", basename(__FILE__, '.php') , $db->insert_id);
		} else {
			$row_array['code'] =  3;
			$row_array['message'] =   $user_no_registered;
		    $row_array['error'] =  $db->error;
		    
		    logData("register User FAIL " . $username, "USER ACTION", basename(__FILE__, '.php') , 0);

			array_push($return_arr, $row_array);
		}

		
	}

	
	$db->close();
	
	echo json_encode($return_arr);
	
?>