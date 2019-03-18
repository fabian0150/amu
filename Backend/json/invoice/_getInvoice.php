<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$id = mysqli_real_escape_string($db, $id);

		$query = "SELECT ID FROM TBL_OFFER WHERE ID=" . $id . ";";
		if ($result = mysqli_query($db, $query)){
			if(mysqli_num_rows($result) == 0) {
				$row_array['code'] =  6;
				$row_array['error'] = $contract_not_found;
				array_push($return_arr, $row_array);

				echo json_encode($return_arr);
				exit();
			} else {
				$query = "SELECT ID FROM TBL_OFFER WHERE ID=" . $id . " AND offer_state = 1";
				if ($result = mysqli_query($db, $query)){
					if(mysqli_num_rows($result) == 0) {
						$row_array['code'] =  6;
						$row_array['error'] = $contract_not_made_yet;
						array_push($return_arr, $row_array);

						echo json_encode($return_arr);
						exit();
					}
				}
			}
		}

		$query = "SELECT o.ID, o.location_id, o.user_id, o.offer_state, o.offer_date, o.record_date, ob.band_id, ob.price 
		FROM TBL_OFFER_BANDS ob
		LEFT JOIN TBL_OFFER o ON o.ID = ob.offer_id
		WHERE ob.offer_id = " . $id . " AND ob.offer_band_chosen = 1 AND o.invoice_number IS NOT NULL AND o.invoice_date IS NOT NULL;";
		
		
	
	
		if ($result = mysqli_query($db, $query)){
			while ($row = mysqli_fetch_assoc($result)) {
				$row_array['code'] =  1;
				$row_array['offer_band_id'] = intval($row['ID']);
				$row_array['location_id'] = intval($row['location_id']);
				$row_array['user_id'] = intval($row['user_id']);
				$row_array['offer_state'] = intval($row['offer_state']);
				$row_array['offer_date'] = $row['offer_date'];
				$row_array['band_id'] = intval($row['band_id']);
				$row_array['price'] = $row['price'];
				$row_array['invoice_number'] = $row['invoice_number'];
				$row_array['invoice_date'] = $row['invoice_date'];
				$row_array['record_date'] = $row['record_date'];
			
				array_push($return_arr, $row_array);
			
		}
		if(mysqli_num_rows($result) == 0) {
				$row_array['code'] =  6;
				$row_array['error'] = $invoice_not_found;
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
		logData("load Invoice ID " . $id , "JSON", basename(__FILE__, '.php') , $log_user_id);
	} else {
		$row_array['code'] =  2;
		$row_array['error'] = $search_error;
		array_push($return_arr, $row_array);
	}
	echo json_encode($return_arr);
?>