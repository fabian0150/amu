<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../config.php');
	include_once('../secure.php');
	
	$id = "NULL";
	
	if(isset($_POST['band_id'])) {
		
		$id= $_POST['band_id'];
		$id = mysqli_real_escape_string($db, $id);
		if($id == ""){
			$row_array['code'] =  2;
			$row_array['message'] =  $data_not_deleted;
			$row_array['error'] = $data_no_id;
			array_push($return_arr, $row_array);
			echo json_encode($return_arr);
			exit();
		} 
	} else {
		$row_array['code'] =  2;
		$row_array['message'] =  $data_not_deleted;
		$row_array['error'] = $data_no_id;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	}
	
	
	
	$query = "SELECT ID FROM TBL_BANDINFO WHERE ID=" . $id . " LIMIT 1;";
		
		
	$result = $db->query($query);

	if ($result->num_rows > 0) { } else {
		$row_array['code'] =  2;
		$row_array['message'] =  $data_not_deleted;
		$row_array['error'] = $data_not_found;
		array_push($return_arr, $row_array);
		echo json_encode($return_arr);
		exit();
	} 
	
	$sql = "DELETE FROM TBL_BANDMEMBERS WHERE band_id=" . $id . ";";

	if ($db->query($sql) === TRUE) {
	}

	$sql = "DELETE FROM TBL_APPOINTMENTS WHERE band_id=" . $id . ";";

	if ($db->query($sql) === TRUE) {
	}
	$sql = "DELETE FROM TBL_OFFER_BANDS WHERE band_id=" . $id . ";";

	if ($db->query($sql) === TRUE) {
	}

	$sql = "DELETE FROM TBL_BANDINFO WHERE ID=" . $id . " LIMIT 1;";

	if ($db->query($sql) === TRUE) {
		$row_array['code'] =  1;
	    $row_array['message'] =  $data_deleted;
		$log_user_id = 0;
		if(isset($_SESSION['session_user'])) {
			$log_user_id = $_SESSION['session_user'];
		}
	    logData("deleted Band ID: " . $id, "DELETED", basename(__FILE__, '.php') , $log_user_id);
		array_push($return_arr, $row_array);

	} else {
		$row_array['code'] =  3;
		$row_array['message'] =  $data_not_deleted;
	    $row_array['error'] =  $db->error;
	    
		array_push($return_arr, $row_array);
	}
	
	$db->close();
	
	echo json_encode($return_arr);
	
?>