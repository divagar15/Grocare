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


<div class="container-fluid">

<div class="container centralize" style="margin-top:130px;" id="product-detail">

<div class="row">

  <div class="col-md-12">

  <h2 class="centralize">{{ucwords($productDetail->name)}}</h2>

  </div>

  <div class="col-md-12" id="productTabDetail">

    <a href="{{URL::to('products/'.$productDetail->product_slug)}}" class="white order-color">Overview</a> <a href="{{URL::to('products/'.$productDetail->product_slug.'/contents')}}" style="margin-left:30px;" class="white order-color">Contents</a>

  </div>

</div>

</div>

</div>






    <div class="container-fluid">

    <div class="container">

      <div class="row">

          <div class="col-md-12">

            <?php echo $productDetail->key_ingredients; ?>

          </div>

      </div>

      </div>

      </div>



<div class="container-fluid">

<div class="container centralize" style="margin-top:50px; margin-bottom:50px;">

<div class="row">

  <div class="col-md-12">

    <?php
      if(!empty($productRegion->sales_price) && $productRegion->sales_price!=0.00) {
        $price =  $productRegion->sales_price;
      } else {
        $price =  $productRegion->regular_price;
      }
    ?>

    <p><a href="javascript:void(0)" id="orderMenu" class="btn btn-default btn-sm orderBtn customGradientBtn white order-color">ORDER NOW</a></p>
    <div class="orderMenu" style="display:none;">
      <p>ORDER MEDICINES FOR</p>
      @if(isset($productCourse) && !empty($productCourse))
      @foreach($productCourse as $proCourse)
      <p style="margin:0;"><a href="{{URL::to('add-to-cart')}}?product_type=1&product_course={{$proCourse->fkcourse_id}}&product_id={{$productDetail->id}}&product_price={{$price}}&product_qty={{$proCourse->quantity}}" class="btn btn-default btn-sm menuBtn customGradientBtn">{{strtoupper($proCourse->course_name)}}</a></p>
      @endforeach
      @endif
      <!-- <p style="margin:0;"><a href="javascript:void(0)" class="btn btn-default btn-sm menuBtn customGradientBtn">3 Months</a></p>
      <p style="margin:0;"><a href="javascript:void(0)" class="btn btn-default btn-sm menuBtn customGradientBtn">Full Treatment</a></p> -->
    </div>
  </div>

</div>

</div>


</div>

@endsection

@section('customJs')

<script type="text/javascript">
  $(document).ready(function(){

    $('#orderMenu').click(function(){
      $('.orderMenu').slideToggle();
    });

  });
</script>

@endsection
