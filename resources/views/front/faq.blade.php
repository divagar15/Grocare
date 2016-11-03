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



<div class="container pageStart">

<div class="wrap contentclass" role="document"><div id="pageheader" class="titleclass"><div class="container"><div class="page-header"><h1> <?php if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?></h1></div></div></div><div id="content" class="container"><div class="row"><div class="main col-md-12" role="main"><p class="bodytitle" style="text-align: left;"><?php if($cms_eval->desciption != ""){ echo trim($cms_eval->desciption, '{}'); } ?></p><p style="text-align: left;"></p>

<div class="panel-group" id="accordionname62">

<?php $j = 1;?>

@foreach($CmsFaq as $CmsFaq)

<div class="panel panel-default panel-even"><div class="panel-heading"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordionname62" href="#collapse_<?php echo  $j;?>"><div class="titleh5"><i class="icon-minus primary-color"></i><i class="icon-plus"></i>Q. {{$CmsFaq->question}}</div></a></div><div id="collapse_<?php echo  $j;?>" class="panel-collapse collapse" style="height: 0px;"><div class="panel-body postclass"> A :&nbsp; <?php echo $CmsFaq->answer;?><br></div></div></div>

<?php $j++;?>

@endforeach

<div class="panel panel-default panel-even"><div class="panel-heading"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordionname62" href="#collapse_650"><div class="titleh5"><i class="icon-minus primary-color"></i><i class="icon-plus"></i>DISCLAIMER </div></a></div><div id="collapse_650" class="panel-collapse collapse" style="height: 0px;"><div class="panel-body postclass"> The above are general guidelines only and not meant for individual’s treatment. Please consult your Physician before treatment<br></div></div></div>

</div></div></div></div></div>





</div>



@endsection



@section('customJs')

<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>

<script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>

<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>

<script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>



@endsection

