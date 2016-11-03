@extends('admin.layout.tpl')
@section('customCss')
<style type="text/css">
    h3.transit {
        font-size: 16px;
    }
    p.transit {
        font-size: 13px;
    }

    .dashboard-stats.panel {
        padding-top: 20px !important;
    }
    h2 {
        font-size: 18px;
    }
    .gridList a {
        color:#000;
    }
</style>
@endsection
@section('content')     	
<div class="page-header"><h1>Dashboard</h1></div>
<div class="warper container-fluid">
        	
            <div class="page-header"><h1> <small></small></h1></div>
            
           
            <div class="row gridList">
            
            	<div class="col-md-3 col-sm-6">
                	<div class="panel panel-default clearfix dashboard-stats rounded">
                        <a href="{{URL::to('admin/reports/order?type=today')}}">
                    	<i class="fa fa-line-chart bg-success transit stats-icon"></i>
                        <h3 class="transit">Rs. {{$today_sales_amount}} <small class="text-green"></small></h3>
                        <p class="text-muted transit">Sales today</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                	<div class="panel panel-default clearfix dashboard-stats rounded">
                        <a href="{{URL::to('admin/reports/order?type=month')}}">
                    	<i class="fa fa-bar-chart bg-primary transit stats-icon"></i>
                        <h3 class="transit">Rs. {{$month_sales_amount}} <small class="text-red"></small></h3>
                        <p class="text-muted transit">Sales this month</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="panel panel-default clearfix dashboard-stats rounded">
                        <a href="{{URL::to('admin/reports/order?type=month')}}">
                        <i class="fa fa-bar-chart bg-primary transit stats-icon"></i>
                        <h3 class="transit">Rs. {{$averageSales}} <small class="text-red"></small></h3>
                        <p class="text-muted transit">Average Daily Sales</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                	<div class="panel panel-default clearfix dashboard-stats rounded">
                        <a href="{{URL::to('admin/reports/order?type=year')}}">
                    	<i class="fa fa-area-chart bg-info transit stats-icon"></i>
                        <h3 class="transit">Rs. {{$year_sales_amount}} <small class="text-green"> </small></h3>
                        <p class="text-muted transit">Sales this year</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                	<div class="panel panel-default clearfix dashboard-stats rounded">
                        <a href="{{URL::to('admin/reports/customer')}}">
                    	<i class="fa fa-users bg-warning transit stats-icon"></i>
                        <h3 class="transit">{{$registeredCustomers+$guestCustomers}} <small class="text-red"></small></h3>
                        <p class="text-muted transit">Unique Customers</p>
                        </a>
                    </div>
                </div>
            
            </div>
            
            
            <div class="row">
            	<div class="col-lg-12">
                
                	<div class="row gridList" id="">
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <a href="{{URL::to('admin/orders/all?type=new')}}">
                                    <h5 class="no-margn"><strong>New / Pending Orders</strong></h5>
                                    <h2 class="text-muted">{{$new_orders}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    	<div class="col-lg-3">
                        	<div class="panel panel-default">
                            	<div class="panel-body text-center">
                                    <a href="{{URL::to('admin/orders/all?type=processing')}}">
                                	<h5 class="no-margn"><strong>Orders Processing</strong></h5>
                                    <h2 class="text-muted">{{$processing_orders}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                        	<div class="panel panel-default">
                            	<div class="panel-body text-center">
                                    <a href="{{URL::to('admin/orders/all?type=shipped')}}">
                                	<h5 class="no-margn"><strong>Orders Shipped</strong></h5>
                                    <h2 class="text-muted">{{$ship_orders}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <div class="panel-body text-center">
                                    <h5 class="no-margn"><strong>Top seller of this month</strong></h5>
                                    <h2 class="text-muted">{{$topSellingProductName}} <span style="font-size:16px;">({{$topSellingProductCount}})</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                	<!--
                    <div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">World Map</div>
								<div class="panel-body">
									<div id="demo-map-1" style="height:250px;"></div>
								</div>
							</div>
						</div>
                    </div>
                -->
                	
                
                	
                	
                </div>
                
            </div>
            
        </div>
@endsection
@section('customJs')
<script>

/****************************************
Vector Map 1
****************************************/

var mapData1 = {
    // "China": 19,
    "India": 1,
    // "United States of America": 4.44,
    // "Indonesia": 3.45,
    // "Brazil": 2.83,
    // "Pakistan": 2.62,
    // "Nigeria": 2.42,
    // "Bangladesh": 2.18,
    // "Russia": 2.04,
    // "Japan": 1.77,
    // "Mexico": 1.67,
    // "Philippines": 1.39,
    // "Vietnam": 1.25,
    // "Ethiopia": 1.23,
    // "Egypt": 1.21,
    // "Germany": 1.13,
    // "Iran": 1.08,
    // "Turkey": 1.07,
    // "Democratic Republic of the Congo": 0.94,
    // "France": 0.92,
    // "Thailand": 0.9,
    // "United Kingdom": 0.89,
    // "Italy": 0.85,
    // "Burma": 0.84,
    // "South Africa": 0.74,
    // "South Korea": 0.7,
    // "Colombia": 0.66,
    // "Spain": 0.65,
    // "Tanzania": 0.63,
	// "Sri Lanka":1,
    // "Kenya": 0.62,
    // "Ukraine": 0.6,
    // "Argentina": 0.59,
    // "Algeria": 0.54,
    // "Poland": 0.54,
    // "Sudan": 0.52,
    // "Canada": 0.49,
    // "Uganda": 0.49,
    // "Iraq": 0.47,
    // "Morocco": 0.46,
    "Uzbekistan": 0.43
};



var getPaletteIndex = function (percent) {
    if (percent < 0.5) {
        return 0;
    } else if (percent < 0.8) {
        return 1;
    } else if (percent < 1) {
        return 2;
    } else if (percent < 2) {
        return 3;
    } else if (percent < 3) {
        return 4;
    } else {
        return 5;
    }
};

$('#demo-map-1').dxVectorMap({
    mapData: DevExpress.viz.map.sources.world,
    bounds: [-180, 85, 180, -60],
	zoomFactor: 2.5,
	controlBar: {
	enabled: false
	},
	areaSettings: {
	    palette: 'Violet',
		paletteSize: 6,
		customize: function(arg) {
		    var percent = mapData1[arg.attributes.name];
		    if(percent) {
		        return {
		            paletteIndex: getPaletteIndex(percent)
		        };
		    }
		}
	},
	tooltip: {
	    enabled: true,
	    customizeTooltip: function(arg) {
	        var name = arg.attribute("name"),
                percent = mapData1[name];
	        if(percent) {
	            return { text: name + ": " + percent + "% of world population" }
	        }
	    }
	}
});





</script>
@endsection