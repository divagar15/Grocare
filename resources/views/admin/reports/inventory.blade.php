@extends('admin.layout.tpl')
@section('customCss')


@endsection
@section('content')     	
<div class="page-header"><h1>Inventory</h1></div>
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
        <div class="form-group">

                                    <label class="col-sm-3 control-label">Products</label>
                                    <div class="col-sm-6">
                                    <select name="product" id="product" class="chosen-select">
                                        <option value=""></option>                                        
                                        @if(isset($simpleProductList) && !empty($simpleProductList))
                                          <optgroup label="Simple Products">
                                          @foreach($simpleProductList as $pro)
                                            <option value="{{$pro->id}}" @if($pro->id==$productId) selected @endif>{{ucwords($pro->name)}}</option>
                                          @endforeach
                                          </optgroup>
                                        @endif
                                        @if(isset($kitProductList) && !empty($kitProductList))
                                          <optgroup label="Bundle Products - Kits">
                                          @foreach($kitProductList as $pro)
                                            <option value="{{$pro->id}}" @if($pro->id==$productId) selected @endif>{{ucwords($pro->name)}}</option>
                                          @endforeach
                                          </optgroup>
                                        @endif
                                      </select>
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

                @if(!empty($productId))


                  <div class="col-lg-3">

                    <div class="col-lg-12">

                      <div class="panel panel-default">

                        <div class="panel-body">
                          <h4>{{$totalSales}}</h4>
                          <p>Total Sales</p>
                        </div>
                        <div class="panel-body">
                          <h4>{{$averageSales}}</h4>
                          <p>@if($difference>31) Average Monthly Sales @else Average Daily Sales @endif</p>
                        </div>

                      </div>

                    </div>

                  </div>

                  <div class="col-lg-9">


                    <div id="bar-chart" style="height:450px; margin-top:30px;"></div>


                  </div>


                @else

                <div class="col-lg-3">

                  <div class="col-lg-12">
                  <div class="panel panel-default">
                        <div class="panel-body">
                          <h4>{{$totalProductsAvailable}}</h4>
                          <p>Total Products Available</p>
                        </div>
                        <div class="panel-body">
                          <h4>{{$totalKitsAvailable}}</h4>
                          <p>Total Kits Available</p>
                        </div>
                        <div class="panel-body">
                          <h4>{{$productQuantitiesSold}}</h4>
                          <p>Product Quantities Sold</p>
                        </div>
                        <div class="panel-body">
                          <h4>{{$kitQuantitiesSold}}</h4>
                          <p>Kit Quantities Sold</p>
                        </div>
                        <div class="panel-body">
                          <h4 id="product_topsell"></h4>
                          <p>Product Top Selling</p>
                        </div>
                        <div class="panel-body">
                          <h4 id="product_lowsell"></h4>
                          <p>Product Low Selling</p>
                        </div>
                        <div class="panel-body">
                          <h4 id="kit_topsell"></h4>
                          <p>Kit Top Selling</p>
                        </div>
                        <div class="panel-body">
                          <h4 id="kit_lowsell"></h4>
                          <p>Kit Low Selling</p>
                        </div>
                    </div>
                  </div>


                </div>

                <div class="col-lg-9">


                <?php
                  $topProductSellerName = '';
                  $topProductSellerQty = 0;
                  $lowProductSellerName = '';
                  $lowProductSellerQty = 0;
                  $topKitSellerName = '';
                  $topKitSellerQty = 0;
                  $lowKitSellerName = '';
                  $lowKitSellerQty = 0;
                ?>

                <h4>Simple Products</h4>
                 <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Product Sold</th>
                                </tr>
                            </thead>
                            <tbody>

                            @if(isset($simpleProductList) && !empty($simpleProductList))

                              @foreach($simpleProductList as $list)

                              <?php $pqty=0; ?>

                              @if(isset($simpleProduct) && !empty($simpleProduct))

                                @foreach($simpleProduct as $simple)

                                  

                                  @if($simple['id']==$list->id)

                                  <?php
                                    $pname = $simple['name'];
                                    $actualQty = $simple['qty'];
                                    if($actualQty>=$topProductSellerQty) {
                                      $topProductSellerQty = $actualQty;
                                      $topProductSellerName = $pname;
                                    }
                                    if($actualQty<=$lowProductSellerQty || $lowProductSellerQty==0) {
                                      $lowProductSellerQty = $actualQty;
                                      $lowProductSellerName = $pname;
                                    }
                                    $pqty = $simple['qty'];
                                  ?>

                                  @endif

                                @endforeach

                              @endif

                                    <tr>
                                      <td>{{ucwords($list->name)}}</td>
                                      <td>{{$pqty}}</td>
                                    </tr>

                                @endforeach

                              @endif

                            </tbody>
                </table>


                <h4>Bundle Products - Kits</h4>
                 <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>Kit Name</th>
                                    <th>Kit Sold</th>
                                </tr>
                            </thead>
                            <tbody>

                            @if(isset($kitProductList) && !empty($kitProductList))

                              @foreach($kitProductList as $list)

                              <?php $pqty=0; ?>

                              @if(isset($kitProduct) && !empty($kitProduct))

                                @foreach($kitProduct as $kit)

                                @if($kit->id==$list->id)

                                <?php
                                    $pname = $kit->name;
                                    $actualQty = $kit->qty;
                                    if($actualQty>=$topKitSellerQty) {
                                      $topKitSellerQty = $actualQty;
                                      $topKitSellerName = $pname;
                                    }
                                    if($actualQty<=$lowKitSellerQty || $lowKitSellerQty==0) {
                                      $lowKitSellerQty = $actualQty;
                                      $lowKitSellerName = $pname;
                                    }
                                    $pqty = $kit->qty;
                                  ?>

                                  @endif

                                    @endforeach

                                @endif

                                    <tr>
                                      <td>{{ucwords($list->name)}}</td>
                                      <td>{{$pqty}}</td>
                                    </tr>

                                @endforeach

                              @endif

                            </tbody>
                </table>





                </div>


                @endif


                    


                </div>
                
            </div>
            
        </div>
@endsection
@section('customJs')

<script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/dx.chartjs.js')}}"></script>
<script>
$(document).ready(function (){

  @if(!empty($productId))


  $("#bar-chart").dxChart({
    dataSource: <?php echo $response; ?>,
    commonSeriesSettings: {
        argumentField: "year",
        type:"bar"
    },
    series: [
        { valueField: "sales", name: "Sales", color: "#27c24c" },
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

  @else

  $('#product_topsell').html('{{$topProductSellerName}} ({{$topProductSellerQty}})');
  $('#product_lowsell').html('{{$lowProductSellerName}} ({{$lowProductSellerQty}})');

    $('#kit_topsell').html('{{$topKitSellerName}} ({{$topKitSellerQty}})');
    $('#kit_lowsell').html('{{$lowKitSellerName}} ({{$lowKitSellerQty}})');

  @endif

});
</script>
@endsection  