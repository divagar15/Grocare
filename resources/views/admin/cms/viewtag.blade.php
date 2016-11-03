@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />
<style>
.clear{
	clear:both;
}
</style>

@endsection

@section('content')     	
<div class="page-header"><h1>View Tag Links</h1></div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">View Tag Links</div>
			 <div class="panel-body">
					<div id="row_1">
						<div class="form-group">
							<label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-8">
								<input disabled="disabled" type="text" name="title" id="title" class="form-control" value="{{$cms_taglinks->title}}">
							</div>
						</div>	
						<br/><br/><br/>
						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<input disabled="disabled" type="text" name="descp" id="descp" class="form-control" value="{{$cms_taglinks->descp}}">
							</div>
						</div>
						<br/><br/><br/>
						<div class="form-group">
							<label class="col-sm-3 control-label">Image</label>
							<div class="col-sm-8">
								<img src="{{URL::to('public/uploads/cms/taglinks/'.$cms_taglinks->image_path.'')}}" alt="{{$cms_taglinks->image_path}}" width="60px;"/>
							</div>
						</div>
						<div class="clear"></div>
						<a onclick="window.history.back();" class="btn btn-default" title="Back">Back</a>
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


@endsection