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
		<p>Dear Admin,</p>
		<p>Grocare Contact Details</p>
		<div>
			<table cellspacing="0" cellpadding="10" border="1">
				<thead>
					<tr style="background-color:#008ddc;color:#fff;">
						<td>Name</td>
						<td>Email</td>
						<td>Phone</td>
						@if($diagnosis_id)
						<td>Diagnosis Id</td>
						@endif
						@if($diagnosis_name)
						<td>Diagnosis Name</td>
						@endif
						<td>Message</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{$name}}</td>
						<td>{{$email}}</td>
						<td>{{$phone}}</td>
						@if($diagnosis_id)
						<td>{{$diagnosis_id}}</td>
						@endif
						@if($diagnosis_name)
						<td>{{$diagnosis_name}}</td>
						@endif
						<td>{{$messages}}</td>

					</tr>
				</tbody>
			</table>		
		</div>
	</div>
</body>
</html>
