@extends('admin.layout.tpl')

@section('customCss')


@endsection

@section('content')     	
<div class="page-header"><h1>Edit Product</h1></div>



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
                                      <select name="product_type" id="product_type" disabled required class="form-control">
                                        @if(isset($productType) && !empty($productType))
                                          @foreach($productType as $key=>$value)
                                            <option value="{{$key}}" @if($product->product_type==$key) selected @endif>{{$value}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-7">
                                    <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}" />
                                    <input type="text" autocomplete="off" value="{{$product->name}}" name="name" id="name" required class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Link</label>
                                    <div class="col-sm-3">
                                      <p style="margin-top:7px;">{{URL::to('/products')}}/</p>
                                    </div>
                                    <div class="col-sm-3">
                                    <input type="text" value="{{$product->product_slug}}" autocomplete="off" name="product_slug" id="product_slug" required class="form-control"> 
                                    </div>
                                  </div>
								  
								  <div class="form-group">
                                    <label class="col-sm-3 control-label">SEO Title</label>
                                    <div class="col-sm-7">
                                      <textarea name="seo_title" id="seo_title" class="form-control">{{$product->seo_title}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="meta_description" id="meta_description" class="form-control">{{$product->meta_description}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Short Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="short_description" id="short_description" required class="form-control">{{$product->short_description}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Did You Know?</label>
                                    <div class="col-sm-7">
                                      <textarea name="did_you_know" id="did_you_know" class="form-control">{{$product->did_you_know}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">

                                            <label class="col-sm-3 control-label">Featured Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="500" data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="featured_image" id="featured_image" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 500px X 300px.
                                            </div>
                                            @if($product->feature_image!='')
                                                <div class="col-md-12">
                                                  <img class="img-responsive" src="{{URL::asset('public/uploads/products/'.$product->id.'/'.$product->feature_image)}}" />
                                                </div>
                                              @endif
                                            </div>
                                  </div>


                                  <div class="form-group">

                                            <label class="col-sm-3 control-label">Disease Page Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="500" data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="feature_image_original" id="feature_image_original" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 500px X 300px.
                                            </div>
                                            @if($product->feature_image_original!='')
                                                <div class="col-md-12">
                                                  <img class="img-responsive" src="{{URL::asset('public/uploads/products/'.$product->id.'/'.$product->feature_image_original)}}" />
                                                </div>
                                              @endif
                                            </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Contents</label>
                                    <div class="col-sm-9">
                                      <textarea name="key_ingredients" id="key_ingredients" class="form-control ckeditor">{{$product->key_ingredients}}</textarea>
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
                                        @if(isset($stockStatus) && !empty($stockStatus))
                                          @foreach($stockStatus as $key=>$value)
                                            <option value="{{$key}}" @if($product->stock_status==$key) selected @endif>{{$value}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                      <label class="col-sm-3 control-label cr-styled">
                                          <input type="checkbox" @if($product->stock_available!='' && $product->stock_limit!='') checked @endif name="set_inventory_limit" id="set_inventory_limit" value="1">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Set Inventory limit and stock available</p>
                                      </div>
                                  </div>

                                  <div class="form-group stockLimit" @if($product->stock_available=='' && $product->stock_limit=='') style="display:none;" @endif>
                                    <label class="col-sm-3 control-label">Available Stock Quantity</label>
                                    <div class="col-sm-7">
                                      <input type="text" value="{{$product->stock_available}}" autocomplete="off" name="stock_available" id="stock_available" required number="true" class="form-control right-align" />
                                    </div>
                                  </div>

                                  <div class="form-group stockLimit" @if($product->stock_available=='' && $product->stock_limit=='') style="display:none;" @endif>
                                    <label class="col-sm-3 control-label">Stock Limit</label>
                                    <div class="col-sm-7">
                                      <input type="text" value="{{$product->stock_limit}}" autocomplete="off" name="stock_limit" id="stock_limit" required number="true" class="form-control right-align" />
                                    </div>
                                  </div>


                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Currency Settings</h4>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Product Base Currency</label>
                                    <div class="col-sm-7">
                                      <select name="product_base_currency" id="product_base_currency" onchange="calculatePrice();" required class="form-control">
                                        @if(isset($enabledCurrencies) && !empty($enabledCurrencies))
                                          @foreach($enabledCurrencies as $key=>$value)
                                            <option value="{{$value}}" @if($value==$product->base_currency) selected @endif>{{$currencies[$value]}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Regular Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" value="{{round($product->regular_price)}}" autocomplete="off" class="regularprice form-control right-align" onkeyup="calculatePrice();" name="regular_price" id="regular_price" required number="true" />
                                    </div>
                                  </div>

                                   <div class="form-group">
                                    <label class="col-sm-3 control-label">Sale Price</label>
                                    <div class="col-sm-7">
                                      <input type="text" value="{{round($product->sales_price)}}" autocomplete="off" class="saleprice form-control right-align" onkeyup="calculatePrice();" name="sale_price" id="sale_price" required number="true" />
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <h4>Region Settings</h4>
                                    </div>
                                  </div>

                                  @if(isset($regions) && !empty($regions))

                                    @foreach($regions as $reg)

                                    <?php $courseRegions = App\Helper\AdminHelper::getCourseRegions($reg->regId);
                                          $courseSelect = array(); 
                                          if(isset($courseRegions) && !empty($courseRegions)) {
                                            foreach($courseRegions as $courseReg) {
                                              $courseSelect[] = $courseReg->fkcourse_id;
                                            }
                                          }
                                      //var_dump($courseSelect);
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
                                          <input type="checkbox" @if($reg->enable==2) checked @endif class="enable_region" name="enable_region_{{$reg->id}}" id="{{$reg->id}}" value="{{$reg->id}}">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Product unavailable for this region</p>
                                      </div>
                                    </div>
									
								  <div class="form-group  currencyregion{{$reg->id}}">
									  <label class="col-sm-3 control-label">SKU Name</label>
                                    <div class="col-sm-8">
										<input type="text" autocomplete="off" name="sku_name[]" id="sku_name_{{$reg->id}}" class="sku_name_{{$reg->id}} form-control" value="{{$reg->sku_name}}" required />
									</div>
								  </div>

                                    <div class="form-group currencyregion{{$reg->id}}" @if($reg->enable==2) style="display:none;" @endif>
                                    <label class="col-sm-3 control-label">Courses</label>
                                    <div class="col-sm-8">
                                      <select name="courses_{{$reg->id}}[]" id="courses_{{$reg->id}}" multiple  class="chosen-select coursesChange" required>
                                        <option value=""></option>
                                        @if(isset($courses) && !empty($courses))
                                          @foreach($courses as $course)
                                            <option value="{{$course->id}}" @if(!empty($courseSelect) && in_array($course->id,$courseSelect)) selected @endif>{{ucwords($course->course_name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div id="courseQuantities{{$reg->id}}" class="currencyregion{{$reg->id}}" @if($reg->enable==2) style="display:none;" @endif>

                                    @if(isset($courseRegions) && !empty($courseRegions))

                                      @foreach($courseRegions as $courseReg)

                                    <div class="form-group"><label class="col-sm-3 control-label">{{$courseReg->course_name}} Quantity</label>
                                      <div class="col-sm-7">
                                        <input type="text" value="{{$courseReg->quantity}}" autocomplete="off" name="course_qty_{{$reg->id}}_{{$courseReg->fkcourse_id}}" id="course_qty_{{$reg->id}}_{{$courseReg->fkcourse_id}}" number="true" class="form-control right-align" />
                                      </div>
                                    </div>

                                      @endforeach

                                    @endif

                                  </div>

                                  <div class="form-group" id="currencyregion{{$reg->id}}" @if($reg->enable==2) style="display:none;" @endif>
                                    <label class="col-sm-3 control-label" style="margin-top:20px;">{{$reg->currency." (".$reg->symbol.")"}}</label>
                                    <div class="col-sm-4">
                                      <label class="control-label">Regular Price</label>
                                      <input type="text" value="{{round($reg->regular_price)}}" autocomplete="off" name="regularprice[]" id="regularprice" class="regularprice{{$reg->currency}} form-control right-align" required number="true" />
                                    </div>
                                    <div class="col-sm-4">
                                      <label class="control-label">Sale Price</label>
                                      <input type="text" value="{{round($reg->sales_price)}}" autocomplete="off" name="saleprice[]" id="saleprice" class="saleprice{{$reg->currency}} form-control right-align" required number="true" />
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

                                        <?php $j=1; ?>

                                        @if(isset($productTiles) && !empty($productTiles))
                                          @foreach($productTiles as $tiles)

                                          <div  id="block_{{$j}}">

                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-7">
                                              <input type="hidden" name="tiles[]" id="tiles_{{$j}}" value="{{$tiles->id}}">
                                              <input type="text" autocomplete="off" class="form-control" name="title[]" id="title_{{$j}}" value="{{$tiles->title}}" required>
                                            </div>
                                            <div class="col-sm-2">
                                            <a href="javascript:void(0)" data-id="{{$tiles->id}}" data-type="{{$j}}" class="removeBlock btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a>
                                            </div>
                                          </div>


                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                              <textarea class="ckeditor" name="description[]" id="description_{{$j}}" required>{{$tiles->description}}</textarea>
                                            </div>

                                          </div>


                                           <div class="form-group">

                                            <label class="col-sm-3 control-label">Image Type</label>
                                            <div class="col-sm-9">
                                              <select name="imagetype[]" id="imagetype_{{$j}}" class="form-control imageType">
                                                <option value="">None</option>
                                                <option value="1" @if($tiles->image_type==1) selected @endif>Normal Image</option>
                                                <option value="2" @if($tiles->image_type==2) selected @endif>Banner Image</option>
                                              </select>
                                            </div>

                                          </div>

                                          <div class="form-group" id="normal_image_{{$j}}"  @if($tiles->image_type!=1) style="display:none;" @endif>

                                            <label class="col-sm-3 control-label">Normal Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="500" data-height="300" data-size="1500" accept="image/*" class="form-control imageCheck" name="normal_image[]" id="normal_image_{{$j}}" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 500px X 300px.
                                              </div>
                                              @if($tiles->image!='' && $tiles->image_type==1)
                                                <div class="col-md-12">
                                                  <img class="img-responsive" src="{{URL::asset('public/uploads/products/'.$product->id.'/'.$tiles->image)}}" />
                                                </div>
                                              @endif
                                            </div>
                                           </div>

                                          <div class="form-group" id="banner_image_{{$j}}"   @if($tiles->image_type!=2) style="display:none;" @endif>

                                            <label class="col-sm-3 control-label">Banner Fullscreen Image</label>
                                            <div class="col-sm-8">
                                              <input type="file" data-width="1000" data-height="500" data-size="1500" accept="image/*" class="form-control imageCheck" name="banner_image[]" id="banner_image_{{$j}}" value="" >
                                              <div class="col-md-12 alert-info">
                                               Image Dimension should be greater than or equal to 1000px X 500px. 
                                              </div>
                                              @if($tiles->image!='' && $tiles->image_type==2)
                                                <div class="col-md-12">
                                                  <img class="img-responsive" src="{{URL::asset('public/uploads/products/'.$product->id.'/'.$tiles->image)}}" />
                                                </div>
                                              @endif
                                            </div>
                                           </div>


                                          </div>

                                          <hr/>

                                          <?php $j++; ?>

                                          @endforeach
                                        @endif

                                      </div>

                                      <?php $j = $j-1; ?>

                                      <div class="form-group">
                                        <label class="col-sm-3 col-sm-offset-2">
                                            <button type="button" class="btn btn-xs btn-info" id="addBlock">
                                              <i class="fa fa-plus"></i> Add Block
                                            </button>
                                        </label>
                                            <input type="hidden" name="counter" id="counter" value="{{$j}}">
                                      </div>




                                      <div class="form-group">
                                        <div class="col-sm-12">
                                          <h4>Visibility Settings</h4>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                          <label class="col-sm-3 control-label cr-styled">
                                              <input type="checkbox" name="visible_site" id="visible_site" value="1" @if($product->website_visible==1) checked @endif>
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-3">
                                            <p style="margin-top:7px;">Visible in product</p>
                                          </div>

                                          <!-- <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="visible_store" id="visible_store" value="1" @if($product->store_visible==1) checked @endif>
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
                                      <button type="button" id="submit-product" class="btn btn-primary btn-sm">Update</button>
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

            $('#product-form').validate();
			
			@if(Session::has('previewSlug'))			
				var form = document.createElement("form");
				form.method = "POST";
				form.action = "{{URL::to('/products-preview/'.Session::get('previewSlug'))}}";
				form.target = "_blank";
				document.body.appendChild(form);
				form.submit();
					 setTimeout(function () {
						window.location.reload();	
					 }, 1500);
			@endif
			
             $('#submit-product').click(function(){

               // var product_type = $('#product_type').val();
                var name = $('#name').val();
                var short_description = $('#short_description').val();
                var stock_status = $('#stock_status').val();
                var courses = $('#courses').val();
                var regular_price = $('#regular_price').val();
                var sale_price = $('#sale_price').val();
              //  var banner_image = $('#banner_image').val();

                if($("#product-form").valid()){

                  var uimage = parseInt($('#update_image_count').val());
                  var nimage = parseInt($('#image_count').val());
                  var timage = nimage-uimage;

                  if(name=='' || short_description=='' || stock_status=='' || courses=='' || regular_price=='') {
                    alert('Please fill out all the mandatory fields');
                    return false;
                  }

                  $( "#product-form" ).submit(); 
                    
                }
              });

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
                var type   = $(this).data('type');
                if(type=='dynamic') {
                  $("#block_"+id).remove();
                } else {
                  var confirmMsg = confirm('Are you sure want to remove this block?');
                  if(confirmMsg) {
                    $.ajax({
                        url: "{{URL::to('admin/catalog/product/delete-block')}}/"+id,
                        method: 'POST',
                        success: function(response){

                          if($.trim(response)=='success') {
                            $("#block_"+type).remove();
                          } else {
                            alert("Please try again later");
                          }

                        }

                    });
                  }
                }
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