@extends('front.layout.tpl')

@section('customCss')

<style>

.clear{

	clear:both;

}

</style>



@endsection



@section('content')



<div class="container pageStart" style="margin-top:130px;">



<div class="row" id="contactAddress">



  <div class="col-md-12">




    <div class="col-md-3">

      <div class="col-md-2 image-icons">
		<i class="fa fa-map-marker fa_fax"></i>
        <!--<img src="{{URL::asset('public/front/images/icons/location.png')}}"/>-->
      </div>

      <div class="col-md-10">
        <h4><?php if($cms_eval->title != ""){ echo trim($cms_eval->title, '{}'); } ?></h4>
		<div class="clear"></div>
        <h4><?php if($cms_eval->address1 != ""){ echo trim($cms_eval->address1, '{}'); } ?></h4>
		<div class="clear"></div>
        <h4><?php if($cms_eval->address2 != ""){ echo trim($cms_eval->address2, '{}'); } ?></h4>
		<div class="clear"></div>
        <p><?php if($cms_eval->city != ""){ echo trim($cms_eval->city, '{}'); } ?> - <?php if($cms_eval->pincode != ""){ echo trim($cms_eval->pincode, '{}'); } ?><br/><?php if($cms_eval->state != ""){ echo trim($cms_eval->state, '{}'); } ?>, <?php if($cms_eval->country != ""){ echo trim($cms_eval->country, '{}'); } ?></p>
      </div>

    </div>



    <div class="col-md-3">

<div class="row">

      <div class="col-md-2 image-icons">
		<i class="fa fa-phone fa_fax"></i>
        <!--<img src="{{URL::asset('public/front/images/icons/phone.png')}}" />-->
      </div>

      <div class="col-md-10">
        <h4><strong>Helpline</strong></h4>
<p><?php if($cms_eval->helplineno != ""){ echo trim($cms_eval->helplineno, '{}'); } ?></p>
        <p>Chat with us on Whatsapp!</p>
      </div>

</div>




    </div>



<div class="col-md-3">

      <div class="col-md-2 image-icons">
		<i class="fa fa-envelope fa_fax"></i>
      </div>

      <div class="col-md-10">
        <h4><strong>Email</strong></h4>
<p>send us your medical reports on <a href="mailto:info@grocare.com">info@grocare.com</a></p>
      </div>

    </div>



    <div class="col-md-3">



      <div class="col-md-12" id="social-icons">

        <h4><strong>Social</strong></h4>

		<ul class="list-unstyled list-inline">

				<li><a href="<?php if($cms_eval->facebooklink != ""){ echo trim($cms_eval->facebooklink, '{}'); } ?>" target="_blank"><i class="fa fa-facebook f_1"></i></a></li>

                <li><a href="<?php if($cms_eval->twitterlink != ""){ echo trim($cms_eval->twitterlink, '{}'); } ?>" target="_blank"><i class="fa fa-twitter f_1"></i></a></li>

                <li><a href="<?php if($cms_eval->googlepluslink != ""){ echo trim($cms_eval->googlepluslink, '{}'); } ?>" target="_blank"><i class="fa fa-google-plus f_1"></i></a></li>

                 <li><a href="<?php if($cms_eval->youtubelink != ""){ echo trim($cms_eval->youtubelink, '{}'); } ?>" target="_blank"><i class="fa fa-youtube f_1"></i></a></li>

				</ul>

      </div>



    </div>







  </div>

  

</div>





<div class="row" id="contactDistributor">



  <div class="col-md-12">



    <h1 class="titleh3">Be Our Distributor</h1>



    <div class="col-md-4">



        <p><img src="{{URL::asset('public/front/images/icons/logo-white.png')}}" />No Licenses Required</p>



    </div>





    <div class="col-md-3">



        <p><img src="{{URL::asset('public/front/images/icons/logo-white.png')}}" />Zero side effects</p>



    </div>





    <div class="col-md-5">



        <p><img src="{{URL::asset('public/front/images/icons/logo-white.png')}}" />All of these amazing things, and such nominal costs</p>



    </div>





      





  </div>



   <div class="col-md-12">



      <p><a href="{{URL::to('/distribute')}}" class="btn btn-default btn-sm moreBtn">FIND OUT MORE</a></p>



   </div>



</div>





<div class="row" id="contactFormLocation">





  <div class="col-md-12 no-padding">

  

	<div class="col-md-12 no-padding">

	@if(Session::has('success_msg'))

	<div class="alert alert-success alert-dismissible" role="alert">

		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

		{{Session::get('success_msg')}}

	</div>

	@endif





	@if(Session::has('error_msg'))

	<div class="alert alert-danger alert-dismissible" role="alert">

		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

		{{Session::get('error_msg')}}

	</div>

	@endif





    <div class=" col-md-offset-2 col-md-8" id="contactForm">



      <form id="contact-form" class="form-horizontal customForm" method="post">



        <div class="form-group">



          <div class="col-md-12"> 



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





<!--    <div class="col-md-6" id="contactLocation">





    </div>-->





  </div>

  </div>



</div>





</div>







@endsection



@section('customJs')

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>

<script type="text/javascript">



jQuery.validator.addMethod("alphanumeric", function(value, element) {

    return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);

}, "Letters only please");



     $(document).ready(function(){



          $('#contact-form').validate({

		 rules: {

			name: {

				alphanumeric: true

			}

		},

	});



          var map;



        var mapOptions = {

          zoom: 16,

          center: new google.maps.LatLng(18.4869164,73.8640425),

          mapTypeId: google.maps.MapTypeId.ROADMAP

        };

        map = new google.maps.Map(document.getElementById('contactLocation'), mapOptions);



        var infoWindow = new google.maps.InfoWindow();

        var marker;





                      //Plot the location as a marker

                      var pos = new google.maps.LatLng(18.4869164,73.8640425); 

                      marker  = new google.maps.Marker({

                          position: pos,

                          map: map,

                          title: "Grocare India",

                      });



              google.maps.event.addListener(marker, "click", (function(marker) {

                return function() {

                  infoWindow.setContent(

                    "<div>" + 

                      "<div class="titleh3">Grocare India</div>" +

                      "<p>Market Yard,<br/>Pune - 411037<br/>Maharastra, India.</p>"+

                    "</div>"

                  );

                  infoWindow.open(map, marker);

                }    

              })(marker));





       

    });

</script>

@endsection

