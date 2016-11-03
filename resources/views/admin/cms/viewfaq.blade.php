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
<div class="page-header"><h1>FAQ</h1></div>



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">FAQ</div>
			 <div class="panel-body">
					<h3>Faq</h3>
					<div class="form-group">
						<label class="col-sm-4 control-label">Question</label>
						<div class="col-sm-6">
							<input disabled="disabled" type="text" name="question" id="question" class="form-control" value="{{$CmsFaq->question}}">
						</div>
					</div>	<br/><br/><br/><br/>
					<div class="form-group">
						<label class="col-sm-4 control-label">Answer</label>
						<div class="col-sm-6">							
							 <textarea disabled class="form-control ckeditor" name="answer" rows="3" id="answer">
								{{$CmsFaq->answer}}
							 </textarea>
						</div>
					</div>	
					<div class="clear"></div>
					<a onclick="window.history.back();" class="btn btn-default" title="Back">Back</a>
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