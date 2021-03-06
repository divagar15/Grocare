@extends('front.layout.tpl')
@section('customCss')

<link rel="stylesheet" type="text/css" href="{{URL::asset('public/front/js/jquery.gritter/css/jquery.gritter.css')}}" />

<style type="text/css">
  #searchContainer {
    margin-top: 0px !important;
  }
  .feature-image{
	  min-height:200px;
	  max-height:200px;
  }

  .productGrid {
    height: 460px;
  }

  .productGrid h3 {
    margin-top: 30px;
  }

  .cart input.qty {
    width: 15%;
    text-align: center;
  }

  .qtyminus, .qtyplus {
    font-size: 11px;
    cursor: pointer;
  }

  /*Gritter Notifications*/
#gritter-notice-wrapper {
  width: 320px;
  top: 45px;
  z-index: 999999 !important;
}
.gritter-item-wrapper {
  background: url(../js/jquery.gritter/images/gritter-bg.png);
  box-shadow: 0 0px 5px rgba(0, 0, 0, 0.32);
}
.gritter-top {
  background: transparent;
}
.gritter-item {
  font-family: 'Open Sans', sans-serif;
  background: transparent;
  color: #FFF;
  padding: 2px 20px 12px;
  padding-right: 35px;
  padding-left: 10px;
}
.gritter-bottom {
  background: transparent;
}
.gritter-item p {
  font-size: 12px;
  line-height: 19px;
}
.gritter-title {
  text-shadow: none;
  font-weight: 300;
  font-size: 17px;
}
.gritter-close {
  display: block !important;
  top: 0;
  right: 0;
  left: auto;
  height: 30px;
  width: 35px;
  background: transparent;
  text-indent: inherit;
}
.gritter-close:after {
  content: 'x';
  position: absolute;
  color: #FFF;
  left: 10px;
  font-size: 24px;
  font-weight: bold;
  text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.12);
}
.gritter-without-image,
.gritter-with-image {
  padding-left: 7px;
  width: 224px;
}
.gritter-item-wrapper.clean {
  background: #FFF;
}
.gritter-item-wrapper.clean .gritter-item {
  color: #555;
}
.gritter-item-wrapper.clean .gritter-close {
  display: block !important;
  top: 0;
  right: 0;
  left: auto;
  height: 100%;
  width: 35px;
  border-left: 1px solid #258fec;
  border-top: 1px solid #52C0FF;
  background-image: -moz-linear-gradient(center top, #52aeff 45%, #2180d3 102%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#52aeff), to(#2180d3));
  /* Chrome, Safari 4+ */
  background-image: -webkit-linear-gradient(top, #52aeff, #2180d3);
  /* Chrome 10-25, iOS 5+, Safari 5.1+ */
  background-image: -o-linear-gradient(top, #52aeff, #2180d3);
  /* Opera 11.10-12.00 */
  background-image: linear-gradient(to bottom, #52aeff, #2180d3);
  background-color: #3290E2;
  text-indent: inherit;
}
.gritter-item-wrapper.clean .gritter-close:after {
  content: 'Ã—';
  position: absolute;
  color: #FFF;
  top: 50%;
  left: 10px;
  font-size: 24px;
  font-weight: bold;
  margin-top: -17px;
  text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.12);
}
.gritter-image {
  margin-top: 3px;
}

.gritter-item-wrapper.primary {
  background: #2494f2;
}
.gritter-item-wrapper.success {
  background: #60c060;
}
.gritter-item-wrapper.info {
  background: #5bc0de;
}
.gritter-item-wrapper.warning {
  background: #ff9900;
}
.gritter-item-wrapper.danger {
  background: #df4b33;
}
.gritter-item-wrapper.dark {
  background: #23262b;
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
       <h3>PRODUCTS</h3>
       </a>
      </div>

    </div>

    <div class="col-md-2">

      <div class="inactive" id="medical-kit">
       <a href="{{URL::to('medicine-kit')}}">
        <img src="{{URL::asset('public/front/images/icons/medical-kit.png')}}">
        <h3>MEDICINE KIT</h3> 
       </a>
      </div>

    </div>


  </div>

</div>

  <div class="row" id="productsList">

    <div class="col-md-12 no-padding">
	<?php //var_dump($productList);?>
      @if((isset($productList)) && (count($productList)>0))


      @foreach($productList as $list)

      <div class="col-md-3">

          <div class="productGrid">
            <a href="{{URL::to('products/'.$list->product_slug)}}">
				<div class="feature-image">
                <img src="{{URL::asset('public/uploads/products/'.$list->id.'/'.$list->feature_image)}}" class="img-responsive" style="padding-top:15px;">
				</div>
                <h3>{{ucwords($list->name)}}</h3>
                <div class="description">
                  <p>{{ucfirst(substr($list->short_description,0,100))}}</p>
                </div>
                <div class="price">
                  <p><strong>{{$symbol}}&nbsp;@if(!empty($list->sales_price) && $list->sales_price!=0.00){{round($list->sales_price)}}@else{{round($list->regular_price)}}@endif</strong></p>
                </div>
              </a>
              <?php
                if(!empty($list->sales_price) && $list->sales_price!=0.00) {
                  $price =  $list->sales_price;
                } else {
                  $price =  $list->regular_price;
                }
              ?>
            <div class="cart">
              <p class="centralize">
                <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field="quantity{{$list->id}}"></span>
                  <input type="text" name="quantity{{$list->id}}" id="quantity{{$list->id}}" value="1" class="qty" readonly>
                  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field="quantity{{$list->id}}"></span> 
              </p>
              <p class="centralize" style="padding-top:5px;">    
              <a href="javascript:void(0)" class="addToCart" data-name="{{ucwords($list->name)}}" data-id="{{$list->id}}" data-price="{{$price}}">ADD TO CART</a>            
<!--               <a href="{{URL::to('add-to-cart')}}?product_type=1&product_course=0&product_id={{$list->id}}&product_price={{$price}}&product_qty=1&ordered_from=store">ADD TO CART</a></p>
 -->            </div>
          </div>

      </div>

      @endforeach

      @else 

        <h5 style="text-align:center;">There are no products to display for your region </h5>

      @endif


    </div>

  </div>

</div>




@endsection

@section('customJs')

<script type="text/javascript" src="{{URL::asset('public/front/js/jquery.gritter/js/jquery.gritter.js')}}"></script>

<script type="text/javascript">

  $(document).ready(function(){


    $('.addToCart').click(function(){

      var product_id = $(this).data('id');
      var product_price = $(this).data('price');
      var name = $(this).data('name');
      var product_type = 1;
      var product_course = 0;
      var product_qty = $('#quantity'+product_id).val();
      var ordered_from = "store";


      $.ajax({
                        url: "{{URL::to('add-to-cart')}}",
                        method: 'POST',
                        data:{product_id:product_id,product_price:product_price,product_type:product_type,product_course:product_course,product_qty:product_qty,ordered_from:ordered_from,add_type:1},
                        success: function(response){
                            if(response!=2) {

                                $.gritter.add({
                                    position: 'bottom-right',
                                    title: name+' added to your cart',
                                    text: '',
                                    class_name: 'primary',
                                    time: '2000'
                                });

                                if(response.total>0) {
                                   // var cartTotal = response.qty+' item(s) - Rs.'+response.total;
                                   $('#cartOrderTotal').html(response.total);
                                } else {
                                   // var cartTotal = '0 item';
                                   $('#cartOrderTotal').html('0');
                                }

                                //$('#cart-total').html(cartTotal);

                            } else {
                                $.gritter.add({
                                    position: 'bottom-right',
                                    title: 'Please try again',
                                    text: '',
                                    class_name: 'danger',
                                    time: '2000'
                                });
                            }
                            
                        },
                        error: function (xhr, status, error) { 
                                                                
                        }
            });

    });


          // This button will increment the value
    $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(1);
        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            if((currentVal-1)==0) {
              $('input[name='+fieldName+']').val(1);
            } else {
            $('input[name='+fieldName+']').val(currentVal - 1);
            }
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(1);
        }
    });
  });
</script>

@endsection