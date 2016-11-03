@extends('front.layout.tpl')
@section('customCss')
  <style type="text/css">
    .panel-default{
      border-top:0px;
    }
	.tablist{
		background: #dddddd;
	}	
	.bodytitle,.kad-sidebar{
		margin:10px 0px;
	}
	.player .video-wrapper, .player .video-wrapper .telecine, .player .video-wrapper object, .player .video-wrapper video{
		width:100%;
		height:100%;
	}
	.player .video-wrapper .video{
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-size: contain;
		margin: 0 auto;
		background-repeat: no-repeat;
		background-position: 50% 50%;
		-webkit-transition: -webkit-filter .25s;
		transition: -webkit-filter .25s;
		transition: filter .25s;
		transition: filter .25s,-webkit-filter .25s;
	}
	.player{
		    width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			-webkit-font-smoothing: auto;
			line-height: normal;
			border-collapse: separate;
			-ms-touch-action: none;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;

	}
	#staticfirst_column{
		width:80%;
		margin:0 auto;
	}
	iframe{
		width:100%;
		height:600px;
	}
	@media (max-width:767px){
		iframe{
		width:100%;
		height:300px;
	}
		
	}
  </style>

@endsection

@section('content')

<div id="staticfirst_column" class="tcol">
	<div class="su-animate fadeInUp animated" style="visibility: visible; animation-duration: 1s; animation-delay: 0s;" data-animation="fadeInUp" data-duration="1" data-delay="0">
		<div class="space_40 clearfix"></div>
		<div class="space_40 clearfix"></div>
		@foreach($media as $key=>$val)
		<h2 style="text-align: center;"><strong>{{$val->title}}</strong></h2>
		<h2 style="text-align: center;">
		
			<strong>
				<div class="kad-youtube-shortcode videofit">
					<div class="fluid-width-video-wrapper">
						<?php $link = str_replace('watch?v=','embed/',$val->link); ?>
						<iframe src="{{$link}}" frameborder="1" allowfullscreen="true" id=""></iframe>
					</div>
				</div>
				<div class="space_40 clearfix"></div>
			</strong>
		</h2>
		@endforeach
		<p>&nbsp;</p>
	</div>
</div>


@endsection

@section('customJs')


@endsection
