<!DOCTYPE html>
<html lang="en">

<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Grocare Admin Login</title>

	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{URL::asset('public/admin/css/bootstrap/bootstrap.css')}}" /> 

    <!-- Fonts  -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/app/app.v1.css')}}" />

    <link rel="stylesheet" href="{{URL::asset('public/admin/css/custom.css')}}" /> 


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>	
    
	
    <div class="container">
    	<div class="row">
    	<div class="col-lg-4 col-lg-offset-4">
        	<h3 class="text-center">Grocare</h3>
            <p class="text-center">Sign in to admin portal</p>

            @if(Session::has('error_msg'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                {{Session::get('error_msg')}}
            </div>
            @endif

            <hr class="clean">
        	<form role="form" id="login-form" action="{{URL::to('auth/login')}}" method="post">
              <div class="form-group input-group">
              	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" name="email" class="form-control" required  placeholder="Email Address">
              </div>
              <div class="form-group input-group">
              	<span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" name="password" class="form-control" required  placeholder="Password">
              </div>
              <input type="hidden" name="page" id="page" value="admin">
              <!-- <div class="form-group">
                <label class="cr-styled">
                    <input type="checkbox" ng-model="todo.done">
                    <i class="fa"></i> 
                </label>
                Remember me
              </div> -->
              <div class="form-group">
                                <label class="cr-styled">
                                   <input type="checkbox" class="remember" id="remember_me" name="remember" placeholder="" value="1"/>
                                    <i class="fa"></i> 
                                </label>
                                Remember me
                              </div>
             <!--  <div class="form-group input-group">
                <label class="col-md-1 no-padding"><input type="checkbox" class="remember" id="remember_me" name="remember" placeholder="" value="1"/></label>
                <label class="col-sm-7 no-padding" for="remember">Remember Me</label>
              </div> -->
        	  <button type="submit" class="btn btn-purple btn-block">Sign in</button>
            </form>
            <hr>
            
            <!-- <p class="text-center text-gray">Dont have account yet!</p>
            <button type="submit" class="btn btn-default btn-block">Create Account</button> -->
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
    
	
    <script src="{{URL::asset('public/admin/js/jquery.validate.js')}}"></script>
    
    <!-- Custom JQuery -->
	<script src="{{URL::asset('public/admin/js/app/custom.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#login-form').validate();
        });
    </script>

</body>
</html>
