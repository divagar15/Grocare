@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>FAQ</h1></div>

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
					<a class="btn btn-primary" href="{{URL::to('admin/cms/addfaq')}}">Add Faq</a>
				</div>
			 </div>
			 <div class="panel-body">
				<!--<form id="faq_form" class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="faq[title]" id="title" class="form-control" value="<?php //if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?>">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Desciption</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="faq[desciption]" rows="3" id="answer1">
								<?php //if($cms_eval->desciption != ""){ echo trim($cms_eval->desciption, '{}'); } ?>
							 </textarea>
						</div>
					</div>
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="button" class="btn btn-primary btn-sm submit" onclick="validate()">Submit</button>
						</div>
					</div>					
				</form>-->
				
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover table-full-width">
						 <thead>
							<tr>
							<th>Question</th>
							<th >Answer</th>
							<th >Action</th>
							</tr>
                        </thead>
                         <tbody>
							@foreach($CmsFaq as $CmsFaq)
								<tr>
									<th style="font-weight:normal;width:300px;">{{$CmsFaq->question}}</th>
									<th style="font-weight:normal;width:400px;"><?php echo $CmsFaq->answer;?></th>
									<th>
										<a class="btn btn-xs btn-success" href="{{URL::to('admin/cms/viewfaq/'.$CmsFaq->id)}}" title="view">View</a>
										<a class="btn btn-xs btn-primary" href="{{URL::to('admin/cms/editfaq/'.$CmsFaq->id)}}" title="Edit">Edit</a>	
										<button type="button"  data-rid="{{$CmsFaq->id}}" class="btn btn-danger btn-xs confirm-delete"> Delete </button> 
									</th>
								</tr>
							@endforeach
                         </tbody>
					</table>
				</div>
				
				
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
		var action = '{{URL::to('admin/cms/faq')}}';
		$( "#faq_form" ).attr('action', action);
		$( "#faq_form" ).submit();
		return true;		
	}
	
	$(document).ready(function(){
		$('.confirm-delete').click(function(){
			var id = $(this).data('rid');
			var confirmMsg = confirm("Are you sure want to delete this member? You cannot undo this action");
			if(confirmMsg) {
				window.location.href = "{{URL::to('admin/cms/deletefaq')}}/"+id;
			}
		});
	});
	
</script>

@endsection