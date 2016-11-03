@extends('admin.layout.tpl')
@section('customCss')

<style type="text/css">

.amountAlign {
	text-align:right;
}

th {
	color:#000;
}

</style>

@endsection
@section('content')     	
<div class="page-header"><h1>Accounting</h1></div>
<div class="warper container-fluid">
        	
            <div class="page-header"><h1> <small></small></h1></div>
            
			<form id="filter-form" class="form-horizontal" role="form" method="post">
				
				<div class="form-group">
					<label class="col-sm-3 control-label">From Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' readonly class="form-control from_date" name="from_date" data-date-format="DD-MM-YYYY" value="@if(isset($fromDate)){{date('d-m-Y',strtotime($fromDate))}}@endif"  />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">To Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' readonly class="form-control to_date" name="to_date" data-date-format="DD-MM-YYYY" value="@if(isset($toDate)){{date('d-m-Y',strtotime($toDate))}}@endif" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" style="margin-top:20px;">
					<div class="col-sm-7 col-sm-offset-4">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
					  
					</div>
				</div>
        <div class="form-group pull-right" style="margin-right:10px;">
              <a href="javascript:void(0)" class="btn btn-info btn-sm" onclick="printDiv('printArea')">Print</a>
        </div>
			</form>           
                   
            <?php $i=1; ?>
            <div class="row" id="printArea" style="margin-top:50px;">
            	<div class="col-lg-12">

            	<h3 style="text-align:center;">Accounting Period ({{date('d-m-Y',strtotime($fromDate))}} to {{date('d-m-Y',strtotime($toDate))}})</h3>

                <table class="table table-bordered">
					
					<tr>
						<th></th>
						<th colspan="2">Sales within maharasthra</th>
						<th colspan="2">Sales outside maharasthra</th>
						<th colspan="2">International Sales</th>
						<th>Total Sales</th>
					</tr>
					
					
					<tr>
						<th>Amount</th>
						<td colspan="2" class="amountAlign">
							@if(isset($salesMaharastra->amount))
								{{round($salesMaharastra->amount)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesRoi->amount))
								{{round($salesRoi->amount)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesInternational->amount))
								{{round($salesInternational->amount)}}
							@endif
						</td>
						<td class="amountAlign">							{{round($salesMaharastra->amount+$salesRoi->amount+$salesInternational->amount)}}
						</td>
					</tr>
					
					
					<tr>
						<th>Shipping Charges</th>
						<td colspan="2" class="amountAlign">
							@if(isset($salesMaharastra->shippingCharge))
								{{round($salesMaharastra->shippingCharge)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesRoi->shippingCharge))
								{{round($salesRoi->shippingCharge)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesInternational->shippingCharge))
								{{round($salesInternational->shippingCharge)}}
							@endif
						</td>
						<td class="amountAlign">							{{round($salesMaharastra->shippingCharge+$salesRoi->shippingCharge+$salesInternational->shippingCharge)}}
						</td>
					</tr>
					
					
					
					
					<tr>
						<th>Number of Orders</th>
						<td colspan="2" class="amountAlign">
							@if(isset($salesMaharastra->count))
								{{round($salesMaharastra->count)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesRoi->count))
								{{round($salesRoi->count)}}
							@endif
						</td>
						<td colspan="2" class="amountAlign">
							@if(isset($salesInternational->count))
								{{round($salesInternational->count)}}
							@endif
						</td>
						<td class="amountAlign">							{{round($salesMaharastra->count+$salesRoi->count+$salesInternational->count)}}
						</td>
					</tr>
					
					@if(isset($productSale) && !empty($productSale))
					
					<tr>
						<th colspan="8">
							<h4>Product Units Sold</h4>
						</th>
					</tr>
						
					@foreach($productSale as $key=>$pro)
					
						<?php
							$productName = '';
							if(isset($products) && !empty($products)) {
								
								foreach($products as $prod) {
									if($prod->id==$pro['product_id']) {
										$productName = $prod->name;
									}
								}
								
							}
						?>
						
						<tr>
							<th>{{ucwords($productName)}}</th>
							<td class="amountAlign">{{$pro[1]['qty']}}</td>
							<td class="amountAlign">{{$pro[1]['price']}}</td>
							<td class="amountAlign">{{$pro[2]['qty']}}</td>
							<td class="amountAlign">{{$pro[2]['price']}}</td>
							<td class="amountAlign">{{$pro[3]['qty']}}</td>
							<td class="amountAlign">{{$pro[3]['price']}}</td>
							<td></td>
						</tr>
					
					@endforeach
					
					@endif
					
					
					@if(isset($kitproductSale) && !empty($kitproductSale))
					
					<tr>
						<th colspan="8">
							<h4>Kits Sold</h4>
						</th>
					</tr>
						
					@foreach($kitproductSale as $key=>$pro)
					
						<?php
							$productName = '';
							if(isset($products) && !empty($products)) {
								
								foreach($products as $prod) {
									if($prod->id==$pro['product_id']) {
										$productName = $prod->name;
									}
								}
								
							}
						?>
						
						<tr>
							<th>{{ucwords($productName)}}</th>
							<td class="amountAlign">{{$pro[1]['qty']}}</td>
							<td class="amountAlign">{{$pro[1]['price']}}</td>
							<td class="amountAlign">{{$pro[2]['qty']}}</td>
							<td class="amountAlign">{{$pro[2]['price']}}</td>
							<td class="amountAlign">{{$pro[3]['qty']}}</td>
							<td class="amountAlign">{{$pro[3]['price']}}</td>
							<td></td>
						</tr>
					
					@endforeach
					
					@endif
					
					
				
				
				</table>


					


                    


                </div>
                
            </div>
            
        </div>
@endsection
@section('customJs')

<script>
$(document).ready(function (){

});

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
@endsection