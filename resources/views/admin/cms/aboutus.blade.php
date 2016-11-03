@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>About Us</h1></div>

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
			 <div class="panel-heading">About Us</div>
			 <div class="panel-body">
				<form id="aboutus_form" class="form-horizontal" role="form" method="post">					
					<div class="form-group">
						<label class="col-sm-3 control-label">Video Link</label>
						<div class="col-sm-4">
							<input type="text" name="aboutus[videolink]" id="video_link" class="form-control" 
							value="<?php if($cms_eval->videolink != ""){ echo trim($cms_eval->videolink, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Title</label>
						<div class="col-sm-4">
							<input type="text" name="aboutus[title]" id="title" class="form-control" value="<?php if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<div class="col-sm-8">							
							 <textarea class="form-control ckeditor" name="aboutus[descp]" rows="5" id="descp">
								<?php if($cms_eval->descp != ""){ echo trim($cms_eval->descp, '{}'); } ?>
							 </textarea>
						</div>
					</div>
					
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="button" class="btn btn-primary btn-sm submit" onclick="validate()">Submit</button>
						</div>
					</div>					
				</form>
				<div class="panel-body">
					<a class="btn btn-primary" href="{{URL::to('admin/cms/aboutus_taglinks')}}">Add Tag Links</a>
				</div>
				
				<div class="portlet-body">
					<table class="table table-striped table-bordered table-hover table-full-width" id="sample_2">
						 <thead>
							<tr>
							<th>Title</th>
							 <th>Description</th>
							 <th>Image</th>
							<th >Action</th>
							</tr>
                        </thead>
                         <tbody>
							<?php $j = 1;?>
							 @foreach($cms_taglinks as $cms_taglinks)
								<tr style="font-weight:normal">
									<th style="font-weight:normal;width:300px">{{$cms_taglinks->title}}</th>
									<th style="font-weight:normal;width:300px;">{{$cms_taglinks->descp}}</th>
									<th>
										<img src="{{URL::to('public/uploads/cms/taglinks/'.$cms_taglinks->image_path.'')}}" alt="{{$cms_taglinks->image_path}}" width="60px;" style="float:left;"/>
										<!--<div title="Edit Image" style="cursor:pointer;float:right;" class="image" data-rid="{{$cms_taglinks->id}}">
											<span style="color: #428bca" class="glyphicon glyphicon-edit"></span>
										</div>		-->								
									</th>
									<th>
										<a class="btn btn-xs btn-success" href="{{URL::to('admin/cms/viewtag/'.$cms_taglinks->id)}}" title="view">View</a>
										<a class="btn btn-xs btn-primary" href="{{URL::to('admin/cms/edittag/'.$cms_taglinks->id)}}" title="Edit">Edit</a>										
										 <button type="button" data-rid="{{$cms_taglinks->id}}" class="btn btn-danger btn-xs confirm-delete"> Delete </button> 
									</th>
								</tr>
								<?php $j++;?>
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
		
		var video_link =  $( "#video_link" ).val();
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

		if(RegExp.test(video_link)){
			var action = '{{URL::to('admin/cms/aboutus')}}';
			$( "#aboutus_form" ).attr('action', action);
			$( "#aboutus_form" ).submit();
			return true;
		}else{
			$( "#video_link" ).css('border-color','red');			
			return false;
		}
	}
	
	$(document).ready(function(){
		$("#addtaglinks").click(function(){
              var counter1 = Number($('#addtaglinks').val());
              var counter = $('#counter').val();
				
				counter = ++counter;
				alert(counter);
             var html = '<tr id="row_'+counter+'"><td><input type="text" required="" class="form-control" id="tagtitle_'+counter+'" value="" name="aboutus[taglinks][title][]" maxlength="3"></td>';
                  html += '<td><input type="text" required="" class="form-control" id="tagdescp_'+counter+'" value="" name="aboutus[taglinks][descp][]" maxlength="3"></td>';
                  html += '<td><input type="file" required="" class="form-control" id="tagimg_'+counter+'" value="" name="aboutus[taglinks][upload][]" maxlength="3"></td>';
                  html += '<td><a href="javascript:void(0)" id="'+counter+'" data-type="0" class="removetaglinks btn btn-xs btn-danger"><i class="fa fa-close"></i></a></td></tr>';

              $('#taglinks').append(html);

              $('#counter').val(counter);

             // $('#taglinks'+counter).chosen();

            });
			
			$(".removetaglinks").click(function (){  
                var id  = $(this).attr('id');
				alert(id);
               // var type   = $(this).data('type');
                //  $("#row_"+id).remove();
            });
			
			 $(document).ready(function(){
        
        
			$('.confirm-delete').click(function(){
				var id = $(this).data('rid');
				var confirmMsg = confirm("Are you sure want to delete this? You cannot undo this action");
				if(confirmMsg) {
						window.location.href = "{{URL::to('admin/cms/deletetag')}}/"+id;
				}
			});
			
			
			$('.image').click(function(){
				var id = $(this).data('rid');
				window.location.href = "{{URL::to('admin/cms/edittagimage')}}/"+id;
			});
    });
			
			
	
	});
</script>

@endsection