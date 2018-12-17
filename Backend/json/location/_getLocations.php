<?php
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../scripts/config.php');
	$return_arr = array();

	$query = "SELECT * FROM TBL_LOCATIONS;";
	
	if ($result = mysqli_query($db, $query)){
	    while ($row = mysqli_fetch_assoc($result)) {
		    $row_array['ID'] = $row['ID'];
		    $row_array['name'] = $row['name'];
		    $row_array['address'] = cleanString($row['address']);
			$row_array['record_date'] = $row['record_date'];
						
		    array_push($return_arr, $row_array);
	   }
	    if(mysqli_num_rows($result) == 0) {
			    $row_array['error'] = "No Locations found";
			    array_push($return_arr, $row_array);
		   }
	} else {
		 $row_array['error'] = "Execution error";
		  array_push($return_arr, $row_array);
	}
	
	logData("load Locations" , "JSON", basename(__FILE__, '.php') , 0);
	echo json_encode($return_arr);
?>