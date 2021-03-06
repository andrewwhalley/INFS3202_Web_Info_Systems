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
        <script src="js/jquery-1.11.1.js" type="text/javascript"></script>
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
                    <input type="button" value="Search" id="search" name="Search" onclick="getResults()"/>
                </form><br>
            <div id="search_results">
                <table id="search_table" style='width:70%; margin:auto;'>
                    <script>
                        function getResults() {
                            if (window.XMLHttpRequest) {
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange=function() {
                                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                                    var response = xmlhttp.responseText.split("%^%");
                                    $("#search_table").html(response[0]);
                                    setMarkers(response[1]);
                                }
                            }
                            xmlhttp.open("GET","getsearch.php?n="+$("#search_name").val()+"&p="+$("#search_price").val()+"&l="+$("#search_location").val(),true);
                            xmlhttp.send();
                        }
                        
                        function setMarkers(results) {
                            if (markers != undefined) {
                                for (var i = 0; i < markers.length; i++) {
                                    markers[i].setMap(null);
                                }
                            }
                            markers = [];
                            var positionsArray = JSON.parse(results);
                            for (var i = 0; i < positionsArray.length; i++) {
                                var info = positionsArray[i].split('::');
                                var pos = info[1].split(',');
                                var location = new google.maps.LatLng(parseFloat(pos[0]), parseFloat(pos[1]));
                                marker = new google.maps.Marker ({
                                    position: location,
                                    map: map,
                                    title: info[0]
                                });
                                markers.push(marker);
                            }
                        }
                        
                        var expDates = <?php echo json_encode($timeouts); ?>;
                        var ids = <?php echo json_encode($timedivs); ?>;
                        if (expDates != null) {
                            for (var i = 0; i < expDates.length; i++) {
                                expDates[i] = new Date(expDates[i]);
                            }
                        }
                        var timer;
                        function dealTimers() {
                            if (ids == null) return;
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
                </table>
                <div id="map-canvas" style="width:500px;height:500px;">
                </div>
                <script type="text/javascript"
                  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0lnlzjwUY6QZBmmUtaN4CSW2EkvSrL5A&sensor=TRUE">
                </script>
                <script type="text/javascript">
                    var map;
                    var markers = [];
                    function initialize() {
                    var mapOptions = {
                      center: new google.maps.LatLng(-27.480056,153.022652),
                      zoom: 10
                    };
                    map = new google.maps.Map(document.getElementById("map-canvas"),
                        mapOptions);
                    }
                    google.maps.event.addDomListener(window, 'load', initialize);
                </script>
            </div>
        </div>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>