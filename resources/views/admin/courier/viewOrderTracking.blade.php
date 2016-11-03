@extends('admin.layout.tpl')

@section('customCss')

<style>
.no-padding{
  padding:0px;
}

#billing-form .form-group {
  text-align: left;
}
.top10{
	margin-top:10px;
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
<div class="page-header"><h1>View Order</h1> 

</div>
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

                                <div class="row">
                
                    <div class="col-md-6">
                    
                        <address class="billing_text">
                          <strong>Billing Address</strong>&nbsp;&nbsp;<br>
                          {{$orderAddress->billing_name}}<br/>
                          {{$orderAddress->billing_address1}}, @if($orderAddress->billing_address2!=''){{$orderAddress->billing_address2.","}}@endif<br>
                          {{$orderAddress->billing_city}}, {{$orderAddress->billing_state}}<br>
                          {{$countries[$orderAddress->billing_country]}}, {{$orderAddress->billing_zip}}<br>
                          <abbr title="Phone">Ph:</abbr> {{$orderAddress->billing_phone}}
                        </address>
                        
                        <address class="billing_text">
                          <strong>Email</strong><br>
                          <a href="mailto:{{$orderAddress->billing_email}}">{{$orderAddress->billing_email}}</a>
                        </address>

                        
                    </div>
                    
                    <div class="col-md-6 text-right">
                    
                        <dl>
                          <dt>Invoice Details</dt>
                          <dd>Invoice number : {{$orderDetail->invoice_no}}</dd>
                          <dd>Order Date : {{date('d-m-Y H:i:s',strtotime($orderDetail->order_placed_date))}}</dd>
                        </dl>
                        <address class="shipping_text">
                          <strong>Shipping Address</strong>&nbsp;&nbsp;<br>
                          {{$orderAddress->shipping_name}}<br/>
                          {{$orderAddress->shipping_address1}}, @if($orderAddress->shipping_address2!=''){{$orderAddress->shipping_address2.","}}@endif<br>
                          {{$orderAddress->shipping_city}}, {{$orderAddress->shipping_state}}<br>
                          {{$countries[$orderAddress->shipping_country]}}, {{$orderAddress->shipping_zip}}<br>
                          @if(!empty($orderAddress->shipping_phone))<abbr title="Phone">Ph:</abbr> {{$orderAddress->shipping_phone}}<br>@endif
                          <a href="mailto:{{$orderAddress->shipping_email}}">{{$orderAddress->shipping_email}}</a>
                        </address>

                    </div>
                
                </div>
                
                
          


                        

            


            <div class="col-md-12">


<h3>Aftership Integration</h3>


<form method="post" id="tracking-form" action="{{URL::to('admin/orders/update-tracking/'.$orderDetail->id)}}">

@if($orderAddress->selected_courier!='')
<div class="form-group">
<label>Courier</label>

      @if(isset($couriers) && !empty($couriers))

        @foreach($couriers as $courier)
        @if($courier->id==$orderAddress->selected_courier)
          <p>{{$courier->name}}</p>
        @endif
        @endforeach

      @endif
</div>
@endif


<table class="table table-striped no-margn" style="margin-bottom:20px;">
                                      <thead>
                                        <tr>
                                          <th width="45%">Tracking Number</th>
                                          <th width="45%">Product</th>
                                          <th width="10%">Action</th>
                                        </tr>
                                      </thead>

                                      <tbody id="trackingDetails">

                                      <?php $i=1; ?>

                                      @if(isset($orderTracking) && count($orderTracking)>0)

                                          @foreach($orderTracking as $tracking)

                                            <?php 

                                              $selectedPro = array();
                                              $pro = $tracking->products;
                                              if(!empty($pro)) {
                                                $selectedPro = explode(',', $pro);
                                              }

                                            ?>

                                            <tr id="rows_{{$i}}">
                                              <td>
                                                <input type="hidden" name="tid_{{$i}}" value="{{$tracking->id}}" />
                                                <input type="text" value="{{$tracking->tracking_number}}" class="form-control" required name="tracking_number_{{$i}}" id="tracking_number_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td>
                                                    <select name="product_{{$i}}[]" id="product_{{$i}}" class="chosen-select" multiple required>
                                                      <option value=""></option>
                                                      @if(isset($orderProducts) && !empty($orderProducts))
                                                        @foreach($orderProducts as $orderPro)
                                                          <option value="{{$orderPro->id}}" @if(!empty($selectedPro) && in_array($orderPro->id,$selectedPro)) selected @endif>{{$orderPro->product_name}}</option>
                                                        @endforeach
                                                      @endif
                                                    </select>
                                              </td>
                                              <td><a href="javascript:void(0)" data-type="normal" data-id="{{$tracking->id}}" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
                                            </tr>

                                            <?php $i++; ?>

                                          @endforeach

                                      @else

                                             <tr id="rows_{{$i}}">
                                              <td>
                                                <input type="hidden" name="tid_{{$i}}" value="0" />
                                                <input type="text" value="" class="form-control"  name="tracking_number_{{$i}}" id="tracking_number_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td>
                                                    <select name="product_{{$i}}[]" id="product_{{$i}}" class="chosen-select" multiple >
                                                      <option value=""></option>
                                                      @if(isset($orderProducts) && !empty($orderProducts))
                                                        @foreach($orderProducts as $orderPro)
                                                          <option value="{{$orderPro->id}}">{{$orderPro->product_name}}</option>
                                                        @endforeach
                                                      @endif
                                                    </select>
                                              </td>
                                              <td><a href="javascript:void(0)" data-type="dynamic" data-id="{{$i}}" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
                                            </tr> 

                                            <?php $i=1; ?>

                                      @endif

                                      </tbody>

                                      <tfoot>
                                        <tr>
                                          <td colspan="4">
                                            <input type="hidden" name="counters" id="counters" value="{{$i}}">
                                            <button type="button" class="btn btn-xs btn-info" id="addTracking">
                                              <i class="fa fa-plus"></i> Add Tracking Number
                                            </button>
                                          </td>
                                        </tr>
                                      </tfoot>                                     
                                  
                                  </table>

                                  <?php $i = $i-1; ?>

                                  

</form>



</div>

            

            
                    
            
                    

</div>


<div class="col-md-3">






</div>


<div class="col-md-12">

<div class="form-group">

<div class="col-md-offset-4 col-md-6">



<button class="btn btn-sm btn-primary" type="submit">Update</button>



<a href="{{URL::to('admin/order-tracking')}}" class="btn btn-sm btn-default">Cancel</a>

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

        var trackingProducts = '';

            @if(isset($orderProducts) && !empty($orderProducts))
                @foreach($orderProducts as $orderPro)
                  trackingProducts +='<option value="{{$orderPro->id}}">{{$orderPro->product_name}}</option>';
                @endforeach
            @endif

        $(document).ready(function(){

            $('#order-form').validate();



          $('#same_as_billing').click(function(){
            if($('#same_as_billing').is(':checked')) {
              $('#shippingAddress').hide();
            } else {
              $('#shippingAddress').show();
            }
          });

          $("#addTracking").on("click", function () {
              var counter = Number($('#counters').val());
              counter = counter+1;

              var html = '<tr id="rows_'+counter+'">';

              html += '<td><input type="hidden" name="tid_'+counter+'" value="0" /><input type="text" class="form-control" required name="tracking_number_'+counter+'" id="tracking_number_'+counter+'" autocomplete="off" /></td>';
              html += '<td><select name="product_'+counter+'[]" id="product_'+counter+'" class="chosen-select" multiple required>'+trackingProducts+'</select></td>';
              html += '<td><a href="javascript:void(0)" data-type="dynamic" data-id="'+counter+'" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>';

              html += '</tr>';

              $('#trackingDetails').append(html);
              $("#counters").val(counter);

              $('#product_'+counter).chosen();
              

            });

            $(document).on('click',".removeTracking",function (){   
                var type = $(this).data('type');
                var id   = $(this).data('id');
                if(type=='dynamic') { 
                  $("#rows_"+id).remove();
                } else {
                  var confirmMsg = confirm('Are you sure want to remove this tracking number?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/order-tracking/delete/'.$orderDetail->id)}}/"+id;
                  }
                }
            });

            $(document).on('click',".removeNote",function (){   
                  var id   = $(this).data('id');
                  var confirmMsg = confirm('Are you sure want to remove this note?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/orders/remove-note/edit/'.$orderDetail->id)}}/"+id;
                  }
            });


           $('#order_status').change(function() {
              var status = $('#order_status').val();
              if(status!='' && status==5) {
                $('.refundAmt').show();
              } else {
                $('.refundAmt').hide();
              }
           });

           

           $('#refund_amount').keyup(function() {
            var value = $('#refund_amount').val();
            var total = {{$orderDetail->grand_total}};
            if(Number(value)>Number(total)) {
              alert('Refund amount should not be more than order amount');
              $('#refund_amount').val('');
              $('#refund_amount').focus();
              return false;
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
                      $('#phone').val(response.phone);
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