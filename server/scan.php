<?php
	require_once("config.php");
	require_once("products.php");

	if ((isset($_GET['rfid']))&&(strlen($_GET['rfid'])>16)) {

		if (!array_key_exists($_GET['rfid'], $code_map)) {
			
			echo "vaue not in DB";
			return "vaue not in DB";
		}
	
		$con=mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSW,MYSQL_DB);
		if (mysqli_connect_errno())	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	
		$query = "
			INSERT IGNORE INTO `".SCAN_TABLE."` (tag, period) VALUES ('".mysqli_real_escape_string($con,$_GET['rfid'])."','1');
		";

		$result = mysqli_query($con,$query);

		if (DEBUG) {
			echo $query;
			if ($result) {
				print_r($result);
			}else{	
				print_r($con);
			};
		};
	}	
	
	//print_r($code_map);

	// Free result set
	mysqli_free_result($result);
	mysqli_close($con);

	echo "";
	