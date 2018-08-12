<?php
	require_once("config.php");

	$con=mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSW,MYSQL_DB);
	if (mysqli_connect_errno())	echo "Failed to connect to MySQL: " . mysqli_connect_error();

	$query = "
		DELETE FROM `".SCAN_TABLE."` WHERE PERIOD='2';
	";
	$result = mysqli_query($con,$query);
	if (DEBUG) {
		if ($result) {
			print_r($result);
		}else{	
			print_r($con);
		};
	};

	$query = "
		UPDATE `".SCAN_TABLE."` SET PERIOD='2';
	";
	$result = mysqli_query($con,$query);
	if (DEBUG) {
		if ($result) {
			print_r($result);
		}else{	
			print_r($con);
		};
	};

	header('Content-Type: application/json');
	echo json_encode(["last_scan"=>$_SESSION["last_scan"]]);

	$_SESSION["last_scan"] = gmdate("D, d M Y H:i:s");

	// Free result set
	mysqli_free_result($result);
	mysqli_close($con);

