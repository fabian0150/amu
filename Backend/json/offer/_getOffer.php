<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	if(isset($_GET['type'])) {
		$type = $_GET['type'];
		$type = mysqli_real_escape_string($db, $type);
		$query = "SELECT ID, location_id, user_id, offer_state, offer_date, record_date FROM TBL_OFFER";
		if ($type == 2) {
			$query .= ";";
		} else {
			$query .= " WHERE offer_state = " . $type;
		}
		
		if(isset($_GET['id'])) { 
			$id = $_GET['id'];
			$id = mysqli_real_escape_string($db, $id);
			$query .= " AND ID = " . $id . ";";
		} else {
			$row_array['code'] =  2;
			$row_array['error'] = $search_error;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		}
	
	
		if ($result = mysqli_query($db, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
				$row_array['code'] =  1;
				$row_array['offer_id'] = intval($row['ID']);
				$row_array['location_id'] = intval($row['location_id']);
				$row_array['user_id'] = intval($row['user_id']);
				$row_array['offer_state'] = intval($row['offer_state']);
				$row_array['offer_date'] = $row['offer_date'];
				$row_array['record_date'] = $row['record_date'];
			
				array_push($return_arr, $row_array);
			
		}
		if(mysqli_num_rows($result) == 0) {
				$row_array['code'] =  6;
				$row_array['error'] = $offer_no_offers_found;
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['code'] =  3;
			$row_array['error'] = $server_error;
			array_push($return_arr, $row_array);
		}
		$log_user_id = 0;
		if(isset($_SESSION['session_user'])) {
			$log_user_id = $_SESSION['session_user'];
		}
		logData("load offers type " . $type , "JSON", basename(__FILE__, '.php') , $log_user_id);
	} else {
		$row_array['code'] =  2;
		$row_array['error'] = $search_error;
		array_push($return_arr, $row_array);
	}
	echo json_encode($return_arr);
?>