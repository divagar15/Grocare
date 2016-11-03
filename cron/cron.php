<?php 

	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.grocare.com/placing-order-email");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);*/
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.grocare.com/checkout-late-email");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.grocare.com/order-tracking-email");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);

        $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.grocare.com/pending-payment-email");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	
?>