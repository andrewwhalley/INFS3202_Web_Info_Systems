<!DOCTYPE html>
<html>
    <head>
        <script>
            var error = "";
        </script>
        <?php
            date_default_timezone_set('Australia/Brisbane');
            session_start();
            if(!isset($_SESSION['ausername']) || !isset($_SESSION['apassword'])){ 
                session_destroy();
                header('Location: admin_login.php'); 
            }
        ?>
        <?php
            if (isset($_POST['Save'])) {
                $con = mysqli_connect('localhost', 'root', 'Square=Fright_horn', 'dealdb');
                if (mysqli_connect_errno()) {
                    echo "Couldn't connect to mysql: " . mysqli_connect_error();
                }
                $id = intval($_REQUEST['deal_id']);
                $name = $_REQUEST['deal_name'];
                $cat = $_REQUEST['deal_category'];
                if (strcmp($name,"") !== 0 && strcmp($cat,"") !== 0) {
                    $price = $_REQUEST['deal_price'];
                    $to = $_REQUEST['deal_date'];
                    $loc = $_REQUEST['deal_location'];
                    $im = $_REQUEST['deal_photo'];
                    $desc = $_REQUEST['deal_desc'];
                    $rev = $_REQUEST['deal_reviews'];
                    $name = str_replace("'", "\'", $name);
                    $desc = str_replace("'", "\'", $desc);
                    $rev = str_replace("'", "\'", $rev);
                    $result = mysqli_query($con, "SELECT * FROM deals WHERE id='$id'");
                    if (mysqli_num_rows($result) > 0) {
                        //update
                        mysqli_query($con, "UPDATE deals SET name='$name', category='$cat', price='$price', timeout='$to', location='$loc', imageurl='$im', description='$desc', reviews='$rev' WHERE id='$id'");
                    } else {
                        //insert
                        mysqli_query($con, "INSERT INTO deals (name, category, price, timeout, location, imageurl, description, reviews) VALUES ('$name', '$cat', '$price','$to','$loc','$im','$desc', '$rev')");
                    }
                } else {
                    ?>
                        <script>
                            error = "Name and Category must not be empty";
                        </script>
                    <?php
                }
                mysqli_close($con);
                header("Location: admin_main.php");
            }
        ?>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script>
            function edit(deal) {
                document.getElementById("deals_form").style.display = "block";
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
					if (xmlhttp.readyState==4 && xmlhttp.status==200) {
						var response = xmlhttp.responseText.split("##");
                        document.getElementById("deal_id").value = response[0];        
                        document.getElementById("deal_name").value = response[1];
                        document.getElementById("deal_category").value = response[2];
                        document.getElementById("deal_price").value = response[3];
                        document.getElementById("deal_date").value = response[4];
                        document.getElementById("deal_location").value = response[5];
                        document.getElementById("deal_photo").value = response[6];
                        document.getElementById("deal_desc").value = response[7];
                        document.getElementById("deal_reviews").value = response[8];
                    }
                }
                xmlhttp.open("GET","getdeal.php?d="+deal,true);
                xmlhttp.send();
            }
            
            function deletefunc(deal) {
                var del = confirm("Are you sure you want to delete this deal?");
				if (del == true) {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange=function() {
						if (xmlhttp.readyState==4 && xmlhttp.status==200) {
							location.reload(true);
						}
					}
					xmlhttp.open("GET","deletedeal.php?d="+deal,true);
					xmlhttp.send();
				}
            }
            function add() {
                document.getElementById("deals_form").style.display = "block";
            }
        </script>
    </head>
    <body>
        <div id="deals">
            <form id="logout" method='post' accept-charset='UTF-8'>
                <div id="welcome">
                    Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>
                    <input type='submit' name='Logout' value='Logout' style="float:right;margin-right:20px;"/>
                </div>
                <?php
                    if (isset($_POST['Logout'])) {
                        session_destroy();
                        header('Location: admin_login.php');
                    }
                ?>
            </form>
            <table style="width:70%; margin:auto;">
                <tr>
                    <td>Deal Name</td>
                    <td>Category</td>
                    <td>Price</td>
                    <td>End Date</td>
                    <td>Location</td>
                    <td>Photo URL</td>
                    <td>Description</td>
                    <td>Reviews</td>
                    <td>Edit</td>
                    <td>Remove</td>
                </tr>
                <?php
                    $host = 'localhost';
                    $user = 'root';
                    $pw = 'Square=Fright_horn';
                    $db = 'dealdb';
                    $con = mysqli_connect($host, $user, $pw, $db);
                    if (mysqli_connect_errno()) {
                        echo "Cannot connect to mysql: " . mysqli_connect_error();
                    }
                    $result = mysqli_query($con, "SELECT * FROM deals");
                    $numrows = mysqli_num_rows($result);
                    for ($i = 1; $i <= $numrows; $i++) {
                        $row = mysqli_fetch_array($result);
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['timeout'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['imageurl'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['reviews'] . "</td>";
                        echo "<td><input type='button' name='edit' id='edit' value='edit' onclick='edit(" . $row['id'] . ")'></td>";
                        echo "<td><input type='button' name='remove' id='remove' value='remove' onclick='deletefunc(" . $row['id'] . ")'></td>";
                        echo "</tr>";
                    }
                    mysqli_close($con);
                ?>
            </table>
            <input type="button" id="add" name="add" value="add" onclick="add()"/>
            <form id='deals_form' method='post' accept-charset='UTF-8' style="margin:auto; margin-top: 20px; display:none;">
                <fieldset id="add_edit">
                    <legend>Deal Details</legend>
                    Deal ID:<input type='text' name='deal_id' id='deal_id' readonly style='float:right; clear:right;'/><br>
                    Deal Name*:<input type='text' name='deal_name' id='deal_name' style="float:right; clear:right;"/><br>
                    Deal Category*:<input type='text' name='deal_category' id='deal_category' style="float:right; clear:right;"/><br>
                    Deal Price:<input type='text' name='deal_price' id='deal_price' style="float:right; clear:right;"/><br>
                    Deal End Date:<input type='text' name='deal_date' id='deal_date'  style="float:right; clear:right;"/><br>
                    Deal Location:<input type='text' name='deal_location' id='deal_location'  style="float:right; clear:right;"/><br>
                    Deal Photo Url:<input type='text' name='deal_photo' id='deal_photo' style="float:right; clear:right;"/><br>
                    Deal Description:<input type='text' name='deal_desc' id='deal_desc'  style="float:right; clear:right;"/><br>
                    Deal Reviews (Separated by '::'):<input type='text' name='deal_reviews' id='deal_reviews'  style="float:right; clear:right;"/><br>
                    <input type='submit' name='Cancel' value='Cancel' style="float:right;margin-right:20px;margin-top:50px;"/>
                    <input type='submit' name='Save' value='Save' style="float:right;margin-right:20px;margin-top:50px;"/>
                    <?php
                        if (isset($_POST['Cancel'])) {
                    ?>
                    <script>
                        document.getElementById("deals_form").style.display = "none";
                    </script>
                    <?php
                        }
                    ?>
                
                    </fieldset>
            </form>
            <div id="error">
                <script>
                    document.write(error);
                </script>
            </div>
        </div>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>
                    