@extends('admin.layout.tpl')
@section('customCss')


@endsection
@section('content')     	

<?php
$countries = Config::get('custom.country');
?>

<div class="page-header"><h1>Order Reports</h1></div>
<div class="warper container-fluid">
        	
            <div class="page-header"><h1> <small></small></h1></div>
            
			<form id="filter-form" class="form-horizontal" role="form" method="post">
				
				<div class="form-group">
					<label class="col-sm-3 control-label">From Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' readonly class="form-control from_date" name="from_date" id="from_date" data-date-format="DD-MM-YYYY" value="@if(isset($fromDate)){{date('d-m-Y',strtotime($fromDate))}}@endif"  />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">To Date</label>
					<div class="col-sm-6">
					  <div class='input-group date datepicker' >
							<input type='text' readonly class="form-control to_date" name="to_date" id="to_date" data-date-format="DD-MM-YYYY" value="@if(isset($toDate)){{date('d-m-Y',strtotime($toDate))}}@endif" />
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" style="margin-top:20px;">
					<div class="col-sm-7 col-sm-offset-4">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
					  <a href="{{URL::to('admin/reports/order')}}" class="btn btn-warning btn-sm">Cancel</a>
					</div>
				</div>
      <!--   <div class="form-group pull-right">
              <a href="" class="btn btn-info btn-sm">Export As Excel</a>
        </div> -->
			</form>           
            <div class="row" style="margin-top:50px;">
            </div>            
            <?php $i=1; ?>
            <div class="row">
            	<div class="col-lg-12">

                <div class="col-lg-3">

                  <div class="col-lg-12">
                  <div class="panel panel-default">
                        <div class="panel-body">
                          <h4>Rs. {{$salesAmount}}</h4>
                          <p>Sales in this period</p>
                        </div>
                        <div class="panel-body">
                          <h4>Rs. {{$averageSales}}</h4>
                          @if($difference>31)
                          <p>Average monthly sales</p>
                          @else
                          <p>Average daily sales</p>
                          @endif
                        </div>
                        <div class="panel-body">
                          <h4>{{$orderPlaced}}</h4>
                          <p>Orders placed</p>
                        </div>
                        <div class="panel-body">
                          <h4>{{$itemsPurchased}}</h4>
                          <p>Items purchased</p>
                        </div>
                        <div class="panel-body">
                          <h4>Rs. {{$shippingCharge}}</h4>
                          <p>Shipping charge</p>
                        </div>
						@if(!empty($source))
							<div class="panel-body">
							  <h4>Source</h4>
							  @foreach($source as $key=>$value)
							  <p style="word-wrap: break-word;">{{$key}} - {{$value}}</p>
							  @endforeach
							</div>
						@endif
                    </div>
                  </div>


                </div>


                <div class="col-lg-9">

                  <div id="line-chart" style="height:450px;"></div>
				
				  <h3>Total Orders</h3>
                  <div id="bar-chart" style="height:450px; margin-top:30px;"></div>
				  
				  <h3>International Orders</h3>
				  <select name="countries" id="countries" class="form-control">
					<option value="">Select Country</option>
					@if(isset($countries) && !empty($countries))
						@foreach($countries as $key=>$value)
							<option value="{{$key}}">{{$value}}</option>
						@endforeach
					@endif
				  </select>
				  <div id="bar-chart-international" style="height:450px; margin-top:30px;"></div>


                </div>


                    


                </div>
                
            </div>
            
        </div>
@endsection
@section('customJs')

<script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/dx.chartjs.js')}}"></script>
<script>
$(document).ready(function (){

$("#line-chart").dxChart({
    dataSource: <?php echo $response; ?>,
    commonSeriesSettings: {
        argumentField: "year"
    },
    series: [
        { valueField: "sales", name: "Sales", color: "#27c24c" },
        @if($difference>31)
          { valueField: "monthAverageSale", name: "Average Sale", color: "#f0ad4e" },
        @endif
       // { valueField: "orderPerDay", name: "Total Orders", color: "#f0ad4e" },
       // { valueField: "totalInternationOrders", name: "International Orders", color: "#428bca" }
    ],
    tooltip:{
        enabled: true,
    font: { size: 16 }
    },
    legend: {
        visible: true
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  }
});

/*
$("#bar-chart").dxChart({
    dataSource: <?php echo $response; ?>,
    commonSeriesSettings: {
        argumentField: "year",
        type:"bar"
    },
    series: [
        { valueField: "orderPerDay", name: "Total Orders", color: "#f0ad4e" },
        { valueField: "totalInternationOrders", name: "International Orders", color: "#428bca" }
    ],
    tooltip:{
        enabled: true,
    font: { size: 16 }
    },
    legend: {
        visible: true
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  }
});*/

$("#bar-chart").dxChart({
    dataSource: <?php echo $response; ?>,
    commonSeriesSettings: {
        argumentField: "year",
        type:"bar"
    },
    series: [
        { valueField: "orderPerDay", name: "Total Orders", color: "#f0ad4e" },
       // { valueField: "totalInternationOrders", name: "International Orders", color: "#428bca" }
    ],
    tooltip:{
        enabled: true,
    font: { size: 16 }
    },
    legend: {
        visible: true
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      },
	  valueType: "numeric"
  }
});
$("#bar-chart-international").dxChart({
    dataSource: <?php echo $response; ?>,
    commonSeriesSettings: {
        argumentField: "year",
        type:"bar"
    },
    series: [
       // { valueField: "orderPerDay", name: "Total Orders", color: "#f0ad4e" },
        { valueField: "totalInternationOrders", name: "International Orders", color: "#428bca" }
    ],
    tooltip:{
        enabled: true,
    font: { size: 16 }
    },
    legend: {
        visible: true
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      },
	  valueType: "numeric"
  }
});

$('#countries').change(function(){
	var value = $(this).val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	$.ajax({
            url: "{{URL::to('admin/reports/order/filter-country')}}",
            method: 'POST',
			data:{from_date:from_date,to_date:to_date,country:value},
            success: function(response){
				//console.log(response);
				var dataSource = JSON.parse(response);;
				$('#bar-chart-international').dxChart('option', 'dataSource', dataSource);
				
			//$('#bar-chart-international').dxChart('instance').option('dataSource', response);
				/*$("#bar-chart-international").dxChart({
					dataSource: response,
					commonSeriesSettings: {
						argumentField: "year",
						type:"bar"
					},
					series: [
					   // { valueField: "orderPerDay", name: "Total Orders", color: "#f0ad4e" },
						{ valueField: "totalInternationOrders", name: "International Orders", color: "#428bca" }
					],
					tooltip:{
						enabled: true,
					font: { size: 16 }
					},
					legend: {
						visible: true
					},
				  valueAxis:{
					grid:{
					  color: '#9D9EA5',
					  width: 0.1
					  }
				  }
				});*/
			}
	});
});

});
</script>
@endsection