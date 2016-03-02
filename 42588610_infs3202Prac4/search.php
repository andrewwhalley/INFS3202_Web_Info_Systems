<!DOCTYPE html>
<?php
    session_start();
    if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){ 
        session_destroy();
        header('Location: index.php'); 
    }

    $time_left = $_SESSION['timeout'] + $_SESSION['start_time'] - time();
    if (isset($_SESSION['start_time'])) {
        $_SESSION['elapsed_time'] = time() - $_SESSION['start_time'];
        if ($_SESSION['elapsed_time'] >= $_SESSION['timeout']) {
            session_destroy();
            header("Location: index.php");
        }
    }
?>
<html>
    <head>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/time.js" type="text/javascript"></script>
        <script type="text/javascript">
            top.title_time(<?php echo json_encode($time_left); ?>, "Deals");
        </script>
        </head>
    
    <body>
        <div id="deals">
            <form id="logout" method='post' accept-charset='UTF-8'>
                <div id="welcome">
                    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>
                    <input type='submit' name='Logout' value='Logout' style="float:right;margin-right:20px;"/>
                    <a href="main.php">Deals</a>
                </div>
                <?php
                    if (isset($_POST['Logout'])) {
                        session_destroy();
                        header('Location: index.php');
                    }
                ?>
            </form>
                <form name="search" method="post" id="search_form">
                    <h2>Search For Deals</h2><br>
                    <h3>Search by:</h3><br>
                    Name: <input type='text' name='search_name' id='search_name'/><br>
                    Price: <input type='text' name='search_price' id='search_price'/><br>
                    Location: <input type='text' name='search_location' id='search_location'/><br>
                    <input type="submit" value="Search" id="search" name="Search"/>
                </form><br>
            <div id="search_results">
                <table style='width:70%; margin:auto;'>
                <?php
                    if (isset($_POST['Search'])) {
                        $con = mysqli_connect('localhost','root','Square=Fright_horn','dealdb');
                        if (mysqli_connect_errno()) {
                            echo "Cannot connect to mysql: " . mysqli_connect_error();
                        }
                        $name = $_REQUEST['search_name'];
                        $price = $_REQUEST['search_price'];
                        $loc = $_REQUEST['search_location'];
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
                        for ($i = 1; $i <= $numrows; $i++) {
                            $row = mysqli_fetch_array($result);
                            array_push($timeouts, $row['timeout']);
                            array_push($timedivs, "time" . $row['id']);
                            echo "<tr>";
                            echo "<td><h3>" . $row['category'] . "</h3></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td><h3>" . $row['name'] . "</h3>";
                            echo "<img src='" . $row['imageurl'] . "' width='150' height='150'></td>";
                            echo "<td> $" . $row['price'] . "<br>";
                            echo "@ " . $row['location'] . "</td>";
                            echo "<td class='dealtime'><div id='time" . $row['id'] . "'></div></td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "</tr>";
                            echo "<tr style='width:100%;'><td>Reviews: <br>";
                            $reviews = explode('::', $row['reviews']);
                            foreach ($reviews as &$review) {
                                echo $review . "<br><hr class='inbody'>";
                            }
                            unset($review);
                            echo "</td></tr>";
                        }
                        mysqli_close($con);
                        ?>
                        <script>
                            var expDates = <?php echo json_encode($timeouts); ?>;
                            var ids = <?php echo json_encode($timedivs); ?>;
                            for (var i = 0; i < expDates.length; i++) {
                                expDates[i] = new Date(expDates[i]);
                            }
                            var timer;
                            function dealTimers() {
                                for (var i=0; i < ids.length; i++) {
                                    var now = new Date();
                                    var distance = expDates[i] - now;
                                    var days = Math.floor(distance / 86400000);
                                    var hours = Math.floor((distance % 86400000) / 3600000);
                                    var minutes = Math.floor((distance % 3600000) / 60000);
                                    var seconds = Math.floor((distance % 60000) / 1000);

                                    if (distance < 0) {
                                        document.getElementById(ids[i]).innerHTML = "deal closed!" + timeleft;
                                        clearInterval(timer);
                                    } else {
                                        timeLeft = days + " days, " + hours + ":" + minutes + ":" + seconds;
                                        document.getElementById(ids[i]).innerHTML = "Deal closes in: " + timeLeft;
                                    }
                                }
                            }
                            timer = setInterval(dealTimers , 1000);
                        </script>
                        <?php
                    }
                ?>
                </table>
                <?php
                    if (isset($_POST['Search'])) {
                        echo "Search found " . $numrows . " results.";
                    }
                ?>
            </div>
        </div>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>