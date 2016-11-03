<?php

$to = "divagar.umm@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "";

if(mail($to,$subject,$txt,$headers)) {
 echo "Mail Sent :)";
} else {
 echo "Mail Sending Failed :(";
}

?>