<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();
	
	
	if(isset($_GET['id'])) {
		logData("load BandMember Band ID: " . $id , "JSON", basename(__FILE__, '.php') , 0);
		$id = $_GET['id'];
		$id = mysqli_real_escape_string($db, $id);
	
		$query = "SELECT bm.*, u.username, b.name 
					FROM TBL_BANDMEMBERS bm
					LEFT JOIN TBL_BANDINFO b ON bm.band_id = b.ID
					LEFT JOIN TBL_USERS u ON bm.user_id = u.ID
					WHERE band_id = " . $id . ";";
		 
		
		if ($result = mysqli_query($db, $query)){
			
		    while ($row = mysqli_fetch_assoc($result)) {
				$row_array['code'] =  1;
			    $row_array['ID'] = $row['ID'];
			    $row_array['band_id'] = $row['band_id'];
			    $row_array['band_name'] = $row['name'];
			    $row_array['user_id'] = $row['user_id'];
			    $row_array['user_name'] = $row['username'];
				$row_array['record_date'] = $row['record_date'];
			
			    array_push($return_arr, $row_array);
		   }
		   if(mysqli_num_rows($result) == 0) {
			   $row_array['code'] =  6;
			    $row_array['error'] = "No Bandmembers";
			    array_push($return_arr, $row_array);
		   } 
		   
		 } else {
			 $row_array['code'] =  3;
			$row_array['error'] = "Execution error";
			array_push($return_arr, $row_array);
		 }
	} else {
		logData("load BandMembers", "JSON", basename(__FILE__, '.php') , 0);
		$query = "SELECT bm.*, u.username, b.name 
					FROM TBL_BANDMEMBERS bm
					LEFT JOIN TBL_BANDINFO b ON bm.band_id = b.ID
					LEFT JOIN TBL_USERS u ON bm.user_id = u.ID";
		 
		
		if ($result = mysqli_query($db, $query)){
		    while ($row = mysqli_fetch_assoc($result)) {
				$row_array['code'] =  1;
			    $row_array['ID'] = $row['ID'];
			    $row_array['band_id'] = $row['band_id'];
			    $row_array['band_name'] = $row['name'];
			    $row_array['user_id'] = $row['user_id'];
			    $row_array['user_name'] = $row['username'];
				$row_array['record_date'] = $row['record_date'];
			
			    array_push($return_arr, $row_array);
		   }
		   if(mysqli_num_rows($result) == 0) {
			   $row_array['code'] =  6;
			    $row_array['error'] = "No Bandmembers";
			    array_push($return_arr, $row_array);
		   } 
		   
		 } else {
			 $row_array['code'] =  3;
			$row_array['error'] = "Execution error";
			array_push($return_arr, $row_array);
		 }	
	}

	
	echo json_encode($return_arr);
?>