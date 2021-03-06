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
				<form id="edittechnicalbulletins_form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">	
					<div id="row_1">
						<input type="hidden" name="edit_id" id="id" class="form-control" value="{{$CmsBulletins->id}}">
						<div class="form-group">
							<label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-4">
								<input type="text" name="title" id="title" class="form-control" value="{{$CmsBulletins->title}}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">File</label>
							<div class="col-sm-4">
								<input disabled="disabled" type="text" name="afile" id="afile" class="form-control" value="{{$CmsBulletins->bulletins}}">
							</div>
							<a class="btn btn-success btn-sm" href="{{URL::to('public/uploads/cms/technicalBulletins/'.$CmsBulletins->bulletins.'')}}" download>				Download
							</a>
						</div>	
						<div class="form-group">
							<label class="col-sm-3 control-label">File Upload</label>
							<div class="col-sm-4">							
								<input type="file"  id="file" value="" name="file" maxlength="3">
							</div>
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
	
<script type="text/javascript">
	function validate(){
		/* var imgVal = $('#file').val(); 
        if(imgVal=='') 
        { 
            alert("Please Upload A File"); 
            return false; 
        }else{ */		
			/* var action = '{{URL::to('admin/cms/edittechnicalbulletins')}}';
			$( "#edittechnicalbulletins_form" ).attr('action', action); 
			$( "#edittechnicalbulletins_form" ).submit();
			return true;	 */ 
		//}
	}
</script>

@endsection