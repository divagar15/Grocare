<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<style type="text/css">

		body {
			font-family:'Lato', sans-serif;
			color: #000;
			font-size:12px;
		}	
		img.logo{
			display: block;
			margin-left: auto;
			margin-right: auto;
		}

		.table
			{
				width: 95%;
				margin-bottom: 20px;
				border-color: gray;
				border-collapse: collapse;
				border-spacing: 0;
			}
			table tr th {
				text-align: left !important;
			}
			.table	tr > td 
			{
				padding: 8px;
				line-height: 1.428571429;
				vertical-align: top;
				border-top: 1px solid #dddddd;	
			}
			table.listoutproducts tbody tr:hover 
			{
				cursor:pointer;
				background-color:#EE5757;
			}

			#bodyContent {
				border-radius:5px; 
				border:1px solid #999; 
				width:80%; 
				margin:0 auto; 
				background: #fff;
			/*	padding-left:15px; 
				padding-right:15px;*/
			}

			#bodyContent h3, #bodyContent h4, #bodyContent p {
				padding-left: 15px;
				padding-right: 15px;
			}

			p {
				color: #737373;
				font-size: 15px;
			}

			#bodyHeader {
				background: #06ACF4;
				width: 100%;
				height: 80px;
			}

			#bodyHeader h2 {
				color:#fff;
				text-align: center;
				margin-top: 0px;
				padding-top: 25px;
				font-size: 25px;
			}

			

	</style>
</head>
<body>
	<div style="background-color: #f5f5f5; padding-top:20px; padding-bottom:20px;"> 
		<img class="logo" src="{{ URL::to('public/front/images/email_logo.png') }}" width="" style="display: block;
			margin-left: auto;
			margin-right: auto;"  /> 
		<br/>
		<div id="bodyContent" style=" border-radius:10px; 
				border:1px solid #dcdcdc; 
				width:70%; 
				margin:0 auto; 
				background: #fdfdfd;">

		<div id="bodyHeader" style="background: #06ACF4; border-top-left-radius:10px; border-top-right-radius:10px; 
				width: 100%;
				height: 80px;">
			<h2 style="color:#fff;
				padding-left: 15px;
				margin-top: 0px;
				padding-top: 25px;
				font-size: 25px;">Thank you for your order</h2>
		</div>

		<p style="padding-left: 15px; padding-right: 15px;">Your order has been received. Your order details are shown below for your reference:</p>
		<p style="padding-left: 15px; padding-right: 15px;">Please deposit the amount into our bank account as mentioned below. After deposit, please send a confirmatory email or SMS or whatsapp on +91 98221 00031, giving your order Ref. No.</p>
<p style="padding-left: 15px; padding-right: 15px;">Your order won’t be shipped until the funds have been deposited in our account.</p>
		
		<h3 style="padding-left: 15px; padding-right: 15px;">Our Bank Details</h3>
		<h4 style="padding-left: 15px; padding-right: 15px;">{{$bankDetails->account_name}}</h4>
		<p style="padding-left: 15px; padding-right: 15px;">Account Number : <strong>{{$bankDetails->account_number}}</strong></p>
		<p style="padding-left: 15px; padding-right: 15px;">Branch : <strong>{{$bankDetails->account_branch}}</strong></p>
		<p style="padding-left: 15px; padding-right: 15px;">IFSC : <strong>{{$bankDetails->account_ifsc}}</strong></p>
		
		<h3 style="padding-left: 15px; padding-right: 15px;">Order: {{$orderDetail->invoice_no}} ({{date('F d, Y',strtotime($orderDetail->order_placed_date))}})</h3>

		<table width="95" border="1" cellspacing="5" cellpadding="5" class="table" style="border-color: #A5A5A5; border-collapse:collapse; margin-left:15px; margin-right:15px; width:95%;">

			<tr>
				<th style="padding:8px;">Product</th>
				<th style="padding:8px;">Quantity</th>
				<th style="padding:8px;">Price</th>
			</tr>

			<?php $j=1; $grandTotal=0.00; ?>
            @foreach($orderProducts as $orderPro)
              <?php
                $subTotal = round($orderPro->product_price)*$orderPro->product_qty;
                $grandTotal += $subTotal;
              ?>
              <tr>
              	<td style="padding:8px;">{{ucwords($orderPro->product_name)}}</td>
              	<td style="padding:8px;">{{$orderPro->product_qty}}</td>
              	<td style="padding:8px;">{{$orderSession->order_symbol}} {{round($subTotal)}}</td>
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
		              	<td style="padding:8px;">{{ucwords($kitPro->product_name)}}<br/>Included with : {{ucwords($orderPro->product_name)}}</td>
		              	<td style="padding:8px;">{{$kitPro->product_qty}}</td>
		              	<td style="padding:8px;">{{$orderSession->order_symbol}} {{round($total)}}</td>
		             </tr>


                @endforeach

                @endif


              @endif


            @endforeach


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

          	<tr>
		        <td colspan="2" style="padding:8px;"><strong>Cart Subtotal:</strong></td>
		        <td style="padding:8px;">{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</td>
		    </tr>

		    <tr>
		        <td colspan="2" style="padding:8px;"><strong>Shipping Charge:</strong></td>
		        <td style="padding:8px;">@if($orderDetail->shipping_charge!='0.00'){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}} @else {{"-"}} @endif</td>
		    </tr>

		    <tr>
		    	<td colspan="2" style="padding:8px;"><strong>Payment Method:</strong></td>
		    	<td style="padding:8px;">Direct Bank Transfer</td>
		    </tr>

		    <tr>
		        <td colspan="2" style="padding:8px;"><strong>Order Total:</strong></td>
		        <td style="padding:8px;">{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}</td>
		    </tr>

		</table>

		<h3 style="padding-left: 15px; padding-right: 15px;">Customer details</h3>

		<p style="padding-left: 15px; padding-right: 15px;"><strong>Email:</strong> {{$orderAddress->billing_email}}</p>
		<p style="padding-left: 15px; padding-right: 15px;"><strong>Phone:</strong> {{$orderAddress->billing_phone}}</p>

<table width="95"  cellspacing="5" cellpadding="5" class="table" border="0" style="width:95%;margin-left:5px; margin-right:5px;">

<tr>
	<th><strong>Billing Address</strong></th>
	<th><strong>Shipping Address</strong></th>
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

<br/>
		<p style="padding-left: 15px; padding-right: 15px;">Regards,</p>
		<p style="color:#A19D9D;padding-left: 15px; padding-right: 15px;">Grocare India<br/></p>
<div class="social">
		    <center>
			   <ul style="list-style-type:none;">
			       <li style="display:inline-block;"> <a href="https://www.facebook.com/grocare" target="_blank" title="Facebook"><img src="{{URL::asset('public/front/images/f.png')}}" style="width:45px; " /> </a> </li>
			       <li style="display:inline-block;"> <a href="https://twitter.com/intent/follow?source=followbutton&variant=1.0&screen_name=grocare_india" target="_blank" title="Twitter"><img src="{{URL::asset('public/front/images/t.png')}}"  style="width:45px;"/> </a> </li>
			       <li style="display:inline-block;"> <a href="https://www.grocare.com/blog" target="_blank" title="Blog"><img src="{{URL::asset('public/front/images/b.png')}}"  style="width:45px;"/> </a> </li>
			   </ul>
			</center>
		 </div>
		</div>
	</div>
</body>
</html>