<%@page import="java.io.*"%>
<%!
    String name;
    int maxAge = 30;
%>
<%
   Cookie[] cookies = request.getCookies();
   if (cookies != null) {
        int count = 0;
        for (int i=0; i < cookies.length; i++) {
            if (!(cookies[i].getName().equals("c_username"))) {
                count++;
            }
            if (cookies[i].getName().equals("c_username")) {
                name = cookies[i].getValue();
                maxAge = cookies[i].getMaxAge();
            }
        }
        if (count == cookies.length) {
            session.invalidate();
            response.sendRedirect("index.jsp");
        }
   } else {
        session.invalidate();
        response.sendRedirect("index.jsp");
    }
%>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
        <title>Deals</title>
        <script src="js/datefunction.js" type="text/javascript"></script>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/time.js" type="text/javascript"></script>
        <script type="text/javascript">
            top.title_time(<% out.println(maxAge); %>, "Deals");
        </script>
        <script>
             function dealTimeout(endTime, divid) {
                var timer;
                function dealTimers() {
                    endTime = new Date(endTime);
                    var now = new Date();
                    var distance = endTime - now;
                    var days = Math.floor(distance / 86400000);
                    var hours = Math.floor((distance % 86400000) / 3600000);
                    var minutes = Math.floor((distance % 3600000) / 60000);
                    var seconds = Math.floor((distance % 60000) / 1000);

                    if (distance < 0) {
                        document.getElementById(divid).innerHTML = "deal closed!" + timeleft;
                        clearInterval(timer);
                    } else {
                        timeLeft = days + " days, " + hours + ":" + minutes + ":" + seconds;
                        document.getElementById(divid).innerHTML = "Deal closes in: " + timeLeft;
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
                    Welcome <% out.println(name); %>
                    <input type='submit' name='Logout' value='Logout' style="float:right;margin-right:20px;"/>
                </div>
                <%
                   if (request.getParameter("Logout") != null) {
                        cookies = request.getCookies();
                        if (cookies != null) {
                            for (int i=0; i < cookies.length; i++) {
                                if (cookies[i].getName().equals("c_username")) {
                                    cookies[i].setMaxAge(0);
                                    response.addCookie(cookies[i]);
                                    session.invalidate();
                                    response.sendRedirect("index.jsp");
                                }
                            }
                        } else {
                            session.invalidate();
                            response.sendRedirect("index.jsp");
                        }
                   }
                %>
            </form>
			<h2>Places to Eat</h2>
            <hr class="inbody">
            <%!
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
            %>
            <%
               String[] deal1 = getDetails("deal1.txt");
               String[] deal2 = getDetails("deal2.txt");
            %>    
			<table>
				<tr>
                    <td><h3><% out.println(deal1[0]); %> - <% out.println(deal1[1]); %></h3><img src= <% out.println(deal1[4]); %> alt="AloyDee" width="150" height="150"></td>
                    <td><h3><% out.println(deal1[3]); %></h3><br><% out.println(deal1[5]); %></td>
                    <td class="dealtime"><div id="time1"><script>
                        dealTimeout("<% out.print(deal1[2]); %>", "time1")</script></div></td>
                </tr>
				<tr>
                    <td><h3><% out.println(deal2[0] + " - " + deal2[1]); %></h3><img src=<% out.println(deal2[4]); %> alt="Thepoint" width="150" height="150"></td>
                    <td><h3><% out.println(deal2[3]); %></h3><br><% out.println(deal2[5]); %></td>
                    <td class="dealtime" id="time2"><script>
                        dealTimeout("<% out.print(deal2[2]); %>", "time2")</script>
                    </td>
                </tr>
			</table>
		</div>
	   <footer>
            This website proudly brought to you by Andrew Whalley
            <p id="date"><script>setDate()</script></p>
        </footer>
    </body>
</html>