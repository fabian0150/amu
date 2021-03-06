<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	$session_key = generateRandomString();
	
	$return_arr = array();
	
	$id = -1;
	
	// variable declaration
	$username = "";


	$_SESSION['success'] = "";
	
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if (empty($username)) {
		$row_array['code'] =  2;
		$row_array['error'] =  $user_name_not_given;
		array_push($return_arr, $row_array);
	}
	if (empty($password)) {
		$row_array['code'] =  2;
		$row_array['error'] =  $user_pwd_not_given;
		array_push($return_arr, $row_array);
	}

	if (count($errors) == 0) {
		$password = md5($password);
		$query = "SELECT ID FROM TBL_USERS WHERE username='$username' AND password='$password' LIMIT 1;";
		
		
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$id = $row["ID"];
				break;
			}
		} 

		if (mysqli_num_rows($result) == 1) {
			$last_login = date("Y-m-d H:i:s");
			
			$sql = "UPDATE TBL_USERS SET session_key='" . $session_key . "', last_login = '" . $last_login . "', session_date = '" . $last_login . "' WHERE ID=" . $id . ";";

			if ($db->query($sql) === TRUE) {
				
				$_SESSION['session_key'] = $session_key;
				$_SESSION['session_loggedin'] = true;
				$_SESSION['session_user'] = intval($id);
				
				$row_array['code'] =  1;
				$row_array['message'] =  $user_login;
				$row_array['session_key'] =  $session_key;
				$row_array['user_id'] = intval($id);
				$row_array['user_name'] = $username;
				
				array_push($return_arr, $row_array);
			} else {
				$row_array['message'] =  $user_no_login;
				$row_array['error'] =  $db->error;
				$row_array['code'] =  3;
				
				array_push($return_arr, $row_array);
			}
			$log_user_id = 0;
			if(isset($_SESSION['session_user'])) {
				$log_user_id = $_SESSION['session_user'];
			}
			logData("login User: " . $username , "USER ACTION", basename(__FILE__, '.php') , $log_user_id);

			
		}else {
			$row_array['code'] =  4;
			$row_array['error'] =  $user_pwd_wrong;
			array_push($return_arr, $row_array);
			logData("failed login User: " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);
			
		}
	}
	
	
	
	

	
	$db->close();
	
	echo json_encode($return_arr);
	
	function generateRandomString() {
		$length = 50;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
?>