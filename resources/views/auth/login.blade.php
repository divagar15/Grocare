@extends('front.layout.tpl')
@section('customCss')
<style>

#remember_me{
	height: auto;
  width: 100%;
}

.socialLogins p {
  color:#000 !important;
}

.socialLogins strong {
  color:#000 !important;
}

</style>

@endsection

@section('content')

<div class="container pageStart container70" id="loginPage" style="margin-top:30px;">

<div class="row">

  
  <div class="col-md-12 no-padding">
     @if(Session::has('success_msg'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
		{{Session::get('success_msg')}}
	</div>
	@endif


	@if(Session::has('error_msg'))
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
		{{Session::get('error_msg')}}
	</div>
	@endif


    <div class="col-md-6 no-padding">

      <h4>LOGIN TO YOUR ACCOUNT</h4>

    </div>

    <div class="col-md-6 no-padding">

      <p style="text-align:right;"><a href="{{URL::to('/forgot-password')}}">Have a problem logging in?</a></p>

    </div>

    <div class="col-md-12 no-padding">

      <hr>

    </div>

  </div>



</div>


<div class="row">

  <div class="col-md-12 no-padding">

      <form class="form-inline user-form" id="login-form" action="{{URL::to('auth/login')}}" method="post">
        <div class="form-group">
          <div class="col-md-12 no-padding">
            <label class="sr-only" for="username"></label>
            <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Username</span></div>
              <input type="text" class="form-control" required id="email" name="email" placeholder="">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 no-padding">
            <label class="sr-only" for="password"></label>
            <div class="input-group">
              <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><span class="label-text">Password</span></div>
              <input type="password" class="form-control" required id="password" name="password" placeholder="">
            </div>
          </div>
        </div>
		
		<div class="col-md-12 no-padding">
			<label class="col-md-1 no-padding"><input type="checkbox" class="remember" id="remember_me" name="remember" placeholder="" value="1"/></label>
			<label class="col-sm-3 no-padding" for="remember">Remember Me</label>
		</div>

          <div class="col-md-12 no-padding"> 
            <button type="submit" class="btn btn-default btn-sm customGradientBtn submitBtn">SUBMIT</button>
            <a class="btn btn-default btn-sm moreBtn" href="{{URL::to('register')}}" style="font-size:14px; margin-left:20px;">CREATE AN ACCOUNT</a>
          </div>

      </form>

  </div>
  
  <div class="col-md-12 no-padding socialLogins" style="margin-top:40px;">

  <a href="{{URL::to('auth/social/facebook')}}">
	<div class="col-md-4">
		<div class="col-md-4">
		<img src="{{URL::to('public/front/images/facebook.png')}}" style="width:64px; height:64px;" />
		</div>
		<div class="col-md-8">
			<p>LOGIN  WITH <br/> <strong>FACEBOOK</strong></p>
		</div>
	</div>
	</a>
  
  <a href="{{URL::to('auth/social/twitter')}}">
	<div class="col-md-4">
		<div class="col-md-4">
		<img src="{{URL::to('public/front/images/twitter.png')}}" style="width:64px; height:64px;" />
		</div>
		<div class="col-md-8">
			<p>LOGIN  WITH <br/> <strong>TWITTER</strong></p>
		</div>
	</div>
  </a>
	
  <a href="{{URL::to('auth/social/google')}}">
	<div class="col-md-4">
		<div class="col-md-4">
		<img src="{{URL::to('public/front/images/google+.png')}}" style="width:64px; height:64px;" />
		</div>
		<div class="col-md-8">
			<p>LOGIN  WITH <br/> <strong>GOOGLE+</strong></p>
		</div>
	</div>
  </a>

  </div>

</div>


</div>



@endsection

@section('customJs')
<script type="text/javascript">
  $(document).ready(function(){

    $('#login-form').validate();

  });
</script>
@endsection
