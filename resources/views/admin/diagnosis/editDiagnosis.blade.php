@extends('admin.layout.tpl')

@section('content')     	

<div class="page-header"><h1>Edit Diagnosis</h1></div>



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

                          
                            <form id="diagnosis-form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">


                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-7">
                                    <input type="text" value="{{$diagnosis->name}}" autocomplete="off" name="name" id="name" required class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Link</label>
                                    <div class="col-sm-3">
                                      <p style="margin-top:7px;">{{URL::to('/diagnose')}}/</p>
                                    </div>
                                    <div class="col-sm-3">
                                    <input type="text" value="{{$diagnosis->diagnosis_slug}}" autocomplete="off" name="diagnosis_slug" id="diagnosis_slug" required class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Title</label>
                                    <div class="col-sm-7">
                                    <input type="text" autocomplete="off" value="{{$diagnosis->title}}" name="diagnosis_title" id="diagnosis_title" class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">SEO Title</label>
                                    <div class="col-sm-7">
                                      <textarea name="seo_title" id="seo_title" class="form-control">{{$diagnosis->seo_title}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Keywords</label>
                                    <div class="col-sm-7">
                                      <textarea name="meta_keywords" id="meta_keywords" class="form-control">{{$diagnosis->meta_keywords}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Meta Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="meta_description" id="meta_description" class="form-control">{{$diagnosis->meta_description}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Disease Short Description</label>
                                    <div class="col-sm-7">
                                      <textarea name="disease_short_description" id="disease_short_description" required class="form-control">{{$diagnosis->disease_short_description}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Disease Description</label>
                                    <div class="col-sm-9">
                                      <textarea name="disease_description" id="disease_description" required class="ckeditor">{{$diagnosis->disease_description}}</textarea>
                                    </div>
                                  </div>

                                   <div class="form-group">
                                    <label class="col-sm-3 control-label">How is it caused?</label>
                                    <div class="col-sm-9">
                                      <textarea name="how_is_it_caused" id="how_is_it_caused"  class="ckeditor">{{$diagnosis->how_is_it_caused}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">How to heal naturally?</label>
                                    <div class="col-sm-9">
                                      <textarea name="how_to_heal_naturally" id="how_to_heal_naturally"  class="ckeditor">{{$diagnosis->how_to_heal_naturally}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">How it works?</label>
                                    <div class="col-sm-9">
                                      <textarea name="how_it_works" id="how_it_works"  class="ckeditor">{{$diagnosis->how_it_works}}</textarea>
                                    </div>
                                  </div>
								  
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Did You Know?</label>
                                    <div class="col-sm-7">
                                      <textarea name="did_you_know" id="did_you_know" class="form-control">{{$diagnosis->did_you_know}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Advertisement Link</label>
                                    <div class="col-sm-7">
                                      <input type="text" url="true" name="ad_link" id="ad_link" class="form-control" value="{{$diagnosis->ad_link}}" />
                                    </div>
                                  </div>

                                  <div class="form-group">

                                            <label class="col-sm-3 control-label">Diet Chart</label>
                                            <div class="col-sm-8">
                                              <input type="file"  class="form-control" name="diet_chart" id="diet_chart" value="" >
                                              <div class="col-md-12 alert-info">
                                               Upload pdf, doc, jpg, png only
                                            </div>
                                             @if($diagnosis->diet_chart!='')
                                                <div class="col-md-12"><br/>
                                                  <a target="_blank" href="{{URL::asset('public/uploads/diagnosis/'.$diagnosis->id.'/'.$diagnosis->diet_chart)}}" class="btn btn-xs btn-info">Click to view uploaded diet chart</a>
                                                </div>
                                              @endif
                                            </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Diet Chart Email Content</label>
                                    <div class="col-sm-9">
                                      <textarea name="diet_chart_content" id="diet_chart_content"  class="ckeditor">{{$diagnosis->die_chart_content}}</textarea>
                                    </div>
                                  </div>

                                   <div class="form-group">
                                      <label class="col-sm-3 control-label cr-styled">
                                          <input type="checkbox"  @if($diagnosis->diet_chart_visible==1) checked @endif name="diet_chart_visible" id="diet_chart_visible" value="1">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Visible Diet Chart in Website</p>
                                      </div>
                                    </div>


                                 <div class="form-group">
                                        <div class="col-sm-12">
                                          <h4>Content Settings</h4>
                                        </div>
                                  </div>


                                      <div id="title_blocks">

                                        <?php $j=1; ?>

                                        @if(isset($diagnosisBlock) && !empty($diagnosisBlock))
                                          @foreach($diagnosisBlock as $tiles)

                                          <div  id="block_{{$j}}">

                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Title</label>
                                            <div class="col-sm-7">
                                              <input type="hidden" name="tiles[]" id="tiles_{{$j}}" value="{{$tiles->id}}">
                                              <input type="text" autocomplete="off" class="form-control" name="title[]" id="title_{{$j}}" value="{{$tiles->title}}" >
                                            </div>
                                            <div class="col-sm-2">
                                            <a href="javascript:void(0)" data-id="{{$tiles->id}}" data-type="{{$j}}" class="removeBlock btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a>
                                            </div>
                                          </div>


                                          <div class="form-group">

                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                              <textarea class="ckeditor" name="description[]" id="description_{{$j}}" >{{$tiles->description}}</textarea>
                                            </div>

                                          </div>


                                          </div>

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
											<h4>Note</h4>
                                        </div>
									</div>
									<div id="title_blocks">
										<div class="form-group">
											<label class="col-sm-3 control-label">Note</label>
                                            <div class="col-sm-9">
                                              <textarea class="ckeditor" name="note" id="note" value="" >{{$diagnosis->note}}</textarea>
                                            </div>
										</div>
									</div>
                                      <div class="form-group">
                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="no_side_effect" id="no_side_effect" @if($diagnosis->no_side_effect==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">No Side Effects</p>
                                          </div>

                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="no_added_steroids" id="no_added_steroids" @if($diagnosis->no_added_steroids==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">No Added Steroids</p>
                                          </div>

                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="no_dietary_restrictions" id="no_dietary_restrictions" @if($diagnosis->no_dietary_restrictions==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">No Dietary Restrictions</p>
                                          </div>

                                         </div>

                                         <div class="form-group">

                                         <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="fda_approved" id="fda_approved" @if($diagnosis->fda_approved==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">FDA Approved</p>
                                          </div>

                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="worldwide_shipping" id="worldwide_shipping" @if($diagnosis->worldwide_shipping==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">Worldwide Shipping</p>
                                          </div>

                                          <label class="col-sm-1 control-label cr-styled">
                                              <input type="checkbox" name="research_based" id="research_based" @if($diagnosis->research_based==1) checked @endif value="1">
                                              <i class="fa"></i> 
                                          </label>
                                          <div class="col-sm-2">
                                            <p style="margin-top:7px;">Research Based</p>
                                          </div>

                                          </div>

                                           <div class="form-group">
                                        <div class="col-sm-12">
                                          <h4>Product Settings</h4>
                                        </div>
                                      </div>

                                  @if(isset($regions) && !empty($regions))

                                    @foreach($regions as $reg)


                                    <?php
                                      $regionProducts = App\Helper\AdminHelper::getDiagnosisProducts($reg->id);
                                      $selectedProduct = App\Helper\AdminHelper::getDiagnosisProductsSelected($diagnosis->id,$reg->id);
                                      //var_dump($regionProducts);
                                      $k=1;
                                      $productSelect = '';
                                      if(!empty($selectedProduct)) {
                                        foreach ($selectedProduct as $selPro) {
                                          if($k==1) {
                                            $productSelect = $selPro->fkproduct_id;
                                          }
                                        }
                                      }
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
                                          <input type="checkbox" @if(empty($productSelect)) checked @endif class="enable_region" name="enable_region_{{$reg->id}}" id="{{$reg->id}}" value="{{$reg->id}}">
                                          <i class="fa"></i> 
                                      </label>
                                      <div class="col-sm-7">
                                        <p style="margin-top:7px;">Product unavailable for this region</p>
                                      </div>
                                    </div>

                                <div class="form-group currencyregion{{$reg->id}}" @if(empty($productSelect)) style="display:none;" @endif>
                                    <label class="col-sm-3 control-label">Products</label>
                                    <div class="col-sm-7">
                                      <select name="products_{{$reg->id}}" id="products_{{$reg->id}}"   class="chosen-select" required onchange="addProductContent({{$reg->id}});">
                                        <option value=""></option>
                                        @if(isset($regionProducts) && !empty($regionProducts))
                                          @foreach($regionProducts as $prod)
                                            <option value="{{$prod->id}}_{{$prod->product_type}}" @if($prod->id==$productSelect) selected @endif>{{ucwords($prod->name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div id="productContent{{$reg->id}}" class="currencyregion{{$reg->id}}" @if(empty($productSelect)) style="display:none;" @endif>

                                  @if(!empty($selectedProduct))

                                    @foreach($selectedProduct as $sel)

                               <div class="form-group"><label class="col-sm-3 control-label">{{$sel->name}}</label><div class="col-sm-7">
                               <input type="hidden" name="productId_{{$reg->id}}[{{$productSelect}}]" value="{{$sel->product_id}}">
                                <textarea rows="6" name="product_{{$reg->id}}[{{$sel->product_id}}]" id="product_{{$reg->id}}_{{$sel->product_id}}" required class="form-control productCourses ">{{$sel->product_content}}</textarea>                             
                                </div></div>

                                    @endforeach

                                  @endif


                                  </div>

                                  <hr class="regionLine" />

                                    @endforeach

                                  @endif
                                 


                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                      <a href="{{URL::to('admin/catalog/diagnosis/list')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div>

                          </form>
                        </div>
                    </div>
                 </div>
            
            </div>

@endsection

@section('customJs')
    <script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $('#diagnosis-form').validate();
			
			/* IF REDIRECT FROM CREATE PROPERTY OPEN NEW TAB PREVIEW START */
		@if(Session::has('diagnosisSlug'))			
			var form = document.createElement("form");
			form.method = "POST";
			form.action = "{{URL::to('/diagnose/'.Session::get('diagnosisSlug'))}}";
			form.target = "_blank";
			document.body.appendChild(form);
			form.submit();
			setTimeout(function () {
				window.location.reload();	
			}, 1500);
						
			
		@endif
			

          $("#addBlock").on("click", function () {
              var counter = Number($('#counter').val());
              counter = counter+1;

              var html = '<div  id="block_'+counter+'"><div class="form-group"><label class="col-sm-3 control-label">Title</label>';
              html += '<div class="col-sm-7"><input type="text" autocomplete="off" class="form-control" name="title[]" id="title_'+counter+'" value="" required></div><div class="col-sm-2"><a href="javascript:void(0)" data-id="'+counter+'" data-type="dynamic" class="removeBlock btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></div></div>';
              html += '<div class="form-group"><label class="col-sm-3 control-label">Description</label>';
              html += '<div class="col-sm-9"><textarea class="ckeditor" name="description[]" id="description_'+counter+'" value="" required></textarea></div></div>';
              html += '</div>';              

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
                        url: "{{URL::to('admin/catalog/diagnosis/delete-block')}}/"+id,
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

      function addProductContent(regId) {
              var product=$('#products_'+regId).val();
              /*var courses=$('#courses_'+regId).val();*/
              var html = '';
              if(product!='') {     
                  //var prod_type = $('select#products_'+regId).find("option:selected").data('prodType');
                  $('select#products_'+regId+' option:selected').each(function() {
                    var text1 = $(this).text();
                    var values = ($(this).val()).split('_');
                    var value1 = values[0];
                    var prod_type = values[1];

                    if(prod_type == 1){
                      //var keyChange = value1+'_'+regId;
                      var keyChange = value1;
                      var selectedValue = $('#product_'+keyChange).val();

                      if(selectedValue==undefined) {
                        selectedValue = '';
                      }

                      html += '<div class="form-group"><label class="col-sm-3 control-label">'+text1+'</label><div class="col-sm-7">';
                      html += '<input type="hidden" name="productId_'+regId+'['+keyChange+']" value="'+value1+'">';
                      html += '<textarea rows="6" name="product_'+regId+'['+keyChange+']" id="product_'+regId+'_'+keyChange+'" required class="form-control productCourses "></textarea>';
                      //html += '<input type="text" value="'+selectedValue+'" placeholder="Quantity" onkeyup="calculatePerItemPrice('+regId+');" autocomplete="off" name="product_course['+keyChange+']" id="product_course_'+keyChange+'" required number="true" class="form-control productCourses right-align" />';
                      html += '</div></div>';
                    }
                    else if(prod_type == 2){
                      html ='';
                      //var keyChange = value1+'_'+regId;
                      var keyChange = value1;
                      $.ajax({
                         type:"get",
                         url:"{{URL::to('admin/catalog/diagnosis/products/get/')}}/"+value1+'/'+regId,                                          
                         success:function(data){
                            //var data=$.parseJSON(result);  
                            //console.log(data);
                            if(data){
                              $(data).each(function(i,val) {
                                html += '<div class="form-group"><label class="col-sm-3 control-label">'+val.name+'</label><div class="col-sm-7">';
                                html += '<input type="hidden" name="productId_'+regId+'['+keyChange+']" value="'+val.id+'">';
                                html += '<textarea rows="6" name="product_'+regId+'['+val.id+']" id="product_'+regId+'_'+val.id+'" required class="form-control productCourses "></textarea>';                                
                                html += '</div></div>';

                              });//each
                              $('#productContent'+regId).html(html);
                            }else{
                               
                            }
                         }                       
                      });//ajax
                    }

                    
                  });               

              }
              $('#productContent'+regId).html(html);
              
            }//addCourse

    </script>

@endsection