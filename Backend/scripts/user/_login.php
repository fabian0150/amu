<?php
	include_once('../config.php');
	


	// variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";
	
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	if (count($errors) == 0) {
		$password = md5($password);
		$query = "SELECT ID FROM TBL_USERS WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) {
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			logData("login User: " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);

			header('location: ../../index.php');
			exit();
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
	logData("login User FAIL " . $username , "USER ACTION", basename(__FILE__, '.php') , 0);

	header('Location: ../../login.php');
	exit();
?>