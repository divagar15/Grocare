@extends('admin.layout.tpl')

@section('customCss')


@endsection

@section('content')     	
<div class="page-header"><h1>Add Product</h1></div>



<div class="row">
            
              <div class="col-md-12">

              <ul role="tablist" class="nav nav-tabs" id="myTab">
                <li class="active"><a data-toggle="tab" role="tab" href="#general">General</a></li>
                <li><a data-toggle="tab" role="tab" href="#cms">CMS</a></li>
              </ul>

                 <!--  <div class="panel panel-default">
                        <div class="panel-body"> -->

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

                        <form id="product-form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                        <div class="tab-content" id="myTabContent">
                          
                            

                              <div id="general" class="tab-pane tabs-up fade in active panel panel-default">
                                
                                <div class="panel-body">

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Type</label>
                                    <div class="col-sm-7">
                                      <select name="product_type" id="product_type" required class="form-control">
                                        <option value="">Select Product Type</option>
                                        @if(isset($productType) && !empty($productType))
                                          @foreach($productType as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
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
                                    <label class="col-sm-3 control-label">SEO Title</label>
                                    <div class="col-sm-7">
                                      <textarea name="seo_title" id="seo_title" class="form-control"></textarea>
                                    </div>
                                  </div>


                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="meta_description" id="meta_description" class="form-control"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Short Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="short_description" id="short_description" required class="form-control"></textarea>
                                    </div>
                                  </div>
								  
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Did You Know?</label>
                                    <div class="col-sm-7">
                                      <textarea name="did_you_know" id="did_you_know" class="form-control"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
									<label class="col-sm-3 control-label">Featured Image</label>
									<div class="col-sm-8">
									  <input type="file" data-width="500" required data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="featured_image" id="featured_image" value="" >
									  <div class="col-md-12 alert-info">
									   Image Dimension should be greater than or equal to 500px X 300px.
									</div>
									</div>
                                  </div>

                                  <div class="form-group">
                  <label class="col-sm-3 control-label">Disease Page Image</label>
                  <div class="col-sm-8">
                    <input type="file" data-width="500" required data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="feature_image_original" id="feature_image_original" value="" >
                    <div class="col-md-12 alert-info">
                     Image Dimension should be greater than or equal to 500px X 300px.
                  </div>
                  </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Contents</label>
                                    <div class="col-sm-9">
                                      <textarea name="key_ingredients" id="key_ingredients" class="form-control ckeditor"></textarea>
                                    </div>
                                  </div>

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
                                      <h4>Course Settings</h4>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Courses</label>
                                    <div class="col-sm-7">
                                      <select name="courses[]" id="courses" multiple  class="chosen-select" required>
                                        <option value=""></option>
                                        @if(isset($courses) && !empty($courses))
                                          @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{ucwords($course->course_name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div id="courseQuantities">



                                  </div> -->

                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Currency Settings</h4>
                                      <span>(Enter the price for one item)</span>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Base Currency</label>
                                    <div class="col-sm-7">
                                      <select name="product_base_currency" id="product_base_currency" onchange="calculatePrice();" required class="form-control">
                                        @if(isset($enabledCurrencies) && !empty($enabledCurrencies))
                                          @foreach($enabledCurrencies as $key=>$value)
                                            <option value="{{$value}}" @if($value==$enabledCurrency->base_currency) selected @endif>{{$currencies[$value]}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Regular Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" class="regularprice form-control right-align" onkeyup="calculatePrice();" name="regular_price" id="regular_price" required number="true" />
                                    </div>
                                  </div>

                                   <div class="form-group">
                                    <label class="col-sm-3 control-label">Sale Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" autocomplete="off" class="saleprice form-control right-align" onkeyup="calculatePrice();" name="sale_price" id="sale_price" number="true" />
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Region Settings</h4>
                                    </div>
                                  </div>

                                  @if(isset($regions) && !empty($regions))

                                    @foreach($regions as $reg)

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
								  <div class="form-group  currencyregion{{$reg->id}}">
									  <label class="col-sm-3 control-label">SKU Name</label>
                                    <div class="col-sm-8">
										<input type="text" autocomplete="off" name="sku_name[]" id="sku_name_{{$reg->id}}" class="sku_name_{{$reg->id}} form-control" required />
									</div>
								  </div>
                                  <div class="form-group currencyregion{{$reg->id}}">
                                    <label class="col-sm-3 control-label">Courses</label>
                                    <div class="col-sm-8">
                                      <select name="courses_{{$reg->id}}[]" id="courses_{{$reg->id}}" multiple  class="chosen-select coursesChange" required>
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
                                      <input type="text" autocomplete="off" name="regularprice[]" id="regularprice" class="regularprice{{$reg->currency}} form-control right-align" required number="true" />
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="control-label">Sale Price</label>
                                      <input type="text" autocomplete="off" name="saleprice[]" id="saleprice" class="saleprice{{$reg->currency}} form-control right-align" number="true" />
                                    </div>
                                  </div>

                                  <hr class="regionLine" />

                                    @endforeach

                                  @endif



                              </div>

                              </div>

                            <div id="cms" class="tab-pane tabs-up fade panel panel-default">
                                  <div class="panel-body">

                                      <div class="form-group">
                                        <div class="col-sm-12">
                                          <h4>Content Settings</h4>
                                        </div>
                                      </div>


                                    
                                      

                                      <div id="title_blocks">

                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-7">
                                              <input type="text" autocomplete="off" class="form-control" name="title[]" id="title_1" value="" >
                                            </div>

                                          </div>


                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                              <textarea class="ckeditor" name="description[]" id="description_1" value="" ></textarea>
                                            </div>

                                          </div>

                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Image Type</label>
                                            <div class="col-sm-9">
                                              <select name="imagetype[]" id="imagetype_1" class="form-control imageType">
                                                <option value="">None</option>
                                                <option value="1">Normal Image</option>
                                                <option value="2">Banner Image</option>
                                              </select>
                                            </div>

                                          </div>

                                          <div class="form-group" id="normal_image_1" style="display:none;">

                                            <label class="col-sm-3 control-label">Normal Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="500" data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="normal_image[]" id="normal_image_1" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 500px X 300px.
                                            </div>
                                            </div>
                                           </div>

                                          <div class="form-group" id="banner_image_1" style="display:none;">

                                            <label class="col-sm-3 control-label">Banner Fullscreen Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="1000" data-height="500" data-size="1500" accept="image/*" class="form-control imageCheck" name="banner_image[]" id="banner_image_1" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 1000px X 500px. 
                                            </div>
                                            </div>
                                           </div>

                                           <hr/>

                                      </div>

                                      <div class="form-group">
                                        <label class="col-sm-3 col-sm-offset-2">
                                            <button type="button" class="btn btn-xs btn-info" id="addBlock">
                                              <i class="fa fa-plus"></i> Add Block
                                            </button>
                                        </label>
                                            <input type="hidden" name="counter" id="counter" value="1">
                                      </div>



                                      <div class="form-group">
                                        <div class="col-sm-12">
                                          <h4>Visibility Settings</h4>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="col-sm-3 control-label cr-styled">
                                              <input type="checkbox" name="visible_site" id="visible_site" value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-3">
                                            <p style="margin-top:7px;">Visible in product</p>
                                          </div>

                                          <!-- <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="visible_store" id="visible_store" value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-3">
                                            <p style="margin-top:7px;">Visible in store</p>
                                          </div> -->
                                        </div>
                                        
                                  </div>
                            </div>

                                 

                          

                      </div>


                         <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="button" id="submit-product" class="btn btn-primary btn-sm">Submit</button>
                                      <a href="{{URL::to('admin/catalog/product/list')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                          </div>


                      </form>

                        <!-- </div>

                    </div> -->

                 </div>
            
            </div>

@endsection

@section('customJs')

    <script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
			calculatePrice();
            $('#product-form').validate();

             $('#submit-product').click(function(){

                var product_type = $('#product_type').val();
                var name = $('#name').val();
                var short_description = $('#short_description').val();
                var stock_status = $('#stock_status').val();
                var courses = $('#courses').val();
                var regular_price = $('#regular_price').val();
                var sale_price = $('#sale_price').val();
                var banner_image = $('#banner_image').val();

                if($("#product-form").valid()){

                  if(product_type=='' || name=='' || short_description=='' || stock_status=='' || courses=='' || regular_price=='') {
                    alert('Please fill out all the mandatory fields');
                    return false;
                  }

                  
                    $('#product-form').submit();

                }
              });

             //$('.imageCheck').on('change', function() {
            $(document).on('change',".imageCheck",function (){  
               //console.log('This file size is: ' + (this.files[0].size/1024/1024).toFixed(2) + " MB");
               var width = Number($(this).data('width'));
               var height = Number($(this).data('height'));
               var size = $(this).data('size');

               var id = this.id;
               //alert(width+'--'+height+'--'+size);

               var uploadedSize = (this.files[0].size/1024).toFixed(2);

               //alert(uploadedSize+'--'+size);

               if(uploadedSize>Number(size)) {
                alert('Image size is large. Please upload a image size of less than '+size+' kb');
                $(this).addClass('error');
                $(this).val('');
               } else {
                $(this).removeClass('error');
               }

               image = new Image();
               image.src = window.URL.createObjectURL( this.files[0] );
               image.onload = function() {
                //alert(width+'--'+this.width+'--'+height+'--'+this.height);
                  if(this.width<width || this.height<height) {
                    alert('Please upload a image with a proper dimensions');
                    $('#'+id).addClass('error');
                    $('#'+id).val('');
                  } else {
                    $(this).removeClass('error');
                   }
                  //alert("The image width is " +this.width + " and image height is " + this.height);
                };

            });


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

/*              $('.regularprice').keyup(function() {
                var regularprice = Number($('.regularprice').val());
                var product_base_currency = $('#product_base_currency').val();
                if(product_base_currency=='INR') {
                  //alert($('.regularprice').val());
                  @foreach($currencyRates as $rates)
                    var price = regularprice*{{$rates->rate}};
                    $('.regularprice{{$rates->from_currency}}').val(price.toFixed(2));
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
                    $('.regularprice{{$rates->from_currency}}').val(price.toFixed(2));
                  @endforeach
                }
              });*/
              
              $(document).on('change',".imageType",function (){  

                var id = this.id;
                var splitId = id.split('_');
                var value = this.value;
                //alert(value);
                if(value==1) {
                  $('#banner_image_'+splitId[1]).hide();
                  $('#banner_image_'+splitId[1]).val('');
                  $('#normal_image_'+splitId[1]).show();
                } else if(value==2) {
                  $('#normal_image_'+splitId[1]).hide();
                  $('#normal_image_'+splitId[1]).val('');
                  $('#banner_image_'+splitId[1]).show();
                } else {
                  $('#normal_image_'+splitId[1]).hide();
                  $('#banner_image_'+splitId[1]).hide();
                }

              });

              $('.coursesChange').change(function() {
                var id = this.id;
                var splitId = id.split('_');
                var html = '';
                //console.log(courses);
                $('select#'+id+' option:selected').each(function() {
                    //alert($(this).text() + ' ' + $(this).val());
                    var text = $(this).text();
                    var value = $(this).val();

                    var selectedValue = $('#course_qty_'+splitId[1]+'_'+value).val();

                    if(selectedValue==undefined) {
                      selectedValue = '';
                    }

                    html += '<div class="form-group"><label class="col-sm-3 control-label">'+text+' Quantity</label>';
                    html += '<div class="col-sm-7"><input type="text" value="'+selectedValue+'" autocomplete="off" name="course_qty_'+splitId[1]+'_'+value+'" id="course_qty_'+splitId[1]+'_'+value+'" number="true" class="form-control right-align" />';
                    html += '</div></div>';
                });
                $('#courseQuantities'+splitId[1]).html(html);
              });


              $('#product_type').change(function() {
                  var value = $('#product_type').val();
                  if(value==2) {
                    window.location = "{{URL::to('admin/catalog/product/add/bundle')}}";
                  }
              });


          $("#addBlock").on("click", function () {
              var counter = Number($('#counter').val());
              counter = counter+1;

              var html = '<div  id="block_'+counter+'"><div class="form-group"><label class="col-sm-3 control-label">Title</label>';
              html += '<div class="col-sm-7"><input type="text" autocomplete="off" class="form-control" name="title[]" id="title_'+counter+'" value="" ></div><div class="col-sm-2"><a href="javascript:void(0)" data-id="'+counter+'" class="removeBlock btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></div></div>';
              html += '<div class="form-group"><label class="col-sm-3 control-label">Description</label>';
              html += '<div class="col-sm-9"><textarea class="ckeditor" name="description[]" id="description_'+counter+'" value="" ></textarea></div></div>';
              html += '<div class="form-group"><label class="col-sm-3 control-label">Image Type</label>';
              html += '<div class="col-sm-9"><select name="imagetype[]" id="imagetype_'+counter+'" class="form-control imageType">';
              html += '<option value="">None</option><option value="1">Normal Image</option><option value="2">Banner Image</option></select></div>';
              html += '</div>';  
              html += '<div class="form-group" id="normal_image_'+counter+'" style="display:none;"><label class="col-sm-3 control-label">Normal Image</label>';
              html += '<div class="col-sm-8"><input type="file" data-width="500" data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="normal_image[]" id="normal_image_'+counter+'" value="" >';
              html += '<div class="col-md-12 alert-info">Image Dimension should be greater than or equal to 500px X 300px.</div>';
              html += '</div></div>';
              html += '<div class="form-group" id="banner_image_'+counter+'" style="display:none;"><label class="col-sm-3 control-label">Banner Fullscreen Image</label>';
              html += '<div class="col-sm-8"><input type="file" data-width="1000" data-height="500" data-size="1500" accept="image/*" class="form-control imageCheck" name="banner_image[]" id="banner_image_'+counter+'" value="" >';
              html += '<div class="col-md-12 alert-info">Image Dimension should be greater than or equal to 1000px X 500px.</div>';
              html += '</div></div>';
              html += '</div><hr/>';              

              $('#title_blocks').append(html);
              $("#counter").val(counter);
              CKEDITOR.replace('description_'+counter);

          });

            $(document).on('click',".removeBlock",function (){   
                var id   = $(this).data('id');
                $("#block_"+id).remove();
            });

        });


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