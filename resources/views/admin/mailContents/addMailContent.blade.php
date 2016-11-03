@extends('admin.layout.tpl')

@section('customCss')


@endsection

@section('content')   
  	
	<div class="page-header"><h1>Add Mail Content</h1></div>
	<div class="row">            
		<div class="col-md-12">
				@if(Session::has('success_msg'))
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					{{Session::get('success_msg')}}
				</div>
				@endif


				@if(Session::has('error_msg'))
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					{{Session::get('error_msg')}}
				</div>
				@endif

				<form id="mail-content-form" class="form-horizontal" role="form" method="post" >
						
						<div class="panel-body">

							<div class="form-group">
								<label class="col-sm-3 control-label">Title</label>
								<div class="col-sm-7">
									<input type="text" autocomplete="off" name="title" id="title"  required class="form-control title"> 
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Subject</label>
								<div class="col-sm-7">
									<input type="text" autocomplete="off" name="subject" id="subject" required class="form-control subject"> 
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mail Contents</label>
								<div class="col-sm-7">
									<textarea autocomplete="off" name="mail_contents" id="mail_contents" required class="form-control mail_contents ckeditor"></textarea>
								</div>
							</div>

							<div class="form-group" style="margin-top:20px;">
								<div class="col-sm-7 col-sm-offset-5">
									  <button type="submit" class="btn btn-primary btn-sm">Submit</button>
									  <a href="{{URL::to('admin/mail-contents')}}" class="btn btn-default btn-sm">Cancel</a>
								</div>
							</div>
						  
						</div>
			</form>
				
		 </div>
	
	</div>

@endsection

@section('customJs')

    <script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){			
            $('#mail-content-form').validate();
		});
    </script>

@endsection