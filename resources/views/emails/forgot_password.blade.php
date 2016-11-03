<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
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
				font-size: 25px;">Forgot Password</h2>
		</div>
		<p style="padding-left: 15px; padding-right: 15px;">Hello {{ucfirst($name)}},</p>
		<p style="padding-left: 15px; padding-right: 15px;">To login visit the following URL: <a href="{{URL::to('auth/login')}}">Click here to login</a></p>
		<p style="padding-left: 15px; padding-right: 15px;">Login Email Id: {{$username}}</p>
		<p style="padding-left: 15px; padding-right: 15px;">Password : {{$password}}</p>
		<br/>
		<p style="padding-left: 15px; padding-right: 15px;">If you have any problems, please contact us at info@grocare.com.</p>
		<br/>
		<p style="color:#A19D9D; padding-left: 15px; padding-right: 15px;">Grocare India</p>
		<p style="padding-left: 15px; padding-right: 15px;">Helpline: +91-98221-00031</p>
		</p>
		</div>
	</div>
</body>
</html>
