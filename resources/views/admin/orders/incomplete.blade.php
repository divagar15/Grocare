@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Incomplete Orders</h1></div>



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

                        <form id="filter-form" class="form-horizontal" role="form" method="post">

                                  <div class="form-group">
                                    <label class="col-sm-4 control-label">Date</label>
                                    <div class="col-sm-4">
                                      <div class='input-group date datepicker' >
                                        <input type='text' readonly class="form-control from_date" name="date" data-date-format="DD-MM-YYYY" value="@if(isset($date) && !empty($date)){{date('d-m-Y',strtotime($date))}}@endif"  />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                      <a href="{{URL::to('admin/orders/incomplete')}}" class="btn btn-default btn-sm">Cancel</a>
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
                                      <option value="successful">Move to Successful</option>
                                      <option value="trash">Move to Trash</option>
                                    </select>
                                    </div>
                                    <div class="col-md-2">
                                    <input type="hidden" name="page" id="page" value="incomplete">
                                    <button class="btn btn-default btn-sm btn-flat applyAction" type="button">Apply</button>
                                    </div>
                                    </div>
                                   </div>



                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer Name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
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
                                      <td>{{$detail->order_symbol}} {{round($detail->grand_total)}}
                                      @if($detail->order_symbol!=$inrSymbol->symbol)
                                      <br/>({{$inrSymbol->symbol}} {{round($detail->grand_total_inr)}})
                                      @endif</td>

                                      
                                      <td>
                                          <a class="btn btn-xs btn-info" href="{{URL::to('admin/orders/incomplete/'.$detail->id)}}">View</a> 
                                          <a class="btn btn-xs btn-success successfulOrder" data-id="{{$detail->id}}" href="javascript:void(0)">Move to Successful Order</a> 
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

          $(document).on("click",".successfulOrder",function() {
            var id = $(this).data('id');
            var confirmMsg = confirm('Are you sure want to move this order to successful?');
            if(confirmMsg) {
              window.location.href = "{{URL::to('admin/orders/successful')}}/"+id;
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
    </script>

@endsection