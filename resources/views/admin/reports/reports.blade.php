@extends('admin.layout.tpl')
@section('customCss')

	<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection
@section('content')     	
<div class="page-header"><h1>Reports</h1></div>
<div class="warper container-fluid">
        	
            <div class="page-header"><h1> <small></small></h1></div>
            
			<form id="filter-form" class="form-horizontal" role="form" method="post">
				
				<div class="form-group">
					<label class="col-sm-3 control-label">From Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' class="form-control from_date" name="from_date" data-date-format="DD-MM-YYYY" value="@if($from_date){{$from_date}}@endif"  />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">To Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' class="form-control to_date" name="to_date" data-date-format="DD-MM-YYYY" value="@if($to_date){{$to_date}}@endif" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" style="margin-top:20px;">
					<div class="col-sm-7 col-sm-offset-4">
					  <button type="button" class="btn btn-primary btn-sm"  onclick="setAction('reports');">Filter</button>
					  <button type="button" class="btn btn-info btn-sm" onclick="setAction('reports-export');">Export As Excel</button>
					  <a href="{{URL::to('admin/reports')}}" class="btn btn-warning btn-sm">Cancel</a>
					</div>
				</div>
			</form>           
            <div class="row">
            </div>            
            <?php $i=1; ?>
            <div class="row">
            	<div class="col-lg-12">
                
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th style="width:10%">S.No</th>
                                    <th style="width:10%">Invoice No</th>
                                    <th style="width:15%">Customer Name</th>
                                    <th style="width:10%;">Amount</th>
                                    <th style="width:10%;">shipping_charge</th>
                                    <th style="width:10%;">Status</th>
                                    <th style="width:25%">Ship To</th>
                                    <th style="width:25%">Billing Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderDetails) && !empty($orderDetails))

                                  @foreach($orderDetails as $detail)

                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{$detail->invoice_no}}</td>
                                      <td>{{ucwords($detail->billing_name)}} @if($detail->fkcustomer_id==0) {{" - Guest"}}@endif</td>
                                      <td>{{$detail->order_symbol}} {{round($detail->grand_total)}}<br/>via {{$paymentType[$detail->payment_type]}}</td>
                                      <td>{{$detail->order_symbol}} {{round($detail->grand_total)}}<br/>via {{$paymentType[$detail->payment_type]}}</td>
                                      <td>@if($detail->order_status!=NULL){{$orderStatus[$detail->order_status]}}@endif</td>
                                      <td>
                                        {{ucwords($detail->shipping_name)}},
                                        {{$detail->shipping_address1}},@if(!empty($detail->shipping_address2)){{$detail->shipping_address2}},@endif
                                        {{$detail->shipping_city}},{{$detail->shipping_state}},{{$countries[$detail->shipping_country]}},{{$detail->shipping_zip}}
                                      </td>
                                      <td>
                                         {{ucwords($detail->billing_name)}},
                                        {{$detail->billing_address1}},@if(!empty($detail->billing_address2)){{$detail->billing_address2}},@endif
                                        {{$detail->billing_city}},{{$detail->billing_state}},{{$countries[$detail->billing_country]}},{{$detail->billing_zip}}
                                      </td>
                                    </tr>

                                    <?php $i++; ?>

                                  @endforeach

                                @endif
                            </tbody>
                          </table>
                </div>
                
            </div>
            
        </div>
@endsection
@section('customJs')

  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>
<script>
$(document).ready(function (){
	$('#datatable').dataTable();
});
function setAction(url){
	$('#filter-form').prop("action","{{URL::to('/admin/')}}"+url);
	$('#filter-form').submit();
}
</script>
@endsection