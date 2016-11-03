@extends('front.layout.tpl')

@section('customCss')





@endsection



@section('content')



<div class="container pageStart container70" id="loginPage" style="margin-top:30px;">



<div class="row">



  

  <div class="col-md-12 no-padding">



    <div class="col-md-6 no-padding">



      <div class="titleh4">LOGIN TO YOUR ACCOUNT</div>



    </div>



    <div class="col-md-6 no-padding">



      <p style="text-align:right;"><a href="#">Have a problem logging in?</a></p>



    </div>



    <div class="col-md-12 no-padding">



      <hr>



    </div>



  </div>







</div>





<div class="row">



  <div class="col-md-12 no-padding">



      <form class="form-inline user-form" id="login-form" action="{{URL::to('auth/login')}}">

        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="username"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Username</span></div>

              <input type="text" class="form-control" required id="username" name="username" placeholder="">

            </div>

          </div>

        </div>

        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="password"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><span class="label-text">Password</span></div>

              <input type="text" class="form-control" required id="password" name="password" placeholder="">

            </div>

          </div>

        </div>



          <div class="col-md-12 no-padding"> 

            <button type="submit" class="btn btn-default btn-sm customGradientBtn submitBtn">SUBMIT</button>

            <a class="btn btn-default btn-sm moreBtn" href="{{URL::to('register')}}" style="font-size:14px; margin-left:20px;">CREATE AN ACCOUNT</a>

          </div>



      </form>



  </div>

  

  <div class="col-md-12 no-padding" style="margin-top:40px;">

	<div class="col-md-4">

    <a href="{{URL::to('auth/facebook')}}">

		<div class="col-md-4">

		<img src="{{URL::to('public/front/images/facebook.png')}}" style="width:64px; height:64px;" />

		</div>

		<div class="col-md-8">

			<p>LOGIN  WITH <br/> <strong>FACEBOOK</strong></p>

		</div>

    </a>

	</div>

	

	<div class="col-md-4">

		<div class="col-md-4">

		<img src="{{URL::to('public/front/images/twitter.png')}}" style="width:64px; height:64px;" />

		</div>

		<div class="col-md-8">

			<p>LOGIN  WITH <br/> <strong>TWITTER</strong></p>

		</div>

	</div>

	

	<div class="col-md-4">

    <a href="{{URL::to('auth/google')}}">

		<div class="col-md-4">

		<img src="{{URL::to('public/front/images/google+.png')}}" style="width:64px; height:64px;" />

		</div>

		<div class="col-md-8">

			<p>LOGIN  WITH <br/> <strong>GOOGLE+</strong></p>

		</div>

    </a>

	</div>

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

