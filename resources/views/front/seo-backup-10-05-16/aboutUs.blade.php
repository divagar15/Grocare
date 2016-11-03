@extends('front.layout.tpl')
@section('customCss')


@endsection

@section('content')

<div class="container-fluid pageStart no-padding">

<div class="row" id="aboutUs">
    <div class="col-md-12 full-one" style="text-align:center;">	
     
      <iframe src="@if(empty($cms_eval->videolink) && $cms_eval->videolink == ""){}@else{{{$cms_eval->videolink}}}@endif" width="100%" class="full-one"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

  </div>
  </div>
  </div>
  <div class="container" style="margin-top:20px;">

<div class="row" id="aboutUs">
  <div class="col-md-12">
 <h2><?php if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?></h2>
   
	
    <p><?php if($cms_eval->descp != ""){ echo trim($cms_eval->descp, '{}'); } ?></p>

  </div>



</div>


<div class="row" id="slogan">
      <div class="col-md-12">
	 @foreach($cms_taglinks as $cms_taglinks)
	<div class="col-md-4" style="height: 400px;">
		<img src="{{URL::asset('public/uploads/cms/taglinks/'.$cms_taglinks->image_path.'')}}" />
		<h5>{{$cms_taglinks->title}}</h5>
		<p><?php echo $cms_taglinks->descp;?></p>
	</div>
	 @endforeach
	  
</div>

<div class="row" style="margin-top:10px;">

  <div class="col-md-12">

    <h5 class="text-center">ALL OF THESE AMAZING THINGS, AND SUCH NOMINAL COSTS</h5>

    <p style="text-align:center;">We believe that healthcare should be inexpensive. We are here for you. And we’ll get our kick when you call us saying you’re healed!</p>

    <p style="text-align:center;"><a href="{{URL::to('/self-diagnosis')}}" class="btn btn-default btn-sm customGradientBtn diagnoseBtn">DIAGNOSE YOURSELF NOW!</a></p>

  </div>

</div>


</div>
</div>
@endsection

@section('customJs')


@endsection
