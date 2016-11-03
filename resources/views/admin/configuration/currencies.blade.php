@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Currency Settings</h1></div>


<div class="row">
            
            	<div class="col-md-12">
                 	<div class="panel panel-default">
                        <div class="panel-heading">Enabled Currencies</div>
                        <div class="panel-body">

                        @if(Session::has('success_msg'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{Session::get('success_msg')}}
                        </div>
                        @endif


                        @if(Session::has('error_msg'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{Session::get('error_msg')}}
                        </div>
                        @endif
                        	
                            <form id="enabled-currency" class="form-horizontal" role="form" method="post">

                            	 <?php 
                            	 	$activeCurrency = array();
                            	 	if(!empty($enabledCurrency->currencies)) {
                            	 		$activeCurrency = explode(',', $enabledCurrency->currencies);
                            	 	}
                            	 	//var_dump($activeCurrency);
                            	 ?>
                            
                                  <div class="form-group">
                                    <label class="col-sm-2 control-label">Currencies</label>
                                    <div class="col-sm-7">
                                      <select name="enabled_currencies[]" id="enabled_currencies" class="chosen-select" multiple required>
                                      	<option value=""></option>
                                      	@if(isset($currencies) && !empty($currencies))
                                      		@foreach($currencies as $key=>$value)
                                      			<option value="{{$key}}" @if(!empty($activeCurrency) && in_array($key,$activeCurrency)) selected @endif>{{$value}}</option>
                                      		@endforeach
                                      	@endif	
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                  	<div class="col-sm-10 col-sm-offset-2">
                                  		<button type="submit" class="btn btn-primary btn-sm">Update</button>
                                  		<a href="{{URL::to('admin/configuration/currencies')}}" class="btn btn-default btn-sm">Cancel</a>
                                  	</div>
                                  </div>

                        	</form>
                        </div>
                    </div>
                 </div>
            
            </div>


<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
                        <div class="panel-heading">Currency Exchange Rates</div>
                        <div class="panel-body">

                          
                            <form id="currency-rate" class="form-horizontal" role="form" method="post" action="{{URL::to('admin/configuration/currency-rates')}}">

                                  <table class="table table-striped no-margn" style="margin-bottom:20px;">
                                      <thead>
                                        <tr>
                                          <th>Currency</th>
                                          <th>Exchange Rate</th>
                                          <th>Symbols</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $i=0; ?>
                                        @if(isset($currencyRates) && !empty($currencyRates))

                                          @foreach($currencyRates as $rates)

                                            <tr>
                                              <td>{{$currencies[$rates->from_currency]}}<input type="hidden" name="rateId_{{$i}}" value="{{$rates->id}}" /></td>
                                              <td>@if($rates->id==1)<p class="right-align">{{$rates->rate}}<input required number="true" autocomplete="off" class="form-control right-align" type="hidden" name="exchange_rate_{{$i}}" id="exchange_rate_{{$i}}" value="{{$rates->rate}}" /></p>@else<input required autocomplete="off" number="true" class="form-control right-align" type="text" name="exchange_rate_{{$i}}" id="exchange_rate_{{$i}}" value="{{$rates->rate}}" />@endif</td>
                                              <td><input type="text" class="form-control" required name="symbol_{{$i}}" id="symbol_{{$i}}" autocomplete="off" value="{{$rates->symbol}}" /></td>
                                            </tr>

                                            <?php $i++; ?>


                                          @endforeach

                                        @endif
                                      </tbody>
                                  </table>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <input type="hidden" name="counter" id="counter" value="{{$i}}">
                                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                      <a href="{{URL::to('admin/configuration/currencies')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div>

                          </form>
                        </div>
                    </div>
                 </div>
            
            </div>

@endsection

@section('customJs')

    <script type="text/javascript">
        $(document).ready(function(){
            $('#enabled-currency').validate();
            $('#currency-rate').validate();
        });
    </script>

@endsection