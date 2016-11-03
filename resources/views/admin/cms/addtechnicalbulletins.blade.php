@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Technical Bulletins</h1></div>


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Technical Bulletins</div>
			 <div class="panel-body">
				<form id="addtechnicalbulletins_form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">							
					<div id="row_1">
						<div class="form-group">
							<label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-4">
								<input type="text" name="title" id="title" class="form-control" value="">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-3 control-label">File Upload</label>
							<div class="col-sm-4">							
								<input type="file" required="" class="form-control" id="file" value="" name="file" maxlength="3">
							</div>
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
		var imgVal = $('#file').val(); 
        if(imgVal=='') 
        { 
            alert("Please Upload A File"); 
            return false; 
        }else{	
			var action = '{{URL::to('admin/cms/addtechnicalbulletins')}}';
			$( "#addtechnicalbulletins_form" ).attr('action', action); 
			$( "#addtechnicalbulletins_form" ).submit();
			return true;	
		}		
	}
</script>

@endsection