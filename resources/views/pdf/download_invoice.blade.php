<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body,td {
			font-size:15px;
		}		
		
		/* .grocare-address,.shipping-address{
			margin-left:70%;
		}	 */	
		/* .shipping-address{
			margin-left:50%;
		} */	
			
		.page-break {
			page-break-after: always;
		}

	</style>
</head>
<body>
<!-- <div class="page-header"></div>
 -->       

<!--  <div style="margin-left:-50px; margin-right:-50px;  width:100%; height:50px;background:#428bca;">         </div>
 -->  
  <table border="0" width="100%" style="margin-top:-45px; margin-left:-50px; margin-right:-50px;  width:100%;border-collapse: collapse;background:#428bca;">
				 
					<tr style="">

						<td width="40%" style="border:none !important;">
							<img src="{{URL::asset('public/front/images/invoice_logo.png')}}" class="logo" style=" vertical-align:middle; padding-left:10px; 
    display: inline-block !important; margin-left:10px; margin-top:10px;
   "><!-- <h2 style="    color: #fff;
    position: absolute;
    margin-top: -33px;
    margin-left: 62px;
    /* background-color: #FFF; */
    font-size: 24px;"> Grocare India</h2> -->
						</td>
						<td width="60%" style="border:none !important;">
							<p style="color:#fff; text-align:right; padding-right:10px;">
								"Shivalik",Plot No. 14, Gangadham,<br/>
								Market Yard, Pune 411037, India<br/>
								Tel: +91 20 24240170; Fax: +91 20 24247181<br/>
								Helpline: +91 98221 00031<br/>
								Email: info@grocare.com; Web: www.grocare.com
							</p>
						</td>

					</tr>

				</table> 

<div class="warper container-fluid"   style="width:100%;margin:0 auto;">


            
            
                
                <div class="row">




				 <table width="100%" style="margin-top:10px; margin-bottom:10px;">
				 
					<tr>
						<td style="vertical-align:top;margin:0px;"> 
							<h3 style="margin:0px;">Proforma Invoice</h3>
						</td>
						<td>
							<div class="col-md-6 grocare-address"   style="margin-left:40%">
								<strong>Grocare India</strong><br>									
									"Shivalik",Plot No. 14, Gangadham, Market Yard,<br/>
									Pune 411037<br>
									Tel : 98221 00031<br>                      
							</div>
						</td>
					</tr>
					
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
						<tr>
						<td style="vertical-align:top;margin:0px;"> 
							
							<table width="100%">
								<tr>
									<td>
									Proforma Invoice Number :<br>
									Order Date & Time :<br>
									Payment Type :<br>
									</td>
									<td>
									 {{$orderDetail->invoice_no}}<br/>
									 {{date('d-m-Y H:i',strtotime($orderDetail->order_placed_date))}}<br/>
									 {{$payment_text}}
									 </td>
								 </tr>
							 </table>
						</td>
						<td>
							<div class="col-md-6 shipping-address"   style="margin-left:40%">
							  <strong>Shipping Address</strong><br>
							  {{$orderAddress->shipping_name}}<br/>
							  {{$orderAddress->shipping_address1}}, @if($orderAddress->shipping_address2!=''){{$orderAddress->shipping_address2.","}}@endif<br>
							  {{$orderAddress->shipping_city}} - {{$orderAddress->shipping_zip}}, {{$orderAddress->shipping_state}}<br>
							  @if($orderAddress->shipping_country!='IN'){{$countries[$orderAddress->shipping_country]}}<br>@endif
							  </div>
                        </td>
                
              </tr>
			  </table>
			  
                        <table width="100%">
                           <thead style="background:#000;">
                                <tr style="padding:2% 0px;">
                                    <th style="color:#fff;width:50%;padding:10px;text-align:left;border:none;">Product</th>
                                    <th style="color:#fff;width:25%;padding:10px;text-align:left;border:none;">Quantity</th>
                                    <th style="color:#fff;width:25%;padding:10px;text-align:left;border:none;">Total</th>
                                </tr>
                            </thead>  
                            <tbody>
                              <?php $grandTotal = 0.00; ?>
                              @foreach($orderProducts as $orderPro)
                                <?php
                                  $subTotal = round($orderPro->product_price)*$orderPro->product_qty;
                                  $grandTotal += $subTotal;
                                ?>
                                <tr>
                                    <td style="width:50%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{ucwords($orderPro->product_name)}}</td>      
                                    <td style="width:25%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{$orderPro->product_qty}}</td>
                                    <td style="width:25%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{$orderSession->order_symbol}} {{round($subTotal)}}</td>
                                </tr>

                             @if($orderPro->product_type==2)
                              <?php
                                $getKitProducts = App\Helper\AdminHelper::getKitProducts($orderPro->oid,$orderPro->id);
                              ?>

                            @if(isset($getKitProducts) && count($getKitProducts)>0)

                              @foreach($getKitProducts as $kitPro)

                              <?php
                                $total = round($kitPro->product_price)*$kitPro->product_qty;
                              ?>

                              <tr>
                                    <td style="width:25%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{ucwords($kitPro->product_name)}} <br/> Included with : {{ucwords($orderPro->product_name)}}</td>      
                                    <td style="width:25%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{$kitPro->product_qty}}</td>
                                    <td style="width:25%;padding: 10px 5px;border-bottom: 1px #F3E8E8 solid;">{{$orderSession->order_symbol}} {{round($total)}}</td>
                                </tr>

                              @endforeach

                            @endif

                            @endif
                          @endforeach

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

                          <tr>
                            <td style="text-align:right;width:25%;padding: 10px 0px;border-bottom: 1px #F3E8E8 solid;"  colspan="2"><strong>Subtotal : </strong></td>
                            <td style="width:25%;padding: 10px 0px;border-bottom: 1px #F3E8E8 solid;">{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</td>
                          </tr>

                          <tr>
                            <td style="text-align:right;width:25%;padding: 10px 0px;border-bottom: 1px #F3E8E8 solid;"  colspan="2"><strong>Shipping and Handling :</strong> </td>
                            <td style="width:25%;padding: 10px 0px;border-bottom: 1px #F3E8E8 solid;">@if($orderDetail->shipping_charge!='0.00'){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}} @else {{"-"}} @endif</td>
                          </tr>
                          <tr>
                            <td style="text-align:right;" colspan="2"><strong>Order Total :</strong> </td>
                            <td style="width:25%;padding: 10px 0px;border-bottom: 1px #F3E8E8 solid;"><strong>{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}</strong></td>
                          </tr>

                            </tbody>
                        </table>

                         <table width="100%">

                         	<tr>
                         		<td><img src="{{URL::asset('public/front/images/invoice_seal.jpg')}}" /></td>
                         		<td align="right"><img src="{{URL::asset('public/front/images/invoice_sign.jpg')}}" /></td>
                         	</tr>

                         </table>

                    </div>
                </div>
</body>
</html>