@extends('admin.layout.tpl')

@section('customCss')

@endsection

@section('content')     	
<div class="page-header"><h1>SEO</h1></div>

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
			 <div class="panel-heading">SEO</div>
			 <div class="panel-body">
				
				<form id="distribute_form" class="form-horizontal" role="form" method="post">
				
					<h3>Home</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[0]" id="seo_id[0]" value="@if(isset($home->id)){{$home->id}}@endif" />
							<input type="text" name="seo_title[0]" id="seo_title[0]" class="form-control" autocomplete="off" value="@if(isset($home->seo_title)){{$home->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[0]" id="meta_description[0]" class="form-control" autocomplete="off" value="@if(isset($home->meta_description)){{$home->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
				
					<h3>About US</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[1]" id="seo_id[1]" value="@if(isset($aboutus->id)){{$aboutus->id}}@endif" />
							<input type="text" name="seo_title[1]" id="seo_title[1]" class="form-control" autocomplete="off" value="@if(isset($aboutus->seo_title)){{$aboutus->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[1]" id="meta_description[1]" class="form-control" autocomplete="off" value="@if(isset($aboutus->meta_description)){{$aboutus->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					<h3>Testimonials</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[2]" id="seo_id[2]" value="@if(isset($testimonials->id)){{$testimonials->id}}@endif" />
							<input type="text" name="seo_title[2]" id="seo_title[2]" class="form-control" autocomplete="off" value="@if(isset($testimonials->seo_title)){{$testimonials->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[2]" id="meta_description[2]" class="form-control" autocomplete="off" value="@if(isset($testimonials->meta_description)){{$testimonials->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					<h3>Contact Us</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[3]" id="seo_id[3]" value="@if(isset($contact->id)){{$contact->id}}@endif" />
							<input type="text" name="seo_title[3]" id="seo_title[3]" class="form-control" autocomplete="off" value="@if(isset($contact->seo_title)){{$contact->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[3]" id="meta_description[3]" class="form-control" autocomplete="off" value="@if(isset($contact->meta_description)){{$contact->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					<h3>Distribute</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[4]" id="seo_id[4]" value="@if(isset($distribute->id)){{$distribute->id}}@endif" />
							<input type="text" name="seo_title[4]" id="seo_title[4]" class="form-control" autocomplete="off" value="@if(isset($distribute->seo_title)){{$distribute->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[4]" id="meta_description[4]" class="form-control" autocomplete="off" value="@if(isset($distribute->meta_description)){{$distribute->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					
					<h3>Technical Bulletins</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[5]" id="seo_id[5]" value="@if(isset($technicalbulletins->id)){{$technicalbulletins->id}}@endif" />
							<input type="text" name="seo_title[5]" id="seo_title[5]" class="form-control" autocomplete="off" value="@if(isset($technicalbulletins->seo_title)){{$technicalbulletins->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[5]" id="meta_description[5]" class="form-control" autocomplete="off" value="@if(isset($technicalbulletins->meta_description)){{$technicalbulletins->meta_description}}@endif">
						</div>
					</div>	
					
					
					<hr/>
					
					
					<h3>FAQ</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[6]" id="seo_id[6]" value="@if(isset($faq->id)){{$faq->id}}@endif" />
							<input type="text" name="seo_title[6]" id="seo_title[6]" class="form-control" autocomplete="off" value="@if(isset($faq->seo_title)){{$faq->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[6]" id="meta_description[6]" class="form-control" autocomplete="off" value="@if(isset($faq->meta_description)){{$faq->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					
					<h3>Store</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[7]" id="seo_id[7]" value="@if(isset($store->id)){{$store->id}}@endif" />
							<input type="text" name="seo_title[7]" id="seo_title[7]" class="form-control" autocomplete="off" value="@if(isset($store->seo_title)){{$store->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[7]" id="meta_description[7]" class="form-control" autocomplete="off" value="@if(isset($store->meta_description)){{$store->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					
					<h3>Self Diagnosis</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[8]" id="seo_id[8]" value="@if(isset($selfdiagnosis->id)){{$selfdiagnosis->id}}@endif" />
							<input type="text" name="seo_title[8]" id="seo_title[8]" class="form-control" autocomplete="off" value="@if(isset($selfdiagnosis->seo_title)){{$selfdiagnosis->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[8]" id="meta_description[8]" class="form-control" autocomplete="off" value="@if(isset($selfdiagnosis->meta_description)){{$selfdiagnosis->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					
					<h3>Scholarship</h3>
					<div class="form-group">
						<label class="col-sm-2 control-label">SEO Title</label>
						<div class="col-sm-8">
							<input type="hidden" name="seo_id[9]" id="seo_id[9]" value="@if(isset($scholarship->id)){{$scholarship->id}}@endif" />
							<input type="text" name="seo_title[9]" id="seo_title[9]" class="form-control" autocomplete="off" value="@if(isset($scholarship->seo_title)){{$scholarship->seo_title}}@endif">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Meta Description</label>
						<div class="col-sm-8">
							<input type="text" name="meta_description[9]" id="meta_description[9]" class="form-control" autocomplete="off" value="@if(isset($scholarship->meta_description)){{$scholarship->meta_description}}@endif">
						</div>
					</div>	
					
					<hr/>
					
					<div class="form-group" style="margin-top:20px;">
						<div class="col-sm-7 col-sm-offset-5">
						<button type="submit" class="btn btn-primary btn-sm">Update</button>
						</div>
					</div>	
				
					
					
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('customJs')

@endsection