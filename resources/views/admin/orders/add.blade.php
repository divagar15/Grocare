@extends('admin.layout.tpl')

@section('customCss')

<style>
.no-padding{
  padding:0px;
}

#billing-form .form-group {
  text-align: left;
}
</style>

@endsection

@section('content')       
<!-- <div class="page-header"><h1>View Order</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
                       <div class="panel-body">



                          
                          
                        </div>
                    </div>
                 </div>
            
            </div> -->
<div class="page-header"><h1>Add Order</h1></div>
<div class="warper container-fluid">

<form id="order-form" class="form-horizontal" role="form" method="post">

<div class="row">
            
              <div class="col-md-9">

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
        
                <hr>
                
                
          <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped">
                           <thead>
                                <tr>
                                    <th style="width:50%">Product</th>
                                    <th style="width:15%;">Price</th>
                                    <th style="width:10%;">Quantity</th>
                                    <th style="width:20%;" class="total">Total</th>
                                    <th style="width:5%;"></th>
                                </tr>
                            </thead>  
                                <tbody id="productDetail">
                                  <tr>
                                    <td>
                                      <select class="chosen-select" name="product[]" id="product_1" required onchange="checkProductPrice(this.id,1)">
                                        <option value=""></option>
                                        @if(isset($simpleProducts) && !empty($simpleProducts))
                                        <optgroup label="Simple Product">
                                          @foreach($simpleProducts as $simple)
                                            <option value="{{$simple->id}}">{{ucwords($simple->name)}}</option>
                                          @endforeach
                                        </optgroup>
                                        @endif
                                        @if(isset($bundleProducts) && !empty($bundleProducts))
                                        <optgroup label="Bundle Product">
                                          @foreach($bundleProducts as $bundle)
                                            <option value="{{$bundle->id}}">{{ucwords($bundle->name)}}</option>
                                          @endforeach
                                        </optgroup>
                                        @endif
                                      </select>
                                    </td>
                                    <td><input type="text" number="true" name="price[]" id="price_1" class="form-control" onkeyup="calculateTotal();" /></td>
                                    <td><input type="text" number="true" maxlength="3" name="quantity[]" id="quantity_1" class="form-control" required onkeyup="calculateTotal();" /></td>
                                    <td>
                                      <span class="total_price_1"></span>
                                      <input type="hidden" name="total_price[]" id="total_price_1" readonly class="form-control" />
                                      <input type="hidden" name="product_type[]" id="product_type_1" readonly class="form-control" />
                                    </td>
                                    <td></td>
                                  </tr>
                                </tbody>     
                                <tfoot>
                                  <tr>
                                    <td colspan="5">
                                      <input type="hidden" name="counter" id="counter" value="1">
                                      <a href="javascript:void(0)" class="btn btn-primary btn-xs" id="addProduct">Add Product</a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" align="right">Cart Subtotal</td>
                                    <td>
                                    <span id="cart_subtotal"></span>
                                    <input type="hidden" name="subtotal" id="subtotal" />
                                    </td>
                                    <td></td>
                                  </tr>
                                  
                                  <tr>
                                    <td colspan="3" align="right">Shipping Charge</td>
                                    <td>
                                      <span id="cart_shipping"></span>
                                      <input type="hidden" name="shipping_charge" id="shipping_charge" />
                                    </td>
                                    <td></td>
                                  </tr>

                                  <tr>
                                    <td colspan="3">Discount Amount<br/><textarea class="form-control" placeholder="Comment" id="discount_comment" name="discount_comment"></textarea></td>
                                    <td>
                                      <br/><input type="text" class="form-control" onkeyup="calculateTotal();" name="discount_amount" id="discount_amount" placeholder="Amount" number="true" />
                                    </td>
                                    <td></td>
                                  </tr>

                                  <tr>
                                    <td colspan="3" align="right">Order Total</td>
                                    <td>
                                    <span id="cart_total"></span>
                                    <input type="hidden" name="total" id="total" />
                                    </td>
                                    <td></td>
                                  </tr>
                                </tfoot>                       
                        </table>
                    </div>
                </div>
              
            </div>

            <div class="col-md-12">

                <div class="form-group">
                  <div class="col-md-4">
                    <label class="control-label">Customer</label>
                    <select name="customer" id="customer" class="chosen-select" required onchange="return getCustomerDetails();">
                      <option value=""></option>
                      <option value="0">Guest</option>
                      @if(isset($customers) && !empty($customers))
                        @foreach($customers as $customer)
                          <option value="{{$customer->id}}">{{ucwords($customer->name)." (".$customer->email.")"}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="control-label">E-Mail ID</label>
                    <input type="email" name="email" id="email" class="form-control" required />
                  </div>
                  <div class="col-md-4">
                    <label class="control-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" required number="true" />
                  </div>
              </div>

            </div>


            <div class="col-md-12">

              <div class="col-md-5">

                <div class="form-group">

                  <h4>Billing Address</h4>

                </div>

                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" name="billing_name" id="billing_name" class="form-control billAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address</label>
                    <input type="text" name="billing_address1" id="billing_address1" class="form-control billAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address Line 2</label>
                    <input type="text" name="billing_address2" id="billing_address2" class="form-control billAddress"  />
                  </div>

                  <div class="form-group">
                    <label class="control-label">City</label>
                    <input type="text" name="billing_city" id="billing_city" class="form-control billAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">State</label>
                    <input type="text" name="billing_state" id="billing_state" class="form-control billAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Country</label>
                    <select name="billing_country" id="billing_country" class="form-control billAddress" required>
                      <option value="">Select Country</option>
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Postal Code</label>
                    <input type="text" name="billing_zip" id="billing_zip" class="form-control billAddress" required />
                  </div>

              </div>



               <div class="col-md-5 col-md-offset-2">

                <div class="form-group">

                  <h4>Shipping Address</h4>

                </div>

                <div class="form-group">
                    <input type="checkbox" name="same_as_billing" id="same_as_billing" class="" value="1" /> Same as billing address
                  </div>

                <div id="shippingAddress">

                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" name="shipping_name" id="shipping_name" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address</label>
                    <input type="text" name="shipping_address1" id="shipping_address1" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address Line 2</label>
                    <input type="text" name="shipping_address2" id="shipping_address2" class="form-control shipAddress"  />
                  </div>

                  <div class="form-group">
                    <label class="control-label">City</label>
                    <input type="text" name="shipping_city" id="shipping_city" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">State</label>
                    <input type="text" name="shipping_state" id="shipping_state" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Country</label>
                    <select name="shipping_country" id="shipping_country" class="form-control shipAddress" required>
                      <option value="">Select Country</option>
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Postal Code</label>
                    <input type="text" name="shipping_zip" id="shipping_zip" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">E-Mail ID</label>
                    <input type="email" name="semail" id="semail" class="form-control shipAddress" required />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input type="text" name="sphone" id="sphone" class="form-control shipAddress" required number="true" />
                  </div>

                </div>

              </div>



            </div>
                    
            
                    

</div>


<div class="col-md-3">

<div class="col-md-12">

<h4>Order Currency</h4>

<p>{{$currencySelected." - ".$currencies[$currencySelected]}}</p>

<div class="form-group">
<label class="control-label">Payment Type</label>
    <select name="payment_type" id="payment_type" class="chosen-select" required>
          <option value="">Select Payment Type</option>
      @if(isset($paymentType) && !empty($paymentType))

        @foreach($paymentType as $key=>$value)
          <option value="{{$key}}">{{$value}}</option>
        @endforeach

      @endif
    </select>
</div>

<h4>Order Note</h4>

<div class="form-group">
<textarea name="order_note" id="order_note" class="form-control"></textarea>
</div>

<div class="form-group">
<select class="form-control" name="note_type" id="note_type">
  <option value="1">Customer Note</option>
  <option value="2">Private Note</option>
</select>
</div>

</div>




</div>


<div class="col-md-12">

<div class="form-group">

<div class="col-md-offset-4 col-md-6">

<input type="hidden" name="region" id="region" value="{{$getRegion->id}}" />
<input type="hidden" name="currency" id="currency" value="{{$currencySelected}}" />
<input type="hidden" name="symbol" id="symbol" value="{{$getSymbol->symbol}}" />

<button class="btn btn-sm btn-primary" type="submit">Submit</button>

<a href="{{URL::to('admin/orders/all')}}" class="btn btn-sm btn-default">Cancel</a>

</div>

</div>

</div>


</div>

</form>


            
            
        </div>


@endsection

@section('customJs')

    <script type="text/javascript">

        var productHtml = '<option value=""></option>';

        @if(isset($simpleProducts) && !empty($simpleProducts))
          productHtml +='<optgroup label="Simple Product">';
          @foreach($simpleProducts as $simple)
            productHtml +='<option value="{{$simple->id}}">{{ucwords($simple->name)}}</option>';
          @endforeach
            productHtml +='</optgroup>';
        @endif
        @if(isset($bundleProducts) && !empty($bundleProducts))
            productHtml +='<optgroup label="Bundle Product">';
          @foreach($bundleProducts as $bundle)
            productHtml +='<option value="{{$bundle->id}}">{{ucwords($bundle->name)}}</option>';
          @endforeach
            productHtml +='</optgroup>';
        @endif



        $(document).ready(function(){

            $('#order-form').validate();

            $("#addProduct").click(function(){
              var counter = Number($('#counter').val());
              counter = ++counter;

              var html = '<tr id="row_'+counter+'"><td><select class="chosen-select" name="product[]" id="product_'+counter+'" required onchange="checkProductPrice(this.id,'+counter+');">'+productHtml+'</select></td>';
                  html += '<td><input type="text" number="true" name="price[]" id="price_'+counter+'" class="form-control" onkeyup="calculateTotal();" /></td>';
                  html += '<td><input type="text" number="true" maxlength="3" name="quantity[]" id="quantity_'+counter+'" class="form-control" required onkeyup="calculateTotal();" /></td>';
                  html += '<td><span class="total_price_'+counter+'"></span>';
                  html += '<input type="hidden" name="total_price[]" id="total_price_'+counter+'" readonly class="form-control total_price_'+counter+'" />';
                  html += '<input type="hidden" name="product_type[]" id="product_type_'+counter+'" readonly class="form-control" />';
                  html += '</td><td><a href="javascript:void(0)" data-id="'+counter+'" class="removeProduct btn btn-xs btn-danger"><i class="fa fa-close"></i></a></td></tr>';

              $('#productDetail').append(html);

              $('#counter').val(counter);

              $('#product_'+counter).chosen();

            });

            $(document).on('click',".removeProduct",function (){   
                var id   = $(this).data('id');
                $("#row_"+id).remove();
                calculateTotal();
            });


          $('#same_as_billing').click(function(){
            if($('#same_as_billing').is(':checked')) {
              $('#shippingAddress').hide();
            } else {
              $('#shippingAddress').show();
            }
          });

        });

        function checkProductPrice(idValue,count) {
          var value = $('#'+idValue).val();
          $.ajax({
                    url: "{{URL::to('admin/orders/check-product-price')}}/"+value,
                    method: 'POST',
                    data: {region:{{$getRegion->id}}},
                    success: function(response){
                      $('#price_'+count).val($.trim(response));
                      calculateTotal();
                    }
          });
          //alert(value);
        }

        function getCustomerDetails() {
          var value = $('#customer').val();
          if(value!='' && value!=0) {
            $.ajax({
              url: "{{URL::to('admin/orders/get-customer-details')}}/"+value,
              method: 'POST',
              success: function(responses){
                  $.each(responses, function(key, response) {
                    if(key=='details') {
                      $('#email').val(response.email);
                      $('#semail').val(response.email);
                      $('#phone').val(response.phone);
                      $('#sphone').val(response.sphone);
                    }

                    if(key=='billingAddress') {
                      if(response!=null) {
                        $('#billing_name').val(response.name);
                        $('#billing_address1').val(response.address);
                        $('#billing_address2').val(response.address2);
                        $('#billing_city').val(response.city);
                        $('#billing_state').val(response.state);
                        $('#billing_country').val(response.country);
                        $('#billing_zip').val(response.postcode);
                      } else {
                        $('.billAddress').val('');
                      }
                    }

                    if(key=='shippingAddress') {
                      if(response!=null) {
                        $('#shipping_name').val(response.name);
                        $('#shipping_address1').val(response.address);
                        $('#shipping_address2').val(response.address2);
                        $('#shipping_city').val(response.city);
                        $('#shipping_state').val(response.state);
                        $('#shipping_country').val(response.country);
                        $('#shipping_zip').val(response.postcode);
                      } else {
                        $('.shipAddress').val('');
                      }
                    }

                  });
              }
          });
          } else {
            $('.billAddress').val('');
            $('.shipAddress').val('');
          }
        }

        function calculateTotal() {

          var symbol = "{{$getSymbol->symbol}}";

          var shipping_charge = Number({{round($getRegion->shipping_charge)}});
          var minimum_amount  = Number({{round($getRegion->minimum_amount)}});


          var count   = $("#counter").val();
          var total  = 0;
          var grandTotal = 0;
          var shippingCharge = 0;

          for(var i = 1; i<=count;i++) {
            var price  = Number($("#price_"+i).val());
            var quantity = Number($("#quantity_"+i).val());
            
            if(!isNaN(price) && !isNaN(quantity)) {
             // alert(price+'--'+quantity);
              var sub_total = price*quantity;
              
                if(sub_total>0) {
                  $('.total_price_'+i).html(symbol+' '+sub_total);
                  $('#total_price_'+i).val(sub_total);
                } else {
                  $('.total_price_'+i).html('');
                  $('#total_price_'+i).val(0);
                }
                total += sub_total;
              }
          }

          //alert(total);

          if(total>0) {
            $('#cart_subtotal').html(symbol+' '+total);
            $('#subtotal').val(total);

            if(shipping_charge>0) {

              if(minimum_amount>0) {

                if(total<minimum_amount) {
                  shippingCharge = shipping_charge;
                }

              } else {
                shippingCharge = shipping_charge;
              }
                  
            }

            grandTotal = total+shippingCharge;

            var discountAmount = Number($('#discount_amount').val());

            if(shippingCharge>0) {
              $('#cart_shipping').html(symbol+' '+shippingCharge);
              $('#shipping_charge').val(shippingCharge);
            }  else {
              $('#cart_shipping').html('-');
              $('#shipping_charge').val(0);
            }

            $('#cart_total').html(symbol+' '+(grandTotal-discountAmount));
            $('#total').val(grandTotal);

          } else {
            $('#cart_subtotal').html('');
            $('#subtotal').val(0);
            $('#cart_shipping').html('-');
            $('#shipping_charge').val(0);
            $('#cart_total').html('');
            $('#total').val(0);
          }


        }




    </script>

@endsection