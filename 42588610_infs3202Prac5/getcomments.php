<?php
    $dealid = intval($_GET['d']);
	$con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');

	// Query database
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$sqlResult = mysqli_query($con, "SELECT comments FROM deals WHERE id = '" . $dealid . "'");
	$row = mysqli_fetch_array($sqlResult);
    $comments = explode('::', $row['comments']);
    $retval = [];
    for ($i = 0; $i < count($comments); $i++) {
        $retval["comment" . $i] = $comments[$i];
    }
    echo json_encode($retval);
    unset($comment);
	mysqli_close($db);
?>