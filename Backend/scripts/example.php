<?php
    header("Content-Type: application/json; charset=UTF-8");
    include_once('config.php');
    $return_arr = array();

    $row_array['code'] =  1;
    $row_array['message'] =  $appointment_created;
    $row_array['appointment_id'] =  intval(1);

    array_push($return_arr, $row_array);
    echo json_encode($return_arr);
?>