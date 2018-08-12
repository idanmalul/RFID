<?php
	require_once("config.php");
	require_once("products.php");

	$con=mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSW,MYSQL_DB);
	if (mysqli_connect_errno())	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		
	$data = array(
		"last_scan" => $_SESSION["last_scan"],
		"current" => [],
		"previous" => [],
		"missed" => []
	);

	$query = "
		SELECT tag FROM `".SCAN_TABLE."` sdn WHERE PERIOD='2' AND tag NOT IN (SELECT tag FROM `scan_data` sdn WHERE PERIOD='1');
	";
	$result = mysqli_query($con,$query);
	if (DEBUG) {
		if ($result) {
			print_r($result);
		}else{	
			print_r($con);
		};
	};
	
	$result = mysqli_query($con,$query);
    while ($rec = mysqli_fetch_assoc($result)) {
		if ($rec['tag']=='ping') {
			continue;
		};
		if (array_key_exists($rec['tag'], $code_map)) {
			$data["missed"][] = $code_map[$rec['tag']];
		}else{
			$data["missed"][] = $rec['tag'];
		};
	}	

/*	
	$query = "
		SELECT tag FROM `".SCAN_TABLE."` WHERE PERIOD='1';
	";
	$result = mysqli_query($con,$query);
	if (DEBUG) {
		if ($result) {
			print_r($result);
		}else{	
			print_r($con);
		};
	};
	
	$result = mysqli_query($con,$query);
    while ($rec = mysqli_fetch_assoc($result)) {
		if ($rec['tag']=='ping') {
			continue;
		};
		if (array_key_exists($rec['tag'], $code_map)) {
			$data["current"][] = $code_map[$rec['tag']];
		}else{
			$data["current"][] = $rec['tag'];
		};
	}	
*/

	$query = "
		SELECT tag FROM `".SCAN_TABLE."` WHERE PERIOD='2';
	";
	$result = mysqli_query($con,$query);
	if (DEBUG) {
		if ($result) {
			print_r($result);
		}else{	
			print_r($con);
		};
	};
	
	$result = mysqli_query($con,$query);
    while ($rec = mysqli_fetch_assoc($result)) {
		if ($rec['tag']=='ping') {
			continue;
		};
		if (array_key_exists($rec['tag'], $code_map)) {
			$data["previous"][] = $code_map[$rec['tag']];
		}else{
			$data["previous"][] = $rec['tag'];
		};
	}	
	
	header('Content-Type: application/json');
	echo json_encode($data);
	
	// Free result set
	mysqli_free_result($result);
	mysqli_close($con);
	