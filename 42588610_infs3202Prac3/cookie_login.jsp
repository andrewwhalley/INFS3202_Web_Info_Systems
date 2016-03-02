<%@ page language="java" import="java.util.*"%>
    
<%
String username = request.getParameter("username");
String password = request.getParameter("password");

if(username == null) {
   username = "";
}
if(password == null) {
   password = "";
}
   
if (username.equals("admin") && password.equals("password")) {
    Cookie cookie = new Cookie("username", username);
    cookie.setMaxAge(300);
    response.addCookie(cookie);

    cookie = new Cookie("password", password);
    cookie.setMaxAge(300);
    response.addCookie(cookie);
    response.sendRedirect("admin_main.jsp");
} else {
   session.setAttribute("failed", "Login Failed. Username or Password incorrect");
   response.sendRedirect("admin_login.jsp");
}
%>