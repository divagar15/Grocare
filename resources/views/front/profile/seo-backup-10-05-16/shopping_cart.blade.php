@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
table thead {
    border:1px solid #6fc677;
  }

  table th,table td {
    padding: 15px !important;
  }

  table th {
    padding-left: 20px;
    border-bottom: 0px solid !important;
  }

  table td {
    border-top: 0px solid !important;
  }

  table tbody {
    border:1px solid #ccc;
  }

  table input {
    border:none; 
    width:12%; 
    text-align: center;
  }

  .qty .glyphicon {
    font-size: 8px;
  }
</style>

@endsection

@section('content')

<div class="container pageStart container80" id="registerPage" style="margin-top:40px;">

<div class="row">
  <div class="col-md-12 no-padding">
	<div class="col-md-4">
		<h3>My Profile</h3>
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
						<li><a href="{{URL::to('/edit-address')}}">My Address</a></li>
						<li class="active"><a href="{{URL::to('shopping-cart')}}">Shopping Cart</a></li>
						<li><a href="{{URL::to('auth/logout')}}">Logout</a></li>
					  </ul>
					</div><!--/.nav-collapse -->
				  </div>
				</div>
		</div>
		
		<div class="col-md-9">
			<h4 class="margin-null">Shopping Cart Details<h4>
				

				  <?php
					$grandTotal = 0.00;
				  ?>

				  @if(isset($orderProducts) && count($orderProducts)>0)

				  <form id="order-form" method="post" action="{{URL::to('update-cart')}}">
				  
				  <div class="col-md-12">


						  <table class="table table-responsive">

							<thead>
							  <tr>
								<th style="width:40%;"><strong style="margin-left:30px;">Product</strong></th>
								<th class="centralize" style="width:20%; border-left:1px solid #ccc;">Price</th>
								<th class="centralize" style="width:20%; border-left:1px solid #ccc;">Quantity</th>
								<th class="centralize" style="width:20%; border-left:1px solid #ccc;">Total</th>
							  </tr>
							</thead>

							<tbody> 
							<?php $j=1; ?>
							@foreach($orderProducts as $orderPro)
							  <?php
								$subTotal = round($orderPro->product_price)*$orderPro->product_qty;
								$grandTotal += $subTotal;
							  ?>
							  <tr>
								<td style="width:20%; border-left:1px solid #ccc;">
								<div class="col-md-10 no-padding">
								<strong style="margin-left:30px;">{{ucwords($orderPro->product_name)}}</strong> 
								</div>
								<div class="col-md-2 no-padding">
								  <a href="javascript:void(0)" class="deleteProduct" data-id="{{$orderPro->id}}"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
								</div>
								</td>
								<td class="centralize" style="width:20%; border-left:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($orderPro->product_price)}}</td>
								<td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
								  <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field='quantity{{$j}}'></span>
								  <input type='text' name='quantity{{$j}}' value='{{$orderPro->product_qty}}' class='qty' />
								  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field='quantity{{$j}}'></span> 
								  <input type="hidden" name="originalQuantity{{$j}}" value="{{$orderPro->product_qty}}">
								  <input type="hidden" name="product_id{{$j}}" value="{{$orderPro->product_id}}">
								  <input type="hidden" name="product_course{{$j}}" value="{{$orderPro->product_course}}">
								  <input type="hidden" name="product_price{{$j}}" value="{{$orderPro->product_price}}">
								  <input type="hidden" name="product_type{{$j}}" value="{{$orderPro->product_type}}">
								</td>

								<td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">{{$orderSession->order_symbol}} {{$subTotal}}</td>
							  </tr>
							  @if($orderPro->product_type==2)
								<?php
								  $getKitProducts = App\Helper\FrontHelper::getKitProducts($orderPro->oid,$orderPro->id);
								?>

							  @if(isset($getKitProducts) && count($getKitProducts)>0)

								@foreach($getKitProducts as $kitPro)

								<?php
								  $total = round($kitPro->product_price)*$kitPro->product_qty;
								?>

								<tr>
								  <td style="width:20%; border-left:1px solid #ccc;">
								<div class="col-md-10 no-padding">
								<strong style="margin-left:30px;">{{ucwords($kitPro->product_name)}}</strong> <br/>
								<span style="margin-left:30px;">Included with : {{ucwords($orderPro->product_name)}}</span>
								</div>
								
								</td>
								<td class="centralize" style="width:20%; border-left:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($kitPro->product_price)}}</td>
								<td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
								  <span>{{$kitPro->product_qty}}</span>
								</td>

								<td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">{{$orderSession->order_symbol}} {{$total}}</td>

								</tr>

								@endforeach

							  @endif

							  @endif
							  <?php $j++; ?>
							@endforeach

							<input type="hidden" name="counter" id="counter" value="{{$j}}" />
							  <!-- <tr>
								<td style="width:20%; border-left:1px solid #ccc;">
								  <div class="col-md-10 no-padding">
								<strong style="margin-left:30px;">Dencare</strong> 
								</div>
								<div class="col-md-2 no-padding">
								<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
								</div>
								</td>
								<td class="centralize" style="width:20%; border-left:1px solid #ccc;">Rs. 120</td>
								<td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
								  <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field='quantity2'></span>
								  <input type='text' name='quantity2' value='2' class='qty' />
								  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field='quantity2'></span> 
								</td>
								<td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">Rs. 240</td>
							  </tr> -->
							</tbody>
							
						  </table>



				  </div>

				  <div class="col-md-12" id="cartCalculation" style="margin-top:20px;">

					  <div class="col-md-6">

						  <div class="col-md-9">

							  <p>CART SUBTOTAL</p>

						  </div>

						  <div class="col-md-3 pull-right">
							<p><strong>{{$orderSession->order_symbol}} {{$grandTotal}}</strong></p>
						  </div>

						  <?php
							$shippingCharge = Session::get('active_shipping_charge');
							$minimumAmount = Session::get('active_minimum_amount');
							if($shippingCharge!=0.00 && ($minimumAmount==0.00 || $minimumAmount>$grandTotal)) {
							  $shippingCharge = round(Session::get('active_shipping_charge'));
							  $orderTotal = round($shippingCharge)+$grandTotal;
							} else {
							  $shippingCharge = "";
							  $orderTotal = $grandTotal;
							}
						  ?>

						  <div class="col-md-9">

							  <p>SHIPPING AND HANDLING </p>

						  </div>

						  <div class="col-md-3 pull-right">
							<p><strong>@if($shippingCharge!=''){{$orderSession->order_symbol}} {{round($shippingCharge)}} @else {{"-"}} @endif</strong></p>
						  </div>

						  <div class="col-md-12" style="border-top:1px solid #ccc; margin-bottom:10px;">
						  </div>

						  <div class="col-md-9">

							  <p><strong>ORDER TOTAL</strong></p>

						  </div>

						  <div class="col-md-3 pull-right">
							<p><strong>{{$orderSession->order_symbol}} {{round($orderTotal)}}</strong></p>
						  </div>

						  <input type="hidden" name="sub_total" value="{{$grandTotal}}">
						  <input type="hidden" name="shipping_charge" value="{{$shippingCharge}}">
						  <input type="hidden" name="grand_total" value="{{$orderTotal}}">

					  </div>


					  <div class="col-md-6 pull-right no-padding" style="text-align:right;">

						  <div class="col-md-12 pull-right">
							<button type="submit" class="btn btn-default cart-btn updateCart"><img src="{{URL::to('public/front/images/icons/update.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>UPDATE CART</button>
						  </div>

						  <div class="col-md-12 pull-right" style="margin-top:10px;">
							<a href="{{URL::to('checkout')}}" class="btn btn-default cart-btn"><img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>PROCEED TO CHECKOUT</a>
						  </div>

					  </div>


				  </div>

				  </form>

				  @else 

					  <h3 style="text-align:center;">Your cart is empty</h3>

				  @endif

		</div>
  
  </div>
  
  </div>

</div>


</div>



@endsection

@section('customJs')

<script type="text/javascript">
  $(document).ready(function () {
	  
    $('.deleteProduct').click(function(){
      var id = $(this).data('id');
      var confirmMsg = confirm('Are you sure want to remove this product?');
      if(confirmMsg) {
        window.location = "{{URL::to('delete-product')}}/"+id;
      }
    });

   // $('#login-form').validate();

        // This button will increment the value
    $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
  });
</script>
@endsection
