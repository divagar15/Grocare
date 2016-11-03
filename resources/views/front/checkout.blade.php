@extends('front.layout.tpl')

@section('customCss')



<style type="text/css">



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



<div class="container pageStart" style="margin-top:60px;">



<div class="row">



  

  <div class="col-md-12">





      <div class="col-md-6" id="addressDetail">

	  

		<div class="col-md-6">



		<div class="titleh5">Billing Address</div>

		

		</div>

		

		<div class="col-md-6">



		<p>Have an account? <a href="{{URL::to('login')}}">Login</a></p>

		

		</div>

		

		

		<div class="col-md-12">

		

		

			<form class="form-inline address-form" id="address-form">

				<div id="billingAddress">

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="country"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="country" name="country" placeholder="Country">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="name"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="name" name="name" placeholder="Name">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_1"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="address_1" name="address_1" placeholder="Address">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="address_2"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="address_2" name="address_2" placeholder="Address Line 2">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="city"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="city" name="city" placeholder="Town / City">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="state"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="state" name="state" placeholder="State">

							</div>

						  </div>

						</div>

						

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="postcode"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="postcode" name="postcode" placeholder="Postcode/Zip">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="email"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="email" name="email" placeholder="Email">

							</div>

						  </div>

						</div>

						

						<div class="form-group">

						  <div class="col-md-12 no-padding">

							<label class="sr-only" for="phone"></label>

							<div class="input-group">

							  <div class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>

							  <input type="text" class="form-control" required id="phone" name="phone" placeholder="Phone number">

							</div>

						  </div>

						</div>

						

						<div class="form-group" style="box-shadow:none;">

							

							<div class="col-md-6 no-padding">

								<input type="checkbox" name="account" id="account"/> Create an account

							</div>

							

							<div class="col-md-6 no-padding">

								<input type="checkbox" name="account" id="account"/> Ship to a different address?

							</div>

							

						

						</div>

						

				</div>

			</form>

		

		

		</div>



      </div>





      <div class="col-md-6" style="margin-top:60px;">



          <div id="cartCalculation">



                  <div class="col-md-9">



                      <p>CART SUBTOTAL</p>



                  </div>



                  <div class="col-md-3 pull-right">

                    <p><strong>Rs. 580</strong></p>

                  </div>



                  <div class="col-md-9">



                      <p>SHIPPING AND HANDLING (*Flat Rate)</p>



                  </div>



                  <div class="col-md-3 pull-right">

                    <p><strong>Rs. 100</strong></p>

                  </div>



                  <div class="col-md-12" style="border-top:1px solid #ccc; margin-bottom:10px;">

                  </div>



                  <div class="col-md-9">



                      <p><strong>ORDER TOTAL</strong></p>



                  </div>



                  <div class="col-md-3 pull-right">

                    <p><strong>Rs. 680</strong></p>

                  </div>

				  

				  <div class="col-md-12" style="margin-top:20px;">

					<div class="col-md-12">

						<input type="radio" name="payment_type" id="card"/> Credit Card/Debit Card/Netbanking

					</div>

					<div class="col-md-12" style="margin-top:10px;">

						<input type="radio" name="payment_type" id="bank"/> Direct Bank Transfer

					</div>

					<div class="col-md-12" style="margin-top:30px;">

						<textarea name="order_note" id="order_note" style="width:100%; height:120px;"></textarea>

					</div>

					<div class="col-md-12" style="margin-top:10px; text-align:right;">

						<a href="#" class="btn btn-default cart-btn"><img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>PROCEED TO PAYMENT</a>

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

  $(document).ready(function(){



    $('#login-form').validate();



  });

</script>

@endsection

