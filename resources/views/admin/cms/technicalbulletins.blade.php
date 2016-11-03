@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Technical Bulletins</h1></div>

@if(Session::has('success_msg'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	{{Session::get('success_msg')}}
	</div>
@endif

@if(Session::has('delete_msg'))
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	{{Session::get('delete_msg')}}
	</div>
@endif

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">
				<div class="panel-body">
					<a class="btn btn-primary" href="{{URL::to('admin/cms/addtechnicalbulletins')}}">Add Bulletins</a>
				</div>
			 </div>
			 
			<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover table-full-width">
						 <thead>
							<tr>
							<th>Tile</th>
							<!--<th >File Name</th>-->
							<th >Action</th>
							</tr>
                        </thead>
                         <tbody>
							@foreach($CmsBulletins as $CmsBulletins)
								<tr>
									<th style="font-weight:normal;width:400px;">
										<a title="download" href="{{URL::to('public/uploads/cms/technicalBulletins/'.$CmsBulletins->bulletins.'')}}" download>@if(empty($CmsBulletins->title) && $CmsBulletins->title == ""){}@else{{{$CmsBulletins->title}}}@endif</a>
									</th>
									<!--<th style="font-weight:normal;width:300px;">
										<div >{{$CmsBulletins->bulletins}}</div>
										<div style="float:right;cursor:pointer;" title="Edit File" style="cursor:pointer;" class="image" data-rid="{{$CmsBulletins->id}}">
											<span style="color: #428bca" class="glyphicon glyphicon-edit"></span>
										</div>
									</th>-->
									<th>
										<a class="btn btn-xs btn-success" href="{{URL::to('admin/cms/viewtechnicalbulletins/'.$CmsBulletins->id)}}" title="view">View</a>
										
										<a class="btn btn-xs btn-primary" href="{{URL::to('admin/cms/edittechnicalbulletins/'.$CmsBulletins->id)}}" title="Edit">Edit</a>	
										<button type="button" data-rid="{{$CmsBulletins->id}}" class="btn btn-danger btn-xs confirm-delete"> Delete </button> 
									</th>
								</tr>
							@endforeach
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
<script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>
	
<script type="text/javascript">
	function validate(){
		var action = '{{URL::to('admin/cms/technicalbulletins')}}';
		$( "#technicalbulletins_form" ).attr('action', action); 
		$( "#technicalbulletins_form" ).submit();
		return true;		
	}
	
	$(document).ready(function(){
		$('.confirm-delete').click(function(){
			var id = $(this).data('rid');
			var confirmMsg = confirm("Are you sure want to delete this ? You cannot undo this action");
			if(confirmMsg) {
				window.location.href = "{{URL::to('admin/cms/deletetechnicalbulletins')}}/"+id;
			}
		});
		
		$('.image').click(function(){
				var id = $(this).data('rid');
				window.location.href = "{{URL::to('admin/cms/edittechnicalbulletinsfile')}}/"+id;
			});
	});
	
	
</script>

@endsection