@extends('admin.layout.tpl')

@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />

@endsection

@section('content')     	
<div class="page-header"><h1>Contact List</h1></div>

@if(Session::has('success_msg'))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	{{Session::get('success_msg')}}
	</div>
@endif

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			 <div class="panel-heading">Contact List</div>
			 <div class="panel-body">
				<form id="contactlist_form" class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label class="col-sm-3 control-label">Title <span style="color:red;font-size: 16px;">*</span></label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[title]" id="title" class="form-control" 
							value="<?php if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Address Line 1 <span style="color:red;font-size: 16px;">*</span></label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[address1]" id="address1" class="form-control" 
							value="<?php if($cms_eval->address1 != ""){ echo trim($cms_eval->address1, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Address Line 2</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[address2]" id="address2" class="form-control" value="<?php if($cms_eval->address2 != ""){ echo trim($cms_eval->address2, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">City</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[city]" id="city" class="form-control" value="<?php if($cms_eval->city != ""){ echo trim($cms_eval->city, '{}'); } ?>">
						</div>
					</div>					
					<div class="form-group">
						<label class="col-sm-3 control-label">Pincode</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[pincode]" id="pincode" class="form-control" value="<?php if($cms_eval->pincode != ""){ echo trim($cms_eval->pincode, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">State</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[state]" id="state" class="form-control" value="<?php if($cms_eval->state != ""){ echo trim($cms_eval->state, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Country</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[country]" id="country" class="form-control" value="<?php if($cms_eval->country != ""){ echo trim($cms_eval->country, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Helpline NO</label>
						<div class="col-sm-4">
							<input type="tel" name="contactlist[helplineno]" id="helplineno" class="form-control" value="<?php if($cms_eval->helplineno != ""){ echo trim($cms_eval->helplineno, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Fax NO</label>
						<div class="col-sm-4">
							<input type="tel" name="contactlist[faxno]" id="faxno" class="form-control" value="<?php if($cms_eval->faxno != ""){ echo trim($cms_eval->faxno, '{}'); } ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Youtube link</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[youtubelink]" id="youtubelink" class="form-control" value="<?php if($cms_eval->youtubelink != ""){ echo trim($cms_eval->youtubelink, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Facebook Link</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[facebooklink]" id="facebooklink" class="form-control" value="<?php if($cms_eval->facebooklink != ""){ echo trim($cms_eval->facebooklink, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Twitter Link</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[twitterlink]" id="twitterlink" class="form-control" value="<?php if($cms_eval->twitterlink != ""){ echo trim($cms_eval->twitterlink, '{}'); } ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Google Plus Link</label>
						<div class="col-sm-4">
							<input type="text" name="contactlist[googlepluslink]" id="googlepluslink" class="form-control" value="<?php if($cms_eval->googlepluslink != ""){ echo trim($cms_eval->googlepluslink, '{}'); } ?>">
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
		
		var title =  $( "#title" ).val();
		var address1 =  $( "#address1" ).val();
		var youtubelink =  $( "#youtubelink" ).val();
		var facebooklink =  $( "#facebooklink" ).val();
		var twitterlink =  $( "#twitterlink" ).val();
		var googlepluslink =  $( "#googlepluslink" ).val();
		var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		
		if(RegExp.test(youtubelink)){
			$( "#youtubelink" ).css('border','1px solid #ccc');
		//	return true;			
		}
		else{
			$( "#youtubelink" ).css('border-color','red');	
		}
		if(RegExp.test(facebooklink)){
			$( "#facebooklink" ).css('border','1px solid #ccc');
			//return true;
		}
		else{
			$( "#facebooklink" ).css('border-color','red');	
		}
		if(RegExp.test(twitterlink)){
			$( "#twitterlink" ).css('border','1px solid #ccc');
			//return true;
			
		}
		else{
			$( "#twitterlink" ).css('border-color','red');
		}
		if(RegExp.test(googlepluslink)){
			$( "#googlepluslink" ).css('border','1px solid #ccc');
		//	return true;
		}
		else{			
			$( "#googlepluslink" ).css('border-color','red');	
		}
		
		if(title != ""){
			$( "#title" ).css('border','1px solid #ccc');
			if(address1 != ""){
				$( "#address1" ).css('border','1px solid #ccc');
				if(RegExp.test(youtubelink) && RegExp.test(facebooklink) && RegExp.test(twitterlink) &&  RegExp.test(googlepluslink)){
					var action = '{{URL::to('admin/cms/contactlist')}}';
					$( "#contactlist_form" ).attr('action', action);
					$( "#contactlist_form" ).submit();
				}
			}else{
				$( "#address1" ).css('border-color','red');	
			}
		}else{			
			$( "#title" ).css('border-color','red');
		}
	}
</script>

@endsection