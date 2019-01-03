<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	
	
	$return_arr = array();
	
	$sql = "SELECT ID, username, session_date FROM TBL_USERS WHERE session_date IS NOT NULL;";
	$result = $db->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		    
		   
		    
		    $last_login =  $row['session_date'];
		    
		    $last_login = date_create_from_format('Y-m-d H:i:s', $last_login);
			//$last_login->getTimestamp();
		    
		    $now = new DateTime();
		   
		    
			$since_start = $last_login->diff($now);
			
			
		    
		    $minutes = $since_start->days * 24 * 60;
			$minutes += $since_start->h * 60;
			$minutes += $since_start->i;
			
			if($minutes >= 30) {
				$sql = "UPDATE TBL_USERS SET session_key=NULL, session_date=NULL WHERE ID=" . $row['ID'] . ";";

				if ($db->query($sql) === TRUE) {
					
					$_SESSION['session_key'] = null;
					$_SESSION['session_loggedin'] = false;
					$_SESSION['session_user'] = null;
					session_destroy();

					$row_array['status'] =  "logged out";
					
				} else {
					$row_array['status'] =  "error logging out";
					
				}
				
				
				 
			} else {
				 $row_array['status'] =  "Logged in";
				 
			}
	
		    
		    
		    
	        $row_array['user_id'] =  intval($row["ID"]);
	        $row_array['user_name'] =  $row["username"];
	        $row_array['last_login'] =  $last_login;
	        //$row_array['last_login'] =  $row['last_login'];
	        $row_array['minutes'] =  intval($minutes);
	       
			array_push($return_arr, $row_array);
	    }
	} else {
	    $row_array['status'] =  "No logged in users";
		array_push($return_arr, $row_array);

	}
	
	
	echo json_encode($return_arr);
?>