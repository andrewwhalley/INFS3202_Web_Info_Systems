<%@ page language="java" %>
<%
   Cookie[] cookies = request.getCookies();
   if (cookies != null) {
        for (int i=0; i < cookies.length; i++) {
            if (cookies[i].getName().equals("username")) {
                response.sendRedirect("admin_main.jsp");
            }
        }
   }
%>
<html>
    <head>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <h1>Deals</h1>
        <form id='login' action='cookie_login.jsp' method='post' accept-charset='UTF-8'>
            <fieldset>
                <legend>Admin Login</legend>
                <input type='hidden' name='submitted' id='submitted' value='1'/>
                <p>Required Fields (*)</p>
                <label for='username' >UserName*:</label>
                <input type='text' name='username' id='username'  maxlength="30" style="float:right;"/>

                <label for='password' style="float:left; padding-top:20px;">Password*:</label>
                <input type='password' name='password' id='password' maxlength="30" style="float:right; margin-top:20px;"/>

                <input type='submit' name='Submit' value='Login' style="float:left; margin-top: 30px; margin-left:10px;"/>
                <a href="index.jsp">client login</a>
                <div id="error">
                    <% 
                       String login_fail = (String) session.getAttribute("failed");
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