@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Edit Tag Links Image</h1></div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Edit Tag Links Image</div>
			 <div class="panel-body">
				<form id="edittag_form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
					<div id="row_1">
						<input type="hidden" name="edit_id" id="id" class="form-control" value="{{$cms_taglinks->id}}">
						<div class="form-group">
							<label class="col-sm-3 control-label">Image</label>
							<div class="col-sm-4">							
								<img src="{{URL::to('public/uploads/cms/taglinks/'.$cms_taglinks->image_path.'')}}" alt="{{$cms_taglinks->image_path}}" width="60px;"/>
							</div>
						</div>
						<br/><br/><br/>
						<div class="form-group">
							<label class="col-sm-3 control-label">Image Upload</label>
							<div class="col-sm-4">							
								<input type="file" required="" class="form-control" id="file" value="" name="file" maxlength="3">
								<span>Recommended Size 128px X 128px</span>
							</div>
						</div>
						<div class="form-group" style="margin-top:20px;">
							<div class="col-sm-7 col-sm-offset-4">
								<button type="submit" class="btn btn-primary btn-sm submit" onclick="validate()">Update</button>
								<a onclick="window.history.back();" class="btn btn-danger" title="Back">Cancel</a>
							</div>
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

<script type="text/javascript">

</script>

@endsection