@extends('front.layout.tpl')
@section('customCss')

<link href="{{URL::asset('public/front/css/owl.carousel.css')}}" rel="stylesheet">
<link href="{{URL::asset('public/front/css/owl.theme.default.min.css')}}" rel="stylesheet">

<style type="text/css">
  #colorbox {
    /*z-index: 999999;*/
  }

  #searchContainer {
    margin-top: 0px !important;
  }

  .diagnonsis-product img {
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
	.content {
		margin: 15px 0px;
	}
	h5.size15{
		font-size:15px;
	}
	.full-width{
		width:100%;
	}
	#cboxLoadedContent{
		overflow:hidden !important;
	}
	div.tcol {
		width: 100% !important;
	}
	.center{
		text-align:center;
	}
	#diagnosis-testimonials .owl-dot{
		display:none;
	}
	#diagnosis-testimonials .owl-nav{
		display:block !important;
	}
	#diagnosis-testimonials{
		padding:10px 0px;
		/*background: linear-gradient(-90deg, #75C967, #00AFE3);		*/
		    background: rgb(0, 141, 220);
			color: #FFF;
		
	}
	.owl-controls{
		display: inline-block !important;
		position: absolute !important;
		top: 1% !important;
		right: 1% !important;
	}
	.owl-prev{
		display:block !important;		
	}
	.owl-next{
		display:block !important;
	}
	.owl-theme .owl-controls .owl-nav [class*=owl-] {
		margin: 0px 5px 0px;
		padding: 1px;
		background:none;
	}
	.owl-theme .owl-controls .owl-nav [class*=owl-]:hover {
		background:none;
	}
	.owl-next img {
		width: 100%;
	}
	.owl-prev img {
		width: 100%;
	}
	.item {
		padding: 2% 7%;
		text-align: justify;		
	}
	.customGradientBtn,.customGradientBtn:hover,.customGradientBtn:focus,.customGradientBtn:active,.customGradientBtn:active:focus{
		color:#fff;
		/*background:linear-gradient(3.5deg, #6fc677, #48bca5);*/
		background:#008ddc;
	}
	.size15{
		font-size:15px;
	}
	.center{
		text-align:center;
	}
	
</style>


@endsection

@section('content')

<div class="container pageStart" style="margin-top:20px;" id="productListing">

<div class="row centralize">

  <div class="col-md-12 content">

    <div class="productTitleBlock">

    
      <h2>@if(!empty($diagnosisDetail->title)){{ucwords($diagnosisDetail->title)}}@else{{ucwords($diagnosisDetail->name)}}@endif</h2>
		<div class="hrule clearfix"></div>
      <p><?php echo $diagnosisDetail->disease_description; ?></p>

    </div>

  </div>

  <div class="col-md-12 content">

      <div class="col-xl-2 col-md-2 col-sm-4 col-md-offset-3">

        <p><a @if($diagnosisDetail->how_it_works!='') href="#how_it_works" @else href="javascript:void(0)" @endif class="btn btn-default btn-sm tabBtn customGradientBtn full-width white">How it works</a></p>

      </div>

      <div class="col-xl-2 col-md-2 col-sm-4">

        <p><a href="#orderMenu" id="orderMenu2" class="btn btn-default btn-sm tabBtn customGradientBtn full-width white order-color order_black">ORDER NOW</a></p>	
      </div>

       <div class="col-xl-2 col-md-2 col-sm-4">

        <p><a @if(isset($testimonials) && count($testimonials)>1) href="#testimonials" @else href="javascript:void(0)" @endif class="btn btn-default btn-sm tabBtn customGradientBtn full-width white" >Testimonials</a></p>

      </div>
	  	

  </div>

  <div class="col-md-12 content" style="margin-top:20px; margin-bottom:10px;">

  <div class="col-md-5 col-md-offset-5">

  <div class="fb-like" data-href="https://www.facebook.com/grocare" data-layout="standard" data-action="like" data-show-faces="false" data-share="false"></div>

  </div>

  </div>

  @if(!empty($diagnosisDetail->how_is_it_caused))

    <div class="col-md-12 content">

    <div class="productTitleBlock">

    
      <h3>How is it caused?</h3>

      <p><?php echo $diagnosisDetail->how_is_it_caused; ?></p>

    </div>

  </div>


  @endif

@if(!empty($diagnosisDetail->how_to_heal_naturally))

    <div class="col-md-12 content">

    <div class="productTitleBlock">

    
      <h3>How to heal naturally?</h3>

      <p><?php echo $diagnosisDetail->how_to_heal_naturally; ?></p>

    </div>

  </div>


@endif

@if(!empty($diagnosisProduct))
  <div class="col-md-12 content">
  <?php

    $countPro = 0;
    if(count($diagnosisProduct)>0) {
      $countPro = count($diagnosisProduct);
    }

    $offsetPro = 0;
      if($countPro==1) {
        $offsetPro = 4;
      } else if($countPro==2) {
        $offsetPro = 2;
      }
  ?>

  @foreach($diagnosisProduct as $keyval=>$diaPro)


      <div class="col-md-4 col-sm-offset-{{$offsetPro}} diagnonsis-product">
      
        <!-- <h3>{{ucwords($diaPro->name)}}</h3> -->

        @if($diaPro->feature_image_original!='')
          <a @if($diaPro->key_ingredients!='') href="{{URL::to('products/'.$diaPro->product_slug)}}" @else href="javascript:void(0)" @endif  target="_blank" title="{{ucwords($diaPro->name)}}"><img src="{{URL::asset('public/uploads/products/'.$diaPro->product_id.'/'.$diaPro->feature_image_original)}}" class="img-responsive"></a>
        @endif

        
         <p style="margin-top:10px; margin-bottom:10px;"><a class="@if($diaPro->key_ingredients!='') inline @endif btn btn-default btn-sm tabBtn customGradientBtn white" @if($diaPro->key_ingredients!='') href="#popup{{$keyval}}"  @else href="javascript:void(0)" @endif >CONTENTS</a></p>
			<div style='display:none'>
				<div id='popup{{$keyval}}' style='padding:29px; background:#fff;'>
					<h3 class="center">{{ucwords($diaPro->name)}}</h3>
					<p><?php echo $diaPro->key_ingredients; ?></p>
				</div>
			</div>
						

        <p><?php echo $diaPro->product_content; ?></p>

      </div>

      <?php $offsetPro = 0; ?>

  @endforeach
  </div>
@endif


@if(!empty($diagnosisDetail->how_it_works))

    <div class="col-md-12 content" id="how_it_works">

    <div class="productTitleBlock">

    
      <h3>How it works?</h3>
		<div class="hrule clearfix"></div>
      <p><?php echo $diagnosisDetail->how_it_works; ?></p>

    </div>

  </div>


@endif

<div class="col-md-12 content" id="">
  <p><a class="iframevideo cboxElement" href="https://player.vimeo.com/video/128459000?autoplay=1"><img src="{{URL::asset('public/front/images/icons/about_us.png')}}" /></a></p>
@if(!empty($diagnosisDetail->ad_link)) 
  <?php $link = str_replace('watch?v=','embed/',$diagnosisDetail->ad_link); ?>
  <p style="margin-top:20px;"><a class="iframevideo cboxElement" href="{{$link}}?autoplay=1"><img src="{{URL::asset('public/front/images/icons/watch_ad.png')}}" /></a></p>
@endif
</div>

@if(!empty($diagnosisBlock))

  @foreach($diagnosisBlock as $diaBlock)

    <div class="col-md-12 content">

    <div class="productTitleBlock">

    
      <h3>{{$diaBlock->title}}</h3>
		<div class="hrule clearfix"></div>
      <p><?php echo $diaBlock->description; ?></p>

    </div>

    </div>

  @endforeach

@endif

@if($diagnosisDetail->diet_chart_visible==1 && $diagnosisDetail->diet_chart!='')

<div class="col-md-12 content">

<div class="productTitleBlock">

<p><a href="{{URL::asset('public/uploads/diagnosis/'.$diagnosisDetail->id.'/'.$diagnosisDetail->diet_chart)}}" download class="btn btn-default btn-sm orderBtn customGradientBtn white">Download Diet Chart</a></p>

</div>

</div>

@endif

@if(isset($testimonials) && count($testimonials)>1)

<div class="col-md-12 content" id="testimonials">
<h3>Testimonials</h3>

<div id="diagnosis-testimonials" class="owl-carousel">
  @foreach($testimonials as $testi)
    <div class="item">
    @if($testi->type==1)
      <p><?php echo ucfirst($testi->testi_content); ?></p>
      @if(!empty($testi->testi_from))<p>- {{$testi->testi_from}}</p>@endif
    @elseif($testi->type==3)
      <img src="{{URL::asset('public/uploads/testimonials/'.$testi->testi_content)}}" class="img-responsive" />
    @endif
    </div>
  @endforeach
</div>

</div>

@endif

<!--  <div class="col-md-12" id="diagnosisDetail">

    <div class="col-md-6 detailGrid">

      <h4>How is it caused?</h4>

      <p>The stomach releases acid to help digest the food that we eat. An increase in acid secretion, causes acidity. The reasons for increased
         acid production may be many, few of which are: Not eating on time, Very long breaks between meals, Stress, Late nights, Skipping breakfast, Eating spicy/ Oily foods,
         Eating fermented foods, Eating very heavy meals etc.</p>

    </div>

     <div class="col-md-6 detailGrid">

      <h4>How to heal naturally?</h4>

      <p>The stomach releases acid to help digest the food that we eat. An increase in acid secretion, causes acidity. The reasons for increased
         acid production may be many, few of which are: Not eating on time, Very long breaks between meals, Stress, Late nights, Skipping breakfast, Eating spicy/ Oily foods,
         Eating fermented foods, Eating very heavy meals etc.</p>

    </div>


  </div>

   <div class="col-md-12">

      <h4>Medication</h4>

      <div>

            <div class="col-md-3 col-md-offset-3">

            <div class="diagnosisKitGrid">
                  <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
                  <h5>ACIDIM (1 Sachet)</h5>
            </div>

            </div>

            <div class="col-md-3">

            <div class="diagnosisKitGrid">
                  <img src="{{URL::asset('public/front/images/gc.png')}}" style="width:135px;">
                  <h5>GC (1 Sachet)</h5>
            </div>

            </div>

      </div>

      <p style="clear:both;">twice daily after meals for 6 months Benefits will be visible within 1 month</p>

  </div>

  <hr style="height:100%; border:2px solid #231F20;">


    <div class="col-md-12">

      <h4>How it works?</h4>

      <p>VINIDIA works on the urinary tract and helps to reduce the filtration
pressure on kidneys. It has nephroprotective properties. GC and ACIDIM
help to correct metabolism and thus reduce the formation of waste
products produced in the body. Together VINIDIA, GC and ACIDIM have a
dissolving effect on kidney stones which slowly get eliminated from the
body. Simultaneously, these herbals do not allow the body’s tendency
to form Stones in future.</p>

    </div> -->

</div>

<?php
  $count = 0;
  $offset = 0;
  if($diagnosisDetail->no_side_effect==1) {
    $count++;
  }
  if($diagnosisDetail->no_added_steroids==1) {
    $count++;
  }
  if($diagnosisDetail->fda_approved==1) {
    $count++;
  }
  if($diagnosisDetail->worldwide_shipping==1) {
    $count++;
  }
  if($diagnosisDetail->research_based==1) {
    $count++;
  }
  if($diagnosisDetail->no_dietary_restrictions==1) {
    $count++;
  }

  if($count==1) {
    $offset = 5;
  } else if($count==2) {
    $offset = 4;
  } else if($count==3) {
    $offset = 3;
  } else if($count==4) {
    $offset = 2;
  } else if($count==5) {
    $offset = 1;
  }
?>

@if($count>0)
<div class="row" id="tagline">
      <div class="col-md-12">
        @if($diagnosisDetail->no_side_effect==1)
          <div class="col-md-2 col-md-offset-{{$offset}}">
            <img src="{{URL::asset('public/front/images/icons/leaf.png')}}" />
            <h5 class="size15">NO SIDE<br/>EFFECTS</h5>
          </div>
          <?php $offset = 0; ?>
        @endif
        @if($diagnosisDetail->no_added_steroids==1)
        <div class="col-md-2 col-md-offset-{{$offset}}">
          <img src="{{URL::asset('public/front/images/icons/no_added_steroids.png')}}" />
          <h5 class="size15">NO ADDED<br/>STEROIDS</h5>
          <?php $offset = 0; ?>
        </div>
        @endif
        @if($diagnosisDetail->no_dietary_restrictions==1)
        <div class="col-md-2 col-md-offset-{{$offset}}">
          <img src="{{URL::asset('public/front/images/icons/diet.png')}}" />
          <h5 class="size15">NO DIETARY<br/>RESTRICTIONS</h5>
          <?php $offset = 0; ?>
        </div>
        @endif
        @if($diagnosisDetail->fda_approved==1)
        <div class="col-md-2 col-md-offset-{{$offset}}">
          <img src="{{URL::asset('public/front/images/icons/fda.png')}}" />
          <h5 class="size15">FDA<br/>APPROVED</h5>
          <?php $offset = 0; ?>
        </div>
        @endif
        @if($diagnosisDetail->research_based==1)
        <div class="col-md-2 col-md-offset-{{$offset}}">
          <img src="{{URL::asset('public/front/images/icons/research_based.png')}}" />
          <h5 class="size15">RESEARCH<br/>BASED</h5>
          <?php $offset = 0; ?>
        </div>
        @endif
        @if($diagnosisDetail->worldwide_shipping==1)
        <div class="col-md-2 col-md-offset-{{$offset}}">
          <img src="{{URL::asset('public/front/images/icons/world_wide_shipping.png')}}" />
          <h5 class="size15">WORLDWIDE<br/>SHIPPING</h5>
          <?php $offset = 0; ?>
        </div>
        @endif
        
      </div>
</div>
@endif

@if(isset($diagnosisPrice) && count($diagnosisPrice)>0 && isset($diagnosisCourse) && count($diagnosisCourse)>0)

<hr style="height:100%; border:1px solid #ccc;">


<div class="row"  id="deliveryDetail">
  <div class="col-md-12">

    <div class="col-md-8">
    <h5>DELIVERED TO YOUR DOORSTEP</h5>
    <p>All shipments are couriered to your address.

You will be provided a tracking number

to track your shipments online</p>
<!-- <p><strong>Note:</strong><span> Full Treatment is for 6 months. However, you may choose to purchase the treatment in parts as per your convenience</span></p>
 -->    </div>
	@if($diagnosisDetail->note)
		<div class="col-md-8">
			<strong>Note :</strong>
			<span>{{$diagnosisDetail->note}}</span>
		</div>
	@endif
    <div class="col-md-4" id="priceSection">

      <div class="col-md-12">
      <div class="col-md-10 col-md-offset-2">
	  <!--<p class="call_btncircle"><i class="fa fa-phone"}></i></p>-->
	  <a href="#contactUsForm" class="text-right call_btncircle inline2"><span><strong>CONTACT US</strong></span><img src="{{URL::asset('public/front/images/blue-30.png')}}" /></a>
	 <br/> </div>
      

      </div>

    <!--   <div class="col-md-3">

        <a href="#"><img src="{{URL::asset('public/front/images/icons/order-now.png')}}" /></a>

      </div> -->


    </div>



  </div>
</div>

<div class="row">


  <div class="col-md-12 centralize">

<h5>PRICE : {{$symbol}} {{$displayPrice}} For {{$displayCourse}}</h5>


       <p>Shipping charges {{$symbol}} {{round($shippingCharge->shipping_charge)}}</p>

       @if($shippingCharge->minimum_amount>0 && $shippingCharge->minimum_amount!=0.00)
        <p><i>Free shipping for order amount above {{$symbol}} {{round($shippingCharge->minimum_amount)}}</i></p>
       @endif

    <p><!--<a href="#contactUsForm" class="inline2 btn btn-default btn-sm orderBtn customGradientBtn white order-color">Contact Us</a>-->
	   <a href="javascript:void(0)" id="orderMenu" class="btn btn-default btn-sm orderBtn customGradientBtn white order-color">ORDER NOW</a></p>
	
    <div class="orderMenu" style="display:none;">
      <p class="size15">ORDER MEDICINES FOR</p>
      @if(isset($diagnosisCourse) && !empty($diagnosisCourse))
      @foreach($diagnosisCourse as $proCourse)
      @if($diagnosisProductType->product_type==1)
      <p style="margin:0;"><a href="{{URL::to('add-to-cart')}}?diagnosis_id={{$diagnosisDetail->id}}&diagnosis_name={{$diagnosisDetail->name}}&product_type=1&product_course={{$proCourse->fkcourse_id}}&product_id={{$diagnosisProductType->fkproduct_id}}&product_price={{$singlePrice}}&product_qty={{$proCourse->quantity}}" class="btn btn-default btn-sm menuBtn customGradientBtn  white order-color">{{strtoupper($proCourse->course_name)}}</a></p>
      @elseif($diagnosisProductType->product_type==2)
      <p style="margin:0;"><a href="{{URL::to('add-to-cart')}}?diagnosis_id={{$diagnosisDetail->id}}&diagnosis_name={{$diagnosisDetail->name}}&product_type=2&product_course={{$proCourse->fkcourse_id}}&product_id={{$diagnosisProductType->fkproduct_id}}&product_price={{$singlePrice}}&product_qty={{$proCourse->rate_multiply}}" class="btn btn-default btn-sm menuBtn customGradientBtn  white order-color">{{strtoupper($proCourse->course_name)}}</a></p>
      @endif
      @endforeach
      @endif
    </div>
	<div style='display:none'>
		<div id='contactUsForm' style='padding:29px; background:#fff;'>
			<div class=" col-md-offset-2 col-md-8" id="contactForm">

				  <form id="contact-form" class="form-horizontal customForm" method="post" action="{{URL::to('/contact')}}">
					<h3 class="center">Contact Us</h3>
					<div class="form-group">

					  <div class="col-md-12"> 
						<input type="hidden" name="diagnosis_id" value="{{$diagnosisDetail->id}}"/>
						<input type="hidden" name="diagnosis_name" value="{{$diagnosisDetail->name}}"/>
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
  </div>


</div>


@endif


</div>

<div id="fb-root"></div>



@endsection

@section('customJs')
<script src="{{URL::asset('public/front/js/owl.carousel.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
	  
	   $('#contact-form').validate();

     $(".iframevideo").colorbox({iframe:true, innerWidth:840, innerHeight:450});
	  
	  $(".inline").colorbox({inline:true, width:"85%",close: "close"});
	  $(".inline2").colorbox({inline:true, width:"85%",close: "close"});

      $('#orderMenu').click(function(){
        $('.orderMenu').slideToggle();
      });
      $('#orderMenu2').click(function(){
        $('.orderMenu').slideDown();
      });

      $('.owl-carousel').owlCarousel({
          loop:true,
          margin:10,
          nav:true,
          autoHeight:true,
          autoplay:false,
          responsive:{
              0:{
                  items:1
              }/*,
              600:{
                  items:3
              },
              1000:{
                  items:5
              }*/
          }
      });
	  $('.owl-prev').html("<img src={{URL::to('public/front/images/up.png')}} />" );
	 // $('.owl-prev').html("<i class="fa fa-chevron-right"></i>" );
	  $('.owl-next').html("<img src={{URL::to('public/front/images/down.png')}} />" );

  });
</script>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
@endsection
