<?php require 'db.php';
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '6000' );

$id = $_POST['mail_id'];
$to = "kirti.grocare@gmail.com";
$subject = "HTML email";

$result = mysql_query("SELECT * FROM mail_contents where id=$id");

// More headers
$headers = 'From: kirti@grocare.com' . "\r\n";
$headers .= "Content-type: text/html\r\n";


//fetch the data from the database
$message ="";
while ($row = mysql_fetch_array($result)) {
   echo $row["mail_contents"];
   mail($to,$row["subject"],$row["mail_contents"],$headers);
//mail($to,$row["subject"],"<html><strong>I am BOLD</strong></html>",$headers);
}





?>