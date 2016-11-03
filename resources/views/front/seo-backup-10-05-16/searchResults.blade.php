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

<div class="row">
	<div class="col-md-12">
		<h3>Search results for : {{$query}}</h3>
		<script>
			  (function() {
				var cx = '011878460634367283375:0a5xdi_cytm';
				var gcse = document.createElement('script');
				gcse.type = 'text/javascript';
				gcse.async = true;
				gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(gcse, s);
			  })();
			</script>
		<gcse:searchresults-only></gcse:searchresults-only>
	</div>
</div>


</div>

@endsection

@section('customJs')
<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>

@endsection
