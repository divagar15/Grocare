@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Regions</h1></div>



<div class="row">
            
              <div class="col-md-12">
                  <div class="panel panel-default">
<!--                         <div class="panel-heading"></div>
 -->                        <div class="panel-body">

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

                            <?php $i=1; ?>
                          
                            <form id="region-form" class="form-horizontal" role="form" method="post">

                                  <table class="table table-striped no-margn" style="margin-bottom:20px;">
                                      <thead>
                                        <tr>
                                          <th width="15%">Region Name</th>
                                          <th width="40%">Countries</th>
                                          <th width="20%">Currency</th>
                                          <th width="15%">Shipping Charge</th>
                                          <th width="15%">Minimum Order Amount</th>
                                          <th width="10%">Action</th>
                                        </tr>
                                      </thead>

                                      <tbody id="regionDetails">

                                        @if(isset($regions) && !empty($regions))

                                          @foreach($regions as $region)

                                            <?php
                                              $selectedCountries = explode(',', $region->countries);
                                            ?>

                                            <tr>
                                              <td>
                                                <input type="hidden" name="rid_{{$i}}" value="{{$region->id}}" />
                                                <input type="text" value="{{$region->region}}" class="form-control" required name="region_{{$i}}" id="region_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td>
                                                <select name="countries{{$i}}[]" class="chosen-select" required multiple id="countries_{{$i}}">
                                                    <option value=""></option>
                                                    @foreach($countries as $key => $value)
                                                      <option value="{{$key}}" @if(in_array($key,$selectedCountries)) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                              </td>
                                              <td>
                                                <select name="currencies_{{$i}}" class="form-control" required id="currencies_{{$i}}">                                                    
                                                    @foreach($enabledCurrencies as $key => $value)
                                                      <option value="{{$value}}" @if($value==$region->currency) selected @endif>{{$currencies[$value]}}</option>
                                                    @endforeach
                                                </select>
                                              </td>
                                               <td>
                                                <input type="text" value="{{$region->shipping_charge}}" class="form-control right-align" number="true" name="shipping_charge_{{$i}}" id="shipping_charge_{{$i}}" autocomplete="off" />
                                              </td>
                                               <td>
                                                <input type="text" value="{{$region->minimum_amount}}" class="form-control right-align" number="true" name="minimum_amount_{{$i}}" id="minimum_amount_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td><a href="javascript:void(0)" data-type="normal" data-id="{{$region->id}}" class="removeRegion btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
                                            </tr>

                                            <?php $i++; ?>

                                          @endforeach

                                        @endif

                                      </tbody>

                                      <tfoot>
                                        <tr>
                                          <td colspan="4">
                                            <button type="button" class="btn btn-xs btn-info" id="addRegion">
                                              <i class="fa fa-plus"></i> Add Region
                                            </button>
                                          </td>
                                        </tr>
                                      </tfoot>                                     
                                  
                                  </table>

                                  <?php $i = $i-1; ?>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <input type="hidden" name="counter" id="counter" value="{{$i}}">
                                      <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                      <a href="{{URL::to('admin/configuration/regions')}}" class="btn btn-default btn-sm">Cancel</a>
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

            $('#region-form').validate();

            var countries = '<option value=""></option>';
            @foreach($countries as $key => $value)
              countries += '<option value="{{$key}}">{{$value}}</option>';
            @endforeach

            var currencies = '<option value="">Select Currencies</option>';
            @foreach($enabledCurrencies as $key => $value)
              currencies += '<option value="{{$value}}">{{$currencies[$value]}}</option>';
            @endforeach


            $("#addRegion").on("click", function () {
              var counter = Number($('#counter').val());
              counter = counter+1;

              var html = '<tr id="row_'+counter+'">';

              html += '<td><input type="hidden" name="rid_'+counter+'" value="0" /><input type="text" class="form-control" required name="region_'+counter+'" id="region_'+counter+'" autocomplete="off" /></td>';
              html += '<td><select name="countries'+counter+'[]" class="chosen-select" required multiple id="countries_'+counter+'">'+countries+'</select></td>';
              html += '<td><select name="currencies_'+counter+'" class="form-control" required id="currencies_'+counter+'">'+currencies+'</select></td>';
              html += '<td><input type="text" class="form-control right-align" number="true" name="shipping_charge_'+counter+'" id="shipping_charge_'+counter+'" autocomplete="off" /></td>';
              html += '<td><input type="text" class="form-control right-align" number="true" name="minimum_amount_'+counter+'" id="minimum_amount_'+counter+'" autocomplete="off" /></td>';
              html += '<td><a href="javascript:void(0)" data-type="dynamic" data-id="'+counter+'" class="removeRegion btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>';

              html += '</tr>';

              $('#regionDetails').append(html);
              $("#counter").val(counter);

              $("#countries_"+counter).chosen();

              //counter++;

              

            });

            $(document).on('click',".removeRegion",function (){   
                var type = $(this).data('type');
                var id   = $(this).data('id');
                if(type=='dynamic') { 
                  $("#row_"+id).remove();
                } else {
                  var confirmMsg = confirm('Are you sure want to remove this region?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/configuration/delete-region')}}/"+id;
                  }
                }
            });

        });
    </script>

@endsection