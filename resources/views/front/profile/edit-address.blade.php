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

#addressDetail h5 {

	text-align:left;

}



#addressDetail p {

	margin-top:10px; 

	text-align:right;

}



#addressDetail a {

	color:#000;

	text-decoration:none;

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

							<img src="{{URL::to('public/front/images/profile.png')}}" style="width:100%"/>

							@endif

						</li>

						<li><a href="{{URL::to('/my-profile')}}">My Orders</a></li>

						<li><a href="{{URL::to('/edit-profile')}}">My Profile</a></li>

						<li class="active"><a href="{{URL::to('/edit-address')}}">My Address</a></li>

						<li><a href="{{URL::to('shopping-cart')}}">Shopping Cart</a></li>

						<li><a href="{{URL::to('auth/logout')}}">Logout</a></li>

					  </ul>

					</div><!--/.nav-collapse -->

				  </div>

				</div>

		</div>

		

		<div class="col-md-9 no-padding">	

			<div class="col-md-12" id="addressDetail">

				<div class="titleh5 margin-null">Billing Address</div>

		

		

		<div class="col-md-12 no-padding">



		

		</div>

				<div id="billingAddress">							

				<form class="form-inline address-form" id="billing-form" method="post">	

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="name"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" value="@if($billingAddress){{$billingAddress->name}}@endif" required id="billing_name" name="billing_name" placeholder="Name">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_1"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="billing_address_1" name="billing_address_1" placeholder="Address" value="@if($billingAddress){{$billingAddress->address}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_2"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control"  id="billing_address_2" name="billing_address_2" placeholder="Address Line 2" value="@if($billingAddress){{$billingAddress->address2}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="city"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="billing_city" name="billing_city" placeholder="Town / City" value="@if($billingAddress){{$billingAddress->city}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="state"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="billing_state" name="billing_state" placeholder="State" value="@if($billingAddress){{$billingAddress->state}}@endif">

							</div>

						  </div>

						</div>

						

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="country"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>

 								<select class="form-control" required id="billing_country" name="billing_country">

 								  	@if(isset($countries) && !empty($countries))

 								  		@foreach($countries as $key=>$value)

 								  			<option value="{{$key}}" @if(isset($billingAddress) && $billingAddress && $billingAddress->country==$key) selected @endif>{{$value}}</option>

 								  		@endforeach

 								  	@endif

 								</select>

 							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="postcode"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="billing_postcode" name="billing_postcode" placeholder="Postcode/Zip" value="@if($billingAddress){{$billingAddress->postcode}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="col-md-12 no-padding"> 

							<button type="submit" class="btn btn-default nextBtn btn-sm customGradientBtn submitBtn" name="billing_submitBtn" value="billing">SUBMIT</button>

						</div>

					</form>

					</div>



			<div id="shippingAddress">

					

					<form class="form-inline address-form" id="shipping-form" method="post">	

					<div class="form-group" style="background:none; box-shadow:none;">

						<div class="col-md-12 no-padding">



						<div class="titleh5">Shipping Address</div>

						

						</div>

					</div>





						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="name"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="shipping_name" name="shipping_name" placeholder="Name"  value="@if($shippingAddress){{$shippingAddress->name}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_1"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="shipping_address_1" name="shipping_address_1" placeholder="Address"  value="@if($shippingAddress){{$shippingAddress->address}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_2"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control"  id="shipping_address_2" name="shipping_address_2" placeholder="Address Line 2"  value="@if($shippingAddress){{$shippingAddress->address2}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="city"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="shipping_city" name="shipping_city" placeholder="Town / City"  value="@if($shippingAddress){{$shippingAddress->city}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="state"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="shipping_state" name="shipping_state" placeholder="State"  value="@if($shippingAddress){{$shippingAddress->state}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="country"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>

 								<select class="form-control" required id="shipping_country" name="shipping_country">

 								  	@if(isset($countries) && !empty($countries))

 								  		@foreach($countries as $key=>$value)

 								  			<option value="{{$key}}" @if(isset($shippingAddress) && $shippingAddress && $shippingAddress->country==$key) selected @endif>{{$value}}</option>

 								  		@endforeach

 								  	@endif

 								</select>

 							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="postcode"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="shipping_postcode" name="shipping_postcode" placeholder="Postcode/Zip"  value="@if($shippingAddress){{$shippingAddress->postcode}}@endif">

							</div>

						  </div>

						</div>

						

						<div class="col-md-12 no-padding"> 

							<button type="submit" class="btn btn-default nextBtn btn-sm customGradientBtn submitBtn" name="shipping_submitBtn" value="shipping">SUBMIT</button>

						</div>

					</form>

				</div>

			</div>

		

			</div>

      



  </div>

  

  </div>

  

  </div>



</div>





@endsection



@section('customJs')



<script type="text/javascript">

  $(document).ready(function () {

	



  $('#billing-form').validate({ // initialize plugin

    ignore: ":not(:visible)"

  });

  $('#shipping-form').validate({ // initialize plugin

    ignore: ":not(:visible)"

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

