@extends('front.layout.tpl')
@section('customCss')

<style type="text/css">
  table thead {
    border:1px solid #6fc677;
  }

  table th,table td {
    padding: 15px !important;
  }

  table th {
    padding-left: 20px;
    border-bottom: 0px solid !important;
  }

  table td {
    border-top: 0px solid !important;
  }

  table tbody {
    border:1px solid #ccc;
  }

  table input {
    border:none; 
    width:10%; 
    margin-left:10px;
  }

  .qty .glyphicon {
    font-size: 8px;
  }

 /* table td {
    text-align: center;
  }*/
</style>

@endsection

@section('content')

<div class="container pageStart" style="margin-top:60px;">

<div class="row">

  
  <div class="col-md-12">


          <table class="table table-responsive">

            <thead>
              <tr>
                <th style="width:40%;"><strong style="margin-left:30px;">Product</strong></th>
                <th class="centralize" style="width:20%; border-left:1px solid #ccc;">Price</th>
                <th class="centralize" style="width:20%; border-left:1px solid #ccc;">Quantity</th>
                <th class="centralize" style="width:20%; border-left:1px solid #ccc;">Total</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td style="width:20%; border-left:1px solid #ccc;">
                <div class="col-md-10 no-padding">
                <strong style="margin-left:30px;">Dencare</strong> 
                </div>
                <div class="col-md-2 no-padding">
                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </div>
                </td>
                <td class="centralize" style="width:20%; border-left:1px solid #ccc;">Rs. 120</td>
                <td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
                  <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field='quantity'></span>
                  <input type='text' name='quantity' value='2' class='qty' />
                  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field='quantity'></span> 
                </td>
                <td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">Rs. 240</td>
              </tr>
              <tr>
                <td style="width:20%; border-left:1px solid #ccc;">
                  <div class="col-md-10 no-padding">
                <strong style="margin-left:30px;">Dencare</strong> 
                </div>
                <div class="col-md-2 no-padding">
                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </div>
                </td>
                <td class="centralize" style="width:20%; border-left:1px solid #ccc;">Rs. 120</td>
                <td class="centralize qty" style="width:20%; border-left:1px solid #ccc;">
                  <span class="glyphicon glyphicon-minus qtyminus" aria-hidden="true" field='quantity'></span>
                  <input type='text' name='quantity' value='2' class='qty' />
                  <span class="glyphicon glyphicon-plus qtyplus" aria-hidden="true" field='quantity'></span> 
                </td>
                <td class="centralize" style="width:20%; border-left:1px solid #ccc; border-right:1px solid #ccc;">Rs. 240</td>
              </tr>
            </tbody>
            
          </table>



  </div>

  <div class="col-md-12" id="cartCalculation" style="margin-top:20px;">

      <div class="col-md-6">

          <div class="col-md-9">

              <p>CART SUBTOTAL</p>

          </div>

          <div class="col-md-3 pull-right">
            <p><strong>Rs. 580</strong></p>
          </div>

          <div class="col-md-9">

              <p>SHIPPING AND HANDLING (*Flat Rate)</p>

          </div>

          <div class="col-md-3 pull-right">
            <p><strong>Rs. 100</strong></p>
          </div>

          <div class="col-md-12" style="border-top:1px solid #ccc; margin-bottom:10px;">
          </div>

          <div class="col-md-9">

              <p><strong>ORDER TOTAL</strong></p>

          </div>

          <div class="col-md-3 pull-right">
            <p><strong>Rs. 680</strong></p>
          </div>


      </div>


      <div class="col-md-6 pull-right no-padding" style="text-align:right;">

          <div class="col-md-12 pull-right">
            <a href="{{URL::to('cart')}}" class="btn btn-default cart-btn"><img src="{{URL::to('public/front/images/icons/update.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>UPDATE CART</a>
          </div>

          <div class="col-md-12 pull-right" style="margin-top:10px;">
            <a href="{{URL::to('checkout')}}" class="btn btn-default cart-btn"><img src="{{URL::to('public/front/images/icons/arrow.png')}}" style="width:24px; height:24px; margin-right: 15px;"/>PROCEED TO CHECKOUT</a>
          </div>

      </div>


  </div>



</div>


</div>



@endsection

@section('customJs')
<script type="text/javascript">
  $(document).ready(function(){

    $('#login-form').validate();

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
            $('input[name='+fieldName+']').val(0);
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
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });

  });
</script>
@endsection
