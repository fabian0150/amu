<?php
	$return_arr = array();
	$session_key = "";
	$id = -1;
	
	
	if(isset($_GET['session_key'])) {
		$session_key = $_GET['session_key'];
	} else {
		$session_key = $_SESSION['session_key'];
	}
	
	if(isset($_GET['web'])) {
		$session_key = $_GET['session_key'];
	}
	
	if(isset($_GET['session_user'])) {
		$id = $_GET['session_user'];
	} else {
		$id = $_SESSION['session_user'];
	}
	
	$query = "SELECT ID FROM TBL_USERS WHERE session_key='$session_key' AND ID='$id' LIMIT 1;";
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['error'] = "User not secure logged in";
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		if(isset($web)) {
			if($web == true) {
				header('Location: ../../index.php');
			}
		}
		exit();
	} 

?>