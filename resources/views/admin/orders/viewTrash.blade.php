@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

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
<div class="page-header"><h1>View Order</h1></div>
<div class="warper container-fluid">

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

          
<!--             <div class="page-header"><h1>View Invoice</h1></div>
 -->            
            
            
            
            
<!--                 <div class="page-header text-right"><h3 class="no-margn">FreakPixels Corporation <small>Pvt. Ltd.</small></h3></div>
 -->                
                <hr>
                
                                <div class="row">
                
                    <div class="col-md-6">
                    
                        <address class="billing_text">
                          <strong>Billing Address</strong>&nbsp;&nbsp;<a href="javascript:void(0)" id="billingCheck"><i class="fa fa-pencil"></i></a><br>
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

<div id="billingForm" style="display:none;">
<form method="post" id="billing-form" action="">
<h5><strong>Billing Address</strong></h5>
<div class="form-group">
    <label class="control-label">Name</label>
    <input type="text" id="bname" name="name" value="{{$orderAddress->billing_name}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Address</label>
    <input type="text" id="baddress" name="address" value="{{$orderAddress->billing_address1}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Address Line 2</label>
    <input type="text" id="baddress2" name="address2" value="{{$orderAddress->billing_address2}}" class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Town / City</label>
    <input type="text" id="bcity" name="city" value="{{$orderAddress->billing_city}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">State</label>
    <input type="text" id="bstate" name="state" value="{{$orderAddress->billing_state}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Country</label>
    <select id="bcountry" name="country" required class="form-control">
    @foreach($countries as $key=>$value)
      <option value="{{$key}}" @if($key==$orderAddress->billing_country) selected @endif>{{$value}}</option>    
    @endforeach
    </select>
</div>
<div class="form-group">
    <label class="control-label">Postal Code</label>
    <input type="text" id="bpostal" name="postal" value="{{$orderAddress->billing_zip}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">E-Mail</label>
    <input type="email" id="bemail" name="email" value="{{$orderAddress->billing_email}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Phone</label>
    <input type="text" id="bphone" name="phone" number="true" value="{{$orderAddress->billing_phone}}" required class="form-control">
</div>
<div class="form-group">
    <label class="control-label">Payment Type</label>
    <select id="paymentType" name="paymentType" required class="form-control">
    @foreach($paymentType as $key=>$value)
      <option value="{{$key}}" @if($orderDetail->payment_type==$key) selected @endif>{{$value}}</option>
    @endforeach
    </select>
</div>


<div class="form-group transactionId" @if($orderDetail->payment_type!=2) style="display:none;"@endif>
    <label class="control-label">Transaction ID</label>
    <input type="text" id="transaction_id" name="transaction_id" value="{{$orderDetail->direct_transaction_id}}" class="form-control">
</div>


<div class="form-group">
<input type="submit" id="submitBilling" name="submit" class="btn btn-sm btn-primary billing-btn" value="Save">
<input type="hidden" name="type" id="type" value="1">
<a href="javascript:void(0)" id="closeBilling" name="submit" class="btn btn-sm btn-default billing-btn">Close</a>
</div>
</form>
</div>
                        
                    </div>
                    
                    <div class="col-md-6 text-right">
                    
                        <dl>
                          <dt>Invoice Details</dt>
                          <dd>Invoice number : {{$orderDetail->invoice_no}}</dd>
                          <dd>Order Date : {{date('d-m-Y H:i:s',strtotime($orderDetail->order_placed_date))}}</dd>
                        </dl>
                        <address class="shipping_text">
                          <strong>Shipping Address</strong>&nbsp;&nbsp;<a href="javascript:void(0)" id="shippingCheck"><i class="fa fa-pencil"></i></a><br>
                          {{$orderAddress->shipping_name}}<br/>
                          {{$orderAddress->shipping_address1}}, @if($orderAddress->shipping_address2!=''){{$orderAddress->shipping_address2.","}}@endif<br>
                          {{$orderAddress->shipping_city}}, {{$orderAddress->shipping_state}}<br>
                          {{$countries[$orderAddress->shipping_country]}}, {{$orderAddress->shipping_zip}}<br>
                          @if(!empty($orderAddress->shipping_phone))<abbr title="Phone">Ph:</abbr> {{$orderAddress->shipping_phone}}<br>@endif
                          <a href="mailto:{{$orderAddress->shipping_email}}">{{$orderAddress->shipping_email}}</a>
                        </address>
                        
                        <div id="shippingForm" style="display:none;">
<form method="post" id="shipping-form" action="">
<h5><strong>Shipping Address</strong></h5>
<div class="form-group" style="text-align:left;">
    <input type="checkbox" id="same_as_billing" name="same_as_billing" value="1" @if($orderAddress->same_as_billing==1) checked @endif class=""> Same as billing address
</div>
<div id="shipAdd" @if($orderAddress->same_as_billing==1) style="display:none;" @endif>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Name</label>
    <input type="text" id="sname" name="name" value="{{$orderAddress->shipping_name}}" required class="form-control">
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Address</label>
    <input type="text" id="saddress" name="address" value="{{$orderAddress->shipping_address1}}" required class="form-control">
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Address Line 2</label>
    <input type="text" id="saddress2" name="address2" value="{{$orderAddress->shipping_address2}}" class="form-control">
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Town / City</label>
    <input type="text" id="scity" name="city" value="{{$orderAddress->shipping_city}}" required class="form-control">
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">State</label>
    <input type="text" id="sstate" name="state" value="{{$orderAddress->shipping_state}}" required class="form-control">
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Country</label>
    <select id="scountry" name="country" required class="form-control">
    @foreach($countries as $key=>$value)
      <option value="{{$key}}" @if($key==$orderAddress->shipping_country) selected @endif>{{$value}}</option>    
    @endforeach
    </select>
</div>
<div class="form-group" style="text-align:left;">
    <label class="control-label">Postal Code</label>
    <input type="text" id="spostal" name="postal" value="{{$orderAddress->shipping_zip}}" required class="form-control">
</div>
</div>
<div class="form-group">
<input type="submit" id="submitShipping" name="submit" class="btn btn-sm btn-primary shipping-btn" value="Save">
<input type="hidden" name="type" id="type" value="2">
<a href="javascript:void(0)" id="closeShipping" name="submit" class="btn btn-sm btn-default shipping-btn">Close</a>
</div>
</form>
</div>

                    </div>
                
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped">
                           <thead>
                                <tr>
                                    <th style="width:50%">Product</th>
                                    <th style="width:25%;">Quantity</th>
                                    <th style="width:25%;" class="total">Total</th>
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
                                    <td>{{ucwords($orderPro->product_name)}}
@if($orderPro->ordered_from!=NULL && !empty($orderPro->ordered_from))<br/><i>Ordered From - {{ucwords($orderPro->ordered_from)}}</i>@endif
</td>      
                                    <td>{{$orderPro->product_qty}}</td>
                                    <td class="total">{{$orderSession->order_symbol}} {{round($subTotal)}}</td>
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
                                    <td>{{ucwords($kitPro->product_name)}} <br/> Included with : {{ucwords($orderPro->product_name)}}</td>      
                                    <td>{{$kitPro->product_qty}}</td>
                                    <td class="total">{{$orderSession->order_symbol}} {{round($total)}}</td>
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
                            <td style="text-align:right;" colspan="2">Subtotal : </td>
                            <td>{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</td>
                          </tr>

                          <tr>
                            <td style="text-align:right;" colspan="2">Shipping and Handling : </td>
                            <td>@if($orderDetail->shipping_charge!='0.00' && !empty($orderDetail->shipping_charge)){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}} @else {{"-"}} @endif</td>
                          </tr>

                          <tr>
                            <td style="text-align:right;" colspan="2">Payment Type : </td>
<td>@if(!empty($orderDetail->payment_type)){{$paymentType[$orderDetail->payment_type]}}@endif</td>                          </tr>

                          <tr>
                            <td style="text-align:right;" colspan="2">Order Total : </td>
                            <td>{{$orderSession->order_symbol}} {{round($orderDetail->grand_total)}}
                            @if($orderDetail->order_symbol!=$inrSymbol->symbol)
                                      <br/>({{$inrSymbol->symbol}} {{round($orderDetail->grand_total_inr)}})
                                      @endif</td>
                          </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                
                    
            
                    

</div>


<div class="col-md-3">

<div class="col-md-12">

<h4>Order Currency</h4>

<p>{{$orderSession->order_currency." - ".$currencies[$orderSession->order_currency]}}</p>

<h4>Customer IP Address</h4>

<p>{{$orderSession->order_ip_address}}</p>




<h4>Order Status</h4>
<form method="post" id="status-form" action="{{URL::to('admin/orders/change-status/'.$orderDetail->id)}}">
<div class="form-group">
    <select name="order_status" id="order_status" class="chosen-select" required>
      @if(isset($orderStatus) && !empty($orderStatus))

        @foreach($orderStatus as $key=>$value)
          <option value="{{$key}}" @if($orderDetail->order_status==$key) selected @endif>{{$value}}</option>
        @endforeach

      @endif
    </select>
</div>

<div class="form-group refundAmt" style="display:none;">
<label class="control-label">Refund Amount</label>
<input type="text" number="true" class="form-control" name="refund_amount" id="refund_amount" required @if($orderDetail->refund_amount!=0.00)value="{{$orderDetail->refund_amount}}"@endif />
</div>


<!-- <div class="form-group">

<button class="btn btn-sm btn-primary" type="submit">Update Status</button>


</div> -->

</form>

<form method="post" id="courier-form" action="{{URL::to('admin/orders/update-courier/'.$orderDetail->id)}}">

@if($orderAddress->courier_preference!='')
<h4>Preferred Courier</h4>

      @if(isset($couriers) && !empty($couriers))

        @foreach($couriers as $courier)
        @if($courier->id==$orderAddress->courier_preference)
          <p>{{$courier->name}}</p>
        @endif
        @endforeach

      @endif
@endif

<div class="form-group">
    <label class="control-label">Courier</label>
    <select name="courier" id="courier" class="chosen-select" required>
      <option value="">Select Couriers</option>
      @if(isset($couriers) && !empty($couriers))
        @foreach($couriers as $courier)
          <option value="{{$courier->id}}" @if($courier->id==$orderAddress->selected_courier) selected @endif>{{$courier->name}}</option>
        @endforeach

      @endif
    </select>
</div>

<div class="form-group">
<input type="hidden" name="page" value="process">
<!-- <button class="btn btn-sm btn-primary" type="submit">Update Couriers</button>
 -->

</div>

</form>

  
</div>



</div>


</div>

<div class="row">

<div class="col-md-12">

@if($orderAddress->selected_courier!='')

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
                                          <!-- <th width="10%">Action</th> -->
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

                                            <tr>
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
<!--                                               <td><a href="javascript:void(0)" data-type="normal" data-id="{{$tracking->id}}" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
 -->                                            </tr>

                                            <?php $i++; ?>

                                          @endforeach

                                      @else

                                            <tr>
                                              <td>
                                                <input type="hidden" name="tid_{{$i}}" value="0" />
                                                <input type="text" value="" class="form-control" required name="tracking_number_{{$i}}" id="tracking_number_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td>
                                                    <select name="product_{{$i}}[]" id="product_{{$i}}" class="chosen-select" multiple required>
                                                      <option value=""></option>
                                                      @if(isset($orderProducts) && !empty($orderProducts))
                                                        @foreach($orderProducts as $orderPro)
                                                          <option value="{{$orderPro->id}}">{{$orderPro->product_name}}</option>
                                                        @endforeach
                                                      @endif
                                                    </select>
                                              </td>
<!--                                               <td><a href="javascript:void(0)" data-type="normal" data-id="" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
 -->                                            </tr>

                                            <?php $i++; ?>

                                      @endif

                                      </tbody>

                                   <!--    <tfoot>
                                        <tr>
                                          <td colspan="4">
                                            <button type="button" class="btn btn-xs btn-info" id="addTracking">
                                              <i class="fa fa-plus"></i> Add Tracking Number
                                            </button>
                                          </td>
                                        </tr>
                                      </tfoot>  -->                                    
                                  
                                  </table>

                                  <?php $i = $i-1; ?>

                                <!--   <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <input type="hidden" name="page" value="process">
                                      <input type="hidden" name="counter" id="counter" value="{{$i}}">
                                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                      <a href="{{URL::to('admin/orders/process/'.$orderDetail->id)}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div> -->

</form>

@endif

</div>

</div>


<div class="row">

<div class="col-md-12">

<h3>Order Notes</h3>


<div class="col-md-12">


<table class="table table-striped no-margn" style="margin-bottom:20px;">
                            <thead>
                                <tr>
                                    <th width="60%">Note</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Posted On</th>
                                    <!-- <th width="10%">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderNotes) && !empty($orderNotes))

                                  @foreach($orderNotes as $note)

                                    <tr>
                                      <td>{{$note->notes}}</td>
                                      <td>@if($note->type==1){{"Customer Note"}}@else{{"Private Note"}}@endif</td>
                                      <td>{{date('d-m-Y H:i:s',strtotime($note->created_at))}}</td>
                                      <!-- <td>
                                          <a class="btn btn-xs btn-danger removeNote" data-id="{{$note->id}}" href="javascript:void(0)"><i class="fa fa-close"></i> Remove</a> 
                                      </td> -->
                                    </tr>

                                    <?php //$i++; ?>

                                  @endforeach

                                @endif
                            </tbody>
                          </table>

</div>

<!-- <div class="col-md-6 col-md-offset-3" style="margin-top:20px;">

<form method="post" id="note-form" action="{{URL::to('admin/orders/add-note/'.$orderDetail->id)}}">

<div class="form-group">
  <textarea class="form-control" name="order_note" id="order_note" placeholder="Order Note" required style="height:100px;"></textarea>
</div>

<div class="form-group">
    <select name="type" id="type" class="chosen-select" required>
      <option value="1">Customer Note</option>
      <option value="2">Private Note</option>
    </select>
</div>

<div class="form-group" style="margin-top:20px;">
  <div class="col-sm-7 col-sm-offset-5">
    <input type="hidden" name="page" value="process">
    <button type="submit" class="btn btn-primary btn-sm">Add</button>
    <a href="{{URL::to('admin/orders/process/'.$orderDetail->id)}}" class="btn btn-default btn-sm">Cancel</a>
  </div>
</div>



</form>

</div> -->

</div>

</div>
            
            
            
        </div>


@endsection

@section('customJs')

  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            var trackingProducts = '';

            @if(isset($orderProducts) && !empty($orderProducts))
                @foreach($orderProducts as $orderPro)
                  trackingProducts +='<option value="{{$orderPro->id}}">{{$orderPro->product_name}}</option>';
                @endforeach
            @endif

           $('#status-form').validate();
           $('#courier-form').validate();
           $('#tracking-form').validate();
           $('#note-form').validate();

           $('#billing-form').validate({
             submitHandler: function (form) {
                $('.billing-btn').attr('disabled','disabled');
                $('#submitBilling').val('Saving...');
                $.ajax({
                  url: "{{URL::to('admin/orders/address-change/'.$orderDetail->id)}}",
                  method: 'POST',
                  data:$('#billing-form').serialize(),
                  success: function(response){
                    $('.billing-btn').removeAttr('disabled');
                    $('#submitBilling').val('Save');
                    location.reload();
                  }
                });
             }
           });

           $('#shipping-form').validate({
             submitHandler: function (form) {
                $('.shipping-btn').attr('disabled','disabled');
                $('#submitShipping').val('Saving...');
                $.ajax({
                  url: "{{URL::to('admin/orders/address-change/'.$orderDetail->id)}}",
                  method: 'POST',
                  data:$('#shipping-form').serialize(),
                  success: function(response){
                    $('.shipping-btn').removeAttr('disabled');
                    $('#submitShipping').val('Save');
                    location.reload();
                  }
                });
             }
           });

           $('#billingCheck').on("click", function () {
            $('.billing_text').hide();
            $('#billingForm').show();
           });

           $('#closeBilling').on("click", function () {
            $('#billingForm').hide();
            $('.billing_text').show();
           });

           $('#shippingCheck').on("click", function () {
            $('.shipping_text').hide();
            $('#shippingForm').show();
           });

           $('#closeShipping').on("click", function () {
            $('#shippingForm').hide();
            $('.shipping_text').show();
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

           $('#same_as_billing').click(function(){
            if($('#same_as_billing').is(':checked')) {
              $('#shipAdd').hide();
            } else {
              $('#shipAdd').show();
            }
          });

            $("#addTracking").on("click", function () {
              var counter = Number($('#counter').val());
              counter = counter+1;

              var html = '<tr id="row_'+counter+'">';

              html += '<td><input type="hidden" name="tid_'+counter+'" value="0" /><input type="text" class="form-control" required name="tracking_number_'+counter+'" id="tracking_number_'+counter+'" autocomplete="off" /></td>';
              html += '<td><select name="product_'+counter+'[]" id="product_'+counter+'" class="chosen-select" multiple required>'+trackingProducts+'</select></td>';
              html += '<td><a href="javascript:void(0)" data-type="dynamic" data-id="'+counter+'" class="removeTracking btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>';

              html += '</tr>';

              $('#trackingDetails').append(html);
              $("#counter").val(counter);

              $('#product_'+counter).chosen();
              

            });

            $(document).on('click',".removeTracking",function (){   
                var type = $(this).data('type');
                var id   = $(this).data('id');
                if(type=='dynamic') { 
                  $("#row_"+id).remove();
                } else {
                  var confirmMsg = confirm('Are you sure want to remove this tracking number?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/orders/remove-tracking/process/'.$orderDetail->id)}}/"+id;
                  }
                }
            });

            $(document).on('click',".removeNote",function (){   
                  var id   = $(this).data('id');
                  var confirmMsg = confirm('Are you sure want to remove this note?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/orders/remove-note/process/'.$orderDetail->id)}}/"+id;
                  }
            });

        });
    </script>

@endsection