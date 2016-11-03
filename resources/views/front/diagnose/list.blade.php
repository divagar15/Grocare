@extends('front.layout.tpl')
@section('customCss')

<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/bootstrap-chosen/chosen.css')}}" />

<style type="text/css">
  #searchContainer {
    margin-top: 0px !important;
  }
</style>


@endsection

@section('content')

<div class="container pageStart" style="margin-top:130px;">

<div class="row">

  <div class="col-md-5" id="searchContainer">

<form action="{{URL::to('/search-ailment')}}" method="post">
    <div id="custom-search-input">
                <div class="col-md-8 no-padding">
                <select class="chosen-select search-query" name="search_ailment" placeholder="" >
            <option value="">Select your ailment to find a Remedy</option>
            @foreach($diagnosisList as $key=>$val)
              <option value="{{$val->diagnosis_slug}}">{{ucwords($val->name)}}</option>
            @endforeach
          </select>     
<!--                     <input type="text" class="search-query form-control" placeholder="Search your ailment to find a Remedy" />
 -->                </div>
                <div class="col-md-4 no-padding">
                  <button class="btn btn-default" type="submit"> <span class=" glyphicon glyphicon-search"></span> &nbsp; &nbsp;SEARCH</button>
                </div>
    </div>
</form>

  </div>

  <div class="col-md-7" id="alphaList">
  <p>
    <?php
        for ($alpha='A'; $alpha!='AA'; $alpha++){ 
    ?>
        
        <a href="{{URL::to('diagnose?search='.strtolower($alpha))}}">{{$alpha}}</a>

    <?php
        }   
    ?>
    </p>
  </div>

    
  </div>


  <div class="row" id="diagnosis">

    <div class="col-md-12 no-padding">

      @if(isset($diagnosis) && count($diagnosis)>0)

        @foreach($diagnosis as $diagon)

      <div class="col-md-3">

          <div class="diagnosisGrid">
            <a href="{{URL::to('diagnose/'.$diagon->diagnosis_slug)}}">
            <div class="titleh3">{{ucwords($diagon->name)}}</div>
            <p>@if(!empty($diagon->disease_short_description))<?php echo substr(strip_tags($diagon->disease_short_description),0,100)."...";
			//echo substr(strip_tags($diagon->disease_short_description),0,100)."...";
			?>@endif</p>
            </a>
          </div>

      </div>

        @endforeach


      @else

      <div class="titleh5 center">There are no self diagnosis available for your search </div>

      @endif

    </div>


  </div>



</div>




@endsection

@section('customJs')
<script src="{{URL::asset('public/admin/js/plugins/bootstrap-chosen/chosen.jquery.js')}}"></script>

<script type="text/javascript">
  $('.chosen-select').chosen();
</script>

@endsection
