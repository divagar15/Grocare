@extends('front.layout.tpl')

@section('customCss')



<style type="text/css">

  .stepwizard-step p {

  margin-top: 10px;

}

.stepwizard-row {

  display: table-row;

}

.stepwizard {

  display: table;

  width: 100%;

  position: relative;

}

.stepwizard-step button[disabled] {

  opacity: 1 !important;

  filter: alpha(opacity=100) !important;

}

.stepwizard-row:before {

  top: 20px;

  bottom: 0;

  position: absolute;

  content: " ";

  width: 100%;

  height: 10px;

  background-color: #ccc;

  z-order: 0;

}

.stepwizard-step {

  display: table-cell;

  /*text-align: center;*/

  position: relative;

}

.stepwizard-step a {

  border: 1px solid #000;

  font-size: 20px;

}

.stepwizard-step p {

  color:#000 !important;

  font-size: 15px;

}

.btn-circle {

  width: 50px;

  height: 50px;

  text-align: center;

  padding: 10px 0;

  font-size: 12px;

  line-height: 1.428571429;

  border-radius: 30px;

}



.btn-gradient {

  border: none !important;

  background: -webkit-linear-gradient(3.5deg, #6fc677, #48bca5); /* For Safari 5.1 to 6.0 */

  background: -o-linear-gradient(3.5deg, #6fc677, #48bca5); /* For Opera 11.1 to 12.0 */

  background: -moz-linear-gradient(3.5deg, #6fc677, #48bca5); /* For Firefox 3.6 to 15 */

  background: linear-gradient(3.5deg, #6fc677, #48bca5); 

  color:#fff !important;

}



.btn[disabled] {

  opacity: 1 !important;

}

#change_password{

	height: auto;

    width: 100%;

}

.user-form{

	margin:0px;

}



</style>



@endsection



@section('content')



<div class="container pageStart container70" id="registerPage" style="margin-top:40px;">



<div class="row">

  <div class="col-md-12 no-padding">

	<div class="col-md-4">

		<div class="titleh3">My Profile</div>

	</div>	

  </div>

</div>





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

<div class="col-md-12 no-padding">

	

		<div class="col-md-3 no-padding">

				<div class="sidebar-nav">

				  <div class="navbar navbar-default" role="navigation">

					<div class="navbar-header">

					  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">

						<span class="sr-only">Toggle navigation</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

					  </button>

					  <span class="visible-xs navbar-brand">Sidebar menu</span>

					</div>

					<div class="navbar-collapse collapse sidebar-navbar-collapse">

					  <ul class="nav navbar-nav">					  

						<li>

							@if($user && $user->id && $user->image!='' && $user->login_type==1)

              <img src="{{URL::to('public/uploads/user/'.$user->id.'/'.$user->image)}}" style="width:100%" />

              @elseif($user && $user->id && $user->image!='' && $user->login_type>1)

              <img src="{{$user->image}}" class="img-responsive" style="width:100%" />

              @else

              @if(strtolower($user->gender)=='male')

              <img src="{{URL::to('public/front/images/profile.png')}}" style="width:100%"/>

              @elseif(strtolower($user->gender)=='female')

              <img src="{{URL::to('public/front/images/profilefemale.png')}}" style="width:100%"/>

              @else

              <img src="{{URL::to('public/front/images/profile.png')}}" style="width:100%"/>

              @endif

              @endif

						</li>

						<li><a href="{{URL::to('/my-profile')}}">My Orders</a></li>

						<li class="active"><a href="{{URL::to('/edit-profile')}}">My Profile</a></li>

						<li><a href="{{URL::to('/edit-address')}}">My Address</a></li>

						<li><a href="{{URL::to('shopping-cart')}}">Shopping Cart</a></li>

						<li><a href="{{URL::to('auth/logout')}}">Logout</a></li>

					  </ul>

					</div><!--/.nav-collapse -->

				  </div>

				</div>

		</div>

		

		<div class="col-md-9 no-padding">



      <form role="form" class="form-inline user-form" action="" method="post" id="register-form" enctype="multipart/form-data">



      <div class="col-md-12">

		 <div class="margin-null titleh4">ACCOUNT DETAILS</div>

        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="username"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Name</span></div>

              <input type="text" class="form-control" required id="username" name="username" placeholder="" value="@if($user){{trim($user->name)}}@endif">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="email"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><span class="label-text">Email Address</span></div>

              <input type="text" email="true" class="form-control" required id="email" name="email" placeholder="" value="@if($user){{trim($user->email)}}@endif" readonly>

            </div>

          </div>

        </div>







      </div>



        <div class="col-md-12">

		 <div class="margin-null titleh4">PROFILE DETAILS</div>



        



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="display_name"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Display Name</span></div>

              <input type="text" class="form-control" id="display_name" name="display_name" placeholder="" value="@if($user){{trim($user->display_name)}}@endif">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="age"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span><span class="label-text">Profile Picture</span></div>

              <input type="file" class="form-control image" id="image" name="image" placeholder="">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="gender"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Gender</span></div>

              <select name="gender" id="gender" class="form-control" required style="height:38px;">

                <option value="">Select Gender</option>

                <option value="male" @if($user->gender=='male') selected @endif>Male</option>

                <option value="female" @if($user->gender=='female') selected @endif>Female</option>

              </select>

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="age"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span><span class="label-text">Age</span></div>

              <input type="text" number="true" required class="form-control" id="age" name="age" placeholder="" value="@if($user){{trim($user->age)}}@endif">

            </div>

          </div>

        </div>





      </div>







        <div class="col-md-12">

		 <div class="margin-null titleh4">SOCIAL PROFILE</div>

        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="facebook_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Facebook</span></div>

              <input type="text" class="form-control" id="facebook_url" name="facebook_url" placeholder="" value="@if($user){{trim($user->facebook_url)}}@endif">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="twitter_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Twitter</span></div>

              <input type="text" class="form-control" id="twitter_url" name="twitter_url" placeholder="" value="@if($user){{trim($user->twitter_url)}}@endif">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="google_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Google+</span></div>

              <input type="text" class="form-control" id="google_url" name="google_url" placeholder="" value="@if($user){{trim($user->google_url)}}@endif">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="website_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Website (URL)</span></div>

              <input type="text" class="form-control" id="website_url" name="website_url" placeholder="" value="@if($user){{trim($user->website_url)}}@endif">

            </div>

          </div>

        </div>

		

		<div class="col-md-12 no-padding">

			<label class="col-md-1 no-padding"><input type="checkbox" class="change_password" id="change_password" name="change_password" placeholder="" value="1"/></label>

			<label class="col-sm-3 no-padding" for="change_password">Change Password</label>

		</div>

        <div class="form-group changePassword">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="password"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><span class="label-text">Password</span></div>

              <input type="password" class="form-control" required id="password" name="password" placeholder="">

            </div>

          </div>

        </div>



        <div class="form-group changePassword">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="cpassword"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><span class="label-text">Confirm Password</span></div>

              <input type="password" equalTo="#password" class="form-control" required id="cpassword" name="cpassword" placeholder="">

            </div>

          </div>

        </div>





         <div class="col-md-12 no-padding"> 

            <button type="submit" class="btn btn-default nextBtn btn-sm customGradientBtn submitBtn">SUBMIT</button>

          </div>



      </div>



          <!-- <div class="col-xs-6 col-md-offset-3">

        <div class="col-md-12">

              <div class="titleh3"> Step 3</div>

              <button class="btn btn-success btn-lg pull-right" type="submit">Submit</button>

            </div>

      </div> -->

  </form>



      



  </div>

  

  </div>

  

  </div>



</div>





</div>







@endsection



@section('customJs')



<script type="text/javascript">

  $(document).ready(function () {

	  changePassword();

	 $('.change_password').change(function(){

		 changePassword();

	 });



  $('#register-form').validate({ // initialize plugin

    ignore: ":not(:visible)",

	rules: {

		facebook_url: {

			url: true

		},

		twitter_url: {

			url: true

		},

		google_url: {

			url: true

		},

		website_url: {

			url: true

		}

	}

  });



  var navListItems = $('div.setup-panel div a'),

      allWells = $('.setup-content'),

      allNextBtn = $('.nextBtn');



  allWells.hide();



  navListItems.click(function (e) {

    e.preventDefault();

    var $target = $($(this).attr('href')),

        $item = $(this);



    if (!$item.hasClass('disabled')) {

      navListItems.removeClass('btn-gradient').addClass('btn-default');

      $item.addClass('btn-gradient');

      allWells.hide();

      $target.show();

      $target.find('input:eq(0)').focus();

    }

  });



  allNextBtn.click(function(){

    var curStep = $(this).closest(".setup-content"),

      curStepBtn = curStep.attr("id"),

      nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),

      curInputs = curStep.find("input[type='text'],input[type='url'],textarea[textarea]"),

      isValid = true;



    /*$(".form-group").removeClass("has-error");

    for(var i=0; i<curInputs.length; i++){

      if (!curInputs[i].validity.valid){

        isValid = false;

        $(curInputs[i]).closest(".form-group").addClass("has-error");

      }

    }*/



    if ($("#register-form").valid()) {

      nextStepWizard.removeAttr('disabled').trigger('click');

    }

  });



  $('div.setup-panel div a.btn-gradient').trigger('click');

});

	function changePassword(){

	

		  var change_password=$('.change_password:checked').val();

		  if(change_password==1){

			  $('.changePassword').show();

		  }else{

			  $('.changePassword').hide();

		  }

	}

</script>

@endsection

