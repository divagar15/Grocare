@extends('front.layout.tpl')
@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/front/css/intlTelInput.css')}}" />

<style type="text/css">
  table thead {
    border:1px solid #fff;
  }

  table th,table td {
    padding: 8px !important;
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
    width:30%; 
    text-align: center;
  }

  .qty .glyphicon {
    font-size: 10px;
    color: #ffffff;
   /* background-color: #008DDC;*/
    padding: 3px 3px 3px 3px;
  }

  .qty .glyphicon:hover {
    font-size: 10px;
    color: #ffffff;
   /* background-color: #008DDC;*/
    padding: 3px 3px 3px 3px;
    cursor: pointer;
  }

  
  #gotoStore a:active {
    background: #159FEC !important;
    color:#fff !important;
  }

.address-form p, .address-form div, .address-form a {
  font-weight:bold;
}

#checkoutBtn:hover {
  color:#fff !important;
}

::-webkit-input-placeholder { color:#555 !important;   }
::-moz-placeholder { color:#555 !important; } /* firefox 19+ */
:-ms-input-placeholder { color:#555 !important; } /* ie */
input:-moz-placeholder { color:#555 !important; }

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

.address-form input[type="text"] {
  text-transform: capitalize;
}

.cartBtn, 
.cartBtn:hover {
background:#f19b2c !important;
color:#fff !important;
}

.address-form .form-group {
    margin-bottom: 0px !important;
}

 /* table td {
    text-align: center;
  }*/
  
  #linksSection_disclaimer {
    background: #fff;
    margin: 1% auto !important;
   
}
/*#subscribeSection {
    background: #008ddc;
    height: 70px;
    z-index: 0;
    position: relative;
}*/
.customGradientBtns {
color: #2e6da4 !important;
font-weight: bold !important;
 border: none !important;
    border-radius: 0px !important;
	background-color:#fff !important;
}

   @media screen and (max-width: 768px) {
    .pay-mob {position: relative !important; text-align: -webkit-center !important; }
		 .btn-mob{margin-top:10% !important;}
		 .btn-mob1{ margin-bottom: 3% !important;}
		 .btn-mob2 h5{ text-align:center !important; margin-top: 0% !important;}
		 .btn-mob3 {margin-top:6% !important;}
		 .mob-ly {display:none !important;}
		 .mob1-ly{display:block !important;}
     }



</style>

@endsection

@section('content')

<div class="container pageStart" style="margin-top:60px; padding-top: 0px;">

<?php
  
  $billingName = Input::old('billing_name');
  $billingAddress1 = Input::old('billing_address_1');
  $billingAddress2 = Input::old('billing_address_2');
  $billingCity = Input::old('billing_city');
  $billingState = Input::old('billing_state');
  $billingCountry = Input::old('billing_country');
  $billingPostcode = Input::old('billing_postcode');
  $billingEmail = Input::old('email');
  $billingPhone = trim(str_replace(' ','',Input::old('phone')));
            
  $shipTo = Input::old('ship_to');

  $shippingName = Input::old('shipping_name');
  $shippingAddress1 = Input::old('shipping_address_1');
  $shippingAddress2 = Input::old('shipping_address_2');
  $shippingCity = Input::old('shipping_city');
  $shippingState = Input::old('shipping_state');
  $shippingCountry = Input::old('shipping_country');
  $shippingPostcode = Input::old('shipping_postcode');
  $shippingEmail = Input::old('semail');
  $shippingPhone = trim(str_replace(' ','',Input::old('sphone')));

  $oldInputCount = count(Input::old());


?>

<div class="row">

    <div class="col-md-12 btn-mob1 mob1-ly" style="margin-top: 8%;text-align: center; display:none;">
            <input type="button" name="submitAction" id="checkoutBtn" data-type="2" class="updateBtnAction btn btn-default cart-btn cartBtn" value="PROCEED TO PAYMENT" style="">
       </div>




<div class="col-md-12">

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
    @if($oldInputCount>0)

      <ul>

        @if(empty($billingName))

          <li>Billing Name should not be empty</li>

        @endif

        @if(empty($billingAddress1))

          <li>Billing Address should not be empty</li>

        @endif


        @if(empty($billingCity))

          <li>Billing City should not be empty</li>

        @endif

        @if(empty($billingState))

          <li>Billing State should not be empty</li>

        @endif

        @if(empty($billingCountry))

          <li>Billing Country should not be empty</li>

        @endif

        @if(empty($billingPostcode) && Session::get('active_currency')=="INR")

          <li>Billing Postcode / Zip should not be empty</li>

        @endif

        @if(empty($billingEmail))

          <li>Billing E-Mail should not be empty</li>

        @endif

        @if (filter_var($billingEmail, FILTER_VALIDATE_EMAIL) === false) 

          <li>Billing E-Mail should be a valid email address</li>

        @endif

        @if(empty($billingPhone))

          <li>Billing Phone should not be empty</li>

        @endif

        @if(!is_numeric($billingPhone)) 

          <li>Billing Phone should be numeric</li>

        @endif

        @if(!empty($shipTo) && $shipTo==1)


          @if(empty($shippingName))

            <li>Shipping Name should not be empty</li>

          @endif

          @if(empty($shippingAddress1))

            <li>Shipping Address should not be empty</li>

          @endif


          @if(empty($shippingCity))

            <li>Shipping City should not be empty</li>

          @endif

          @if(empty($shippingState))

            <li>Shipping State should not be empty</li>

          @endif

          @if(empty($shippingCountry))

            <li>Shipping Country should not be empty</li>

          @endif

          @if(empty($shippingPostcode) && Session::get('active_currency')=="INR")

            <li>Shipping Postcode / Zip should not be empty</li>

          @endif

          @if(empty($shippingEmail))

            <li>Shipping E-Mail should not be empty</li>

          @endif

          @if (filter_var($shippingEmail, FILTER_VALIDATE_EMAIL) === false) 

            <li>Shipping E-Mail should be a valid email address</li>

          @endif

          @if(empty($shippingPhone))

            <li>Shipping Phone should not be empty</li>

          @endif

          @if(!is_numeric($shippingPhone)) 

            <li>Shipping Phone should be numeric</li>

          @endif



        @endif
             

      </ul>

    @endif
  </div>
  @endif

</div>

  <?php
    $grandTotal = 0.00;
  ?>

  @if(isset($orderProducts) && count($orderProducts)>0)


    
  
  <div class="col-md-12">


        <form id="order-form" method="post" action="{{URL::to('update-cart')}}">


      <div class="col-md-5 btn-mob3" style="background-color: #00afec; margin-top: 3%;">


<!--       <form id="order-form" method="post" action="#">
 -->  
  <div class="col-md-12 col-xs-12">


          <table class="table table-responsive"style="margin-top:15px;background-color: #00afec;color: #fff;">

            <thead>
              <tr>
                <th style="width:40%;"><strong style="margin-left:30px;">Product</strong></th>
                <!--<th class="centralize" style="width:20%; border-left:1px solid #ccc;">Price</th>-->
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
                <div class="col-md-8 col-xs-10 no-padding">
                <strong style="margin-left:0px;">{{ucwords($orderPro->product_name)}}</strong> 
                @if($orderPro->product_type==2)
                  <br/>
                  @if($orderPro->product_course==1)
                    <span  style="margin-left:0px;">(1.5 Months)</span>
                  @elseif($orderPro->product_course==2)
                    <span  style="margin-left:0px;">(3 Months)</span>
                  @elseif($orderPro->product_course==1)
                    <span  style="margin-left:0px;">(Full Treatment)</span>
                  @endif
                @endif
                </div>
                <div class="col-md-4 col-xs-2 no-padding">
                  <a href="javascript:void(0)" class="deleteProduct" data-id="{{$orderPro->id}}"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"style="color:#fff;"></span>
                </div>
                </td>
                <!--<td class="centralize" style="width:20%; border-left:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($orderPro->product_price)}}</td>-->
                <td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
                  <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field='quantity{{$j}}'></span>
                  <input type='text' name='quantity{{$j}}' value='{{$orderPro->product_qty}}' class='qty' style="background-color: #00afec;"/>
                  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field='quantity{{$j}}'></span> 
                  <input type="hidden" name="originalQuantity{{$j}}" value="{{$orderPro->product_qty}}"style="background-color: #00afec;">
                  <input type="hidden" name="product_id{{$j}}" value="{{$orderPro->product_id}}"style="background-color: #00afec;">
                  <input type="hidden" name="product_course{{$j}}" value="{{$orderPro->product_course}}"style="background-color: #00afec;">
                  <input type="hidden" name="product_price{{$j}}" value="{{$orderPro->product_price}}"style="background-color: #00afec;">
                  <input type="hidden" name="product_type{{$j}}" value="{{$orderPro->product_type}}"style="background-color: #00afec;">
				  
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
                <strong style="margin-left:0px;">{{ucwords($kitPro->product_name)}}</strong> <br/>
                <span style="margin-left:0px;">Included with : {{ucwords($orderPro->product_name)}}</span>
                </div>
                
                </td>
                <!--<td class="centralize" style="width:20%; border-left:1px solid #ccc;">{{$orderSession->order_symbol}} {{round($kitPro->product_price)}}</td>-->
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




          <input type="hidden" name="sub_total" value="{{$grandTotal}}">
          <input type="hidden" name="shipping_charge" value="{{$shippingCharge}}">
          <input type="hidden" name="grand_total" value="{{$orderTotal}}">

         
            <thead>
              <tr>
                <th style="width:60%;"><strong style="margin-left:30px;">Shipping</strong></th>
              
              
                <th class="" style="width:40%; text-align:right;"colspan="2">@if($shippingCharge!=''){{$orderSession->order_symbol}} {{round($shippingCharge)}} @else {{"-"}} @endif</th>
              </tr>
            </thead>

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
           <thead>
              <tr>
                <th style="width:60%;"><strong style="margin-left:30px;">Total</strong></th>
              
                
                <th class="" style="width:40%; text-align:right;" colspan="2">{{$orderSession->order_symbol}} {{round($orderTotal)}}</th>
              </tr>
            </thead>
			 
          </table>



  </div>
  <div class="col-md-12" id="cartCalculation" style="">
       <p style="color:#fff;"><strong>Delivery Time</strong>: You will get the items within 4-6 days </p>
   </div>
  <div class="col-md-12" id="cartCalculation" style="margin-top:20px;">
       
      <div class="col-md-6 no-padding">
         
         <p id="gotoStore" style="text-align:left; margin-top: -15px; margin-bottom: 15px;"><a style="font-size:10px;" href="{{URL::to('products')}}" class="btn btn-primary btn-bg customGradientBtns">Add More Items</a></p>

      </div>

      <div class="col-md-6 no-padding" style="display:none;">

          <div class="col-md-12 no-padding">
            <button type="button" style="margin-top: -15px; margin-bottom: 15px;" data-type="1" class="updateBtnAction btn btn-default pull-right cart-btn updateCart"><img src="{{URL::to('public/front/images/icons/update.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>UPDATE CART</button>
          </div>

         <!--  <div class="col-md-12 pull-right" style="margin-top:10px;">
            <a href="{{URL::to('checkout')}}" class="btn btn-default cart-btn cartBtn"><img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>PROCEED TO CHECKOUT</a>
          </div> -->

      </div>
<div class="clearfix"></div>






  </div>

<!--   </form>
 -->
      



          <div id="cartCalculation">


          
          

          <div class="col-md-12" style="margin-top:10px;display:none;" id="errorForm">
            <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><!-- <span aria-hidden="true">×</span><span class="sr-only">Close</span> --></button>
            <strong>Please fill out all the required fields</strong>
          </div>
         </div>

              </div>

      </div>

      </form>

      <form class="form-inline address-form" id="checkoutform" name="checkoutform" method="post">
	  
	  <div class="col-md-4" id="addressDetail">
    
    <div class="col-md-6">

    <h5>Billing Address</h5>
    
    </div>
    
    <div class="col-md-6">

    @if(!isset(Auth::user()->id))<!-- <p>Have an account? <a href="{{URL::to('login')}}">Login</a></p> -->
    <!--<p style="text-align:center;">(or)</p><p style="text-align:center;">Login through</p>-->@endif
    
    </div>

    <div class="col-md-12">
    <h6 style="color:#f00;"><strong>Fields marked with * are mandatory</strong></h6>
    </div> 

    @if(!isset(Auth::user()->id))

    <!--<div class="col-md-12" style="margin-bottom:45px; padding-right:90px;">

      <a href="{{URL::to('auth/social/facebook')}}">
      <div class="col-md-1 pull-right">
        <img src="{{URL::to('public/front/images/facebook.png')}}" style="width:32px; height:32px;" />
      </div>
      </a>

      <a href="{{URL::to('auth/social/twitter')}}">
      <div class="col-md-1 pull-right">
        <img src="{{URL::to('public/front/images/twitter.png')}}" style="width:32px; height:32px;" />
      </div>
      </a>

      <a href="{{URL::to('auth/social/google')}}">
      <div class="col-md-1 pull-right">
        <img src="{{URL::to('public/front/images/google+.png')}}" style="width:32px; height:32px;" />
      </div>
      </a>

    </div>-->

    @endif
    
    
    <div class="col-md-12" style="clear: both;">
    
    <?php
      //var_dump(Input::all());
    ?>
    
      
        <div id="billingAddress">
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="country"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>
                <input type="hidden" class="form-control"  id="billing_country" name="billing_country" placeholder="Country" value="{{Session::get('active_country')}}">
              
                  <select class="form-control" id="billing_countrys" disabled name="billing_countrys" onchange="checkCountry();">
                    <option value="">Select Country</option>
                    @if(isset($countries) && !empty($countries))
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}" @if(Session::get('active_country')==$key) selected @endif>{{$value}}</option>
                      @endforeach
                    @endif
                  </select>
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="name"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('billing_name')) value="{{Input::old('billing_name')}}" @else @if(isset($billing_address->name)) value="{{$billing_address->name}}" @else value="@if(isset(Auth::user()->name)){{Auth::user()->name}}@endif" @endif @endif id="billing_name" name="billing_name" placeholder="Name *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="address_1"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('billing_address_1')) value="{{Input::old('billing_address_1')}}" @else @if(isset($billing_address->address)) value="{{$billing_address->address}}" @endif @endif id="billing_address_1" name="billing_address_1" placeholder="Address *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="address_2"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('billing_address_2')) value="{{Input::old('billing_address_2')}}" @else  @if(isset($billing_address->address2)) value="{{$billing_address->address2}}" @endif @endif id="billing_address_2" name="billing_address_2" placeholder="Address Line 2">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="city"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('billing_city')) value="{{Input::old('billing_city')}}" @else  @if(isset($billing_address->city)) value="{{$billing_address->city}}" @endif @endif id="billing_city" name="billing_city" placeholder="Town / City *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="state"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control"  @if(Input::old('billing_state')) value="{{Input::old('billing_state')}}" @else @if(isset($billing_address->state)) value="{{$billing_address->state}}" @endif @endif id="billing_state" name="billing_state" placeholder="State *">
              </div>
              </div>
            </div>
            
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="postcode"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('billing_postcode')) value="{{Input::old('billing_postcode')}}" @else @if(isset($billing_address->postcode)) value="{{$billing_address->postcode}}" @endif @endif id="billing_postcode" name="billing_postcode" placeholder="Postcode/Zip">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="email"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
                <input type="email" class="form-control" @if(Input::old('email')) value="{{Input::old('email')}}" @else value="@if(isset(Auth::user()->email)){{Auth::user()->email}}@endif" @endif id="email" name="email" placeholder="Email *">
              </div>
              </div>
            </div>
            
              <label style="margin-top:5px;">Mobile Number</label>

            <div class="form-group">
              <div class="col-md-12 no-padding">
              <!--<label class="sr-only" for="phone"></label>-->
              <!--<div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>-->
                
                <input type="text" class="form-control phone" @if(Input::old('phone')) value="{{Input::old('phone')}}" @else value="@if(isset(Auth::user()->phone)){{Auth::user()->phone}}@endif" @endif id="phone" name="phone" placeholder="Phone *" style="width:100% !important;">
            <!--  </div>-->
            <input type="hidden" name="dialCode" id="dialCode" value="">
              </div>
            </div>

          </div>

          
             <div class="form-group" style="box-shadow:none;">
              
              @if(!isset(Auth::user()->id))
              <!-- <div class="col-md-6 no-padding">
                <input type="checkbox" name="create_account" id="create_account" value="1" /> Create an account
              </div> -->
              @endif

              
              
              <div class="col-md-12 no-padding" style="margin-top:10px;">
                <input type="checkbox" name="ship_to" id="ship_to" value="1" @if(!empty($shipTo) && $shipTo==1) checked @endif /> <strong>Ship to a different address?</strong>
              </div>
              
            
            </div>



      <div id="shippingAddress" @if(!empty($shipTo) && $shipTo==1) @else style="display:none" @endif>

          <div class="form-group" style="background:none; box-shadow:none;">
            <div class="col-md-6 no-padding">

            <h5 style="text-align:left;">Shipping Address</h5>
            
            </div>
          </div>


            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="country"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>

                <select class="form-control" id="shipping_country" name="shipping_country" onchange="checkCountry();">
                    <option value="">Select Country *</option>
                    @if(isset($countries) && !empty($countries))
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}" @if(!empty($shippingCountry) && $shippingCountry==$key) selected @else  @if(isset($shipping_address->country) && $shipping_address->country==$key) selected @endif @endif>{{$value}}</option>
                      @endforeach
                    @endif
                </select>
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="name"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_name')) value="{{Input::old('shipping_name')}}" @else @if(isset($shipping_address->name)) value="{{$shipping_address->name}}" @else value="@if(isset(Auth::user()->name)){{Auth::user()->name}}@endif" @endif @endif id="shipping_name" name="shipping_name" placeholder="Name *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="address_1"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_address_1')) value="{{Input::old('shipping_address_1')}}" @else @if(isset($shipping_address->address)) value="{{$shipping_address->address}}" @endif @endif id="shipping_address_1" name="shipping_address_1" placeholder="Address *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="address_2"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_address_2')) value="{{Input::old('shipping_address_2')}}" @else  @if(isset($shipping_address->address2)) value="{{$shipping_address->address2}}" @endif @endif id="shipping_address_2" name="shipping_address_2" placeholder="Address Line 2">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="city"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_city')) value="{{Input::old('shipping_city')}}" @else @if(isset($shipping_address->city)) value="{{$shipping_address->city}}" @endif @endif id="shipping_city" name="shipping_city" placeholder="Town / City *">
              </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="state"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_state')) value="{{Input::old('shipping_state')}}" @else @if(isset($shipping_address->state)) value="{{$shipping_address->state}}" @endif @endif id="shipping_state" name="shipping_state" placeholder="State *">
              </div>
              </div>
            </div>
            
            
            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="postcode"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
                <input type="text" class="form-control" @if(Input::old('shipping_postcode')) value="{{Input::old('shipping_postcode')}}" @else @if(isset($shipping_address->postcode)) value="{{$shipping_address->postcode}}" @endif @endif id="shipping_postcode" name="shipping_postcode" placeholder="Postcode/Zip">
              </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="email"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
                <input type="email" class="form-control" @if(Input::old('semail')) value="{{Input::old('semail')}}" @else value="@if(isset(Auth::user()->email)){{Auth::user()->email}}@endif" @endif id="semail" name="semail" placeholder="Email *">
              </div>
              </div>
            </div>
            <label style="margin-top:5px;">Mobile Number</label>
            <div class="form-group">
              <div class="col-md-12 no-padding">
                <input type="text" class="form-control phone" @if(Input::old('sphone')) value="{{Input::old('sphone')}}" @else  value="@if(isset(Auth::user()->phone)){{Auth::user()->phone}}@endif" @endif id="sphone" name="sphone" placeholder="Phone *" style="width:100% !important;">
            
            <input type="hidden" name="sdialCode" id="sdialCode" value="">
              </div>
            </div>
            
        </div>
            
           

        @if(!isset(Auth::user()->id))
        <!-- <div id="accountCreate" style="display:none">

            <div class="form-group">
              <div class="col-md-12 no-padding">
              <label class="sr-only" for="password"></label>
              <div class="input-group">
                <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Account Password">
              </div>
              </div>
            </div>
            
        </div> -->
        @endif
         
    </div>

      </div>   


       
      
    
    
	  
	  
	  
	  <div class="col-md-3 pay-mob"style="position:relative; margin-top: 7%;">
	     <div class="col-md-12" style="">
		 <div class="col-md-12 btn-mob1 mob-ly" style="margin-top:5%;margin-bottom:10%; ">
            <input type="button" name="submitAction" id="checkoutBtn" data-type="2" class="updateBtnAction btn btn-default cart-btn cartBtn" value="PROCEED TO PAYMENT" />
            <!-- <input type="submit" name="submitAction" value="Submit"> -->
            <!-- <a href="javascript:void(0)" class="btn btn-default cart-btn" id="checkoutBtn" style="background: #159FEC !important;
    color: #fff;"> --><!--<img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>--><!-- PROCEED TO PAYMENT</a> -->
          </div>  
           <div class="col-md-12 btn-mob2">
            <h5 style="font-size:18px;text-align:left; margin-top: 15%;">Payment Information</h5>
          </div>
          <div class="col-md-12">
            @if(Session::get('active_currency')=="INR")
            <input type="radio" name="payment_type" id="card" checked  value="1" /> Credit Card/Debit Card/Netbanking
            @else
            <input type="hidden" name="payment_type" id="card"  value="3" /> <strong>Credit Card/Debit Card/Netbanking</strong>
            @endif
          </div>
          @if(Session::get('active_currency')=="INR")
          <div class="col-md-12" style="margin-top:10px;">
            <input type="radio" name="payment_type" id="bank"  value="2"/> Direct Bank Transfer
          </div>
          @endif
          <?php /* ?>
          <div class="col-md-12" style="margin-top:10px;">
            <label class="control-label">Courier Preference</label>
            <div class="clearfix"></div>
            <select class="form-control" id="courier_preference" name="courier_preference" style="width:100%;">
              <option value="">Select Courier</option>
                    @if(isset($couriers) && !empty($couriers))
                      @foreach($couriers as $cour)
                        <option value="{{$cour->id}}">{{ucwords($cour->name)}}</option>
                      @endforeach
                    @endif
                </select>
          </div>
          <?php */ ?>
          <!--<div class="col-md-12" style="margin-top:10px;">
            <label class="control-lable">Order Notes</label>
            <textarea name="order_note" id="order_note" style="width:100%; height:120px;"></textarea>
          </div>-->
          <div class="col-md-12 btn-mob" style="margin-top:30%;">
            <input type="button" name="submitAction" id="checkoutBtn" data-type="2" class="updateBtnAction btn btn-default cart-btn cartBtn" value="PROCEED TO PAYMENT" />
            <!-- <input type="submit" name="submitAction" value="Submit"> -->
            <!-- <a href="javascript:void(0)" class="btn btn-default cart-btn" id="checkoutBtn" style="background: #159FEC !important;
    color: #fff;"> --><!--<img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>--><!-- PROCEED TO PAYMENT</a> -->
          </div>
          </div>
	  
	  </div>

</form>

  </div>



  @else 

      <div class="titleh3 center">Your cart is empty</div>

      <p id="gotoStore" style="text-align:center;"><a href="{{URL::to('products')}}" class="btn btn-default btn-bg customGradientBtn know_1">Go to Store</a></p>


  @endif


</div>


</div>



@endsection

@section('customJs')
<script src="{{URL::asset('public/front/js/intlTelInput.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){


    $(".updateBtnAction").click(function(){
      var type = $(this).data("type");
      if(type==1) {
        $("#checkoutform").attr("action", "{{URL::to('update-cart')}}");
        $("#checkoutform").submit();
       // $('#checkoutform').trigger('submit');
      } else if(type==2) {
        $("#checkoutform").attr("action", "{{URL::to('checkout')}}");;
        $("#checkoutform").submit();
      }
      return false;
    });

    $('#ship_to').click(function() {
      if($('#ship_to').is(":checked")) {
        $('#shippingAddress').slideDown();
      } else {
        $('#shippingAddress').slideUp();
      }
    }); 

    $('#create_account').click(function() {
      if($('#create_account').is(":checked")) {
        $('#accountCreate').slideDown();
      } else {
        $('#accountCreate').slideUp();
      }
    }); 

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
            $('input[name='+fieldName+']').val(1);
        }
        $('#order-form').submit();
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
            if((currentVal-1)==0) {
              $('input[name='+fieldName+']').val(1);
            } else {
              $('input[name='+fieldName+']').val(currentVal - 1);
            }
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(1);
        }
        $('#order-form').submit();
    });


      $("#phone").intlTelInput({// allowDropdown: false,
      // autoHideDialCode: false,
       autoPlaceholder: false,
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
       initialCountry: "@if(Input::old('billing_country')){{strtolower(Input::old('billing_country'))}}@else{{strtolower(Session::get("active_country"))}}@endif",
      // nationalMode: false,
      // numberType: "MOBILE",
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
       preferredCountries: ['strtolower({{Session::get("active_country")}})'],
       separateDialCode: true,
      utilsScript: "{{URL::asset('public/front/js/utils.js')}}"
    });

      var countryData = $("#phone").intlTelInput("getSelectedCountryData");
  $('#dialCode').val(countryData.dialCode);

    $("#sphone").intlTelInput({// allowDropdown: false,
      // autoHideDialCode: false,
       autoPlaceholder: false,
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
       initialCountry: "@if(Input::old('shipping_country')){{strtolower(Input::old('shipping_country'))}}@else{{strtolower(Session::get("active_country"))}}@endif",
      // nationalMode: false,
      // numberType: "MOBILE",
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
       preferredCountries: ['strtolower({{Session::get("active_country")}})'],
       separateDialCode: true,
      utilsScript: "{{URL::asset('public/front/js/utils.js')}}"
    });


  var countryData = $("#sphone").intlTelInput("getSelectedCountryData");
  $('#sdialCode').val(countryData.dialCode);

  });
</script>
@endsection
