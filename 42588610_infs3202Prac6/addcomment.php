<?php
    $dealid = intval($_GET['d']);
    $username = $_GET['user'];
	$con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');

	// Query database
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
    $sqlResult = mysqli_query($con, "SELECT comments FROM deals WHERE id = '" . $dealid . "'");
	$row = mysqli_fetch_array($sqlResult);
    $comment = $row['comments'];
    $comment = $comment . "::" . $username . ": " . $_GET['c'];
	mysqli_query($con, "UPDATE deals SET comments='$comment' WHERE id='$dealid'");
    mysqli_close($db);
?>