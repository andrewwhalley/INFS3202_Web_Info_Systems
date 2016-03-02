<!DOCTYPE html>
<?php 
    date_default_timezone_set('Australia/Brisbane');
    session_start();
    if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){ 
        session_destroy();
        header('Location: index.php'); 
    }

    if ($_SESSION['firstload']) {
        $_SESSION['firstload'] = FALSE;
        $_SESSION['timeout'] = intval($_SESSION['timeout']); // Set timeout seconds
        $_SESSION['start_time'] = time();
    }

    if (isset($_POST['Logout'])) {
        session_destroy();
        header('Location: index.php');
    }

    // Cover refreshing the page
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
        <meta charset="UTF-8">
        <title>Deals</title>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-1.11.1.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/time.js" type="text/javascript"></script>
        <script type="text/javascript">
            top.title_time(<?php echo json_encode($time_left); ?>, "Deals");
        </script>
        <script>
            
            function retrieveComments(dealid) {
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                        var comments = JSON.parse(xmlhttp.responseText);
                        $('#comments'+dealid).html("Comments:<br>");
                        for (i=0; i < Object.keys(comments).length; i++) {
                            var name = "comment"+i;
				            $('#comments'+dealid).html($('#comments'+dealid).html() + comments[name] + "<br><hr class='inbody'>");
                        }
                    }
                }
                xmlhttp.open("GET","getcomments.php?d="+dealid,true);
                xmlhttp.send();
            }
            
            function addComment(dealid) {
                if ($('#commentfield'+dealid).val() != "") {
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function() {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                            retrieveComments(dealid);
                        }
                    }
                    xmlhttp.open("GET","addcomment.php?d="+dealid+"&c="+$("#commentfield"+dealid).val()+"&user="+"<?php echo $_SESSION['username']; ?>",true);
                    cancelComment(dealid);
                    xmlhttp.send();
                }
            }
            
            function hideComments(dealid) {
                $("#comments"+dealid).hide();
                $("#addcomment"+dealid).hide();
                $("#showcomments"+dealid).val("Show Comments");
                $("#showcomments"+dealid).attr("onclick","showComments("+dealid+")");
            }
            
            function showComments(dealid) {
                $("#comments"+dealid).show();
                $("#addcomment"+dealid).show();
                $("#showcomments"+dealid).val("Hide Comments");
                $("#showcomments"+dealid).attr("onclick","hideComments("+dealid+")");
                retrieveComments(dealid);
            }
            
            function cancelComment(dealid) {
                //Isn't working yet
                $("#commentfield"+dealid).val("");
            }
        </script>
    </head>
	<body>
        <h1>Deals</h1>
        <div id="deals">
            <form id="logout" method='post' accept-charset='UTF-8'>
                <div id="welcome">
                    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>
                    <input type='submit' name='Logout' value='Logout' style="float:right;margin-right:20px;"/>
                </div>
                <a href="search.php">Search</a>
            </form>
			<h2>Places to Eat</h2>
            <hr class="inbody">
			<table>
                <?php
                    $host = 'localhost';
                    $user = 'root';
                    $pw = 'Square=Fright_horn';
                    $db = 'dealdb';
                    $con = mysqli_connect($host, $user, $pw, $db);
                    $timeouts = array();
                    $timedivs = array();
                    if (mysqli_connect_errno()) {
                        echo "Cannot connect to mysql: " . mysqli_connect_error();
                    }
                    $result = mysqli_query($con, "SELECT * FROM deals");
                    $numrows = mysqli_num_rows($result);
                    for ($i = 1; $i <= $numrows; $i++) {
                        $row = mysqli_fetch_array($result);
                        $id = $row['id'];
                        array_push($timeouts, $row['timeout']);
                        array_push($timedivs, "time" . $id);
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
                        echo "</td>";
                        echo "<td><input type='button' id='showcomments$id' value='Show Comments' onclick='showComments($id)'/></td>";
                        echo "</tr>";
                        echo "<tr><td id='comments$id' style='display:none'></td>";
                        echo "<td id='addcomment$id' style='display:none'><input type='text' id='commentfield$id'/><br><input type='button' value='Add Comment' onclick='addComment($id)'/><input type='button' value='Cancel' onclick='cancelComment($id)'/></td>";
                        echo "</tr>";
                    }
                    mysqli_close($con);
                ?>
			</table>
            <script>
                window.onload = function() {
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
                                document.getElementById(ids[i]).innerHTML = "deal closed!";
                                clearInterval(timer);
                            } else {
                                timeLeft = days + " days, " + hours + ":" + minutes + ":" + seconds;
                                document.getElementById(ids[i]).innerHTML = "Deal closes in: " + timeLeft;
                            }
                        }
                    }
                    timer = setInterval(dealTimers , 1000);
                }
            </script>
		</div>
	   <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>