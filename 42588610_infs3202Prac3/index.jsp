<!DOCTYPE html>
<%
   Cookie[] cookies = request.getCookies();
   if (cookies != null) {
        for (int i=0; i < cookies.length; i++) {
            if (cookies[i].getName().equals("c_username")) {
                response.sendRedirect("main.jsp");
            }
        }
   }
%>
<html>
	<head>
        <meta charset="UTF-8">
        <title>Deals</title>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
	<body>
        <h1>Deals</h1>
        <form id='login' action='client_login.jsp' method='post' accept-charset='UTF-8'>
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
                <a href="admin_login.jsp">Admin login</a>
                <div id="error">
                <%
                   String login_fail = (String) session.getAttribute("c_failed");
                   if (login_fail != null) {
                        out.println(login_fail);
                   }
                %>
                </div>
            </fieldset>
        </form>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>