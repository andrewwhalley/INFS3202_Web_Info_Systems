<?php
    $con = mysqli_connect('localhost','root','Square=Fright_horn','dealdb');
    if (mysqli_connect_errno()) {
        echo "Cannot connect to mysql: " . mysqli_connect_error();
    }
    $name = $_GET['n'];
    $price = $_GET['p'];
    $loc = $_GET['l'];
    $symbol = "";
    if ((strpos($price, "<") === 0 || strpos($price, ">") === 0) && strpos($price, "=") !== 1) {
        $symbol = $price[0];
        if (strlen($price) > 1) {
            $price = substr($price, 1);
        }
    }
    if ((strpos($price, "<") === 0 || strpos($price, ">") === 0) && strpos($price, "=") === 1) {
        $symbol = $price[0] . $price[1];
        if (strlen($price) > 2) {
            $price = substr($price, 2);
        }
    }
    $query = "";
    if (strlen($symbol) > 0) {
        $query = "SELECT * FROM deals WHERE name LIKE '%$name%' AND price $symbol $price AND location LIKE '%$loc%'";
    } else {
        $query = "SELECT * FROM deals WHERE name LIKE '%$name%' AND price LIKE '%$price%' AND location LIKE '%$loc%'";
    }
    $result = mysqli_query($con, $query);
    $numrows = mysqli_num_rows($result);
    $timeouts = array();
    $timedivs = array();
    $output = "";
    for ($i = 1; $i <= $numrows; $i++) {
        $row = mysqli_fetch_array($result);
        array_push($timeouts, $row['timeout']);
        array_push($timedivs, "time" . $row['id']);
        $output = $output . "<tr>";
        $output = $output . "<td><h3>" . $row['category'] . "</h3></td>";
        $output = $output . "</tr>";
        $output = $output . "<tr>";
        $output = $output . "<td><h3>" . $row['name'] . "</h3>";
        $output = $output . "<img src='" . $row['imageurl'] . "' width='150' height='150'></td>";
        $output = $output . "<td> $" . $row['price'] . "<br>";
        $output = $output . "@ " . $row['location'] . "</td>";
        $output = $output . "<td class='dealtime'><div id='time" . $row['id'] . "'></div></td>";
        $output = $output . "<td>" . $row['description'] . "</td>";
        $output = $output . "</tr>";
        $output = $output . "<tr><td>Reviews: <br>";
        $reviews = explode('::', $row['reviews']);
        foreach ($reviews as &$review) {
            $output = $output . $review . "<br><hr class='inbody'>";
        }
        unset($review);
        $output = $output . "</td></tr>";
    }
    $output = $output . "Search found " . $numrows . " results.";
    echo $output;
    mysqli_close($con);
?>