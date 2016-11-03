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

    height: 20px;

    margin: 0px;

    margin-bottom: 2%;

    padding: 0px;

}

.margin-null{

	margin:0px;

}

.user-form{

	margin:0px;

}

.view{

	background: #39D039;

    margin-right: 6%;

    padding: 2% 4%;

    color: #fff !important;

    border-radius: 9%;

}

.cancel{

	background: #DA3510;

    margin-right: 6%;

    padding: 2% 4%;

    color: #fff !important;

    border-radius: 9%;

}

#myTable_wrapper{

	font-size:14px;

	font-weight:none;

}

</style>



@endsection



@section('content')



<div class="container pageStart container80" id="registerPage" style="margin-top:40px;">



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

						<li class="active"><a href="{{URL::to('/my-profile')}}">My Orders</a></li>

						<li><a href="{{URL::to('/edit-profile')}}">My Profile</a></li>

						<li><a href="{{URL::to('/edit-address')}}">My Address</a></li>

						<li><a href="{{URL::to('shopping-cart')}}">Shopping Cart</a></li>

						<li><a href="{{URL::to('auth/logout')}}">Logout</a></li>

					  </ul>

					</div><!--/.nav-collapse -->

				  </div>

				</div>

		</div>

		

		<div class="col-md-9">

			<div class="margin-null titleh4">Order Details<div>

			

					@if(count($orderDetails)>0)

			<table id="myTable" class="display dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">

				<thead>

				  <tr>

					<th>S.No</th>

					<th>Invoice No</th>

					<th>Amount</th>

					<th>Status</th>

					<th>Auction</th>

				  </tr>

				</thead>

				<tbody>

					<?php 	$i=1;

							$status=array('0'=>'Placed','1'=>'Pending Payment','2'=>'Onhold','3'=>'Processing','4'=>'Completed','5'=>'Cancelled','6'=>'Refunded','7'=>'Failed',);

					?>

						@foreach($orderDetails as $key=>$val)

					

					  <tr>

						<td>{{$i}}</td>

						<td>{{$val->invoice_no}}</td>

						<td>{{$val->grand_total}}</td>

						<td>@if(isset($status[$val->order_status])){{$status[$val->order_status]}}@endif</td>

						<td><a href="{{URL::to('/order-details/'.$val->id)}}" class="view">View</a>@if($val->order_status<3)<a href="{{URL::to('/cancel-order/'.$val->id)}}" class="cancel">Cancel</a>@endif</td>

					  </tr>

						<?php $i++;?>

						@endforeach

				</tbody>

			</table>

			@else

				<span>No Data Found.</span>

					@endif

      



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

	  

	   $('#myTable').DataTable();

	  

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

