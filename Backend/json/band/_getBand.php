<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$id = mysqli_real_escape_string($db, $id);
	
		$query = "SELECT b.*, u.username FROM TBL_BANDINFO b LEFT JOIN TBL_USERS u ON b.leader_id = u.ID WHERE b.ID = " . $id . ";";
		
		if ($result = mysqli_query($db, $query)){
			    while ($row = mysqli_fetch_assoc($result)) {
					$row_array['code'] =  1;
					$row_array['ID'] = $row['ID'];
					$row_array['name'] = $row['name'];
					$row_array['logo_path'] = $row['logo_path'];
					$row_array['website_url'] = $row['website_url'];
					$row_array['notes'] = $row['notes'];
					$row_array['leader_id'] = $row['leader_id'];
					$row_array['leader_username'] = $row['username'];
					$row_array['record_date'] = $row['record_date'];
				
					array_push($return_arr, $row_array);
			   }
			   
			   if(mysqli_num_rows($result) == 0) {
				   $row_array['code'] =  6;
				    $row_array['error'] = $band_no_exist;
				    array_push($return_arr, $row_array);
			   }
		   
		 } else {
			 $row_array['code'] =  3;
			$row_array['error'] = $server_error;
			array_push($return_arr, $row_array);
		 }

	} else {
		$row_array['code'] =  2;
		$row_array['error'] = $band_not_given;
		array_push($return_arr, $row_array);
	}
	
	logData("load Band ID: " . $id , "JSON", basename(__FILE__, '.php') , 0);
	echo json_encode($return_arr);
?>