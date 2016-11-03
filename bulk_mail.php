<?php require 'db.php';?>
<html>

<head>
<title>Bulk Mails</title>
	<style> 
	
		body{width:100%;padding:60px;}
		div{margin-left:auto;margin-right:auto;} 
		input,select,button{height:70px; width:50%;font-size:25px;text-align:center;padding 5px;cursor:pointer;margin:20px;}
	
	</style>
</head>
<body>
<div>
<input type="text" value="All Customers/Subscribers" disabled/><br/>
<select id="mail_id">
<?php $result = mysql_query("SELECT id,title FROM mail_contents");
//fetch tha data from the database
while ($row = mysql_fetch_array($result)) {
   echo '<option value="'.$row["id"].'">'.$row["title"].'</option>';
}
?>	
</select><br/>
<button type="button" onclick="sendMail()">Send Mails</button><br/>
</div>
<div id="demo">hii</div>
<script>
function sendMail() {
  var e = document.getElementById("mail_id");
  var mail_id = e.options[e.selectedIndex].value;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      document.getElementById("demo").innerHTML = xhttp.responseText;
    }
  };
  xhttp.open("POST", "bulk_mail_send.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("mail_id="+mail_id);
}
</script>
</body>
</html>