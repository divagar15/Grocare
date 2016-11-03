@extends('front.layout.tpl')
@section('customCss')
  <style type="text/css">
    .panel-default{
      border-top:0px;
    }
	.tablist{
		background: #dddddd;
	}
	.form-search .search-query {
		display: block;
		position: relative;
		right: 0;
		width: 100%;
		height: 35px;
		margin: 0;
		border: none;
		padding: 5px 5px 5px 35px;
		font-size: 13px;
		font-weight: 400;
		color: #777;
		background-color: #fff;
		border: 1px solid;
		border-color: #eee;
		border-color: rgba(196,196,196,.4);
		-moz-border-radius: 0;
		-webkit-border-radius: 0;
		border-radius: 0;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
	}
	.form-search .search-icon{
		display: block;
		margin: 0;
		width: 30px;
		font-weight: 400;
		font-variant: normal;
		text-transform: none;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		padding: 5px 5px 5px 10px;
		position: absolute;
		left: 0;
		top: 0;
		border: 0;
		cursor: pointer;
		height: 35px;
		color: #777;
		background: 0 0;
		font-family: FontAwesome;
	}

	cancellation-refund-policymedia="all"
	button, input, select[multiple], textarea {
		background-image: none;
	}
	.header{
		border-bottom: 1px solid #eee;
	}
	.bodytitle,.kad-sidebar{
		margin:10px 0px;
	}
  </style>

@endsection

@section('content')

<div class="wrap contentclass" role="document"><div id="pageheader" class="titleclass"><div class="container"><div class="page-header"><h1> <?php if(empty($cms_eval->shipping_tilte) && $cms_eval->shipping_tilte == ""){}else{echo $cms_eval->shipping_tilte;}?></h1></div></div></div><div id="content" class="container"><div class="row"><div class="main col-lg-9 col-md-8" role="main"><p class="bodytitle"><strong><?php if(empty($cms_eval->shipping_tilte) && $cms_eval->shipping_tilte == ""){}else{echo $cms_eval->shipping_tilte;}?></strong></p><p><?php if(empty($cms_eval->shipping_descp) && $cms_eval->shipping_descp == ""){}else{ echo $cms_eval->shipping_descp;}?></p></div> 

<aside class="col-lg-3 col-md-4 kad-sidebar" role="complementary"><div class="sidebar"> <section id="search-2" class="widget-1 widget-first widget widget_search"><div class="widget-inner"><form role="search" method="get" id="searchform" class="form-search" action="{{URL::to('/')}}"> <label class="hide" for="s">Search for:</label> <input type="text" value="" name="s" id="s" class="search-query" placeholder="Search"> <button type="submit" id="searchsubmit" class="search-icon"><i class="icon-search"></i></button></form></div></section></div> </aside></div></div></div>



@endsection

@section('customJs')


@endsection
