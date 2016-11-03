<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
</head>
<body class="bodydbg">
	<div class="page-content-wrapper">
		<div class="page-content" style="margin:0px;">
			<!-- BEGIN PAGE CONTENT-->
			<div class="row margin-bottom-30">

		<p>Previous Url From Where User Came: {{ $previous }}</p>
		<p>The Url On Which Error Occurred: {{ $url }}</p>
		<p>The Users Details: {{ $device }}</p>
		<p>The User's Real IP Address: {{ $ip }}</p>
		<p>The User's Real IP Address: {{ $ip }}</p>
		<p>The Error Name: {{ $true_error }}</p>
		<p>The File In Which Error Occured: {{ $file_name }}</p>
		<p>The Line Number on which error found: {{ $line_number }}</p>
		</br></br>
		<p>The Detailed Description: </br></br>{!! $desc !!}</p>

</div>
</div>
</div>

</body>
</html>