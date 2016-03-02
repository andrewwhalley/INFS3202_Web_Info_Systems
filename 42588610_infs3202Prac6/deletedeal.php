<?php 
	$dealid = intval($_GET['d']);
	$con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');

	// Query database
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	mysqli_query($con, "DELETE FROM deals WHERE id = '$dealid'");
	mysqli_close($db);
?>