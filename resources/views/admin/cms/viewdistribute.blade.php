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
<div class="page-header"><h1>View Distribute</h1></div>



<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">View Distribute</div>
			 <div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">Title</label>
						<div class="col-sm-6">
							<input disabled="disabled" type="text" name="title" id="title" class="form-control" value="{{$CmsDistribute->title}}">
						</div>						
					</div><br/><br/>
					<div class="form-group">
						<label class="col-sm-4 control-label">Description</label>
						<div class="col-sm-6">							
							 <textarea disabled class="form-control ckeditor" name="discription" rows="5" id="discription">
									{{$CmsDistribute->description}}
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
		$("#taglink_sh1").click(function(){
			if($(this).is(":checked"))
				$(this).val("1");
			else
				$(this).val("2");
		});
		$("#taglink_sh2").click(function(){
			if($(this).is(":checked"))
				$(this).val("1");
			else
				$(this).val("2");
		});
		$("#taglink_sh3").click(function(){
			if($(this).is(":checked"))
				$(this).val("1");
			else
				$(this).val("2");
		});
		$("#taglink_sh4").click(function(){
			if($(this).is(":checked"))
				$(this).val("1");
			else
				$(this).val("2");
		});
	});
</script>

@endsection