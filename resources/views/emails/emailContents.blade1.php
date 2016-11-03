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
			

	</style>
</head>
<body>
	<div> 
		<img src="{{ URL::to('public/front/images/email_logo.png') }}" width="" /> 
		<p>Dear {{ucfirst($name)}},</p>
		<div><?php echo $mail_contents; ?></div>
		<p></p>
		<br/>
		<p>If you have any problems, please contact us at info@grocare.com.</p>
		<br/>
		<p style="color:#A19D9D">Grocare India<br/>
		Helpline: +91-98221-00031
		</p>
	</div>
</body>
</html>
