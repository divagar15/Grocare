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
	li {
	    font-size: 16px;
	    line-height: 18px;
	}
  </style>

@endsection

@section('content')
<div class="wrap contentclass" role="document">
	<div id="pageheader" class="titleclass">
		<div class="container">
			<div class="page-header">
				<h1> Technical Bulletins</h1>
			</div>
		</div>
	</div>
	<div id="content" class="container">
		<div class="row">
			<div class="main col-md-12" role="main">
			<ul>
			@foreach($cms_bulletins as $cms_bulletins)
					<li>
					<a href="{{URL::to('public/uploads/cms/technicalBulletins/'.$cms_bulletins->bulletins.'')}}" download>@if(empty($cms_bulletins->title) && $cms_bulletins->title == ""){}@else{{{$cms_bulletins->title}}}@endif</a></li>		
			@endforeach
				</ul>
				<p>&nbsp;</p>
			</div>
		</div>
	</div>
</div>

@endsection

@section('customJs')


@endsection
