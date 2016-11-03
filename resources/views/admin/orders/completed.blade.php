@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Completed Orders</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
<!--                         <div class="panel-heading"><a href="{{URL::to('admin/testimonials/add')}}" class="btn btn-sm btn-primary">Add Testimonial</a></div>
 -->                        <div class="panel-body">

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


                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Invoice No</th>
                                    <th>Customer Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($orderDetails) && !empty($orderDetails))

                                  @foreach($orderDetails as $detail)

                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{$detail->invoice_no}}</td>
                                      <td>{{ucwords($detail->billing_name)}} @if($detail->fkcustomer_id==0) {{" - Guest"}}@endif</td>
                                      <td>{{$detail->order_symbol}} {{round($detail->grand_total)}}</td>
                                      <td>{{$orderStatus[$detail->order_status]}}</td>
                                      <td>
                                          <a class="btn btn-xs btn-info" href="{{URL::to('admin/orders/completed/'.$detail->id)}}">View</a> 
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

@endsection

@section('customJs')

  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
          
        });
    </script>

@endsection