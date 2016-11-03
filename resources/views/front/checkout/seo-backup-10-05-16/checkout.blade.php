@extends('front.layout.tpl')
@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/front/css/intlTelInput.css')}}" />

<style type="text/css">

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

</style>

@endsection

@section('content')

<div class="container pageStart" style="margin-top:60px;">

<div class="row">

  <form class="form-inline address-form" id="checkout-form" method="post">
  
  <div class="col-md-12">


      <div class="col-md-6" id="addressDetail">
	  
		<div class="col-md-6">

		<h5>Billing Address</h5>
		
		</div>
		
		<div class="col-md-6">

		@if(!isset(Auth::user()->id))<p>Have an account? <a href="{{URL::to('login')}}">Login</a></p>
		<!--<p style="text-align:center;">(or)</p><p style="text-align:center;">Login through</p>-->@endif
		
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
							  <input type="text" class="form-control" @if(isset($billing_address->name)) value="{{$billing_address->name}}" @else value="@if(isset(Auth::user()->name)){{Auth::user()->name}}@endif" @endif required id="billing_name" name="billing_name" placeholder="Name">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="address_1"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control" @if(isset($billing_address->address)) value="{{$billing_address->address}}" @endif required id="billing_address_1" name="billing_address_1" placeholder="Address">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="address_2"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($billing_address->address2)) value="{{$billing_address->address2}}" @endif id="billing_address_2" name="billing_address_2" placeholder="Address Line 2">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="city"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($billing_address->city)) value="{{$billing_address->city}}" @endif required id="billing_city" name="billing_city" placeholder="Town / City">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="state"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control" required  @if(isset($billing_address->state)) value="{{$billing_address->state}}" @endif id="billing_state" name="billing_state" placeholder="State">
							</div>
						  </div>
						</div>
						
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="postcode"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($billing_address->postcode)) value="{{$billing_address->postcode}}" @endif required id="billing_postcode" name="billing_postcode" placeholder="Postcode/Zip">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="email"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
							  <input type="email" class="form-control" value="@if(isset(Auth::user()->email)){{Auth::user()->email}}@endif" required id="email" name="email" placeholder="Email">
							</div>
						  </div>
						</div>
						
    					<label>Mobile Number</label>

						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<!--<label class="sr-only" for="phone"></label>-->
							<!--<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></div>-->
							  
							  <input type="text" class="form-control phone" number="true" value="@if(isset(Auth::user()->phone)){{Auth::user()->phone}}@endif" required id="phone" name="phone" placeholder="" style="width:100% !important;">
						<!--	</div>-->
						<input type="hidden" name="dialCode" id="dialCode" value="">
						  </div>
						</div>

					</div>

			<div id="shippingAddress" style="display:none;">

					<div class="form-group" style="background:none; box-shadow:none;">
						<div class="col-md-6">

						<h5>Shipping Address</h5>
						
						</div>
					</div>


						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="country"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></div>
<!-- 							  <input type="text" class="form-control" required id="shipping_country" name="shipping_country" placeholder="Country">
 -->
 								<select class="form-control" required id="shipping_country" name="shipping_country" onchange="checkCountry();">
 								  	<option value="">Select Country</option>
 								  	@if(isset($countries) && !empty($countries))
 								  		@foreach($countries as $key=>$value)
 								  			<option value="{{$key}}"  @if(isset($shipping_address->country) && $shipping_address->country==$key) selected @endif>{{$value}}</option>
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
							  <input type="text" class="form-control" @if(isset($shipping_address->name)) value="{{$shipping_address->name}}" @else value="@if(isset(Auth::user()->name)){{Auth::user()->name}}@endif" @endif required id="shipping_name" name="shipping_name" placeholder="Name">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="address_1"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control" @if(isset($shipping_address->address)) value="{{$shipping_address->address}}" @endif required id="shipping_address_1" name="shipping_address_1" placeholder="Address">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="address_2"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($shipping_address->address2)) value="{{$shipping_address->address2}}" @endif id="shipping_address_2" name="shipping_address_2" placeholder="Address Line 2">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="city"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($shipping_address->city)) value="{{$shipping_address->city}}" @endif required id="shipping_city" name="shipping_city" placeholder="Town / City">
							</div>
						  </div>
						</div>
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="state"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($shipping_address->state)) value="{{$shipping_address->state}}" @endif required id="shipping_state" name="shipping_state" placeholder="State">
							</div>
						  </div>
						</div>
						
						
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="postcode"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></div>
							  <input type="text" class="form-control"  @if(isset($shipping_address->postcode)) value="{{$shipping_address->postcode}}" @endif required id="shipping_postcode" name="shipping_postcode" placeholder="Postcode/Zip">
							</div>
						  </div>
						</div>

						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="email"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></div>
							  <input type="email" class="form-control" value="@if(isset(Auth::user()->email)){{Auth::user()->email}}@endif" required id="semail" name="semail" placeholder="Email">
							</div>
						  </div>
						</div>
						<label>Mobile Number</label>
						<div class="form-group">
						  <div class="col-md-12 no-padding">
							  <input type="text" class="form-control phone" number="true" value="@if(isset(Auth::user()->phone)){{Auth::user()->phone}}@endif" required id="sphone" name="sphone" placeholder="" style="width:100% !important;">
						
						<input type="hidden" name="sdialCode" id="sdialCode" value="">
						  </div>
						</div>
						
				</div>
						
						<div class="form-group" style="box-shadow:none;">
							
							@if(!isset(Auth::user()->id))
							<div class="col-md-6 no-padding">
								<input type="checkbox" name="create_account" id="create_account" value="1" /> Create an account
							</div>
							@endif
							
							<div class="col-md-6 no-padding">
								<input type="checkbox" name="ship_to" id="ship_to" value="1" /> Ship to a different address?
							</div>
							
						
						</div>

				@if(!isset(Auth::user()->id))
				<div id="accountCreate" style="display:none">

						<div class="form-group">
						  <div class="col-md-12 no-padding">
							<label class="sr-only" for="password"></label>
							<div class="input-group">
							  <div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
							  <input type="password" class="form-control" required id="password" name="password" placeholder="Account Password">
							</div>
						  </div>
						</div>
						
				</div>
				@endif
						


				
			
		
		
		</div>

      </div>


      <div class="col-md-6" style="margin-top:60px;">

          <div id="cartCalculation">

                  <div class="col-md-9">

                      <p>CART SUBTOTAL</p>

                  </div>

                  <div class="col-md-3 pull-right">
                    <p><strong>{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</strong></p>
                  </div>

                  <div class="col-md-9">

                      <p>SHIPPING AND HANDLING <!-- (*Flat Rate) --></p>

                  </div>

                  <div class="col-md-3 pull-right">
                    <p><strong>@if($orderDetail->shipping_charge==0.00){{"-"}}@else{{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}}@endif</strong></p>
                  </div>

                  <div class="col-md-12" style="border-top:1px solid #ccc; margin-bottom:10px;">
                  </div>

                  <div class="col-md-9">

                      <p><strong>ORDER TOTAL</strong></p>

                  </div>

                  <div class="col-md-3 pull-right">
                    <p><strong>{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}</strong></p>
                  </div>
				  
				  <div class="col-md-12" style="margin-top:20px;">
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
					<div class="col-md-12" style="margin-top:10px; text-align:right;">
						<a href="javascript:void(0)" class="btn btn-default cart-btn" id="checkoutBtn" style="background: #159FEC !important;
    color: #fff;"><!--<img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>-->PROCEED TO PAYMENT</a>
					</div>
				  </div>

				  <div class="col-md-12" style="margin-top:10px;display:none;" id="errorForm">
				  	<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><!-- <span aria-hidden="true">×</span><span class="sr-only">Close</span> --></button>
						<strong>Please fill out all the required fields</strong>
					</div>
				 </div>

              </div>

      </div>



  </div>

</form>
  
</div>


</div>



@endsection

@section('customJs')

<script src="{{URL::asset('public/front/js/intlTelInput.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){
	  
	$("#phone").intlTelInput({// allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: false,
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
       initialCountry: "{{strtolower(Session::get("active_country"))}}",
      // nationalMode: false,
      // numberType: "MOBILE",
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
       preferredCountries: ['strtolower({{Session::get("active_country")}})'],
       separateDialCode: true,
      utilsScript: "{{URL::asset('public/front/js/utils.js')}}"
    });
	
$("#phone").on("countrychange", function(e, countryData) {
	$('#dialCode').val(countryData.dialCode);
		//console.log(countryData);
  // do something with countryData
  //alert('ok');
});

	var countryData = $("#phone").intlTelInput("getSelectedCountryData");
	$('#dialCode').val(countryData.dialCode);


/*
$('#phone').blur(function(){
	var checkPhone = $("#phone").intlTelInput("isValidNumber");
	if(!checkPhone) {
		$('#phone').addClass('error');
	} else {
		$('#phone').removeClass('error');
	}
});*/



	$("#sphone").intlTelInput({// allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: false,
      // dropdownContainer: "body",
      // excludeCountries: ["us"],
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
       initialCountry: "{{strtolower(Session::get("active_country"))}}",
      // nationalMode: false,
      // numberType: "MOBILE",
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
       preferredCountries: ['strtolower({{Session::get("active_country")}})'],
       separateDialCode: true,
      utilsScript: "{{URL::asset('public/front/js/utils.js')}}"
    });
	
$("#sphone").on("countrychange", function(e, countryData) {
	$('#sdialCode').val(countryData.dialCode);
		//console.log(countryData);
  // do something with countryData
  //alert('ok');
});

	var countryData = $("#sphone").intlTelInput("getSelectedCountryData");
	$('#sdialCode').val(countryData.dialCode);


/*$('#sphone').blur(function(){
	var checkPhone = $("#sphone").intlTelInput("isValidNumber");
	if(!checkPhone) {
		$('#sphone').addClass('error');
	} else {
		$('#sphone').removeClass('error');
	}
});*/
	  
	  $.validator.addMethod("phoneValidate", function (value, element) {
        
    }, 'Password and Confirm Password should be same');
	
	jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
}, "Letters only please");

    $('#checkout-form').validate({
		 rules: {
			billing_name: {
				alphanumeric: true
			},
			shipping_name: {
				alphanumeric: true
			}
		},
	});

    $('#checkoutBtn').click(function(){
    	if (!$("#checkout-form").valid()) {
    		//$("#errorForm").show().delay( 10000 ).hide();
    		  var $div2 = $('#errorForm');
    		  $div2.fadeIn();

			  setTimeout( function(){
			    $div2.fadeOut();
			  }, 5000);
    	}
    });
	


/*    $('#checkout-form')
        .find('[name="phone"]')
            .intlTelInput({
                utilsScript: '{{URL::asset("public/front/js/utils.js")}}',
                autoPlaceholder: true,
                preferredCountries: ['fr', 'us', 'gb']
            });*/
			


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

    $('#checkoutBtn').click(function() {
    	if ($("#checkout-form").valid()) {
    		$('#checkout-form').submit();
    	}

    });
	FormValidation.Validator.intPhoneNumber = {
        html5Attributes: {
            message: 'message',
            autoplaceholder: 'autoPlaceholder',
            preferredcountries: 'preferredCountries',
            utilsscript: 'utilsScript'
        },

        init: function(validator, $field, options) {
            // Determine the preferred countries
            var autoPlaceholder    = options.autoPlaceholder === true || options.autoPlaceholder === 'true',
                preferredCountries = options.preferredCountries || 'us';
            if ('string' === typeof preferredCountries) {
                preferredCountries = preferredCountries.split(',');
            }

            // Attach the intlTelInput on field
            $field.intlTelInput({
                utilsScript: options.utilsScript || '',
                autoPlaceholder: autoPlaceholder,
                preferredCountries: preferredCountries
            });

            // Revalidate the field when changing the country
            var $form     = validator.getForm(),
                fieldName = $field.attr('data-fv-field');
            $form.on('click.country.intphonenumber', '.country-list', function() {
                $form.formValidation('revalidateField', fieldName);
            });
        },

        destroy: function(validator, $field, options) {
            $field.intlTelInput('destroy');

            validator.getForm().off('click.country.intphonenumber');
        },

        validate: function(validator, $field, options) {
            return $field.val() === '' || $field.intlTelInput('isValidNumber');
        }
    };

/*    $('#checkout-form').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            phoneNumber: {
                validators: {
                    intPhoneNumber: {
                        utilsScript: "{{URL::to('public/front/js/phonenumberutil.js')}}",
                        autoPlaceholder: true,
                        preferredCountries: 'fr,us,gb',
                        message: 'The phone number is not valid'
                    }
                }
            }
        }
    });*/

  });

 function checkCountry() {
/* 	var activeCountry = "{{Session::get('active_country')}}";

 	var billingCountry = $('#billing_country').val();
 	var shippingCountry = $('#shipping_country').val();
 	if(billingCountry!='' && billingCountry!=activeCountry) {
 		alert('Billing country should be same as your website access location, otherwise you cannot proceed with payment');
 		$('#checkoutBtn').hide();
 	} else if(shippingCountry!='' && shippingCountry!=activeCountry) {
 		alert('Shipping country should be same as your website access location, otherwise you cannot proceed with payment');
 		$('#checkoutBtn').hide();
 	} else {
 		$('#checkoutBtn').show();
 	}*/
 }
</script>
@endsection
