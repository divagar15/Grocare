@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
  #searchContainer {
    margin-top: 0px !important;
  }
  footer {
    margin-top: 0px !important;
  }
</style>


@endsection

@section('content')

<div class="container-fluid" style="background:url({{URL::asset('public/front/images/hernica.png')}}) no-repeat; background-position: center;
    background-size: cover;">

<div class="container pageStart" style="margin-top:130px; margin-bottom:30px;" id="product-detail">

<div class="row">

  <div class="col-md-12">

  <h2 class="centralize">ACIDIM</h2>

  </div>

  <div class="col-md-12 centralize" id="productSpecs">

    <div class="col-md-4">

      <div class="specList">

            <h3>No Surgery Period</h3>

            <p>We care for you much more than you care for yourself. We show this by
              specifically crafting Hernica as per your needs. No one likes to get themselves cut
              open. With Hernica, we make sure you won’t ever have to.</p>

      </div>

    </div>


    <div class="col-md-4">

      <div class="specList">

            <h3>The Revolution in healthcare</h3>

            <p>For years, people have had the impression that Herbal medicines are good, but
              they work slowly. We wanted to turn this concept around. The result was a
              completely unbelievable herbal medicine that not only had higher efficiency, but
              also sustainability that exceeded our expectations.</p>

      </div>

    </div>


    <div class="col-md-4">

      <div class="specList">

            <h3>A Unique Mode of Action</h3>

            <p>Hernica is the only herbal formulation that is specifically designed to strengthen the intestines and abdominal walls.
              This helps heal the initial discomfort. The reduction in inflammation overtime helps the hernia retrieve 
              naturally.</p>

      </div>

    </div>

  </div>

  <div class="col-md-12 centralize" style="margin-top:30px;">

    <p><a href="#" class="btn btn-default btn-sm orderBtn">ORDER NOW</a></p>

  </div>

</div>

</div>


</div>

@endsection

@section('customJs')


@endsection
