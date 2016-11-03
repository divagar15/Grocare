@extends('admin.layout.tpl')
@section('customCss')
<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection
@section('content')     	
<div class="page-header"><h1>Customer Reports</h1></div>
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
					  <button type="button" class="btn btn-primary btn-sm filter">Filter</button>
					 
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

                <div class="col-lg-5">

                  <div class="col-lg-8">
                  <div class="panel panel-default">
                        <div class="panel-body">
                          <h4 style="text-align:center;">{{$newSignups}}</h4>
                          <p style="text-align:center;">Signups in this period</p>
                        </div>
                    </div>
                  </div>

                  <div class="col-lg-12">

                    <div id="doughnut-chart" style="height:250px;"></div>


                    </div>

                  


                </div>


                <div class="col-lg-7">

                  <div id="stacked-bar" style="height:250px;"></div>


                 </div>


                </div>
                
            </div>
			<!--all customer start -->
			<div class="row">
			 <div class="col-md-12">
			 
			 <div class="col-md-6">
			  <h2>Overall Customer</h2>
			 </div>
		<div align="right" style="margin-top: 15px;"
 class="col-md-6">
				 <button type="button" class="btn btn-primary btn-sm exportCustomer">Export Customer</button>
			 </div>
			 
			 </div>
           
			 
              <div class="col-md-12">
                  <div class="panel panel-default">
                        
                        <div class="panel-body"> 
                            <?php $i=1; ?>


                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Customer Name</th>
                                    <th>Gender</th>
                                    <th>Email ID</th>
                                    <th>Number of Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($allCustomers) && !empty($allCustomers))

                                  @foreach($allCustomers as $vals)

                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{ucwords($vals->name)}}</td>
                                      <td>@if(strtolower($vals->gender)=='male'){{"Male"}} @elseif(strtolower($vals->gender)=='female'){{"Female"}} @endif</td>
                                      <td>{{$vals->email}}</td>
                                      <td>{{$vals->numorder}}</td>
                                      <td>
									  <a class="btn btn-xs btn-info" href="{{URL::to('admin/reports/customer/view/'.$vals->id)}}">View </a> 
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
            
            </div>
			<!--all customer end -->
            
        </div>
@endsection
@section('customJs')
<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/dx.chartjs.js')}}"></script>
<script>
$(document).ready(function (){


$("#doughnut-chart").dxPieChart({
    dataSource: [
        {orders: "Customer Orders", val: {{$customerOrders}}},
        {orders: "Guest Order", val: {{$guestOrders}}},
      ],
  legend: {
    visible: true
  },
  palette: ["#428bca", "#4DC5F9"],
  series: [{
    type: "doughnut",
    argumentField: "orders",
    label: {
      visible: true,
      //format: "millions",
      connector: {
        visible: true
      }
    }
  }]
});



$("#stacked-bar").dxChart({
    dataSource: <?php echo $response; ?>
         /*[ { year: "01-Mar", customer_order: 10, guest_order: 20 },
          { year: "02-Mar", customer_order: 9, guest_order: 11},
          { year: "03-Mar", customer_order: 22, guest_order: 33 },
          { year: "04-Mar", customer_order: 15, guest_order: 16 }]*/
        ,
    commonSeriesSettings: {
        argumentField: "year",
        type: "stackedBar"
    },
    series: [
        { valueField: "customer_order", name: "Customer Orders", color: '#428bca' },
        { valueField: "guest_order", name: "Guest Orders", color: '#4DC5F9' },
    ],
    legend: {
        visible: false
    },
  valueAxis:{
    grid:{
      color: '#9D9EA5',
      width: 0.1
      }
  },
    tooltip: {
        enabled: true,
        customizeText: function () {
            return this.valueText +' '+ this.seriesName;
        },
    font: { size: 16 }
    }
});




});
 $('.filter').click(function(){

var action = '{{URL::to('admin/reports/customer')}}';
 
      $( "#filter-form" ).attr('action', action);

  $( "#filter-form" ).submit();
});

$('.exportCustomer').click(function(){
var action = '{{URL::to('/admin/reports/exportcustomer')}}';
      $( "#filter-form" ).attr('action', action);
  $( "#filter-form" ).submit();
});
</script>
@endsection