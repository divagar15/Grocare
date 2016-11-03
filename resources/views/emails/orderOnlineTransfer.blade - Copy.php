<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

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
				width: 70%;
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
			

	</style>
</head>
<body>
	<div> 
		<img class="logos" src="{{ URL::to('public/front/images/logo.png') }}" width="100" style="display:block; margin-left:auto; margin-right:auto;" /> 
		<h2>Thank you for your order</h2>
		<p>Your order has been received and is now being processed. Your order details are shown below for your reference:</p>
		
		
		<h3>Order: {{$orderDetail->invoice_no}}</h3>

		<table width="70%"  cellspacing="5" cellpadding="5" class="table">

			<tr>
				<th>Product</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>

			<?php $j=1; $grandTotal=0.00; ?>
            @foreach($orderProducts as $orderPro)
              <?php
                $subTotal = round($orderPro->product_price)*$orderPro->product_qty;
                $grandTotal += $subTotal;
              ?>
              <tr>
              	<td>{{ucwords($orderPro->product_name)}}</td>
              	<td>{{$orderPro->product_qty}}</td>
              	<td>{{$orderSession->order_symbol}} {{round($subTotal)}}</td>
              </tr>

              @if($orderPro->product_type==2)
                <?php
                  $getKitProducts = App\Helper\FrontHelper::getKitProducts($orderPro->id);
                ?>

                @if(isset($getKitProducts) && count($getKitProducts)>0)

                @foreach($getKitProducts as $kitPro)

                <?php
                  $total = round($kitPro->product_price)*$kitPro->product_qty;
                ?>

                	 <tr>
		              	<td>{{ucwords($kitPro->product_name)}}<br/>Included with : {{ucwords($orderPro->product_name)}}</td>
		              	<td>{{$kitPro->product_qty}}</td>
		              	<td>{{$orderSession->order_symbol}} {{round($total)}}</td>
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
		        <td colspan="2"><strong>Cart Subtotal:</strong></td>
		        <td>{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</td>
		    </tr>

		    <tr>
		        <td colspan="2"><strong>Shipping Charge:</strong></td>
		        <td>@if($orderDetail->shipping_charge!='0.00'){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}} @else {{"-"}} @endif</td>
		    </tr>

		    <tr>
		    	<td colspan="2"><strong>Payment Method:</strong></td>
		    	<td>Online Payment</td>
		    </tr>

		    <tr>
		        <td colspan="2"><strong>Order Total:</strong></td>
		        <td>{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}</td>
		    </tr>

		</table>

		<h3>Customer details</h3>

		<p><strong>Email:</strong> {{$orderAddress->billing_email}}</p>
		<p><strong>Phone:</strong> {{$orderAddress->billing_phone}}</p>

<table width="70%"  cellspacing="5" cellpadding="5" class="table" >

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
		<p>Regards,</p>
		<p style="color:#A19D9D">Grocare India<br/>
	</div>
</body>
</html>
