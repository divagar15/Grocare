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

 /* table td {
    text-align: center;
  }*/
</style>



<div style="display:none;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/983092394/?value={{round($orderDetail->grand_total_inr)}}&amp;currency_code=INR&amp;label=zJ4PCLbKzmQQqpnj1AM&amp;guid=ON&amp;script=0"/>
</div>



@endsection

@section('content')

<div class="container pageStart" style="margin-top:20px;">

<div class="row">

<div class="col-md-12">

@if(Session::has('success_msg'))
<div class="alert alert-success" role="alert" style="margin-top:15px;">
  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  {{Session::get('success_msg')}}
</div>
@endif

</div>

  <?php
    $grandTotal = 0.00;
    $getTrackingThankyouCode = App\Helper\FrontHelper::getTrackingID(2); 
  ?>



  <form id="order-form" method="post" action="{{URL::to('update-cart')}}">
  
  <div class="col-md-12">

  <h2>Thank you for your order</h2>
    <p>Your order has been received. Your order details are shown below for your reference:</p>
		<p>Please deposit the amount into our bank account as mentioned below. After deposit, please send a confirmatory email or SMS or whatsapp on +91 98220 00031, giving your order Ref. No.</p>
<p>Your order won’t be shipped until the funds have been deposited in our account.</p>
    
    <h3>Our Bank Details</h3>
    <h4>{{$bankDetails->account_name}}</h4>
    <p>Account Number : <strong>{{$bankDetails->account_number}}</strong></p>
    <p>Branch : <strong>{{$bankDetails->account_branch}}</strong></p>
    <p>IFSC : <strong>{{$bankDetails->account_ifsc}}</strong></p>
    
    <h3>Order: {{$orderDetail->invoice_no}}</h3>


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

  <div class="col-md-12" id="cartCalculation" style="margin-top:20px;">

      <div class="col-md-6 pull-right">

          <div class="col-md-9">

              <p>CART SUBTOTAL</p>

          </div>

          <div class="col-md-3 pull-right">
            <p><strong>{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</strong></p>
          </div>

          <?php
           /* $shippingCharge = Session::get('active_shipping_charge');
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

      <div class="col-md-6 nopadding">
              <h3>Customer details</h3>

    <p><strong>Email:</strong> {{$orderAddress->billing_email}}</p>
    <p><strong>Phone:</strong> {{$orderAddress->billing_phone}}</p>


      </div>




  </div>

  <div class="col-md-12"  style="margin-top:10px; margin-left:10px;">

<table width="70%" border="0"  cellspacing="3" cellpadding="3" class="table addressTable" >

<tr>
  <th><h3>Billing Address</h3></th>
  <th><h3>Shipping Address</h3></th>
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

  </form>




</div>


</div>



@endsection

@section('customJs')

<script>
//loads up ecommerce plugin google analytics 
ga('require', 'ecommerce');

//Below given code sends transaction information to google analytics
ga('ecommerce:addTransaction', {
  'id': '{{$orderDetail->invoice_no}}',                     // Transaction ID. Required.
 // 'affiliation': 'XXXXXX',   // Affiliation or store name.
  'revenue': '{{round($orderDetail->grand_total_inr)}}',               // Grand Total.
  'shipping': '{{round($orderDetail->shipping_charge_inr)}}',                  // Shipping.
  //'tax': 'XXXXXX'                     // Tax.
});

//Below given code sends item information to google analytics
// It should come as many times as many items there in cart
ga('ecommerce:addItem'
  @foreach($orderProducts as $orderPro)
  , {
  'id': '{{$orderDetail->invoice_no}}',                     // Transaction ID. Required.
  'name': '{{ucwords($orderPro->product_name)}}',    // Product name. Required.
 // 'sku': 'XXXXXX',                 // SKU/code.
 // 'category': 'XXXXXX',         // Category or variation.
  'price': '{{round($orderPro->product_price)}}',                 // Unit price.
  'quantity': '{{round($orderPro->product_qty)}}'                   // Quantity.
}
@endforeach
);

//Finally, once you have configured all your ecommerce data , you send the data to Google Analytics using the ecommerce:send command
ga('ecommerce:send');
</script>

@if(isset($getTrackingThankyouCode))<?php echo $getTrackingThankyouCode; ?>@endif
@endsection