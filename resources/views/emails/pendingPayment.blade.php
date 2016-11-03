<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<style type="text/css">

		body {
			font-family:'Lato', sans-serif;
			color: #000;
			font-size:12px;
		}	
		img.logo{
			display: block;
			margin-left: auto;
			margin-right: auto;
		}

		.table
			{
				width: 95%;
				margin-bottom: 20px;
				border-color: gray;
				border-collapse: collapse;
				border-spacing: 0;
			}
			table tr th {
				text-align: left !important;
			}
			.table	tr > td 
			{
				padding: 8px;
				line-height: 1.428571429;
				vertical-align: top;
				border-top: 1px solid #dddddd;	
			}
			table.listoutproducts tbody tr:hover 
			{
				cursor:pointer;
				background-color:#EE5757;
			}

			#bodyContent {
				border-radius:5px; 
				border:1px solid #999; 
				width:80%; 
				margin:0 auto; 
				background: #fff;
			/*	padding-left:15px; 
				padding-right:15px;*/
			}

			#bodyContent h3, #bodyContent h4, #bodyContent p {
				padding-left: 15px;
				padding-right: 15px;
			}

			p {
				color: #737373;
				font-size: 15px;
			}

			#bodyHeader {
				background: #06ACF4;
				width: 100%;
				height: 80px;
			}

			#bodyHeader h2 {
				color:#fff;
				text-align: center;
				margin-top: 0px;
				padding-top: 25px;
				font-size: 25px;
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
				font-size: 25px;">Order Payment Pending</h2>
		</div>

		<p style="padding-left: 15px; padding-right: 15px;">Dear {{ucwords($name)}},</p>
<p style="padding-left: 15px; padding-right: 15px;">Your order payment with Grocare India is still pending. Please deposit the amount into our bank account. Once we receive the payment, we will process your order and ship it within 24 hours. You shall receive tracking details of the same via email. </p>
		
		<h3 style="padding-left: 15px; padding-right: 15px;">Our Bank account details are:</h3>
		<h4 style="padding-left: 15px; padding-right: 15px;">{{$bankDetails->account_name}}</h4>
		<p style="padding-left: 15px; padding-right: 15px;">Account Number : <strong>{{$bankDetails->account_number}}</strong></p>
		<p style="padding-left: 15px; padding-right: 15px;">Branch : <strong>{{$bankDetails->account_branch}}</strong></p>
		<p style="padding-left: 15px; padding-right: 15px;">IFSC : <strong>{{$bankDetails->account_ifsc}}</strong></p>
		<p style="padding-left: 15px; padding-right: 15px;">Type of Account : <strong>Current</strong></p>
		<br/>
		<p style="padding-left: 15px; padding-right: 15px;">Pls send us your a mail with invoice number after depositing the amount in our bank account.</p>
		<p style="padding-left: 15px; padding-right: 15px;">For any further query, pl call on 098221 00031. You can also contact us at info@grocare.com.</p>




<br/>
		<p style="padding-left: 15px; padding-right: 15px;">Regards,</p>
		<p style="color:#A19D9D;padding-left: 15px; padding-right: 15px;">Grocare India<br/></p>

		</div>
	</div>
</body>
</html>