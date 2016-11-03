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
  </style>

@endsection

@section('content')

<div class="wrap contentclass" role="document"><div id="pageheader" class="titleclass"><div class="container"><div class="page-header"><h1> <?php if(empty($cms_eval->customerpolicy_tilte) && $cms_eval->customerpolicy_tilte == ""){}else{ echo $cms_eval->customerpolicy_tilte;}?></h1></div></div></div><div id="content" class="container"><div class="row"><div class="main col-md-12" role="main"><p class="bodytitle"><strong><?php if(empty($cms_eval->customerpolicy_tilte) && $cms_eval->customerpolicy_tilte == ""){}else{ echo $cms_eval->customerpolicy_tilte;}?></strong></p><p><?php if(empty($cms_eval->customerpolicy_descp) && $cms_eval->customerpolicy_descp == ""){}else{ echo $cms_eval->customerpolicy_descp;}?></p></div></div></div></div>


@endsection

@section('customJs')


@endsection
