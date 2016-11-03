@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
  table thead {
    border:1px solid #6fc677;
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

  .addressTable td, .addressTable th {
    padding: 5px !important;
    border:0px solid #ccc !important;
  }

  .addressTable tbody {
    border:0px solid #ccc !important;
  }

  table input {
    border:none; 
    width:12%; 
    text-align: center;
  }

  .qty .glyphicon {
    font-size: 8px;
  }
.cancel{
	background: #DA3510;
    margin-right: 6%;
    padding: 1% 2%;
    color: #fff !important;
    border-radius: 9%;
}
.order-content h3{
	font-size:20px;
}
.order-content{
	font-size:14px;
}
.order-content{
	margin-bottom: 4%;
}
.table th{
	padding:5px !important;
}
.bottom2{
	margin-bottom:2%;
}
.center{
	text-align:center;
}
</style>

@endsection

@section('content')

  <?php
    $grandTotal = 0.00;
  ?>

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
			<h3 class="margin-null">Order Details</h3>
			 <div class="col-md-12 no-padding order-content">
			 
						
						  <div class="col-md-6 nopadding">
								  <h3 class="margin-null">Customer details</h3>

						<p><strong>Email:</strong> {{$orderAddress->billing_email}}</p>
						<p><strong>Phone:</strong> {{$orderAddress->billing_phone}}</p>


						  </div>
						  <div class="col-md-6 nopadding">
								  <h3 class="margin-null">Invoice Details</h3>
								  
							<?php 
								
								$payment_type=$orderDetail->payment_type;
								if($payment_type==2){
									$payment_text='Direct Bank Transfer';
								}else if($payment_type==1){
									$payment_text='Online EBS Payment';
								}else if($payment_type==3){
									$payment_text='Online Paypal Payment';
								}else{
									$payment_text='-';
								}
							?>

						<p><strong>Order Number:</strong>{{$orderDetail->invoice_no}}</p>
						<p><strong>Order Date :</strong> {{date('d-m-Y H:i:s'),strtotime($orderDetail->created_at)}}</p>
						<p><strong>Payment Type  :</strong> {{$payment_text}}</p>


						  </div>
						  
						 <div class="col-md-12">

							<table width="70%" border="0"  cellspacing="3" cellpadding="3" class="addressTable" >

							<tr>
							  <th><h3 class="margin-null">Billing Address</h3></th>
							  <th><h3 class="margin-null">Shipping Address</h3></th>
							</tr>

							<tr>
							  <td>{{$orderAddress->billing_name}}</td>
							  <td>{{$orderAddress->shipping_name}}</td>
							</tr>

							<tr>
							  <td>{{$orderAddress->billing_address1}}@if(!empty($orderAddress->billing_address2)){{", ".$orderAddress->billing_address2}}@endif</td>
							  <td>{{$orderAddress->shipping_address1}}@if(!empty($orderAddress->shipping_address2)){{", ".$orderAddress->shipping_address2}}@endif</td>
							</tr>

							<tr>
							  <td>{{$orderAddress->billing_state}}</td>
							  <td>{{$orderAddress->shipping_state}}</td>
							</tr>

							<tr>
							  <td>{{$orderAddress->billing_city}}</td>
							  <td>{{$orderAddress->shipping_city}}</td>
							</tr>

							<tr>
							  <td>{{$countries[$orderAddress->billing_country]}}</td>
							  <td>{{$countries[$orderAddress->shipping_country]}}</td>
							</tr>

							<tr>
							  <td>{{$orderAddress->billing_zip}}</td>
							  <td>{{$orderAddress->shipping_zip}}</td>
							</tr>

							</table>
					  </div>	
					  
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
									</td>
									<td class="centralize" style="width:20%; border-left:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($orderPro->product_price)}}</td>
									<td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
									  <input type='text' name='quantity{{$j}}' readonly value='{{$orderPro->product_qty}}' class='qty' />
							 
									</td>

									<td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($subTotal)}}</td>
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

									<td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($total)}}</td>

									</tr>

									@endforeach

								  @endif

								  @endif
								  <?php $j++; ?>
								@endforeach


								</tbody>
								
							  </table>



					  </div>

					  <div class="col-md-12" id="cartCalculation">

						  <div class="col-md-6 pull-right">

							  <div class="col-md-9">

								  <p>CART SUBTOTAL</p>

							  </div>

							  <div class="col-md-3 pull-right">
								<p><strong>{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</strong></p>
							  </div>

							  <?php
								/*$shippingCharge = Session::get('active_shipping_charge');
								$minimumAmount = Session::get('active_minimum_amount');
								if($shippingCharge!=0.00 && ($minimumAmount==0.00 || $minimumAmount>$grandTotal)) {
								  $shippingCharge = round(Session::get('active_shipping_charge'));
								  $orderTotal = round($shippingCharge)+$grandTotal;
								} else {
								  $shippingCharge = "";
								  $orderTotal = $grandTotal;
								}*/
							  ?>

							  <div class="col-md-9">

								  <p>SHIPPING AND HANDLING </p>

							  </div>

							  <div class="col-md-3 pull-right">
								<p><strong>@if($orderDetail->shipping_charge!='0.00'){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}} @else {{"-"}} @endif</strong></p>
							  </div>
							  <div class="col-md-12" style="border-top:1px solid #ccc; margin-bottom:10px;">
							  </div>

							  <div class="col-md-9">

								  <p><strong>ORDER TOTAL</strong></p>

							  </div>

							  <div class="col-md-3 pull-right">
								<p><strong>{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}</strong></p>
							  </div>
						  </div>
					  </div>
					  
						@if($orderDetail->order_status<3)
						<div class="col-md-12 center">
							<a href="{{URL::to('/cancel-order/'.$orderDetail->id)}}" class="cancel">Cancel</a>
						</div>
						@else
					  <div class="col-md-12">
						  <h3 class="margin-null">Order Tracking Details</h3>
							@if($couriers)
								<div class="col-md-12 bottom2">
									<div class="col-md-4 "><h4 class="margin-null">Selected Courier :</h4></div><div class="col-md-8 ">{{$couriers->name}}</div>							
								</div>		
							@endif
							@if(count($orderTracking)>0)
								<div class="col-md-12 bottom2">
									<div class="col-md-4 "><h4  class="margin-null">Tracking Numbers :</h4></div><div class="col-md-8 ">
									@foreach($orderTracking as $key=>$orderTrack)
										<span>@if($key>0) , @endif<a style="color:blue; text-decoration:underline !important;" href="{{URL::to('order-tracking/'.$orderTrack)}}" target="_blank">{{$orderTrack}}&nbsp;</a></span>
									@endforeach
									{{--implode(', ',$orderTracking)--}}
									</div>							
								</div>		
							@endif
							@if(count($orderNotes)>0)
								<div class="col-md-12 bottom2">
									<table 	class="table table-responsive ">
										<thead>
											<tr>
												<td style="width:10%;">S.No</td>
												<td style="width:60%;text-align:center;">Notes</td>
												<td style="width:30%;">Date Time</td>
											</tr>
										</thead>
										<tbody><?php $i=1; ?>
											@foreach($orderNotes as $key=>$val)
											<tr>
												<td style="width:10%;">{{$i}}</td>
												<td style="width:60%;text-align:center;">{{$val->notes}}</td>
												<td style="width:30%;">{{date('d-m-Y H:i:s'),strtotime($val->created_at)}}</td>
											</tr>
											<?php $i++; ?>
											@endforeach
										</tbody>
									</table>
								</div>		
							@endif
					  </div>
					  @endif
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
  
  });
</script>
@endsection
