<!DOCTYPE html>
<?php
 $checkLoginStatus = App\Helper\AdminHelper::checkLoginStatus();
 if(!$checkLoginStatus) {
?>
<script type="text/javascript">
    window.location = "{{URL::to('/admin')}}";
</script>
<?php
 }
?>
<html lang="en">

<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Grocare Admin</title>

	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{URL::asset('public/admin/css/bootstrap/bootstrap.css')}}" /> 
	
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css')}}" />
	<!-- Calendar Styling  -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/calendar/calendar.css')}}" />
    
    <!-- Fonts  -->
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,300' rel='stylesheet' type='text/css'>
    
    <!-- Base Styling  -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/app/app.v1.css')}}" />

    <link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/bootstrap-chosen/chosen.css')}}" />

    <link rel="stylesheet" href="{{URL::asset('public/admin/css/custom.css')}}" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('customCss')

</head>
<body data-ng-app>
<?php
  $segment1 = Request::segment(1);
  $segment2 = Request::segment(2);
  $segment3 = Request::segment(3);
  $segment4 = Request::segment(4);
?>
	
    <!-- Preloader -->
    <div class="loading-container">
      <div class="loading">
        <div class="l1">
          <div></div>
        </div>
        <div class="l2">
          <div></div>
        </div>
        <div class="l3">
          <div></div>
        </div>
        <div class="l4">
          <div></div>
        </div>
      </div>
    </div>
    <!-- Preloader -->
    	
    
	<aside class="left-panel">
    		
            <div class="user text-center">
                  <img src="{{URL::asset('public/admin/images/avtar/administrator.png')}}" class="img-circle" alt="@if(isset(Auth::user()->name)){{ucwords(Auth::user()->name)}}@endif">
                  <h4 class="user-name">@if(isset(Auth::user()->name)){{ucwords(Auth::user()->name)}}@endif</h4>
                  
                 <!--  <div class="dropdown user-login">
                  <button class="btn btn-xs dropdown-toggle btn-rounded" type="button" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-circle status-icon available"></i> Available <i class="fa fa-angle-down"></i>
                  </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" href="#"><i class="fa fa-circle status-icon busy"></i> Busy</a></li>
                    <li role="presentation"><a role="menuitem" href="#"><i class="fa fa-circle status-icon invisibled"></i> Invisible</a></li>
                    <li role="presentation"><a role="menuitem" href="#"><i class="fa fa-circle status-icon signout"></i> Sign out</a></li>
                  </ul>
                  </div>	 --> 
            </div>
            
            
            
            <nav class="navigation">
            	<ul class="list-unstyled">
                    @if(Auth::user()->user_type==1)
                	<li @if($segment2=='dashboard')class="active"@endif><a href="{{URL::to($segment1.'/dashboard')}}"><i class="fa fa-bookmark-o"></i><span class="nav-label">Dashboard</span></a></li>
                    <li class="has-submenu @if($segment2=='configuration') {{"active"}} @endif"><a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Configuration</span></a>
                    	<ul class="list-unstyled">
                        	<li @if($segment3=='currencies')class="active"@endif><a href="{{URL::to($segment1.'/configuration/currencies')}}">Currencies</a></li>
                            <li @if($segment3=='regions')class="active"@endif><a href="{{URL::to($segment1.'/configuration/regions')}}">Regions</a></li>
                            <li @if($segment3=='courses')class="active"@endif><a href="{{URL::to($segment1.'/configuration/courses')}}">Courses</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu @if($segment2=='catalog') {{"active"}} @endif"><a href="#"><i class="fa fa-database"></i> <span class="nav-label">Catalog</span></a>
                        <ul class="list-unstyled">
                            <li @if($segment4=='simple-list')class="active"@endif><a href="{{URL::to($segment1.'/catalog/product/simple-list')}}">Simple Products</a></li>
                            <li @if($segment4=='bundle-list')class="active"@endif><a href="{{URL::to($segment1.'/catalog/product/bundle-list')}}">Bundle Products</a></li>
                            <li @if($segment3=='diagnosis')class="active"@endif><a href="{{URL::to($segment1.'/catalog/diagnosis/list')}}">Diagnosis</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu @if($segment2=='testimonials') {{"active"}} @endif"><a href="{{URL::to($segment1.'/testimonials/list')}}"><i class="fa fa-edit"></i> <span class="nav-label">Testimonials</span></a></li>
                    <li class="has-submenu @if($segment2=='orders') {{"active"}} @endif"><a href="#"><i class="fa fa-file-text"></i> <span class="nav-label">Orders</span></a>
                        <ul class="list-unstyled">
                            <li @if($segment3=='all' || $segment3=='process')class="active"@endif><a href="{{URL::to($segment1.'/orders/all')}}">All Orders</a></li>
                            <li @if($segment3=='incomplete')class="active"@endif><a href="{{URL::to($segment1.'/orders/incomplete')}}">Incomplete Orders</a></li>
                            <li @if($segment3=='hanging')class="active"@endif><a href="{{URL::to($segment1.'/orders/hanging')}}">Hanging Orders</a></li>
                            <li @if($segment3=='trash')class="active"@endif><a href="{{URL::to($segment1.'/orders/trash')}}">Trash</a></li><!-- 
                            <li @if($segment3=='completed')class="active"@endif><a href="{{URL::to($segment1.'/orders/completed')}}">Completed</a></li>
                            <li @if($segment3=='refunded')class="active"@endif><a href="{{URL::to($segment1.'/orders/refunded')}}">Refunded</a></li>
                            <li @if($segment3=='trash')class="active"@endif><a href="{{URL::to($segment1.'/orders/trash')}}">Cancelled / Failed</a></li> -->
                        </ul>
                    </li>			

                    <li class="has-submenu @if($segment2=='courier-login') {{"active"}} @endif"><a href="{{URL::to($segment1.'/courier-login/list')}}"><i class="fa fa-archive"></i> <span class="nav-label">Courier Logins</span></a></li>


                  <!--  <li class="has-submenu @if($segment2=='contact-us') {{"active"}} @endif"><a href="{{URL::to('/admin/contact-us')}}"><i class="fa fa-user"></i> <span class="nav-label">Contact Us</span></a></li>				
                    <li class="has-submenu @if($segment2=='newsletter') {{"active"}} @endif"><a href="{{URL::to('/admin/newsletter')}}"><i class="fa fa-envelope-o"></i> <span class="nav-label">Newsletter</span></a></li>		
                    --><li class="has-submenu @if($segment2=='media' || $segment2=='add-media' || $segment2=='edit-media') {{"active"}} @endif"><a href="{{URL::to('/admin/media')}}"><i class="fa fa-video-camera"></i> <span class="nav-label">Media</span></a></li>
                    <li class="has-submenu @if($segment2=='inventory') {{"active"}} @endif"><a href="{{URL::to($segment1.'/inventory')}}"><i class="fa fa-bar-chart"></i> <span class="nav-label">Inventory</span></a>
                    </li>  
					<li class="has-submenu @if($segment2=='accounting') {{"active"}} @endif"><a href="{{URL::to($segment1.'/accounting')}}"><i class="fa fa-money"></i> <span class="nav-label">Accounting</span></a>
                    </li>  
                    <li class="has-submenu @if($segment2=='reports') {{"active"}} @endif"><a href="#"><i class="fa fa-table"></i> <span class="nav-label">Reports</span></a>
                        <ul class="list-unstyled">
                            <li @if($segment3=='order')class="active"@endif><a href="{{URL::to($segment1.'/reports/order')}}">Order</a></li>
                            <li @if($segment3=='customer')class="active"@endif><a href="{{URL::to($segment1.'/reports/customer')}}">Customer</a></li>
							<li @if($segment3=='export')class="active"@endif><a href="{{URL::to($segment1.'/reports/export')}}">Export</a></li>
                        </ul>
                    </li>   
<!--                     <li class="has-submenu @if($segment2=='reports') {{"active"}} @endif"><a href="{{URL::to('/admin/reports')}}"><i class="fa fa-table"></i> <span class="nav-label">Reports</span></a></li>
 -->                
                    <li class="has-submenu @if($segment2=='tracking-ids') {{"active"}} @endif"><a href="{{URL::to('/admin/tracking-ids')}}"><i class="fa fa-bullseye"></i> <span class="nav-label">Tracking ID</span></a></li>
					
                    <li class="has-submenu @if($segment2=='mail-contents') {{"active"}} @endif"><a href="{{URL::to($segment1.'/mail-contents/list')}}"><i class="fa fa-envelope"></i> <span class="nav-label">Mail Contents</span></a>
                    </li>  
					
					 <li class="has-submenu @if($segment2=='cms') {{"active"}} @endif"><a href="#"><i class="fa fa-file-text"></i> <span class="nav-label">CMS</span></a>
                        <ul class="list-unstyled">
                            <li @if($segment3=='aboutus')class="active"@endif><a href="{{URL::to($segment1.'/cms/aboutus')}}">About Us</a></li>
                            <li @if($segment3=='contactlist')class="active"@endif><a href="{{URL::to($segment1.'/cms/contactlist')}}">Contact List</a></li>
							<li @if($segment3=='supportlist')class="active"@endif><a href="{{URL::to($segment1.'/cms/supportlist')}}">Support </a></li>
							<li @if($segment3=='distribute')class="active"@endif><a href="{{URL::to($segment1.'/cms/distribute')}}">Distribute </a></li>
							<li @if($segment3=='faq')class="active"@endif><a href="{{URL::to($segment1.'/cms/faq')}}">FAQ </a></li>
							<li @if($segment3=='technicalbulletins')class="active"@endif><a href="{{URL::to($segment1.'/cms/technicalbulletins')}}">Technical Bulletins </a></li>
                        </ul>
                    </li>	
                    @elseif(Auth::user()->user_type==3)
                    <li @if($segment2=='order-tracking')class="active"@endif><a href="{{URL::to($segment1.'/order-tracking')}}"><i class="fa fa-edit"></i><span class="nav-label">Orders</span></a></li>
                    
					@elseif(Auth::user()->user_type==4)
                    <li @if($segment3=='diagnosis')class="active"@endif><a href="{{URL::to($segment1.'/catalog/diagnosis/list')}}"><i class="fa fa-database"></i><span class="nav-label">Diagnosis</span></a></li>

                    @endif
					
					 <li class="has-submenu @if($segment2=='seo') {{"active"}} @endif"><a href="{{URL::to($segment1.'/seo')}}"><i class="fa fa-google-wallet"></i> <span class="nav-label">SEO</span></a></li>
                </ul>
            </nav>
            
    </aside>
    <!-- Aside Ends-->
    
    <section class="content">
    	
        <header class="top-head container-fluid">
            <button type="button" class="navbar-toggle pull-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
                    
            
            <ul class="nav-toolbar">
            	
                <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                	<div class="dropdown-menu lg pull-right arrow panel panel-default arrow-top-right">
                    	<!-- <div class="panel-heading">
                        	More Apps
                        </div> -->
                        <div class="panel-body text-center">
                        	<div class="row">
<!--                             	<div class="col-xs-6 col-sm-4"><a href="#" class="text-green"><span class="h2"><i class="fa fa-cog"></i></span><p class="text-gray no-margn">Settings</p></a></div>
 -->
 <div class="col-xs-6 col-sm-4"></div>
                                <div class="col-xs-6 col-sm-4"><a href="#" class="text-purple"><span class="h2"><i class="fa fa-user"></i></span><p class="text-gray no-margn">My Profile</p></a></div>
                                
                                <div class="col-xs-12 visible-xs-block"><hr></div>
                                
                                <div class="col-xs-6 col-sm-4"><a href="{{URL::to('admin/logout')}}" class="text-red"><span class="h2"><i class="fa fa-sign-out"></i></span><p class="text-gray no-margn">Logout</p></a></div>
                                
                                <!-- <div class="col-lg-12 col-md-12 col-sm-12  hidden-xs"><hr></div>
                            
                            	<div class="col-xs-6 col-sm-4"><a href="#" class="text-yellow"><span class="h2"><i class="fa fa-folder-open-o"></i></span><p class="text-gray">Folders</p></a></div>
                                
                                <div class="col-xs-12 visible-xs-block"><hr></div>
                                
                                <div class="col-xs-6 col-sm-4"><a href="#" class="text-primary"><span class="h2"><i class="fa fa-flag-o"></i></span><p class="text-gray">Task</p></a></div>
                                <div class="col-xs-6 col-sm-4"><a href="#" class="text-info"><span class="h2"><i class="fa fa-star-o"></i></span><p class="text-gray">Favorites</p></a></div> -->
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
        <!-- Header Ends -->
        
        
        <div class="warper container-fluid">
        	
            @yield('content')
            
        </div>
        <!-- Warper Ends Here (working area) -->
        
        
        <footer class="container-fluid footer">
        	Copyright &copy; {{date('Y')}} <a href="URL::to('/')" target="_blank">Grocare</a>
            <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
        </footer>
        
    
    </section>
    <!-- Content Block Ends Here (right box)-->
    
    
    <!-- JQuery v1.9.1 -->
	<script src="{{URL::asset('public/admin/js/jquery/jquery-1.9.1.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::asset('public/admin/js/plugins/underscore/underscore-min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{URL::asset('public/admin/js/bootstrap/bootstrap.min.js')}}"></script>
    
    <script src="{{URL::asset('public/admin/js/moment/moment.js')}}"></script>
    <script src="{{URL::asset('public/admin/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js')}}"></script>
    <!-- Globalize -->
    <script src="{{URL::asset('public/admin/js/globalize/globalize.min.js')}}"></script>
    
    <!-- NanoScroll -->
    <script src="{{URL::asset('public/admin/js/plugins/nicescroll/jquery.nicescroll.min.js')}}"></script>
	
 <!--   <script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/dx.chartjs.js')}}"></script>
    <script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/world.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/demo-charts.js')}}"></script>
    <script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/demo-vectorMap.js')}}"></script>-->
	
    <script src="{{URL::asset('public/admin/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <script src="{{URL::asset('public/admin/js/plugins/sparkline/jquery.sparkline.demo.js')}}"></script>

     <script src="{{URL::asset('public/admin/js/plugins/bootstrap-chosen/chosen.jquery.js')}}"></script>

      <script src="{{URL::asset('public/admin/js/jquery.validate.js')}}"></script>

    <!-- Custom JQuery -->
	<script src="{{URL::asset('public/admin/js/app/custom.js')}}" type="text/javascript"></script>
	
        
    @yield('customJs')

</body>
</html>
