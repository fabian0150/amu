<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	$query = "SELECT * FROM TBL_USERS;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
		    $row_array['ID'] = $row['ID'];
		    $row_array['name'] = $row['name'];
		    $row_array['phone_number'] = $row['phone_number'];
		    $row_array['address'] = cleanString($row['address']);
			$row_array['mail'] = $row['mail'];
			$row_array['notes'] = cleanString($row['notes']);
			$row_array['user_type'] = $row['user_type'];
			$row_array['user_description'] = $row['user_description'];
			$row_array['username'] = $row['username'];
			$row_array['record_date'] = $row['record_date'];
						
		    array_push($return_arr, $row_array);
	   }
	   if(mysqli_num_rows($result) == 0) {
			    $row_array['error'] = "No Users found";
			    array_push($return_arr, $row_array);
		   }
	} else {
		 $row_array['error'] = "Execution error";
		  array_push($return_arr, $row_array);
	}
	
	logData("load Users" , "JSON", basename(__FILE__, '.php') , 0);
	echo json_encode($return_arr);
?>