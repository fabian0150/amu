<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();
	
	
	if(isset($_GET['id'])) {

		$id = $_GET['id'];
		$id = mysqli_real_escape_string($db, $id);
	
		$query = "SELECT ob.*, b.name 
					FROM TBL_OFFER_BANDS ob
					LEFT JOIN TBL_BANDINFO b ON ob.band_id = b.ID
					WHERE ob.offer_id = " . $id . ";";
		 
		
		if ($result = mysqli_query($db, $query)){
			
		    while ($row = mysqli_fetch_assoc($result)) {
					$row_array['code'] =  1;
			    $row_array['ID'] = intval($row['ID']);
					$row_array['band_id'] = intval($row['band_id']);
					$row_array['band_name'] = $row['name'];
			    $row_array['price'] = $row['price'];
			    $row_array['offer_band_chosen'] = $row['offer_band_chosen'];
					$row_array['record_date'] = $row['record_date'];
			
			    array_push($return_arr, $row_array);
		   }
		   if(mysqli_num_rows($result) == 0) {
			   $row_array['code'] =  6;
			    $row_array['error'] = $offer_no_bands;
			    array_push($return_arr, $row_array);
		   } 
		   
		 } else {
			 $row_array['code'] =  3;
			$row_array['error'] = $server_error;
			array_push($return_arr, $row_array);
		 }
	} else {
		$row_array['code'] =  2;
		$row_array['error'] = $search_error;
		array_push($return_arr, $row_array);
	}

	$log_user_id = 0;
	if(isset($_SESSION['session_user'])) {
		$log_user_id = $_SESSION['session_user'];
	}
	logData("load Offer Bands for Offer ID: " . $id , "JSON", basename(__FILE__, '.php') , $log_user_id);
	echo json_encode($return_arr);
?>