<?php
    //header("Content-Type: application/json; charset=UTF-8");
    include_once('../config.php');
    $return_arr = array();

    $error = false;

    $query = '';
    $sqlScript = file('database.sql');
    foreach ($sqlScript as $line)	{
        
        $startWith = substr(trim($line), 0 ,2);
        $endWith = substr(trim($line), -1 ,1);
        
        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
            continue;
        }
            
        $query = $query . $line;
        if ($endWith == ';') {
            mysqli_query($db,$query) or $error = true;
            $query= '';		
        }
    }


    if($error) {
        $row_array['code'] =  3;
        $row_array['error'] =  "Datenbank konnte nicht aufgesetzt werden";
        array_push($return_arr, $row_array);
    } else {
        $row_array['code'] =  1;
        $row_array['message'] =  "Datenbank wurde erfolgreich aufgesetzt";
        array_push($return_arr, $row_array);
    }
   
    echo json_encode($return_arr);
    $db->close();
    exit();

?>