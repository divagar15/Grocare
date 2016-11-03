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
.clear{
	clear:both;
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
<div class="page-header"><h1>Edit Order</h1> 
@if($pastOrderCount>0 && $orderDetail->fkcustomer_id!=0) 
<a target="_blank" href="{{URL::to('admin/orders/view-previous-orders/'.$orderDetail->fkcustomer_id.'/'.$orderDetail->id)}}" class="btn btn-sm btn-primary">View Previous Orders</a>
@elseif($orderDetail->fkcustomer_id==0) 
<a href="javascript:void(0)" class="btn btn-sm btn-primary">Guest Customer</a>
@else
<a href="javascript:void(0)" class="btn btn-sm btn-primary">New Customer</a>
@endif

<a href="{{URL::to('admin/orders/resend-order-receipt/'.$orderDetail->oid.'/'.$orderDetail->id)}}" class="pull-right btn btn-sm btn-info">Resend Order Receipt</a>
</div>
<div class="warper container-fluid">

<form id="order-form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

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
						  <div id="address_copy">
							  {{$orderAddress->shipping_name}}<br/>
							  {{$orderAddress->shipping_address1}}, @if($orderAddress->shipping_address2!=''){{$orderAddress->shipping_address2.","}}@endif<br>
							  {{$orderAddress->shipping_city}}, {{$orderAddress->shipping_state}}<br>
							  {{$countries[$orderAddress->shipping_country]}}, {{$orderAddress->shipping_zip}}<br>
							  @if(!empty($orderAddress->shipping_phone))<abbr title="Phone">Ph:</abbr> {{$orderAddress->shipping_phone}}<br>@endif
						  </div>
                          <a href="mailto:{{$orderAddress->shipping_email}}">{{$orderAddress->shipping_email}}</a>
                        </address>
						<a id="btn_copy" class="btn btn-primary btn-xs" href="javascript:void(0)" style="margin: 0 0 2% 0">Copy Shipping Address</a>
                    </div>
                
                </div>
                
                
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

                                <?php 
                                  $j=1;
                                ?>

                                @if(isset($orderProducts) && !empty($orderProducts))

                                @foreach($orderProducts as $pro)

                                  <tr>
                                    <input type="hidden" name="productId[]" id="productId_{{$j}}" value="{{$pro->id}}">
                                    <td>
                                      <select class="chosen-select" name="product[]" id="product_{{$j}}" required onchange="return checkProductPrice(this.id,{{$j}});">
                                        @if(isset($simpleProducts) && !empty($simpleProducts))
                                        <optgroup label="Simple Product">
                                          @foreach($simpleProducts as $simple)
                                            <option value="{{$simple->id}}" @if($pro->product_id==$simple->id) selected @endif>{{ucwords($simple->name)}}</option>
                                          @endforeach
                                        </optgroup>
                                        @endif
                                        @if(isset($bundleProducts) && !empty($bundleProducts))
                                        <optgroup label="Bundle Product">
                                          @foreach($bundleProducts as $bundle)
                                            <option value="{{$bundle->id}}" @if($pro->product_id==$bundle->id) selected @endif>{{ucwords($bundle->name)}}</option>
                                          @endforeach
                                        </optgroup>
                                        @endif
                                      </select>
                                      @if($pro->ordered_from!=NULL && !empty($pro->ordered_from))<i>Ordered From - {{ucwords($pro->ordered_from)}}</i>@endif
                                    </td>
                                    <td><input type="text" number="true" name="price[]" id="price_{{$j}}" value="{{round($pro->product_price)}}" readonly class="form-control" /></td>
                                    <td><input type="text" number="true" maxlength="3" name="quantity[]" value="{{$pro->product_qty}}" id="quantity_{{$j}}" class="form-control" required onkeyup="calculateTotal();" /></td>
                                    <td>
                                      <span class="total_price_{{$j}}">{{$orderSession->order_symbol}} {{round($pro->product_price*$pro->product_qty)}}</span>
                                      <input type="hidden" name="total_price[]" id="total_price_{{$j}}" readonly class="form-control" value="{{round($pro->product_price*$pro->product_qty)}}" />
                                      <input type="hidden" name="product_type[]" id="product_type_{{$j}}" value="{{$pro->product_type}}" readonly class="form-control" />
                                    </td>
                                    <td>
                                      <a href="javascript:void(0)" data-id="{{$j}}" data-type="{{$pro->id}}" class="removeProduct btn btn-xs btn-danger"><i class="fa fa-close"></i></a>
                                    </td>
                                  </tr>


                                  @if($pro->product_type==2)
                              <?php
                                $getKitProducts = App\Helper\AdminHelper::getKitProducts($pro->oid,$pro->id);
                              ?>

                            @if(isset($getKitProducts) && count($getKitProducts)>0)

                              @foreach($getKitProducts as $kitPro)

                              <?php
                                $total = round($kitPro->product_price)*$kitPro->product_qty;
                              ?>

                              <tr>
                                    <td>{{ucwords($kitPro->product_name)}} <br/> Included with : {{ucwords($pro->product_name)}}</td>      
                                    <td>{{round($kitPro->product_price)}}</td>
                                    <td><span style="margin-left:10px;">{{$kitPro->product_qty}}</span></td>
                                    <td class="total">{{$orderSession->order_symbol}} {{round($total)}}</td>
                                    <td></td>
                                </tr>

                              @endforeach

                            @endif

                            @endif

                                  <?php $j++; ?>

                                  @endforeach

                                @endif
                                </tbody>     
                                <?php $j = $j-1; ?>
                                <tfoot>
                                  <tr>
                                    <td colspan="5">
                                      <input type="hidden" name="counter" id="counter" value="{{$j}}">
                                      <a href="javascript:void(0)" class="btn btn-primary btn-xs" id="addProduct">Add Product</a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" align="right">Cart Subtotal</td>
                                    <td>
                                    <span id="cart_subtotal">{{$orderSession->order_symbol}} {{round($orderDetail->subtotal)}}</span>
                                    <input type="hidden" name="subtotal" id="subtotal" value="{{round($orderDetail->subtotal)}}" />
                                    </td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" align="right">Shipping Charge</td>
                                    <td>
                                      <span id="cart_shipping">@if($orderDetail->shipping_charge!='0.00'){{$orderSession->order_symbol}} {{round($orderDetail->shipping_charge)}}@else{{"-"}}@endif</span>
                                      <input type="hidden" name="shipping_charge" id="shipping_charge" value="@if($orderDetail->shipping_charge!='0.00'){{round($orderDetail->shipping_charge)}}@endif" />
                                    </td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">Discount Amount<br/><textarea class="form-control" placeholder="Comment" id="discount_comment" name="discount_comment">{{$orderDetail->discount_comment}}</textarea></td>
                                    <td>
                                      <br/><input type="text" class="form-control" onkeyup="calculateTotal();" name="discount_amount" id="discount_amount" placeholder="Amount" number="true" @if($orderDetail->discount_amount!=0.00)value="{{round($orderDetail->discount_amount)}}"@endif/>
                                    </td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" align="right">Order Total</td>
                                    <td>
                                    <span id="cart_total">{{$orderSession->order_symbol}} {{round($orderDetail->grand_total-$orderDetail->discount_amount)}}</span>
                                    <input type="hidden" name="total" id="total" value="{{round($orderDetail->grand_total)}}" />
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
                      <option value="0" @if($orderDetail->fkcustomer_id==0) selected @endif>Guest</option>
                      @if(isset($customers) && !empty($customers))
                        @foreach($customers as $customer)
                          <option value="{{$customer->id}}" @if($customer->id==$orderDetail->fkcustomer_id) selected @endif>{{ucwords($customer->name)}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="control-label">E-Mail ID</label>
                    <input type="email" name="email" id="email" class="form-control" required value="{{$orderAddress->billing_email}}" />
                  </div>
                  <div class="col-md-4">
                    <label class="control-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" required number="true" value="{{$orderAddress->billing_phone}}" />
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
                    <input type="text" name="billing_name" id="billing_name" class="form-control billAddress" required value="{{$orderAddress->billing_name}}" />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address</label>
                    <input type="text" name="billing_address1" id="billing_address1" class="form-control billAddress" required value="{{$orderAddress->billing_address1}}"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address Line 2</label>
                    <input type="text" name="billing_address2" id="billing_address2" class="form-control billAddress"  value="{{$orderAddress->billing_address2}}"  />
                  </div>

                  <div class="form-group">
                    <label class="control-label">City</label>
                    <input type="text" name="billing_city" id="billing_city" class="form-control billAddress" required  value="{{$orderAddress->billing_city}}"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">State</label>
                    <input type="text" name="billing_state" id="billing_state" class="form-control billAddress" required  value="{{$orderAddress->billing_state}}"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Country</label>
                    <select name="billing_country" id="billing_country" class="form-control billAddress" required>
                      <option value="">Select Country</option>
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}" @if($key==$orderAddress->billing_country) selected @endif>{{$value}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Postal Code</label>
                    <input type="text" name="billing_zip" id="billing_zip" class="form-control billAddress" required  value="{{$orderAddress->billing_zip}}" />
                  </div>

              </div>



               <div class="col-md-5 col-md-offset-2">

                <div class="form-group">

                  <h4>Shipping Address</h4>

                </div>

                <div class="form-group">
                    <input type="checkbox" name="same_as_billing" id="same_as_billing" value="1" class="" @if($orderAddress->same_as_billing==1) checked @endif/> Same as billing address
                  </div>

                <div id="shippingAddress" @if($orderAddress->same_as_billing==1) style="display:none;" @endif>

                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" name="shipping_name" id="shipping_name" class="form-control shipAddress" required value="{{$orderAddress->shipping_name}}" />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address</label>
                    <input type="text" name="shipping_address1" id="shipping_address1" class="form-control shipAddress" required value="{{$orderAddress->shipping_address1}}" />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Address Line 2</label>
                    <input type="text" name="shipping_address2" id="shipping_address2" class="form-control shipAddress" value="{{$orderAddress->shipping_address2}}"  />
                  </div>

                  <div class="form-group">
                    <label class="control-label">City</label>
                    <input type="text" name="shipping_city" id="shipping_city" class="form-control shipAddress" required value="{{$orderAddress->shipping_city}}" />
                  </div>

                  <div class="form-group">
                    <label class="control-label">State</label>
                    <input type="text" name="shipping_state" id="shipping_state" class="form-control shipAddress" required value="{{$orderAddress->shipping_state}}" />
                  </div>

                  <div class="form-group">
                    <label class="control-label">Country</label>
                    <select name="shipping_country" id="shipping_country" class="form-control shipAddress" required>
                      <option value="">Select Country</option>
                      @foreach($countries as $key=>$value)
                        <option value="{{$key}}" @if($key==$orderAddress->shipping_country) selected @endif>{{$value}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Postal Code</label>
                    <input type="text" name="shipping_zip" id="shipping_zip" class="form-control shipAddress" required  value="{{$orderAddress->shipping_zip}}"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">E-Mail ID</label>
                    <input type="email" name="semail" id="semail" class="form-control shipAddress" required  value="{{$orderAddress->shipping_email}}"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input type="text" name="sphone" id="sphone" class="form-control shipAddress" required number="true"  value="{{$orderAddress->shipping_phone}}"/>
                  </div>

                </div>

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

            
<div class="col-md-12">

<h3>Order Notes</h3>


<div class="col-md-12">


<table class="table table-striped no-margn" style="margin-bottom:20px;">
                            <thead>
                                <tr>
                                    <th width="60%">Note</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Posted On</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderNotes) && !empty($orderNotes))

                                  @foreach($orderNotes as $note)

                                    <tr>
                                      <td>{{$note->notes}}</td>
                                      <td>@if($note->type==1){{"Customer Note"}}@else{{"Private Note"}}@endif</td>
                                      <td>{{date('d-m-Y H:i:s',strtotime($note->created_at))}}</td>
                                      <td>
                                          <a class="btn btn-xs btn-danger removeNote" data-id="{{$note->id}}" href="javascript:void(0)"><i class="fa fa-close"></i> Remove</a> 
                                      </td>
                                    </tr>

                                    <?php //$i++; ?>

                                  @endforeach

                                @endif
                            </tbody>
                          </table>

</div>

<div class="col-md-6 col-md-offset-3" style="margin-top:20px;">


<div class="form-group">
  <textarea class="form-control" name="order_note" id="order_note" placeholder="Order Note"  style="height:100px;"></textarea>
</div>

<div class="form-group">
    <select name="type" id="type" class="chosen-select" >
      <option value="1">Customer Note</option>
      <option value="2">Private Note</option>
    </select>
</div>






</div>

</div>

            
                    
            
                    

</div>


<div class="col-md-3">

<div class="col-md-12">

<h4>Source</h4>

<p>@if(!empty($orderSession->referrer)){{$orderSession->referrer}}@else{{"Unknown"}}@endif</p>


<h4>Order Currency</h4>

<p>{{$currencySelected." - ".$currencies[$currencySelected]}}</p>

<div class="form-group">
<label class="control-label">Payment Type</label>
    <select name="payment_type" id="payment_type" class="chosen-select" required>
      @if(isset($paymentType) && !empty($paymentType))

        @foreach($paymentType as $key=>$value)
          <option value="{{$key}}" @if($orderDetail->payment_type==$key) selected @endif>{{$value}}</option>
        @endforeach

      @endif
    </select>
</div>


<div class="form-group">
<label class="control-label">Order Status</label>
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
<input type="text" number="true" class="form-control" name="refund_amount" id="refund_amount" required @if($orderDetail->refund_amount!=0.00)value="{{round($orderDetail->refund_amount)}}"@endif />
</div>

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
	<form action="{{URL::to('admin/mail-contents/send/'.$orderDetail->id)}}" method="post" class="email-content-form" enctype="multipart/form-data">
		<label class="control-label">Email Content</label>
		<select name="email_content_id" id="email_content_id" class="chosen-select email_content_id" required>
		  <option value="">Select Email Content</option>
		  @if(isset($mailContents) && !empty($mailContents))
			@foreach($mailContents as $mailContents)
			  <option value="{{$mailContents->id}}" >{{ucwords($mailContents->title)}}</option>
			@endforeach

		  @endif
		</select>
		<div class="col-md-12 no-padding top10">
		<button type="submit" name="email-submit" class="btn btn-primary btn-xs" >Send Email</button>
		</div>
	</form>
</div>
<div class="form-group">
	<table name="email-content-list" class="table table-striped no-margn">
		<thead>
			<tr>
				<th>S.No</th>
				<th>Title</th>
			</tr>
		</thead>
		<tbody>
			@foreach($mailContentsTrackList as $key=>$val)
			<tr>
				<td>{{$key+1}}</td>
				<td>{{$val->title}}</td>
			</tr>
			@endforeach
		</tbody>
		
		
	</table>
</div>

</div>


<div class="col-md-12 no-padding">
<div class="form-group no-padding">
<h4>Download PDF</h4>
</div>
<div class="form-group no-padding">
<a target="_blank" href="{{URL::to('/admin/orders/download-invoice/'.$orderDetail->id)}}"><button class="btn btn-sm btn-primary" type="button">Invoice</button></a>
</div>
<div class="form-group no-padding">
<a target="_blank" href="{{URL::to('/admin/orders/download-packing-slip/'.$orderDetail->id)}}"><button class="btn btn-sm btn-primary" type="button">Packing Slip</button></a>
</div>
</div>

</div>


<div class="col-md-12">

	<div class="form-group">
		<label class="col-sm-2 control-label">Packed Image</label>
		<div class="col-sm-4" style="margin: 0 0 0 2%;">							
			<input type="file" required="" class="form-control" id="file" value="" name="packed_file" maxlength="3">
		</div>
	</div>
	@if(!empty($orderDetail->packed_order_image) && $orderDetail->packed_order_image != "")
		<div class="col-md-12" style="float:none;">
			<img src="{{URL::to('public/uploads/packed_images/'.$orderDetail->packed_order_image.'')}}" alt="{{$orderDetail->packed_order_image}}"  class="col-md-6 col-md-offset-2" style="margin-bottom: 2%;margin-top: 2%;"/>
			<div class="clear"></div>
		</div>
	@endif
<div class="form-group">

<div class="col-md-offset-4 col-md-6">

<input type="hidden" name="region" id="region" value="{{$orderSession->order_region}}" />
<input type="hidden" name="currency" id="currency" value="{{$currencySelected}}" />
<input type="hidden" name="symbol" id="symbol" value="{{$orderSession->order_symbol}}" />




<button class="btn btn-sm btn-primary" type="submit">Update</button>

<?php
  $status = 'process';
  if($orderDetail->order_status<=4) {
    $status = 'process';
  } else if($orderDetail->order_status==5) {
    $status = 'refunded';
  } else if($orderDetail->order_status==6) {
    $status = 'completed';
  } else if($orderDetail->order_status==7) {
    $status = 'cancelled';
  }
?>

<a href="{{URL::to('admin/orders/'.$status.'/'.$orderDetail->id)}}" class="btn btn-sm btn-default">Cancel</a>

</div>

</div>

</div>



</div>

</form>


            
            
        </div>


@endsection

@section('customJs')
<script src="{{URL::asset('public/admin/js/clipboard.min.js')}}"></script>
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

            $("#addProduct").click(function(){
              var counter = Number($('#counter').val());
              counter = ++counter;

              var html = '<tr id="row_'+counter+'"><td><select class="chosen-select" name="product[]" id="product_'+counter+'" required onchange="return checkProductPrice(this.id,'+counter+');">'+productHtml+'</select></td>';
                  html += '<td><input type="text" number="true" name="price[]" id="price_'+counter+'" readonly class="form-control" /></td>';
                  html += '<td><input type="text" number="true" maxlength="3" name="quantity[]" id="quantity_'+counter+'" class="form-control" required onkeyup="calculateTotal();" /></td>';
                  html += '<td><span class="total_price_'+counter+'"></span>';
                  html += '<input type="hidden" name="total_price[]" id="total_price_'+counter+'" readonly class="form-control total_price_'+counter+'" />';
                  html += '<input type="hidden" name="product_type[]" id="product_type_'+counter+'" readonly class="form-control" />';
                  html += '</td><td><a href="javascript:void(0)" data-id="'+counter+'" data-type="0" class="removeProduct btn btn-xs btn-danger"><i class="fa fa-close"></i></a></td></tr>';

              $('#productDetail').append(html);

              $('#counter').val(counter);

              $('#product_'+counter).chosen();

            });

            $(document).on('click',".removeProduct",function (){   
                var id   = $(this).data('id');
                var type   = $(this).data('type');
                if(type!=0) {
                  var confirmMsg = confirm('Are you sure want to remove this item?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/orders/delete-items/'.$orderSession->id.'/'.$orderDetail->id)}}/"+type;
                  }
                } else {
                  $("#row_"+id).remove();
                  calculateTotal();
                }
            });


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
                    window.location = "{{URL::to('admin/orders/remove-tracking/edit/'.$orderDetail->id)}}/"+id;
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

		
	var clipboard = new Clipboard('#btn_copy', {
		target: function() {
			//alert('Shipping Address copied!');
			return document.querySelector('#address_copy');
		}
	});

	clipboard.on('success', function(e) {
		console.log(e);
	});

	clipboard.on('error', function(e) {
		console.log(e);
	});


    </script>

@endsection