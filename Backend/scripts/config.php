<?php
	session_start();
	
	include_once('text.php');
	
	$db = new mysqli('localhost', 'amu_admin', '0Km#3u4b', 'amu_db');
	if($db === false){
    	die("ERROR: Could not connect. " . mysqli_connect_error());
	}


	if (!$db->set_charset("utf8")) {
	    printf("Error loading character set utf8: %s\n", $db->error);
	    
	} 
	
	
	function cleanString($text) {
	    $utf8 = array(
	        '/[áàâãªä]/u'   =>   'a',
	        '/[ÁÀÂÃÄ]/u'    =>   'A',
	        '/[ÍÌÎÏ]/u'     =>   'I',
	        '/[íìîï]/u'     =>   'i',
	        '/[éèêë]/u'     =>   'e',
	        '/[ÉÈÊË]/u'     =>   'E',
	        '/[óòôõºö]/u'   =>   'o',
	        '/[ÓÒÔÕÖ]/u'    =>   'O',
	        '/[úùûü]/u'     =>   'u',
	        '/[ÚÙÛÜ]/u'     =>   'U',
	        '/ß/u'   		=>   'ss',
	        '/ç/'           =>   'c',
	        '/Ç/'           =>   'C',
	        '/ñ/'           =>   'n',
	        '/Ñ/'           =>   'N',
	        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
	        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
	        '/[“”«»„]/u'    =>   ' ', // Double quote
	        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
	    );
	    return preg_replace(array_keys($utf8), array_values($utf8), $text);
	}
	
	function logData($text, $type, $file, $user) {
		$db_log = new mysqli('localhost', 'amu_admin', '0Km#3u4b', 'amu_db');

		$sql = "INSERT INTO TBL_AMU_LOG (user_id, log_type, log_file, log_message) VALUES (" . $user . ", '" . $type . "', '" . $file . "', '" . $text . "');";
		
	
		if ($db_log->query($sql) === TRUE) { }
		
	}
	
?>