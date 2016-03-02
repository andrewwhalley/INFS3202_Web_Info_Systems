<%@page import="java.io.*"%>
<%!
    String name;
    String editFile;
    
    public String[] getDetails(String filename) {
        String filePath = getServletContext().getRealPath("prac3/deal_files/" + filename);
        BufferedReader reader;
        try {
            reader = new BufferedReader(new FileReader(filePath));
        } catch (FileNotFoundException e) {
            return new String[]{"","b","c","d","e","f"};
        }
        String[] arr = new String[6];
        try {
            for (int i=0; i < 6; i++) {
                arr[i] = reader.readLine();
            }
            reader.close();
        } catch (IOException e) {

        }
        return arr;
    }
    
     public String saveDetails(String[] new_details) {
        if (new_details[0].length() < 4) {
            return "Name must be at least 4 letters long";
        }
        String filename = getServletContext().getRealPath("prac3/deal_files/" + editFile);
        try {
            PrintWriter pw = new PrintWriter(new FileOutputStream(filename));
            for (int i=0; i < new_details.length; i++) {
                pw.println(new_details[i]);
            }
            pw.close();
        } catch (IOException e) {
            return "Failed to write to the file";
        }
        return "Successfully Saved deal to file";
    }
%>
    <%
        String[] deal1 = getDetails("deal1.txt");
        String[] deal2 = getDetails("deal2.txt");
    %>
<%
   Cookie[] cookies = request.getCookies();
   if (cookies != null) {
        int count = 0;
        for (int i=0; i < cookies.length; i++) {
            if (!(cookies[i].getName().equals("username"))) {
                count++;
            }
            if (cookies[i].getName().equals("username")) {
                name = cookies[i].getValue();
            }
        }
        if (count == cookies.length) {
            session.invalidate();
            response.sendRedirect("admin_login.jsp");
        }
   } else {
        session.invalidate();
        response.sendRedirect("admin_login.jsp");
    }
%>
<html>
    <head>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script>
            function showForm(deal) {
                document.getElementById("deals_form").style.display = "block";
                if (deal == 1) {
                    document.getElementById("deal_name").value = "<% out.print(deal1[0]); %>";
                    document.getElementById("deal_price").value = "<% out.print(deal1[1]); %>";
                    document.getElementById("deal_date").value = "<% out.print(deal1[2]); %>";
                    document.getElementById("deal_location").value = "<% out.print(deal1[3]); %>";
                    document.getElementById("deal_photo").value = "<% out.print(deal1[4]); %>";
                    document.getElementById("deal_desc").value = "<% out.print(deal1[5]); %>";
                    document.getElementById("hidden_field").value = "deal1.txt";
                } else {
                    document.getElementById("deal_name").value = "<% out.print(deal2[0]); %>";
                    document.getElementById("deal_price").value = "<% out.print(deal2[1]); %>";
                    document.getElementById("deal_date").value = "<% out.print(deal2[2]); %>";
                    document.getElementById("deal_location").value = "<% out.print(deal2[3]); %>";
                    document.getElementById("deal_photo").value = "<% out.print(deal2[4]); %>";
                    document.getElementById("deal_desc").value = "<% out.print(deal2[5]); %>";
                    document.getElementById("hidden_field").value = "deal2.txt";
                }
            }
        </script>
    </head>
    
    <body>
        <div id="deals">
            <form id="logout" method='post' accept-charset='UTF-8'>
                <div id="welcome">
                    Welcome <% out.println(name); %>
                    <input type='submit' name='Logout' value='Logout' style="float:right;margin-right:20px;"/>
                </div>
                <%
                   if (request.getParameter("Logout") != null) {
                        cookies = request.getCookies();
                        if (cookies != null) {
                            for (int i=0; i < cookies.length; i++) {
                                if (cookies[i].getName().equals("username")) {
                                    cookies[i].setMaxAge(0);
                                    response.addCookie(cookies[i]);
                                    session.invalidate();
                                    response.sendRedirect("admin_login.jsp");
                                }
                            }
                        } else {
                            session.invalidate();
                            response.sendRedirect("admin_login.jsp");
                        }
                   }
                %>
            </form>
            <table style="width:70%; margin:auto;">
                <tr>
                    <td>Deal Name</td>
                    <td>Price</td>
                    <td>End Date</td>
                    <td>Location</td>
                    <td>Photo URL</td>
                    <td>Description</td>
                    <td>Edit</td>
                </tr>
                <tr>
                    <td><% out.println(deal1[0]); %></td>
                    <td><% out.println(deal1[1]); %></td>
                    <td><% out.println(deal1[2]); %></td>
                    <td><% out.println(deal1[3]); %></td>
                    <td><% out.println(deal1[4]); %></td>
                    <td><% out.println(deal1[5]); %></td>
                    <td><input id="edit1" type="button" value="Edit" onclick="showForm(1)" >
                    </td>
                </tr>
                <tr>
                    <td><% out.println(deal2[0]); %></td>
                    <td><% out.println(deal2[1]); %></td>
                    <td><% out.println(deal2[2]); %></td>
                    <td><% out.println(deal2[3]); %></td>
                    <td><% out.println(deal2[4]); %></td>
                    <td><% out.println(deal2[5]); %></td>
                    <td><input id="edit2" type="button" value="Edit" onclick="showForm(2)" >
                    </td>
                </tr>
            </table>
            <form id='deals_form' action='admin_main.jsp' method='post' accept-charset='UTF-8' style="width:70%; margin:auto; margin-top: 20px; display:none;">
                <fieldset>
                    <legend>Deal Details</legend>
                    <label for='deal_name' >Deal Name*:</label>
                    <input type='text' name='deal_name' id='deal_name'  maxlength="30" style="float:right; clear:right;"/><br>
                    <label for='deal_price' >Deal Price:</label>
                    <input type='text' name='deal_price' id='deal_price'  maxlength="30" style="float:right; clear:right;"/><br>
                    <label for='deal_date' >Deal End Date:</label>
                    <input type='text' name='deal_date' id='deal_date'  maxlength="30" style="float:right; clear:right;"/><br>
                    <label for='deal_location' >Deal Location:</label>
                    <input type='text' name='deal_location' id='deal_location'  maxlength="30" style="float:right; clear:right;"/><br>
                    <label for='deal_photo' >Deal Photo Url:</label>
                    <input type='text' name='deal_photo' id='deal_photo'  maxlength="100" style="float:right; clear:right;"/><br>
                    <label for='deal_desc' >Deal Description:</label>
                    <input type='text' name='deal_desc' id='deal_desc'  maxlength="200" style="float:right; clear:right;"/>
                    <input type='submit' name='Cancel' value='Cancel' style="float:right;margin-right:20px;"/>
                    <input type='submit' name='Save' value='Save' style="float:right;margin-right:20px;"/>
                    <input type="hidden" name="hidden_field" id="hidden_field"/>
                    <%
                       if (request.getParameter("Cancel") != null) {
                            %><script>document.getElementById("deals_form").style.display = "none";</script>
                        <%
                       }
                        if (request.getParameter("Save") != null) {
                            editFile = request.getParameter("hidden_field");
                            String[] new_details = new String[6];
                            new_details[0] = request.getParameter("deal_name");
                            new_details[1] = request.getParameter("deal_price");
                            new_details[2] = request.getParameter("deal_date");
                            new_details[3] = request.getParameter("deal_location");
                            new_details[4] = request.getParameter("deal_photo");
                            new_details[5] = request.getParameter("deal_desc");
                            String success = saveDetails(new_details);
                            session.setAttribute("success", success);
                            response.sendRedirect("admin_main.jsp");
                        }
                    %>
                </fieldset>
            </form>
            <div id="error">
                <%
                   if (request.isRequestedSessionIdValid()) {
                       String message = (String) session.getAttribute("success");
                       if (message != null) {
                            out.println(message);
                       }
                   }
                %>
            </div>
        </div>
        <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>