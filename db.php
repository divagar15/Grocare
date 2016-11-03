<?php
die('test');
$username = "grocarei_kirti";
$password = "chotukiku275";
$hostname = "localhost:3306"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
mysql_select_db("grocarei_live",$dbhandle) 
  or die("Could not select examples");
?>