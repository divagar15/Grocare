@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Orders</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
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



                          

                            <?php $i=1; ?>

                        <form id="tracking-form" method="post">

                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:10%">Invoice No</th>
                                    <th style="width:15%">Customer Name</th>
                                    <th style="width:10%;">Purchased</th>
                                    <th style="width:25%">Ship To</th>
                                    <th style="width:25%;">Tracking Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderDetails) && !empty($orderDetails))

                                  @foreach($orderDetails as $detail)

                                    <tr>
                                      <td>
                                        {{$i}}
                                      </td>
                                      <td>{{$detail->invoice_no}}</td>
                                      <td>{{ucwords($detail->billing_name)}}</td>
                                      <td><a data-toggle="modal" onclick="getProduct('{{$detail->invoice_no}}','{{$detail->oid}}');" data-target="#getProduct" style="font-weight:bold;" href="javascript:void(0)">@if($detail->items>1){{$detail->items." items"}}@else{{$detail->items." item"}}@endif</a></td>
                                      <td>
                                        {{ucwords($detail->shipping_name)}},
                                        {{$detail->shipping_address1}},@if(!empty($detail->shipping_address2)){{$detail->shipping_address2}},@endif
                                        {{$detail->shipping_city}},{{$detail->shipping_state}},@if(!empty($detail->shipping_country)){{$countries[$detail->shipping_country]}},@endif{{$detail->shipping_zip}}
                                      @if(!empty($detail->shipping_email))<br/><strong>E:</strong><i> {{$detail->shipping_email}}</i>@endif
                                        @if(!empty($detail->shipping_phone))<br/><strong>Ph:</strong><i> {{$detail->shipping_phone}}</i>@endif
                                      </td>
                                      
                                      <td>

                                      <?php
                                        $trackingNumber1 = '';
                                        $trackingNumber2 = '';
                                        $tid1 = 0;
                                        $tid2 = 0;

                                        $k=1;
                                      ?>

                                      @if(isset($orderTracking) && !empty($orderTracking))

                                        @foreach($orderTracking as $track)

                                          @if($track->order_id==$detail->id)


                                          <?php

                                            if($k==1) {

                                              $trackingNumber1 = $track->tracking_number;
                                              $tid1 = $track->id;

                                            } elseif($k==2) {

                                              $trackingNumber2 = $track->tracking_number;
                                              $tid2 = $track->id;

                                            }

                                            $k++;
                                          ?>


                                          @endif

                                        @endforeach

                                      @endif

                                      <input type="text" class="form-control" name="trackingNumber1[{{$detail->id}}_{{$tid1}}]" value="{{$trackingNumber1}}"><br/>
                                      <input type="text" class="form-control" name="trackingNumber2[{{$detail->id}}_{{$tid2}}]" value="{{$trackingNumber2}}">
   
                                           
                                      </td>
                                    </tr>

                                    <?php $i++; ?>

                                  @endforeach

                                @endif
                            </tbody>
                          </table>

                          <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-4">
                                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                      <a href="{{URL::to('admin/courier-login/list')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div>

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