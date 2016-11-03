@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Orders</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
                         <div class="panel-heading"><a data-toggle="modal" data-target="#addNote" href="javascript:void(0)" class="btn btn-sm btn-primary">Add Order</a></div>
                         <div class="panel-body">

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



                            <form id="filter-form" class="form-horizontal" role="form" method="post">

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">From Date</label>
                                    <div class="col-sm-4">
                                      <div class='input-group date datepicker' >
                                        <input type='text' readonly class="form-control from_date" name="fromdate" data-date-format="DD-MM-YYYY" value="@if(isset($fromdate) && !empty($fromdate)){{date('d-m-Y',strtotime($fromdate))}}@endif"  />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">To Date</label>
                                    <div class="col-sm-4">
                                      <div class='input-group date datepicker' >
                                        <input type='text' readonly class="form-control from_date" name="todate" data-date-format="DD-MM-YYYY" value="@if(isset($todate) && !empty($todate)){{date('d-m-Y',strtotime($todate))}}@endif"  />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">Products</label>
                                    <div class="col-sm-4">
                                    <select name="product" id="product" class="chosen-select">
                                        <option value="">Select Product</option>                                        
                                        @if(isset($products) && !empty($products))
                                          @foreach($products as $pro)
                                            <option value="{{$pro->id}}" @if($pro->id==$productSelected) selected @endif>{{ucwords($pro->name)}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">Order Location</label>
                                    <div class="col-sm-4">
                                    <select name="country" id="country" class="chosen-select">
                                        <option value="">Select Location</option>                                        
                                        @if(isset($countries) && !empty($countries))
                                          @foreach($countries as $key=>$value)
                                            <option value="{{$key}}" @if($key==$countrySelected) selected @endif>{{$value}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">Couriers</label>
                                    <div class="col-sm-4">
                                    <select name="courier" id="courier" class="chosen-select">
                                        <option value="">Select Courier</option>                                        
                                        @if(isset($couriers) && !empty($couriers))
                                          @foreach($couriers as $cour)
                                            <option value="{{$cour->id}}" @if($cour->id==$courierSelected) selected @endif>{{ucwords($cour->name)}}</option>
                                          @endforeach
                                        @endif
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">Tracking Number</label>
                                    <div class="col-sm-4">
                                      <input type="text" name="tracking_number" id="tracking_number" class="form-control" value="@if(isset($tracking_number)){{$tracking_number}}@endif">
                                    </div>
                                  </div>


                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                      <a href="{{URL::to('admin/orders/all')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div>

                          </form>

                            <?php $i=1; ?>
<form id="applyAction" method="post" class="" role="form" action="{{URL::to('admin/orders/apply-action')}}">
<div class="form-group">
<div class="row">
<div class="col-md-2">
                                  <select class="form-control  form-control-flat input-sm" id="action" name="action">
                                      <option value="">Actions</option>
                                      <option value="trash">Move to Trash</option>
                                      <option value="delete">Delete Permanently</option>
                                    </select>
                                    </div>
                                    <div class="col-md-2">
                                    <input type="hidden" name="page" id="page" value="all">
                                    <button class="btn btn-default btn-sm btn-flat applyAction" type="button">Apply</button>
                                    </div>
                                    </div>
                                   </div>

                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:10%">Invoice No</th>
                                    <th style="width:15%">Customer Name</th>
                                    <th style="width:10%;">Purchased</th>
                                    <th style="width:25%">Ship To</th>
                                    <th style="width:10%;">Amount</th>
                                    <th style="width:10%;">Status</th>
                                    <th style="width:5%">Order Receipt</th>
                                    <th style="width:5%">Diet Chart</th>
                                    <th style="width:10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderDetails) && !empty($orderDetails))

                                  @foreach($orderDetails as $detail)

                                    <tr>
                                      <td>
                                      <label class="col-sm-3 control-label cr-styled">
                                          <input type="checkbox"  name="checkOrder[]" id="checkOrder[]" value="{{$detail->id}}">
                                          <i class="fa"></i> 
                                      </label>
<!--                                       <input type="checkbox" name="checkOrder[]" id="checkOrder" value="{{$detail->id}}" />
 -->                                      </td>
                                      <td>{{$detail->invoice_no}}</td>
                                      <td>{{ucwords($detail->billing_name)}} @if($detail->fkcustomer_id==0) {{" - Guest"}}@endif</td>
                                      <td><a data-toggle="modal" onclick="getProduct('{{$detail->invoice_no}}','{{$detail->oid}}');" data-target="#getProduct" style="font-weight:bold;" href="javascript:void(0)">@if($detail->items>1){{$detail->items." items"}}@else{{$detail->items." item"}}@endif</a></td>
                                      <td>
                                        {{ucwords($detail->shipping_name)}},
                                        {{$detail->shipping_address1}},@if(!empty($detail->shipping_address2)){{$detail->shipping_address2}},@endif
                                        {{$detail->shipping_city}},{{$detail->shipping_state}},@if(!empty($detail->shipping_country) && isset($countries[$detail->shipping_country])){{$countries[$detail->shipping_country]}},@endif{{$detail->shipping_zip}}
                                        @if(!empty($detail->shipping_email))<br/><strong>E:</strong><i> {{$detail->shipping_email}}</i>@endif
                                        @if(!empty($detail->shipping_phone))<br/><strong>Ph:</strong><i> {{$detail->shipping_phone}}</i>@endif
                                      </td>
                                      <td>{{$detail->order_symbol}} {{round($detail->grand_total)}}
                                      @if($detail->order_symbol!=$inrSymbol->symbol)
                                      <br/>({{$inrSymbol->symbol}} {{round($detail->grand_total_inr)}})
                                      @endif
                                    @if(!empty($detail->payment_type))  <br/>via {{$paymentType[$detail->payment_type]}} @endif</td>
                                      <td>@if($detail->order_status!=NULL){{$orderStatus[$detail->order_status]}}@elseif($detail->order_status==0) Placed @endif</td>
                                     <td style="text-align:center;">
                                      @if($detail->order_email==1)
                                      <a class="btn btn-xs btn-success" href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                      @endif
                                      </td>
                                      <td style="text-align:center;">
                                      @if($detail->dietChartCount>0)
                                      <a class="btn btn-xs btn-success" href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                      @endif
                                      </td>
                                      <td>
                                          <?php
                                            if($detail->order_status<=4) {
                                              $url = 'process';
                                            } elseif($detail->order_status==5) {
                                              $url = 'refunded';
                                            } elseif($detail->order_status==6) {
                                              $url = 'completed';
                                            } elseif($detail->order_status==7) {
                                              $url = 'cancelled';
                                            } else {
                                              $url = 'process';
                                            }
                                          ?>
                                          <!-- <a class="btn btn-xs btn-info" href="{{URL::to('admin/orders/'.$url.'/'.$detail->id)}}">View</a> -->
                                           <a class="btn btn-xs btn-info" href="{{URL::to('admin/orders/edit-ordered-items/'.$detail->oid.'/'.$detail->id)}}">View</a>&nbsp;
                                          <a class="btn btn-xs btn-danger deleteOrder" data-id="{{$detail->id}}" href="javascript:void(0)">Move to Trash</a> 
                                      </td>
                                    </tr>

                                    <?php $i++; ?>

                                  @endforeach

                                @endif
                            </tbody>
                          </table>
                           </form>
                          
                        </div>
                    </div>
                 </div>
            
            </div>


    <!-- Modal -->
    <div class="modal fade" id="addNote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Order Currency</h4>
          </div>
          <form role="form" id="currency-form" method="get" action="{{URL::to('admin/orders/add')}}">
          <div class="modal-body">

           <?php
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            $baseCurrency      = $enabledCurrency->base_currency;
           ?>
            
            
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                        <label for="currency">Select Currency</label>
                        <select class="form-control" required id="currency" name="currency">
                          @if(isset($enabledCurrencies) && !empty($enabledCurrencies))
                            @foreach($enabledCurrencies as $key=>$val)
                              <option value="{{$val}}" @if($baseCurrency==$val) selected @endif>{{$currencies[$val]}}</option>
                            @endforeach
                          @endif
                        </select>
                      </div>
                </div>
              </div>
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Proceed</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    

    <div class="modal fade" id="getProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Product Details for Order No : <span id="orderNo"></span></h4>
          </div>
          <form role="form" id="currency-form" method="get" action="{{URL::to('admin/orders/add')}}">
          <div class="modal-body">

            
            
              <div class="row">
                <div class="col-lg-12">
                  <table class="table">

                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>                       
                      </tr>
                    </thead>

                    <tbody id="productDetails">
                      
                    </tbody>
                    
                  </table>
                </div>
              </div>
            
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          </form>
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
          $(document).on("click",".deleteOrder",function() {
            var id = $(this).data('id');
            var confirmMsg = confirm('Are you sure want to move this order to trash?');
            if(confirmMsg) {
              window.location.href = "{{URL::to('admin/orders/delete')}}/"+id;
            }
          });

          $(document).on("click",".applyAction",function() {
            var value = $('#action').val();
            var count = $("[type='checkbox']:checked").length;
            //alert(count);
            if(count==0) {
              alert('Select data to proceed');
              return false;
            }
            if(value!='') {
              var confirmMsg = confirm('Are you sure want to apply this action?');
              if(confirmMsg) {
                $('#applyAction').submit();
              }
            }
          });
        });

        function getProduct(invoice_no,id) {
          $('#productDetails').html('');
          $('#orderNo').html(invoice_no);
          $.ajax({
                    url: "{{URL::to('admin/orders/get-product')}}/"+id,
                    method: 'POST',
                    success: function(responses){
                      $.each(responses, function(key, response) {
                        if(key=='orderProducts') {
                          $.each(response, function(key1, response1) {
                            var html = '<tr id='+response1.id+'>';
                            html += '<td><span style="font-weight:600;">'+response1.product_name+'</span></td>';
                            html += '<td>'+response1.product_qty+'</td>';
                            html += '</tr>';
                            $('#productDetails').append(html);
                          });
                        }
                        if(key=='orderBundleProducts') {
                          $.each(response, function(key1, response1) {
                            var html = '<tr>';
                            html += '<td><span style="margin-left:10px;">'+response1.product_name+'</span></td>';
                            html += '<td><span style="margin-left:0px;">'+response1.product_qty+'</span></td>';
                            html += '</tr>';
                            $('#'+response1.order_product_id).after(html);
                          });
                        }
                      });
                    }
          });
        } 
    </script>

@endsection