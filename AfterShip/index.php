<?php
require('Exception/AftershipException.php');
require('Core/Request.php');
require('Couriers.php');
require('Trackings.php');
require('Notifications.php');
require('LastCheckPoint.php');

$key = '0e5c06be-d7ba-458f-aaa0-8ac9b3b62ee8';

$couriers = new AfterShip\Couriers($key);
$response = $couriers->get();

/*$trackings = new AfterShip\Trackings($key);

$tracking_info = array(
    'slug'    => 'dtdc',
    'title'   => 'P361735616',
    'emails'  => 'divagar.umm@gmail.com',
    'smses'   => '+919994533083'
);
$response = $trackings->create('P361735616', $tracking_info);*/

/*$notifications = new AfterShip\Notifications($key);
$response = $notifications->create('dtdc', 'P361735616', array (
                'emails' => ['tvkumaran007@gmail.com']
            ));
*/
var_dump($response);
?>