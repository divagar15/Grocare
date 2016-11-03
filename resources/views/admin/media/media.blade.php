@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Media</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
                        <div class="panel-heading"><a href="{{URL::to('admin/add-media')}}" class="btn btn-sm btn-primary">Add Media</a></div>
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
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($media) && !empty($media))

                                  @foreach($media as $val)

                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{ucwords($val->title)}}</td>
                                      <td>{{$val->link}}</td>
                                      <td>
                                          <a class="btn btn-xs btn-info" href="{{URL::to('admin/edit-media/'.$val->id)}}">Edit</a> 
                                          <a class="btn btn-xs btn-danger" href="javascript:void(0)" onclick="deleteMedia({{$val->id}});">Delete</a>
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
		function deleteMedia(id){
			var confirmMsg = confirm('Are you sure want to delete this Media?');
            if(confirmMsg) {
              window.location.href = "{{URL::to('admin/delete-media/')}}/"+id;
            }
		}
    </script>

@endsection