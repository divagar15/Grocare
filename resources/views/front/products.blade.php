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



<div class="row">



  <div class="col-md-12" id="productTab">



    <div class="col-md-2 col-md-offset-4">



      <div class="active" id="products">

       <a href="{{URL::to('products')}}">

       <img src="{{URL::asset('public/front/images/icons/product-active.png')}}">

       <h1 class="titleh3">PRODUCTS</h1>

       </a>

      </div>



    </div>



    <div class="col-md-2">



      <div class="inactive" id="medical-kit">

       <a href="{{URL::to('medicine-kit')}}">

        <img src="{{URL::asset('public/front/images/icons/medical-kit.png')}}">

        <div class="titleh3">MEDICINE KIT</div> 

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

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



      <div class="col-md-3">



          <div class="productGrid">

            <img src="{{URL::asset('public/front/images/acidim.jpg')}}" style="width:200px;">

            <div class="titleh3">ACIDIM</div>

            <p>manage the ph of the entire body impeccably</p><br/>

            <p class="centralize"><a href="{{URL::to('products/acidim')}}">Read more about this</a></p>

          </div>



      </div>



    </div>



  </div>



</div>









@endsection



@section('customJs')





@endsection

