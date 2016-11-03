@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
  #searchContainer {
    margin-top: 0px !important;
  }
</style>


@endsection

@section('content')

<div class="container pageStart" style="margin-top:130px;" id="productListing">

<div class="row centralize">

  <div class="col-md-12">

    
      <h2>ACIDITY</h2>

      <p>Heartburn, Regurgitation, Indigestion, Sour burps, Sore throat, Nausea, Chest pain, Dry persistent cough, Mouth Ulcers, Difficulty or Pain in swallowing are symptoms of acidity. Acidity can happen to any person despite following a healthy lifestyle. Many a times, a person may suffer from acidity, but it may not show in the form of the above symptoms. It may be reflected in the form of recurrent Headaches, Migraines, Muscle and Joint aches.</p>

  </div>

  <div class="col-md-12" id="diagnosisDetail">

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

    </div>

</div>

<div class="row" id="tagline">
      <div class="col-md-12">
        <div class="col-md-4">
          <img src="{{URL::asset('public/front/images/icons/leaf.png')}}" />
          <h5>NO SIDE EFFECTS</h5>
          <p>GC & ACIDIM are completely herbal in nature and thus have zero side effects.</p>
        </div>
        <div class="col-md-4">
          <img src="{{URL::asset('public/front/images/icons/fda.png')}}" />
          <h5>FDA APPROVED</h5>
          <p>All of Grocare’s products are licensed by the FDA and promise the finest in healthcare</p>
        </div>
        <div class="col-md-4">
          <img src="{{URL::asset('public/front/images/icons/diet.png')}}" />
          <h5>NO DIETARY RESTRICTIONS</h5>
          <p>With Grocare’s products you have the freedom to eat anything, anytime.</p>
        </div>
      </div>
</div>

<hr style="height:100%; border:1px solid #ccc;">


<div class="row"  id="deliveryDetail">
  <div class="col-md-12">

    <div class="col-md-8">
    <h5>DELIVERED TO YOUR DOORSTEP</h5>
    <p>All shipments are couriered to your address.

You will be provided a tracking number

to track your shipments online</p><br/>
<p><strong>Note:</strong><span> Full Treatment is for 6 months. However, you may choose to purchase the treatment in parts as per your convenience</span></p>
    </div>

    <div class="col-md-4" id="priceSection">

      <div class="col-md-9">

      <h5>PRICE : RS. 1,480 (1.5 months)</h5>

      <p>Shipping charges Rs.100</p>

      </div>

      <div class="col-md-3">

        <a href="#"><img src="{{URL::asset('public/front/images/icons/order-now.png')}}" /></a>

      </div>


    </div>



  </div>
</div>


</div>




@endsection

@section('customJs')


@endsection
