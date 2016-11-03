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
		<p>Grocare Scholarship Application Details</p>
		<div>
			<table cellspacing="0" cellpadding="10" border="1">
				<tbody>
					<tr>
						<th>First Name</th>
						<td>{{$firstname}}</td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td>{{$lastname}}</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>{{$email}}</td>
					</tr>
					<tr>
						<th>Phone</th>
						<td>{{$phone}}</td>
					</tr>
					<tr>
						<th>University / College</th>
						<td>{{$college}}</td>
					</tr>
					<tr>
						<th>Major of Study</th>
						<td>{{$major}}</td>
					</tr>
					<tr>
						<th>Essay</th>
						<td>{{$description}}</td>
					</tr>
				</tbody>
			</table>		
		</div>
	</div>
</body>
</html>
