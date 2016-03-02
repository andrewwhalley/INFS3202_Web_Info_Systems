<?php 
	$dealid = intval($_GET['d']);
	$con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');

	// Query database
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$sqlResult = mysqli_query($con, "SELECT * FROM deals WHERE id = '" . $dealid . "'");
	$row = mysqli_fetch_array($sqlResult);
    echo $row['id'] . "##" . $row['name'] . "##" . $row['category'] . "##" . $row['price'] . "##" . $row['timeout'] . "##" . $row['location'] . "##" . $row['imageurl'] . "##" . $row['description'] . "##" . $row['reviews'];
	mysqli_close($db);
?>