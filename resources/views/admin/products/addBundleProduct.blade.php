@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Add Bundle Product</h1></div>



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

                          
                            <form id="product-form" class="form-horizontal" role="form" method="post">

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Type</label>
                                    <div class="col-sm-7">
                                      <select name="product_type" id="product_type" required disabled class="form-control">
                                        <option value="">Select Product Type</option>
                                        @if(isset($productType) && !empty($productType))
                                          @foreach($productType as $key=>$value)
                                            <option value="{{$key}}" @if($key==2) selected @endif>{{$value}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-7">
                                    <input type="hidden" name="product_id" id="product_id" />
                                    <input type="text" autocomplete="off" name="name" id="name" required class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Short Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="short_description" id="short_description" required class="form-control"></textarea>
                                    </div>
                                  </div>

                                <!--   <div class="form-group">
                                    <label class="col-sm-3 control-label">Did You Know?</label>
                                    <div class="col-sm-7">
                                      <textarea name="did_you_know" id="did_you_know" class="form-control"></textarea>
                                    </div>
                                  </div> -->


                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Inventory Settings</h4>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Stock Status</label>
                                    <div class="col-sm-7">
                                      <select name="stock_status" id="stock_status" required class="form-control">
                                        <option value="">Select Stock Status</option>
                                        @if(isset($stockStatus) && !empty($stockStatus))
                                          @foreach($stockStatus as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                      <label class="col-sm-3 control-label cr-styled">
                                          <input type="checkbox" name="set_inventory_limit" id="set_inventory_limit" value="1">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Set Inventory limit and stock available</p>
                                      </div>
                                  </div>

                                  <div class="form-group stockLimit" style="display:none;">
                                    <label class="col-sm-3 control-label">Available Stock Quantity</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" name="stock_available" id="stock_available" required number="true" class="form-control right-align" />
                                    </div>
                                  </div>

                                  <div class="form-group stockLimit" style="display:none;">
                                    <label class="col-sm-3 control-label">Stock Limit</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" name="stock_limit" id="stock_limit" required number="true" class="form-control right-align" />
                                    </div>
                                  </div>

<!--                                   <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Product Settings</h4>
                                    </div>
                                  </div>
                                 
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Products</label>
                                    <div class="col-sm-7">
                                      <select name="products[]" id="products" multiple  class="chosen-select" required onchange="addCourse();">
                                        <option value=""></option>
                                        @if(isset($simpleProducts) && !empty($simpleProducts))
                                          @foreach($simpleProducts as $prod)
                                            <option value="{{$prod->id}}">{{ucwords($prod->name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Courses</label>
                                    <div class="col-sm-7">
                                      <select name="courses[]" id="courses" multiple  class="chosen-select" required onchange="addCourse();">
                                        <option value=""></option>
                                        @if(isset($courses) && !empty($courses))
                                          @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{ucwords($course->course_name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>


                                  <div id="courseDetails">


                                  </div>
 -->

                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Currency Settings</h4>
									   
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Base Currency</span></label>
                                    <div class="col-sm-7">
                                      <select name="product_base_currency" id="product_base_currency" onchange="calculatePerItemPrice();" required class="form-control">
                                        @if(isset($enabledCurrencies) && !empty($enabledCurrencies))
                                          @foreach($enabledCurrencies as $key=>$value)
                                            <option value="{{$value}}" @if($value==$enabledCurrency->base_currency) selected @endif>{{$currencies[$value]}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

<!--                                   <div class="form-group">
                                    <label class="col-sm-3 control-label">Regular Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" class="regularprice form-control right-align" onkeyup="calculatePerItemPrice();" name="regular_price" id="regular_price" required number="true" />
                                    </div>
                                  </div>

                                   <div class="form-group">
                                    <label class="col-sm-3 control-label">Sale Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" class="saleprice form-control right-align" onkeyup="calculatePerItemPrice();" name="sale_price" id="sale_price" required number="true" />
                                    </div>
                                  </div> -->

                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Region Settings</h4>
                                      <span>(Prices will be calculated for the first selected course & their product quantity)</span>
                                    </div>
                                  </div>

                                  @if(isset($regions) && !empty($regions))

                                    @foreach($regions as $reg)


                                    <?php
                                      $regionProducts = App\Helper\AdminHelper::getRegionProducts($reg->id);
                                      //var_dump($regionProducts);
                                    ?>

                                    <div class="form-group">
                                      <label class="col-sm-3 control-label boldTxt">Region</label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;"><strong class="boldTxt">{{ucwords($reg->region)}}</strong></p>
                                        <input type="hidden" name="region[]" value="{{$reg->id}}">
                                      </div>
                                    </div>

                                    <div class="form-group">
                                      <label class="col-sm-3 control-label cr-styled">
                                          <input type="checkbox" class="enable_region" name="enable_region_{{$reg->id}}" id="{{$reg->id}}" value="{{$reg->id}}">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Product unavailable for this region</p>
                                      </div>
                                    </div>

                                <div class="form-group currencyregion{{$reg->id}}">
                                    <label class="col-sm-3 control-label">Products</label>
                                    <div class="col-sm-7">
                                      <select name="products_{{$reg->id}}[]" id="products_{{$reg->id}}" multiple  class="chosen-select" required onchange="addCourse({{$reg->id}});">
                                        <option value=""></option>
                                        @if(isset($regionProducts) && !empty($regionProducts))
                                          @foreach($regionProducts as $prod)
                                            <option value="{{$prod->id}}">{{ucwords($prod->name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group currencyregion{{$reg->id}}">
                                    <label class="col-sm-3 control-label">Courses</label>
                                    <div class="col-sm-7">
                                      <select name="courses_{{$reg->id}}[]" id="courses_{{$reg->id}}" multiple  class="chosen-select" required onchange="addCourse({{$reg->id}});">
                                        <option value=""></option>
                                        @if(isset($courses) && !empty($courses))
                                          @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{ucwords($course->course_name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>




                                  <div id="courseQuantities{{$reg->id}}" class="currencyregion{{$reg->id}}">


                                  </div>

                                  <div class="form-group" id="currencyregion{{$reg->id}}">
                                    <label class="col-sm-3 control-label" style="margin-top:20px;">{{$reg->currency." (".$reg->symbol.")"}}</label>
                                    <div class="col-sm-4">
                                      <label class="control-label">Regular Price</label>
                                      <input type="hidden" name="currencies{{$reg->id}}[]" id="currencies{{$reg->id}}" value="{{$reg->currency}}">
                                      <input type="text" autocomplete="off" name="regularprice[]" id="regularprice{{$reg->id}}" class="regularprice{{$reg->currency}} form-control right-align" required number="true" readonly />
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="control-label">Sale Price</label>
                                      <input type="text" autocomplete="off" name="saleprice[]" id="saleprice{{$reg->id}}" class="saleprice{{$reg->currency}} form-control right-align" required number="true" readonly />
                                    </div>
                                  </div> 

                                  <hr class="regionLine" />

                                    @endforeach

                                  @endif

                                  <div class="form-group">
                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="visible_site" id="visible_site" value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-3">
                                            <p style="margin-top:7px;">Visible in Website</p>
                                          </div>
                                  </div>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                      <a href="{{URL::to('admin/catalog/product/list')}}" class="btn btn-default btn-sm">Cancel</a>
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

            $('#product-form').validate();

            $('#set_inventory_limit').on('click', function() {
              if ($('#set_inventory_limit').is(':checked')){
                $('.stockLimit').show();
              } else {
                $('.stockLimit').hide();
              }
            });

             $('.enable_region').on('click', function() {
                var id = this.id;
                //alert(id);
                  if ($('#'+id).is(':checked')){
                    $('#currencyregion'+id).hide();
                    $('.currencyregion'+id).hide();
                  } else {
                    $('#currencyregion'+id).show();
                    $('.currencyregion'+id).show();
                  }
              });

        });

        function addCourse(regId) {
          var product=$('#products_'+regId).val();
          var courses=$('#courses_'+regId).val();
          var html = '';
          if(product!='') {

            $('select#courses_'+regId+' option:selected').each(function() {

              var text = $(this).text();
              var value = $(this).val();

              html += '<div class="form-group"><div class="col-sm-10 col-sm-offset-1"><h5>'+text+'</h5></div></div>';

              $('select#products_'+regId+' option:selected').each(function() {
                var text1 = $(this).text();
                var value1 = $(this).val();

                var keyChange = regId+'_'+value+'_'+value1;

                var selectedValue = $('#product_course_'+keyChange).val();

                if(selectedValue==undefined) {
                  selectedValue = '';
                }

                html += '<div class="form-group"><label class="col-sm-3 control-label">'+text1+'</label><div class="col-sm-7">';
                html += '<input type="text" value="'+selectedValue+'" placeholder="Quantity" onkeyup="calculatePerItemPrice('+regId+');" autocomplete="off" name="product_course['+keyChange+']" id="product_course_'+keyChange+'" required number="true" class="form-control productCourses right-align" />';
                html += '</div></div>';
              });

            });

            

                    

          }
          $('#courseQuantities'+regId).html(html);
		      calculatePerItemPrice(regId);
        }


      function calculatePerItemPrice(regId) {
        /* var product = $('#products').val();
         var courses=$('#courses').val();
         var product_course=$('#product_course[]').val();
		 
         var product_base_currency = $('#product_base_currency').val();
         console.log(product_course);*/
          $.ajax({
            url: "{{URL::to('admin/catalog/product/bundle/caclulate-price')}}",
            method: 'POST',
			      data:{data:$('#product-form').serialize(),regId:regId},
           // data:{product:product,product_base_currency:product_base_currency,courses:courses,'product_course[]':product_course},
            success: function(response){
              $(response).each(function(key,value) {
                  var currencies = $('#currencies'+regId);
                  $('#regularprice'+regId).val(value.regularprice);
                  $('#saleprice'+regId).val(value.salesprice);

                 // calculatePrice();
              });
            }
          });
          
      }


    function calculatePrice() {

              var product_base_currency = $('#product_base_currency').val();

              if(product_base_currency!='') {

                var regularprice = Number($('.regularprice').val());

                if(regularprice!='') {

                    if(product_base_currency=='INR') {
                      //alert($('.regularprice').val());
                      @foreach($currencyRates as $rates)
                        var price = regularprice*{{$rates->rate}};
                        $('.regularprice{{$rates->from_currency}}').val(price.toFixed());
                      @endforeach
                    } else {
                      var inrValue = '';
                      @foreach($currencyRates as $rates)
                        if("{{$rates->from_currency}}"==product_base_currency) {
                          var inrValue = (regularprice/{{$rates->rate}}).toFixed(2);
                        }
                      @endforeach
                      @foreach($currencyRates as $rates)
                        var price = inrValue*{{$rates->rate}};
                        $('.regularprice{{$rates->from_currency}}').val(price.toFixed());
                      @endforeach
                    }

                  }else{
					  
                    if(product_base_currency=='INR') {
                      //alert($('.regularprice').val());
                      @foreach($currencyRates as $rates)
                        $('.regularprice{{$rates->from_currency}}').val(0);
                      @endforeach
                    } else {
                      @foreach($currencyRates as $rates)
                        var price = inrValue*{{$rates->rate}};
                        $('.regularprice{{$rates->from_currency}}').val(0);
                      @endforeach
                    }
				  }


                var saleprice = Number($('.saleprice').val());

                if(saleprice!='') {

                    if(product_base_currency=='INR') {
                      //alert($('.regularprice').val());
                      @foreach($currencyRates as $rates)
                        var price = saleprice*{{$rates->rate}};
                        $('.saleprice{{$rates->from_currency}}').val(price.toFixed());
                      @endforeach
                    } else {
                      var inrValue = '';
                      @foreach($currencyRates as $rates)
                        if("{{$rates->from_currency}}"==product_base_currency) {
                          var inrValue = (saleprice/{{$rates->rate}}).toFixed(2);
                        }
                      @endforeach
                      @foreach($currencyRates as $rates)
                        var price = inrValue*{{$rates->rate}};
                        $('.saleprice{{$rates->from_currency}}').val(price.toFixed());
                      @endforeach
                    }

                  }else{
					  
                    if(product_base_currency=='INR') {
                      @foreach($currencyRates as $rates)
                        var price = saleprice*{{$rates->rate}};
                        $('.saleprice{{$rates->from_currency}}').val(0);
                      @endforeach
                    } else {
                      @foreach($currencyRates as $rates)
                        $('.saleprice{{$rates->from_currency}}').val(0);
                      @endforeach
                    }
				  }

              }
    }
    </script>

@endsection