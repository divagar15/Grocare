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

  background: -webkit-linear-gradient(3.5deg, #159FEC , #159FEC ); /* For Safari 5.1 to 6.0 */

  background: -o-linear-gradient(3.5deg, #159FEC , #159FEC ); /* For Opera 11.1 to 12.0 */

  background: -moz-linear-gradient(3.5deg, #159FEC , #159FEC ); /* For Firefox 3.6 to 15 */

  background: linear-gradient(3.5deg, #159FEC , #159FEC ); 

  color:#fff !important;

}



.btn[disabled] {

  opacity: 1 !important;

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



<div class="container pageStart container70" id="registerPage" style="margin-top:130px;">



<div class="row">



  <div class="col-md-12 no-padding socialLogins">



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



  

  <div class="col-md-12 no-padding" style="margin-top:40px;">



    <div class="col-md-6 no-padding">



      <div class="titleh4">REGISTER AN ACCOUNT</div>



    </div>



    <div class="col-md-6 no-padding">



      <p style="text-align:right;">Have an account? <a href="{{URL::to('login')}}">Login here</a></p>



    </div>



    <div class="col-md-12 no-padding">



      <hr>



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







  <div class="stepwizard">

    <div class="stepwizard-row setup-panel">

          <div class="stepwizard-step">

        <a href="#step-1" type="button" class="btn btn-gradient btn-circle">1</a>

        <p>ACCOUNT DETAILS</p>

      </div>

          <div class="stepwizard-step" style="text-align:center;">

        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>

        <p>PROFILE DETAILS</p>

      </div>

          <div class="stepwizard-step" style="text-align:right;">

        <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>

        <p>SOCIAL PROFILE</p>

      </div>

        </div>

  </div>

      <form role="form" class="form-inline user-form" action="" method="post" id="register-form" enctype="multipart/form-data">

    <div class="row setup-content" id="step-1">



      <div class="col-md-12">



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

            <label class="sr-only" for="email"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><span class="label-text">Email Address</span></div>

              <input type="text" email="true" class="form-control" required id="email" name="email" placeholder="">

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



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="cpassword"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span><span class="label-text">Confirm Password</span></div>

              <input type="password" equalTo="#password" class="form-control" required id="cpassword" name="cpassword" placeholder="">

            </div>

          </div>

        </div>



         <div class="col-md-12 no-padding"> 

            <button type="button" class="btn btn-default nextBtn btn-sm customGradientBtn submitBtn">NEXT</button>

          </div>



      </div>

         <!--  <div class="col-xs-6 col-md-offset-3">

        <div class="col-md-12">

              <div class="titleh3"> Step 1</div>

              <div class="form-group">

            <label class="control-label">First Name</label>

            <input  maxlength="100" type="text" required="required" class="form-control" placeholder="Enter First Name"  />

          </div>

              <div class="form-group">

            <label class="control-label">Last Name</label>

            <input maxlength="100" type="text" required="required" class="form-control" placeholder="Enter Last Name" />

          </div>

              <div class="form-group">

            <label class="control-label">Address</label>

            <textarea required="required" class="form-control" placeholder="Enter your address" ></textarea>

          </div>

              <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>

            </div>

      </div> -->

    </div>

    <div class="row setup-content" id="step-2">



        <div class="col-md-12">



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="display_name"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="label-text">Profile Display Name</span></div>

              <input type="text" class="form-control" id="display_name" name="display_name" placeholder="">

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

            <label class="sr-only" for="age"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span><span class="label-text">Age</span></div>

              <input type="text" number="true" class="form-control" id="age" name="age" placeholder="">

            </div>

          </div>

        </div>





         <div class="col-md-12 no-padding"> 

            <button type="button" class="btn btn-default nextBtn btn-sm customGradientBtn submitBtn">NEXT</button>

          </div>



      </div>





          <!-- <div class="col-xs-6 col-md-offset-3">

        <div class="col-md-12">

              <div class="titleh3"> Step 2</div>

              <div class="form-group">

            <label class="control-label">Company Name</label>

            <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Name" />

          </div>

              <div class="form-group">

            <label class="control-label">Company Address</label>

            <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Address"  />

          </div>

              <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>

            </div>

      </div> -->

        </div>

    <div class="row setup-content" id="step-3">





        <div class="col-md-12">



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="facebook_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Facebook</span></div>

              <input type="text" class="form-control" id="facebook_url" name="facebook_url" placeholder="">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="twitter_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Twitter</span></div>

              <input type="text" class="form-control" id="twitter_url" name="twitter_url" placeholder="">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="google_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Google+</span></div>

              <input type="text" class="form-control" id="google_url" name="google_url" placeholder="">

            </div>

          </div>

        </div>



        <div class="form-group">

          <div class="col-md-12 no-padding">

            <label class="sr-only" for="website_url"></label>

            <div class="input-group">

              <div class="input-group-addon"><span class="glyphicon glyphicon-link" aria-hidden="true"></span><span class="label-text">Website (URL)</span></div>

              <input type="text" class="form-control" id="website_url" name="website_url" placeholder="">

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

        </div>

  </form>



      



  </div>



</div>





</div>







@endsection



@section('customJs')



<script type="text/javascript">



jQuery.validator.addMethod("alphanumeric", function(value, element) {

    return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);

}, "Letters only please");



  $(document).ready(function () {



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

		},

		username: {

			alphanumeric: true

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

</script>

@endsection

