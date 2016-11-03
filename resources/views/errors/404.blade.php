<!DOCTYPE html>
<html lang="en">

<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Grocare  - 404 Page not found</title>

	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{URL::asset('public/admin/css/bootstrap/bootstrap.css')}}" /> 

    <!-- Fonts  -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/app/app.v1.css')}}" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body style="display:none;">	
    
<?php
$segment1 = Request::segment(1);
?>
	
    <div class="container">
    
    	<div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <h1 class="text-center" style="font-size:150px; font-weight:800;">404</h1>
                <p class="text-center lead">The Page you requested does not exist</p>
                <hr class="">
            </div>
        </div>
        
<!--         <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
            
            <form>
            <div class="input-group">
              <input type="search" class="form-control" placeholder="Search Here...">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-purple">Search</button>
              </span>
            </div>
            </form>
              
            
            </div>
        </div> -->
        
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 text-center ">
                @if($segment1=='admin')
                    <a href="{{URL::to('/admin/dashboard')}}" class="btn btn-default">Go Home</a>
                @else
                    <a href="{{URL::to('/')}}" class="btn btn-default">Go Home</a>
                @endif
                
            </div>
        </div>
        
    </div>
    
    
    
    <!-- JQuery v1.9.1 -->
	<script src="{{URL::asset('public/admin/js/jquery/jquery-1.9.1.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('public/admin/js/plugins/underscore/underscore-min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{URL::asset('public/admin/js/bootstrap/bootstrap.min.js')}}"></script>
    
    <!-- Globalize -->
    <script src="{{URL::asset('public/admin/js/globalize/globalize.min.js')}}"></script>
    
    <!-- NanoScroll -->
    <script src="{{URL::asset('public/admin/js/plugins/nicescroll/jquery.nicescroll.min.js')}}"></script>
    
	
    
    
    <!-- Custom JQuery -->
	<script src="{{URL::asset('public/admin/js/app/custom.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            @if($segment1=='admin')
                window.location = "{{URL::to('admin/dashboard')}}";
            @else
                window.location = "{{URL::to('/')}}";
            @endif
        });

    </script>
    

</body>
</html>
