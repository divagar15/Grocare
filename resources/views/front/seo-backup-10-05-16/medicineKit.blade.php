@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
  #searchContainer {
    margin-top: 0px !important;
  }
</style>


@endsection

@section('content')

<div class="container pageStart" style="margin-top:30px;" id="productListing">

<div class="row">

  <div class="col-md-12" id="productTab">

    <div class="col-md-2 col-md-offset-4">

      <div class="inactive" id="products">
      <a href="{{URL::to('products')}}">
       <img src="{{URL::asset('public/front/images/icons/product.png')}}">
       <h3>PRODUCTS</h3>
       </a>
      </div>

    </div>

    <div class="col-md-2">

      <div class="active" id="medical-kit">
        <a href="{{URL::to('medicine-kit')}}">
        <img src="{{URL::asset('public/front/images/icons/medical-kit-active.png')}}">
        <h3>MEDICINE KIT</h3>
        </a>
      </div>

    </div>


  </div>

</div>

  <div class="row" id="productsList">

    <div class="col-md-12 no-padding">


      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

      <div class="col-md-3">

          <div class="productGrid">
            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">
            <h3>ACIDIM</h3>
            <p>manage the ph of the entire body impeccably</p><br/>
            <p class="centralize"><a href="{{URL::to('self-diagnosis/acidim')}}">Read more about this</a></p>
          </div>

      </div>

    </div>

  </div>

</div>




@endsection

@section('customJs')


@endsection
