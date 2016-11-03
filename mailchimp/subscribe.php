<?php
/*$api_key = "dc5e7f1b1b612f801216d0bd3825bcd5-us9";
$list_id = "e2eb9b470e";
require('Mailchimp.php');
$_POST['email'] = 'divagar.umm@gmail.com';
$Mailchimp = new Mailchimp( $api_key );
$Mailchimp_Lists = new Mailchimp_Lists( $Mailchimp );
$subscriber = $Mailchimp_Lists->subscribe( $list_id, array( 'email' => htmlentities($_POST['email']) ) );
if ( ! empty( $subscriber['leid'] ) ) {
   echo "success";
}
else
{
    echo "fail";
}*/
include('Mailchimp.php'); 
use \DrewM\MailChimp\MailChimp;
$MailChimp = new MailChimp('dc5e7f1b1b612f801216d0bd3825bcd5-us9');
$result = $MailChimp->get('lists');

$list_id = 'e2eb9b470e';

$result = $MailChimp->post("lists/".$list_id."/members", [
                'email_address' => 'tvkumaran007@gmail.com',
                'status'        => 'pending',
                'merge_fields' => ['FNAME'=>'Tamil', 'LNAME'=>''],
            ]);

$status = $result['status'];

if($status=='pending') {

} else {

}

print_r($result);
?>