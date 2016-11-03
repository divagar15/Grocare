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

  .addressTable td, .addressTable th {
    padding: 5px !important;
    border:0px solid #ccc !important;
  }

  .addressTable tbody {
    border:0px solid #ccc !important;
  }

  table input {
    border:none; 
    width:12%; 
    text-align: center;
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

<div class="container pageStart" style="margin-top:10px;">

<div class="row">

<div class="col-md-12">


              <form class="form-horizontal group-border-dashed" method="post" id="payment-form" action="{{$paypalURL}}">

                <input type="hidden" name="business" value="{{$paypalId}}">
                <input type="hidden" name="cmd" value="_cart">
                <input type="hidden" name="upload" value="1"> 

                @foreach($orderProducts as $key=>$pro)

                <input type="hidden" name="item_name_{{$key+1}}" value="{{ucwords($pro->product_name)}}">
                <input type="hidden" name="quantity_{{$key+1}}" value="{{ucwords($pro->product_qty)}}">
                <input type="hidden" name="amount_{{$key+1}}" value="{{ucwords($pro->product_price)}}"> 
                @endforeach


                <input type="hidden" name="shipping_1" value="{{$orderDetail->shipping_charge}}">
<!--                 <input type="hidden" name="item_number" value="2">
              <input type="hidden" name="credits" value="510">   --> 
                <input type="hidden" name="userid" value="{{Session::get('payment_id')}}">
               <!--  <input type="hidden" name="amount" value="{{Session::get('payment_amount')}}"> -->
<!--                 <input type="hidden" name="cpp_header_image" value="http://www.phpgang.com/wp-content/uploads/gang.jpg">
               <input type="hidden" name="no_shipping" value="1"> --> 
                <input type="hidden" name="currency_code" value="{{$currency}}">
                <input type="hidden" name="handling" value="0">
                <input type="hidden" name="cancel_return" value="{{$paypalCancelURL}}">
                <input type="hidden" name="return" value="{{$paypalSuccessURL}}">

             </form>

             <div>

<!--                 <img src="{{URL::asset('public/front/images/loader.gif')}}" style="display: block; margin-left: auto; margin-right: auto;" />
 -->
                <h3 style="text-align:center;">Loading Payment Process. Please wait...</h3>

             </div>

</div>


</div>


</div>

@endsection

@section('customJs')
<script type="text/javascript">
  $(document).ready(function(){
    $('#payment-form').submit();
  });
</script>
@endsection
