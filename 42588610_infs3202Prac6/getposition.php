<?php
    $dealid = intval($_GET['d']);
	$con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');

	// Query database
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$sqlResult = mysqli_query($con, "SELECT name, position FROM deals WHERE id = '" . $dealid . "'");
	$row = mysqli_fetch_array($sqlResult);
    $retval = $row['name'] . "::" . $row['position'];
    echo json_encode($retval);
	mysqli_close($db);
?>