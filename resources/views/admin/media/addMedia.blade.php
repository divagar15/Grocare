@extends('admin.layout.tpl')

@section('customCss')


@endsection

@section('content')   
  	
	<div class="page-header"><h1>Add Media</h1></div>
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

				<form id="media-form" class="form-horizontal" role="form" method="post" >
						
						<div class="panel-body">

							<div class="form-group">
								<label class="col-sm-3 control-label">Media Title</label>
								<div class="col-sm-7">
									<input type="text" autocomplete="off" name="title" id="title"  required class="form-control"> 
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Media Link</label>
								<div class="col-sm-7">
									<input type="text" autocomplete="off" name="link" id="link" required class="form-control"> 
								</div>
							</div>

							<div class="form-group" style="margin-top:20px;">
								<div class="col-sm-7 col-sm-offset-5">
									  <button type="submit" class="btn btn-primary btn-sm">Submit</button>
									  <a href="{{URL::to('admin/media')}}" class="btn btn-default btn-sm">Cancel</a>
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
            $('#media-form').validate();
		});
    </script>

@endsection