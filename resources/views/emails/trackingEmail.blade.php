<!doctype html>
<html lang="en">
<head>

  
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "ParcelDelivery",
  "itemShipped" : {
    "@type" : "Product",
    "name" : "items"
  },
  "deliveryAddress" : {
    "@type" : "PostalAddress",
    "streetAddress" : "{{ucfirst($address1)}}, @if(!empty($address2)) {{ucfirst($address2).","}} @endif",
    "addressLocality" : "{{ucfirst($city)}}",
    "addressRegion" : "{{ucfirst($state)}}",
    "addressCountry" : {
      "@type" : "Country",
      "name" : "{{ucfirst($country)}}"
    },
    "postalCode" : "{{$zipcode}}"
  },
"expectedArrivalUntil": "{{date('Y-m-d')}}T12:00:00-08:00",
  "carrier" : {
    "@type" : "Organization",
    "name" : "{{ucwords($courier_name)}}"
  },
"trackingNumber" : "{{$tracking_number}}",
  "trackingUrl" : "{{URL::to('/order-tracking/'.$tracking_number)}}",
  "partOfOrder" : {
    "@type" : "Order",
    "orderNumber" : "{{$invoice_no}}",
    "merchant" : {
      "@type" : "Organization",
      "name" : "Grocare India"
    },
"orderStatus": "OrderInTransit"
  }
}
</script>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			font-family:'Lato', sans-serif;
			color: #000;
			font-size:12px;
		}	
		img{
			display: block;
			margin-left: auto;
			margin-right: auto;
		}
		span a{
			background: #0E86ED;
			text-decoration: none;
			color: #fff !important;
			padding: 1% 2%;
			border-radius: 5%;
			font-size: 15px;
		}		

    p {
        color: #737373;
        font-size: 15px;
      }

	</style>


</head>

<body>

 
    <div style="background-color: #f5f5f5; padding-top:20px; padding-bottom:20px;"> 
    <img class="logo" src="{{ URL::to('public/front/images/email_logo.png') }}" width="" style="display: block;
      margin-left: auto;
      margin-right: auto;"  /> 
    <br/>

    <div id="bodyContent" style=" border-radius:10px; 
        border:1px solid #dcdcdc; 
        width:70%; 
        margin:0 auto; 
        background: #fdfdfd;">

    <div id="bodyHeader" style="background: #06ACF4; border-top-left-radius:10px; border-top-right-radius:10px; 
        width: 100%;
        height: 80px;">
      <h2 style="color:#fff;
        padding-left: 15px;
        margin-top: 0px;
        padding-top: 25px;
        font-size: 25px;">Order Tracking</h2>
    </div>
		<p style="padding-left: 15px; padding-right: 15px;">Dear {{ucfirst($name)}},</p>
		<p style="padding-left: 15px; padding-right: 15px;">Your items for order no {{$invoice_no}} have been shipped via {{ucwords($courier_name)}} courier. </p>
    <p style="padding-left: 15px; padding-right: 15px;">Please allow 3-5 working days for the Parcel to reach you. It has been shipped to the below address :</p><br/>
		<p style="padding-left: 15px; padding-right: 15px;">
    {{ucwords($shipping_name)}}<br/>
    {{ucfirst($address1)}}, @if(!empty($address2)) {{ucfirst($address2).","}} @endif<br/>
    {{ucfirst($city)}}, {{ucfirst($state)}},<br/>
    {{ucfirst($country)}} - {{$zipcode}}
    </p><br/>
    <p style="padding-left: 15px; padding-right: 15px;">Tracking No. : {{$tracking_number}}</p><br/>
    <p style="padding-left: 15px; padding-right: 15px;"><span> <a id="button" href="{{URL::to('/order-tracking/'.$tracking_number)}}" target="_blank" >Track Your Order</a></span></p>
		<br/>
		<p style="padding-left: 15px; padding-right: 15px;">If you have any problems, please contact us at info@grocare.com.</p>
		<br/>
		<p style="color:#A19D9D; padding-left: 15px; padding-right: 15px;">Grocare India</p>
		<p  style="padding-left: 15px; padding-right: 15px;">Helpline: +91-98221-00031
		</p>
	</div>

  </div>

</body>
</html>