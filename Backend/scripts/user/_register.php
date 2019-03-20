<?php
	include_once('../config.php');
	
	session_start();

	// variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";
	
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { array_push($errors, "Username is required"); }
	if (empty($email)) { array_push($errors, "Email is required"); }
	if (empty($password_1)) { array_push($errors, "Password is required"); }

	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
		logData("register User FAIL " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
		$query = "INSERT INTO TBL_USERS (username, mail, password) 
				  VALUES('$username', '$email', '$password')";
		mysqli_query($db, $query);

		$_SESSION['username'] = $username;
		$_SESSION['success'] = "You are now logged in";
		logData("register User: " . $username , "USER ACTION", basename(__FILE__, '.php') , $db->insert_id);
		header('location: ../../index.php');
	}
	
?>