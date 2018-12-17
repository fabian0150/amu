<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();
	$query = "SELECT b.*, u.username FROM TBL_BANDINFO b LEFT JOIN TBL_USERS u ON b.leader_id = u.ID;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
		    $row_array['ID'] = $row['ID'];
		    $row_array['name'] = $row['name'];
		    $row_array['logo_path'] = $row['logo_path'];
		    $row_array['website_url'] = $row['website_url'];
			$row_array['leader_id'] = $row['leader_id'];
			$row_array['leader_username'] = $row['username'];
			$row_array['record_date'] = $row['record_date'];
		
		    array_push($return_arr, $row_array);
		  
	   }
	   if(mysqli_num_rows($result) == 0) {
			    $row_array['error'] = "No Bandinfo found";
			    array_push($return_arr, $row_array);
		   }
	 } else {
		$row_array['error'] = "Execution error";
		array_push($return_arr, $row_array);
	 }
	
	logData("load Bands" , "JSON", basename(__FILE__, '.php') , 0);
	echo json_encode($return_arr);
?>