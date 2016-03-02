<%@ page language="java" import="java.util.*"%>
    
<%
    String username = request.getParameter("username");
    String password = request.getParameter("password");
    int timeout = 30;
    try {
        timeout = Integer.parseInt(request.getParameter("dropdown"));
    } catch (Exception e) {
    
    }

    if(username == null) {
       username = "";
    }
    if(password == null) {
       password = "";
    }

    if (username.equals("infs") && password.equals("3202")) {
        Cookie cookie = new Cookie("c_username", username);
        cookie.setMaxAge(timeout);
        response.addCookie(cookie);

        cookie = new Cookie("c_password", password);
        cookie.setMaxAge(timeout);
        response.addCookie(cookie);
        response.sendRedirect("main.jsp");
    } else {
       session.setAttribute("c_failed", "Login Failed. Username or Password incorrect");
       response.sendRedirect("index.jsp");
    }
%>