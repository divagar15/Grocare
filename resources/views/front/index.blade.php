@extends('front.layout.tpl')
@section('customCss')

<!--     <link href="{{URL::asset('public/front/css/carousel.css')}}" rel="stylesheet">
 -->
    <link href="{{URL::asset('public/front/css/owl.carousel.css')}}" rel="stylesheet">

    <link href="{{URL::asset('public/front/css/owl.theme.default.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/bootstrap-chosen/chosen.css')}}" />

<!--         <link href="{{URL::asset('public/front/css/window-popup.css')}}" rel="stylesheet">
 -->

    <style type="text/css">
      .navbar-wrapper {
        box-shadow: none;
      }
	  .elipsis{
		white-space: nowrap; 
		width: 100%; 
		overflow: hidden;
		text-overflow: ellipsis; 
	  }
	  .carousel-inner{
			/*height: 450px;
*/
      height: auto;
	  }
	  .sections{
		  margin:5% 0px;
	  }
	  .btn-default.readmore,.btn-default.readmore:hover {
		color: #333 !important;
		background-color: #fff !important; 
		border-color: #ccc !important; 
	}
		@media (max-width:767px){
		#custom-search-input{    margin-top: 0px;}
		 .carousel-inner1{height: auto;	  }
		 #didYouKnow .item {    height: auto;}
		 .test_slider .owl-controls{display:none;}
		 .carousel-inner {    height: auto !important;}
		 #happyCustomer h3{    margin-top: 0px;    padding-top: 10px;}
		 #securePayments h3{    margin-top: 0px;    padding-top: 10px;}
		 #informationBox{    margin-top: 0px;}
	}
	.did_you_know{
		font-size:16px;
	}
  #securePayments p {
      font-size: 14px;
  }


  iframe[id^='twitter-widget-0'] {

height:600px !important;

margin-bottom:10px !important;

width:100% !important;

}

.fb-page, 
.fb-page span, 
.fb-page span iframe[style] { 
    width: 100% !important; 
}
/*
.fb-social-plugin {
    width:98%!important;

}

.fb_iframe_widget span {
    width:100%!important;
}

.fb-comments iframe[style] {width: 100% !important;}*/

  </style>

@endsection

@section('content')

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=1016468615076179";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


	
	<div id="boxes">
  <div style="display: none;" id="dialog" class="window">
    <div id="lorem">
	 <div style="position: absolute;right: 0;top: 0;cursor: pointer;" class="close-btn"><img src="{{URL::asset('public/front/images/close.png')}}" /></div>
	 <div style="padding:5%;"> 
      <div class="titleh3" style="font-weight:bold; font-size:28px;">We are updating our servers to improve customer experience. Please bear with us for 24 hours.</div>
    </div>
    </div>
   
  </div>
  <div style="width: 100%; font-size: 32pt; color:white; height: auto; display: none; opacity: 0.8;" id="mask"></div>
</div>

    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
    <!--   <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol> -->
      <div class="carousel-inner" role="listbox">

      <div class="item active">
                <iframe src="https://www.youtube.com/embed/FrarfPLSW6U" width="100%" class="full-one"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 

       <div class="item">
                <iframe src="https://player.vimeo.com/video/128459000" width="100%" class="full-one"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div> 

        <div class="item">
          <a href="{{URL::to('diagnose/hernia')}}"><img class="first-slide" src="{{URL::asset('public/uploads/banner/1.jpg')}}" alt="Grocare"></a>
        </div>

        <div class="item">
          <a href="{{URL::to('diagnose/varicocele')}}"><img class="" src="{{URL::asset('public/uploads/banner/2.jpg')}}" alt="Grocare"></a>
        </div>

        <div class="item">
          <a href="{{URL::to('diagnose/high-cholestrol')}}"><img class="" src="{{URL::asset('public/uploads/banner/3.jpg')}}" alt="Grocare"></a>
        </div>

        <div class="item">
          <a href="{{URL::to('diagnose/kidney-stones')}}"><img class="" src="{{URL::asset('public/uploads/banner/4.jpg')}}" alt="Grocare"></a>
        </div>

        <div class="item">
          <a href="{{URL::to('diagnose/gall-bladder-stones')}}"><img class="" src="{{URL::asset('public/uploads/banner/5.jpg')}}" alt="Grocare"></a>
        </div>

        <div class="item">
          <img class="" src="{{URL::asset('public/uploads/banner/6.jpg')}}" alt="Grocare">
        </div>

        <div class="item">
          <img class="" src="{{URL::asset('public/uploads/banner/7.jpg')}}" alt="Grocare">
        </div>

        <div class="item">
          <a href="{{URL::to('diagnose/tooth-sensitivity')}}"><img class="" src="{{URL::asset('public/uploads/banner/8.jpg')}}" alt="Grocare"></a>
        </div>

       
       <!--  <div class="item active">
          <img class="first-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Example headline.</h1>
              <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
            </div>
          </div>
        </div> -->
        
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->


<div class="container" id="searchContainer">
  <div class="row">
			<form action="{{URL::to('/search-ailment')}}" method="post">
			<div id="custom-search-input">
                <div class="col-md-10 no-padding">
                    <select class="chosen-select search-query" name="search_ailment" placeholder="" >
						<option value="">Select your ailment to find a Remedy</option>
						@foreach($diagnosis as $key=>$val)
							<option value="{{$val->diagnosis_slug}}">{{ucwords($val->name)}}</option>
						@endforeach
					</select>                                
                </div>				
                <div class="col-md-2 no-padding">
                  <button class="btn btn-default" type="submit"> <span class=" glyphicon glyphicon-search"></span> &nbsp; &nbsp;SEARCH</button>
                </div>
				<!--
                <div class="col-md-10 no-padding">
                    <input type="text" class="search-query form-control" placeholder="Search your ailment to find a Remedy" />
                                
                </div>
                <div class="col-md-2 no-padding">
                  <button class="btn btn-default" type="button"> <span class=" glyphicon glyphicon-search"></span> &nbsp; &nbsp;SEARCH</button>
                </div>-->
          </div>
		  </form>
  </div>
</div>

<div class="container" id="informationBox">
    <div class="row">
      <div class="col-md-12 no-padding">

        <div class="col-md-6" id="didYouKnow">
          <div class="titleh5">TESTIMONIALS</div>
          <div class="owl-carousel test_slider">
			@foreach($testimonials as $key=>$val)
            <div class="item">
              <p class=""><?php echo substr(strip_tags($val->testi_content),0,180)."..."; // echo $val->testi_content;?></p>
             @if(!empty($val->testi_from))<!--  <p style="text-align:right; margin:0 0 0px !important;">- {{ucwords($val->testi_from)}}</p> --> @endif
             <p> <a href="{{URL::to('/testimonials')}}" class="btn btn-sm readmore">Read More</a> @if(!empty($val->testi_from))<span style="text-align:right; float:right;">- {{ucwords($val->testi_from)}}</span>@endif</p>
            </div>
			@endforeach
          </div>
        </div>

        <div class="col-md-3" id="happyCustomer">
		
          <div class="titleh3">HAPPY <br/> CUSTOMERS</div>
          <p>ALL OVER THE WORLD</p>
          <div class="titleh2">{{$happy_customers}}</div>
        </div>

        <div class="col-md-3" id="securePayments">
        <div class="titleh3">SECURE <br/> PAYMENTS</div>
          <p>With Credit / Debit Cards / Net Banking / Direct Bank Transfer</p>
          <div class="titleh2" style="margin-top:5px;">100%</div>
        </div>
        
      </div>
    </div>
</div>

<div class="container">

<div class="row sections section_home">
      <div class="col-md-12">
          <p style="text-align:center;">At Grocare India we believe in offering specific result oriented formulations, which will help tackle day to day discomforts or problems. We understand your needs and innovate our products very precisely to not only aim at elimination of the root cause of any problem, but also to enrich other aspects of the body with it. Keeping this in mind, our reliable and affordable products ensure personal healthcare in all possible ways.</p>
          <p style="text-align:center;"><a href="{{URL::to('/about-us')}}" class="btn btn-default btn-bg customGradientBtn know_1">Know More</a></p>
      </div>
</div>

<div class="row sections" id="tagline">
      <div class="col-md-12">
        <div class="col-md-2 col-md-offset-1">
          <img src="{{URL::asset('public/front/images/icons/leaf.png')}}" />
          <div class="titleh5">NO SIDE <br/> EFFECTS</div>
        </div>

 <div class="col-md-2">
          <img src="{{URL::asset('public/front/images/icons/no_added_steroids.png')}}" />
          <div class="titleh5">NO ADDED <br/> CHEMICALS</div>
        </div>

<!--                 <div class="col-md-4">
          <img src="{{URL::asset('public/front/images/icons/diet.png')}}" />
          <div class="titleh5">NO DIETARY RESTRICTIONS</div>
       </div>
 -->
        <div class="col-md-2">
          <img src="{{URL::asset('public/front/images/icons/fda.png')}}" />
          <div class="titleh5">FDA <br/> APPROVED</div>
        </div>



         <div class="col-md-2">
          <img src="{{URL::asset('public/front/images/icons/research_based.png')}}" />
          <div class="titleh5">RESEARCH <br/> BASED</div>
        </div>

         <div class="col-md-2">
          <img src="{{URL::asset('public/front/images/icons/world_wide_shipping.png')}}" />
          <div class="titleh5">WORLDWIDE <br/> SHIPPING</div>
        </div>
      </div>
</div>

<div class="row centralize sections">
<div class="col-md-10 col-md-offset-1">
<h1 style="text-align: center;font-family: 'lato-light';">DID YOU KNOW?</h1>
<div class="" id="testimonialSlider">
	@if(count($simpleProducts)>0)
	@foreach($simpleProducts as $key=>$val)
    <div class="item">
      <div class="review-container display-inline-block vertical-align-top">
			<div class="review-content">
				<p class="text review-text">
				<?php echo $val->did_you_know;?>
				</p>
				<a href="{{URL::to('/products/'.$val->product_slug)}}" class="btn btn-default btn-bg customGradientBtn1 did_you_know">Try {{$val->name}}</a>                          
			</div>
      </div>
    </div>
	@endforeach
	@endif
	
	@if(count($diagnosisDetail)>0)
	@foreach($diagnosisDetail as $key=>$val)
    <div class="item">
      <div class="review-container display-inline-block vertical-align-top">
			<div class="review-content">
				<p class="text review-text">
				<?php echo $val->did_you_know;?>
				</p>
				<a href="{{URL::to('/diagnose/'.$val->diagnosis_slug)}}" class="btn btn-default btn-bg customGradientBtn1 did_you_know">Read More</a>                          
			</div>
      </div>
    </div>
	@endforeach
	@endif


</div>
</div>
</div>


</div>


<div class="container">
<!--   <h1 style="text-align: center;font-family: 'lato-light';margin:1% 0 3% 0;">Social Media</h1>
 -->  
<div class="row">

<div class="col-md-6">
        <a class="twitter-timeline"  href="https://twitter.com/Grocare_India" data-widget-id="717646686403690496">Tweets by @Grocare_India</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>


<div class="col-md-6">
<!--     <div class="fb-page" data-href="https://www.facebook.com/grocare" data-tabs="timeline" data-width="350" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false"></div>
 -->

 <div class="fb-page" data-href="https://www.facebook.com/grocare" data-tabs="timeline" data-width="500" data-height="600" data-show-text="true" data-adapt-container-width="true"></div>
</div>

</div>

</div>



@endsection

@section('customJs')

    <script  src="{{URL::asset('public/front/js/owl.carousel.js')}}"></script>
    <script  src="{{URL::asset('public/admin/js/plugins/bootstrap-chosen/chosen.jquery.js')}}"></script>
<!--        <script type="text/javascript"  src="{{URL::asset('public/front/js/window-popup.js')}}"></script>-->

    <script type="text/javascript">

/*    $('#myCarousel').carousel({
      interval: 3000
    }).on('slide.bs.carousel', function () {
      document.getElementById('player').pause();
    });*/

    $('.chosen-select').chosen();

    $('.owl-carousel').owlCarousel({
          loop:false,
          margin:10,
          nav:true,
          autoplay:true,
          responsive:{
              0:{
                  items:1
              }
          }
      });
	var owl = $("#testimonialSlider");
 
  owl.owlCarousel({
    navigation : true,
	slideSpeed: 300,
	autoplay:true,
    transitionStyle : "fade",
	responsive:{
              0:{
                  items:1
              },
              600:{
                  items:1
              },
              1000:{
                  items:1
              }
          }
  });
   /* $('#testimonialSlider').owlCarousel({
		
          loop:false,
          margin:10,
          nav:false,
          autoplay:false,
		  transitionStyle : "fade",
          responsive:{
              300:{
                  items:1
              },
              600:{
                  items:1
              },
              1000:{
                  items:1
              }
          }
      });*/
    </script>

@endsection
