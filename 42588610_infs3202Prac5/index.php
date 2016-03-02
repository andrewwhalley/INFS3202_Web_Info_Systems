<?php
    include 'alreadyloggedin.php';
?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <title>Deals</title>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script>
            //if ($("#error") != null) {
//                document.getElementById("error").innerHTML = "";
            //}
        </script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
	<body>
        <h1>Deals</h1>
        <form id='login' action='index.php' method='post' accept-charset='UTF-8'>
            <fieldset>
                <legend>Login</legend>
                <input type='hidden' name='submitted' id='submitted' value='1'/>
                <p>Required Fields (*)</p>
                <label for='username' >UserName*:</label>
                <input type='text' name='username' id='username'  maxlength="30" style="float:right;"/>

                <label for='password' style="float:left; padding-top:20px;">Password*:</label>
                <input type='password' name='password' id='password' maxlength="30" style="float:right; margin-top:20px;"/>

                <input type='submit' name='Submit' value='Login' style="float:left; margin-top: 30px; margin-left:10px;"/>
                <select name="logintime" style="float:right; margin-top:30px; margin-right:10px">
                    <option value="30">30 Seconds</option>
                    <option value="86400">1 Day</option>
                    <!--<option value="5">1 Day</option>-->
                </select>
                <a href="admin_login.php">Admin login</a>
                <div id="error">
                <?php
                
                if(isset($_POST['Submit']))
                {
                    session_start();
                    if(($_REQUEST['username']=="infs" || $_REQUEST['username']=="INFS") && $_REQUEST['password']=="3202"){ 
                        $_SESSION['username'] = $_POST['username']; 
                        $_SESSION['password'] = $_POST['password']; 
                        $_SESSION['timeout'] = $_POST['logintime'];
                        $_SESSION['firstload'] = TRUE;
                        header('Location: main.php');
                    } else {
                        echo 'Username or Password is wrong';
                    }
                } 
                ?>
                </div>
            </fieldset>
        </form>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>