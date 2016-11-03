@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Support</h1></div>

@if(Session::has('success_msg'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	{{Session::get('success_msg')}}
	</div>
@endif

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Support</div>
			 <div class="panel-body">
				<form id="supportlist_form" class="form-horizontal" role="form" method="post">
					<h3>Customer Policy</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[customerpolicy_tilte]" id="customerpolicy_tilte" class="form-control" value="@if(empty($cms_eval->customerpolicy_tilte) && $cms_eval->customerpolicy_tilte == ""){}@else{{{$cms_eval->customerpolicy_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[customerpolicy_descp]" rows="5" id="customerpolicy_descp">
								@if(empty($cms_eval->customerpolicy_descp) && $cms_eval->customerpolicy_descp == ""){}@else{{{$cms_eval->customerpolicy_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<h3>Shipping & Delivery Policy</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[shipping_tilte]" id="shipping_tilte" class="form-control" value="@if(empty($cms_eval->shipping_tilte) && $cms_eval->shipping_tilte == ""){}@else{{{$cms_eval->shipping_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[shipping_descp]" rows="5" id="shipping_descp">
								@if(empty($cms_eval->shipping_descp) && $cms_eval->shipping_descp == ""){}@else{{{$cms_eval->shipping_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<h3>Cancellation & Refund Policy</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[cancellation_tilte]" id="cancellation_tilte" class="form-control" value="@if(empty($cms_eval->cancellation_tilte) && $cms_eval->cancellation_tilte == ""){}@else{{{$cms_eval->cancellation_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[cancellation_descp]" rows="5" id="cancellation_descp">
								@if(empty($cms_eval->cancellation_descp) && $cms_eval->cancellation_descp == ""){}@else{{{$cms_eval->cancellation_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<h3>Disclaimer policy</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[disclaimer_tilte]" id="disclaimer_tilte" class="form-control" value="@if(empty($cms_eval->disclaimer_tilte) && $cms_eval->disclaimer_tilte == ""){}@else{{{$cms_eval->disclaimer_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[disclaimer_descp]" rows="5" id="disclaimer_descp">
									@if(empty($cms_eval->disclaimer_descp) && $cms_eval->disclaimer_descp == ""){}@else{{{$cms_eval->disclaimer_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<h3>Privacy Policy</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[privacy_tilte]" id="privacy_tilte" class="form-control" value="@if(empty($cms_eval->privacy_tilte) && $cms_eval->privacy_tilte == ""){}@else{{{$cms_eval->privacy_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[privacy_descp]" rows="5" id="privacy_descp">
								@if(empty($cms_eval->privacy_descp) && $cms_eval->privacy_descp == ""){}@else{{{$cms_eval->privacy_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<h3>Terms & Conditions</h3>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="support[terms_tilte]" id="terms_tilte" class="form-control" value="@if(empty($cms_eval->terms_tilte) && $cms_eval->terms_tilte == ""){}@else{{{$cms_eval->terms_tilte}}}@endif">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="support[terms_descp]" rows="5" id="terms_descp">
								@if(empty($cms_eval->terms_descp) && $cms_eval->privacy_descp == ""){}@else{{{$cms_eval->terms_descp}}}@endif
							 </textarea>
						</div>
					</div>	
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="button" class="btn btn-primary btn-sm submit" onclick="validate()">Submit</button>
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
	function validate(){
		var action = '{{URL::to('admin/cms/supportlist')}}';
		$( "#supportlist_form" ).attr('action', action);
		$( "#supportlist_form" ).submit();
		return true;		
	}
</script>

@endsection