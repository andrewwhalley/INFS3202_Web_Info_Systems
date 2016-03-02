<!DOCTYPE html>
<?php 
    date_default_timezone_set('Australia/Brisbane');
    session_start();
    if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){ 
        header('Location: index.php'); 
    }

    if ($_SESSION['firstload']) {
        $file = fopen('/var/tmp/log.txt','a');
        // append to file
        $curr_time = date('d-m-Y H:i:s');
        $output = $curr_time . ' ' . $_SESSION['username'] . ": LOGIN\n";
        fwrite($file, $output);
        fclose($file);
        $_SESSION['firstload'] = FALSE;
        $_SESSION['timeout'] = intval($_SESSION['timeout']); // Set timeout seconds
        $_SESSION['start_time'] = time();
    }

    if (isset($_POST['Logout'])) {
        $file = fopen('/var/tmp/log.txt','a');
        // append to file
        $curr_time = date('d-m-Y H:i:s');
        $output = $curr_time . ' ' . $_SESSION['username'] . ": LOGOUT by Button\n";
        fwrite($file, $output);
        fclose($file);
        session_destroy();
        header('Location: index.php');
    }

    // Cover refreshing the page
    $time_left = $_SESSION['timeout'] + $_SESSION['start_time'] - time();

    if (isset($_SESSION['start_time'])) {
        $_SESSION['elapsed_time'] = time() - $_SESSION['start_time'];
        if ($_SESSION['elapsed_time'] >= $_SESSION['timeout']) {
            $file = fopen('/var/tmp/log.txt','a');
            // append to file
            $curr_time = date('d-m-Y H:i:s');
            $output = $curr_time . ' ' . $_SESSION['username'] . ": LOGOUT by Timeout\n";
            fwrite($file, $output);
            fclose($file);
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
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/time.js" type="text/javascript"></script>
        <script type="text/javascript">
            top.title_time(<?php echo json_encode($time_left); ?>, "Deals");
        </script>
        <script>
             window.onload = function() {
                 var expDates = [new Date("04/07/14 10:00 AM"), new Date("04/13/14 09:45 PM"), new Date("04/27/14 11:59 PM"), new Date("04/07/14 09:37 AM"), new Date("06/12/14 06:30 PM"), new Date("05/10/14 12:00 PM")];
                 var ids = ["time1", "time2", "time3", "time4", "time5", "time6"];
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
            </form>
			<h2>Places to Eat</h2>
            <hr class="inbody">
			<table>
				<tr>
                    <td><h3>Free serve of entrees with mains @ Aloy Dee Thai</h3><img src="images/eat/aloydee.jpg" alt="AloyDee" width="150" height="150"></td>
                    <td>Address: 3/58 Beach St, Woolgoolga, NSW 2456.<br>Phone: (02)66540777</td>
                    <td class="dealtime"><div id="time1"></div></td>
                </tr>
				<tr>
                    <td><h3>Special Mothers Day Breakfast @ The Point Restaurant</h3><img src="images/eat/thepoint.jpg" alt="Thepoint" width="150" height="150"></td>
                    <td>Address: Little Stanley Street, South Bank, QLD 4101.<br>Phone: (07)38465555</td>
                    <td class="dealtime" id="time2"></td>
                </tr>
			</table>
			<h2>Drinks</h2>
            <hr class="inbody">
			<table>
				<tr>
                    <td><h3>2 for 1 Drinks @ Exchange Hotel</h3><img src="images/drink/exchange.jpg" alt="exchange" width="150" height="150"></td>
                    <td>Address: 131 Edward St, Brisbane QLD, 4000.<br>Phone: (07)32293522</td>
                    <td class="dealtime" id="time3"></td>
                </tr>
				<tr>
                    <td><h3>Free Beer or Wine with Mains @ Pig &amp; Whistle</h3><img src="images/drink/pigwhistle.jpg" alt="Pig&amp;Whistle" width="150" height="150"></td>
                    <td>Address: Riverside Centre, 123 Eagle Street, Brisbane, QLD 4000.<br>Phone: (07)38329099</td>
                    <td class="dealtime" id="time4"></td>
                </tr>
			</table>
			<h2>Concerts</h2>
            <hr class="inbody">
			<table>
				<tr>
                    <td><h3>Between the Buried and Me @ The Zoo</h3><img src="images/thrash/thezoo.jpg" alt="theZoo" width="150" height="150"></td>
                    <td>Address: 711 Ann St, Fortitude Valley, QLD 4006.<br>Phone: (07)38541381</td>
                    <td class="dealtime" id="time5"></td>
                </tr>
				<tr>
                    <td><h3>Every Time I Die @ The Hifi</h3><img src="images/thrash/thehifi.jpg" alt="theHifi" width="150" height="150"></td>
                    <td>Address: 123-125 Boundary St, West End, QLD 4101.<br>Phone: 1300843443</td>
                    <td class="dealtime" id="time6"></td>
                </tr>
			</table>
		</div>
	   <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>