@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Edit Distribute</h1></div>



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Edit Distribute</div>
			 <div class="panel-body">
				<form id="editdistribute_form" class="form-horizontal" role="form" method="post">
					<input type="hidden" name="edit_id" id="id" class="form-control" value="{{$CmsDistribute->id}}">
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="title" id="title" class="form-control" value="{{$CmsDistribute->title}}">
						</div>						
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="description" rows="5" id="description">
								{{$CmsDistribute->description}}
							 </textarea>
						</div>
					</div>
					
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="submit" class="btn btn-primary btn-sm submit" onclick="validate()">Update</button>
						<a onclick="window.history.back();" class="btn btn-danger btn-sm" title="Back">Cancel</a>
						</div>
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
	


@endsection