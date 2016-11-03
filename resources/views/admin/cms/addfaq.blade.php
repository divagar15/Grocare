@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>FAQ</h1></div>



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Add Faq</div>
			 <div class="panel-body">
				<form id="addfaq_form" class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label class="col-sm-3 control-label">Question</label>
						<div class="col-sm-4">
							<input type="text" name="question" id="question" class="form-control" value="">
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Answer</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="answer" rows="3" id="answer">
								
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
		var action = '{{URL::to('admin/cms/addfaq')}}';
		$( "#addfaq_form" ).attr('action', action);
		$( "#addfaq_form" ).submit();
		return true;		
	}
</script>

@endsection