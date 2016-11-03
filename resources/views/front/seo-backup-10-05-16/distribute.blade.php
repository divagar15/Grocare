@extends('front.layout.tpl')
@section('customCss')
  <style type="text/css">
    .panel-default{
      border-top:0px;
    }
	.tablist{
		background: #dddddd;
	}
	.header{
		border-bottom: 1px solid #eee;
	}
	.bodytitle,.kad-sidebar{
		margin:10px 0px;
	}
	.accordion-toggle h5{
		text-align:left;
	}
	a {
		color: #00f;
	}
	iframe{
		width:100%;
		height:550px;
	}
	
  </style>

@endsection

@section('content')

<?php
//echo $cms_eval->videolink;
$videolink = str_replace("watch?v=", "embed/",$cms_eval->videolink);
?>


<div class="wrap contentclass" role="document">
	<div id="pageheader" class="titleclass">
		<div class="container">
			<div class="page-header">
				<h1> <?php if($cms_eval->pagetitle != ""){ echo trim($cms_eval->pagetitle, '{}'); } ?></h1>
			</div>
		</div>
	</div>
	<div id="content" class="container">
		<div class="row">
			<div class="main col-md-12" role="main">
				<p style="text-align: center;"></p>
				
				
				
				<div class="kad-youtube-shortcode videofit">
					<div class="fluid-width-video-wrapper" style=""><iframe src="<?php echo $videolink;?>" frameborder="0" allowfullscreen="true" id="fitvid312275" ></iframe></div>
				</div>
				<p style="text-align: center;"></p>
				<div class="space_40 clearfix"></div>
				<h1 style="text-align: center;"><?php if($cms_eval->headtitle != ""){ echo trim($cms_eval->headtitle, '{}'); } ?></h1>
				@foreach($CmsDistribute as $CmsDistribute)
				<div class="space_40 clearfix"></div>
				<h2 style="text-align: center;">{{$CmsDistribute->title}}</h2>
				<div class="hrule clearfix"></div>
				<p style="text-align: center;">
					<?php echo $CmsDistribute->description;?>
				</p>
				<div class="space_80 clearfix"></div>
				@endforeach
				<p style="text-align: center;">
					<a href="#contactUsForm" class="call_btncircle inline2"><img src="{{URL::asset('public/front/images/blue-30.png')}}" /></a>
					<style type="text/css" media="screen">#kadbtn91:hover{background: !important;color:#FFF !important}</style>
				</p>
				<h2 style="text-align: center;">
					<div class="space_40 clearfix"></div>
				</h2>
				<h3 style="text-align: center;">Help Us, Help You</h3>
				<h1 style="text-align: center;"></h1>
			</div>
		</div>
	</div>
</div>

	<div style='display:none'>
		<div id='contactUsForm' style='padding:29px; background:#fff;'>
			<div class=" col-md-offset-2 col-md-8" id="contactForm">

				  <form id="contact-form" class="form-horizontal customForm" method="post" action="{{URL::to('/contact')}}">
					<h3 class="center">Contact Us</h3>
					<div class="form-group">

					  <div class="col-md-12"> 
					  <input type="text" required name="name" id="name" class="form-control" placeholder="Name" />

					  </div>

					</div>


					<div class="form-group">

					  <div class="col-md-12"> 

					  <input type="email" required name="email" id="email" class="form-control" placeholder="Email ID" />

					  </div>

					</div>


					<div class="form-group">

					  <div class="col-md-12"> 

					  <input type="text" required number="true" name="phone" id="phone" class="form-control" placeholder="Mobile number" />

					  </div>

					</div>

					<div class="form-group">

					  <div class="col-md-12"> 

						<textarea name="message" id="message" autogrow="false" required class="form-control" placeholder="Message"></textarea>

					  </div>

					</div>


					<div class="form-group">

					  <div class="col-md-12"> 
						<button type="submit" class="btn btn-default btn-sm customGradientBtn submitBtn">SUBMIT</button>
					  </div>

					</div>


				  </form>


				</div>
		</div>
	</div>

@endsection

@section('customJs')
<script>
$(document).ready(function(){
	  
	   $('#contact-form').validate();
	  
	  // $(".inline").colorbox({inline:true, width:"85%",close: "close"});
	  $(".inline2").colorbox({inline:true, width:"85%",close: "close"});
	  
});
</script>

@endsection
