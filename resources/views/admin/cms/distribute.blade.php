@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Distribute</h1></div>

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
			 <div class="panel-heading">Distribute</div>
			 <div class="panel-body">
				<h3>Distribute</h3>
				<form id="distribute_form" class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label class="col-sm-3 control-label">Youtube Video Link</label>
						<div class="col-sm-4">
							<input type="text" name="distribute[videolink]" id="videolink" class="form-control" value="<?php if($cms_eval->videolink != ""){ echo trim($cms_eval->videolink, '{}'); } ?>">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Page Title</label>
						<div class="col-sm-4">
							<input type="text" name="distribute[pagetitle]" id="pagetitle" class="form-control" value="<?php if($cms_eval->pagetitle != ""){ echo trim($cms_eval->pagetitle, '{}'); } ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Head Title</label>
						<div class="col-sm-4">
							<input type="text" name="distribute[headtitle]" id="headtitle" class="form-control" value="<?php if($cms_eval->headtitle != ""){ echo trim($cms_eval->headtitle, '{}'); } ?>">
						</div>
					</div>					
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="button" class="btn btn-primary btn-sm submit" onclick="validate()">Submit</button>
						</div>
					</div>	
					<div class="panel-body">
						<a class="btn btn-primary" href="{{URL::to('admin/cms/adddistribute')}}">Add Distribute</a>
					</div>
					<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover table-full-width">
						 <thead>
							<tr>
							<th>Title</th>
							<th >Description</th>
							<th >Action</th>
							</tr>
                        </thead>
                         <tbody>
								@foreach($CmsDistribute as $CmsDistribute)
								<tr>								
									<th style="font-weight:normal;width:300px;">{{$CmsDistribute->title}}</th>
									<th style="font-weight:normal;width:400px;"><?php echo $CmsDistribute->description;?></th>
									<th>
										<a class="btn btn-xs btn-success" href="{{URL::to('admin/cms/viewdistribute/'.$CmsDistribute->id)}}" title="view">View</a>
										<a class="btn btn-xs btn-primary" href="{{URL::to('admin/cms/editdistribute/'.$CmsDistribute->id)}}" title="Edit">Edit</a>	
										<button type="button" data-rid="{{$CmsDistribute->id}}" class="btn btn-danger btn-xs confirm-delete"> Delete </button> 
									</th>								
								</tr>
								@endforeach	
                         </tbody>
					</table>
				</div>
					
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
<script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>
	
<script type="text/javascript">
	function validate(){
		
		var videolink =  $( "#videolink" ).val();
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

		if(RegExp.test(videolink)){
			var action = '{{URL::to('admin/cms/distribute')}}';
			$( "#distribute_form" ).attr('action', action);
			$( "#distribute_form" ).submit();
			return true;
		}else{
			$( "#videolink" ).css('border-color','red');			
			return false;
		}
	}
	
	$(document).ready(function(){
		$('.confirm-delete').click(function(){
			var xs = $(this).data('rid');
			var del = confirm("Are you sure want to delete ? You cannot undo this action");
			if(del) {							
				window.location.href = "{{URL::to('admin/cms/deletedist')}}/"+xs;		
			}
		});
	});

</script>

@endsection