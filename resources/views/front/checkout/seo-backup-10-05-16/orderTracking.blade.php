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

<div class="container pageStart" style="margin-top:20px;">

<div class="row">

<div class="col-md-12">

<iframe src="https://grocare.aftership.com/{{$trackingNumber}}" width="100%" height="900px"></iframe>

</div>


</div>


</div>



@endsection

@section('customJs')

@endsection
