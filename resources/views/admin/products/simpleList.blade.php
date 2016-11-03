@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Simple Products</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
                        <div class="panel-heading"><a href="{{URL::to('admin/catalog/product/add')}}" class="btn btn-sm btn-primary">Add Simple Product</a></div>
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


                          <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Stock Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($products) && !empty($products))

                                  @foreach($products as $product)

                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{ucwords($product->name)}}</td>
                                      <td>{{$stockStatus[$product->stock_status]}}</td>
                                      <td>
                                          <a class="btn btn-xs btn-info" href="{{URL::to('admin/catalog/product/simple/edit/'.$product->id)}}">Edit</a> 
                                          <a class="btn btn-xs btn-danger deleteProduct" href="javascript:void(0)" data-id="{{$product->id}}">Delete</a>
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
          $(document).on("click",".deleteProduct",function() {
            var id = $(this).data('id');
            var confirmMsg = confirm('Are you sure want to delete this product?');
            if(confirmMsg) {
              window.location.href = "{{URL::to('admin/catalog/product/delete/')}}/"+id;
            }
          });
        });
    </script>

@endsection