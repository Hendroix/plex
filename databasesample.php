<?php
	$hostName = "";
	$userName = "";
	$password = "";
	$dbName = "";

	$link = mysqli_connect($hostName, $userName, $password, $dbName);
	if(!$link){
		echo "Error: " . PHP_EOL;
		echo "DebuggingErrNo: " . mysqli_connect_errno() . PHP_EOL;
		echo "DebuggingError: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
?>