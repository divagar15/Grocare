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


              <form class="form-horizontal group-border-dashed" method="post" id="payment-form" action="{{$actionURL}}">

                <input name="channel" type="hidden" value="{{$channel}}"/>
                <input name="account_id" type="hidden" value="{{$accountId}}"/>
                <input name="secretkey" type="hidden" value="{{$secretKey}}"/>
                <input name="reference_no" type="hidden" value="{{Session::get('payment_id')}}"/>
                <input name="amount" type="hidden" value="{{Session::get('payment_amount')}}"/>
                <input name="currency" type="hidden" value="{{$currency}}"/>
                <input name="description" type="hidden" value="Payment Online"/>
                <input name="return_url" type="hidden" value="{{$responeURL}}"/>
                <input name="mode" type="hidden" value="{{$mode}}"/>
                <input name="name" type="hidden" value="{{Session::get('payment_name')}}"/>
                <input name="address" type="hidden" value="{{Session::get('payment_address')}}"/>
                <input name="city" type="hidden" value="{{Session::get('payment_city')}}"/>
                <input name="state" type="hidden" value="{{Session::get('payment_state')}}"/>
                <input name="postal_code" type="hidden" value="{{Session::get('payment_zipcode')}}"/>
                <input name="country" type="hidden" value="{{Session::get('payment_country')}}"/>
                <input name="email" type="hidden" value="{{Session::get('payment_email')}}"/>
                <input name="phone" type="hidden" value="{{Session::get('payment_phone')}}"/>
                <input type="hidden" value="{{$secureHash}}" name="secure_hash"/>

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
