@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Update Testimonial</h1></div>



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

                          
                            <form id="testimonial-form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Testimonial Type</label>
                                    <div class="col-sm-4">
                                      <input type="hidden" value="{{$testimonial->type}}" name="testi_type">
                                    <select name="testi_type2" id="testi_type" class="chosen-select" required disabled>                                        
                                        <option value="1" {{($testimonial->type==1)?'selected':''}}>Text</option>
                                        <option value="2" {{($testimonial->type==2)?'selected':''}}>Audio</option> 
                                        <option value="3" {{($testimonial->type==3)?'selected':''}}>Social Media</option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group ">
                                    <label class="col-sm-3 control-label">Testimonial For</label>
                                    <div class="col-sm-4">
                                      <select name="testi_for" id="testi_for" class="chosen-select" required >
                                        <option value=""></option>
                                        <option value="0" {{($testimonial->testi_for==0)?'selected':''}}>Website</option>
                                        @if(isset($diagnosis) && !empty($diagnosis))
                                          @foreach($diagnosis as $diag)
                                            <option value="{{$diag->id}}" {{($diag->id==$testimonial->testi_for)?'selected':''}}>{{ucwords($diag->name)}}</option>
                                          @endforeach
                                        @endif  
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-7">
                                    <input type="text" autocomplete="off" name="testi_name" id="testi_name" required value="{{$testimonial->testi_from}}" class="form-control"> 
                                    </div>
                                  </div>
									
									
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label" for="homepage_view">Homepage View</label>
                                    <div class="col-sm-7">
                                    <input type="checkbox" autocomplete="off" name="homepage_view" id="homepage_view" class="" @if($testimonial->homepage_view==1){{'checked'}}@endif  value="1"> 
                                    </div>
                                  </div>

                                   <div class="form-group">
                                    <label class="col-sm-3 control-label" for="homepage_view">Do not display on disease page</label>
                                    <div class="col-sm-7">
                                    <input type="checkbox" autocomplete="off" name="disease_view" id="disease_view" class="" @if($testimonial->disease_view==1){{'checked'}}@endif value="1"> 
                                    </div>
                                  </div>
                                  

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Testimonial Description</label>
                                    <div class="col-sm-9">
                                      @if($testimonial->type==1)
                                      <div id="testi_text" class="testi_types">
                                        <textarea name="testi_content" id="testi_content" required class="ckeditor">{{$testimonial->testi_content}}</textarea>
                                      </div>
                                      @elseif($testimonial->type==2)                                      
                                      <div id="testi_audio" class="testi_types">
                                        <p>Current Audio Clip</p>
                                        <audio controls>
                                         <source src="{{URL::to('public/uploads/testimonials').'/'.$testimonial->testi_content}}" type='audio/mpeg; codecs="mp3"'>
                                        </audio>
                                        <p>Update</p>
                                        <input type="hidden" value="{{$testimonial->testi_content}}" name="testi_content">
                                        <input type="file"   class="form-control" name="testi_audioclip" id="testi_audioclip" value="" >
                                        <div class="col-md-12 alert-info">
                                         Upload .mp3 only
                                        </div>
                                      </div>
                                      @elseif($testimonial->type==3)                                      
                                      <div id="testi_image" class="testi_types">
                                        <p>Current Image</p>
                                        <img src="{{URL::to('public/uploads/testimonials').'/'.$testimonial->testi_content}}">
                                        <p>Update</p>
                                        <input type="hidden" value="{{$testimonial->testi_content}}" name="testi_content">
                                        <input type="file"   class="form-control" name="testi_img" id="testi_img" value="" >
                                        <div class="col-md-12 alert-info">
                                         Upload .jpg .png only
                                        </div>
                                      </div>
                                      @endif

                                      <div id="testi_text" class="testi_types" style="display:none;">
                                        <textarea name="testi_content1" id="testi_content" required class="ckeditor">{{$testimonial->testi_content}}</textarea>
                                      </div>
                                      <div id="testi_audio" class="testi_types" style="display:none;">
                                        <input type="file"   class="form-control" name="testi_audioclip1" id="testi_audioclip" value="" >
                                        <div class="col-md-12 alert-info">
                                         Upload .mp3 only
                                        </div>
                                      </div>
                                      <div id="testi_image" class="testi_types" style="display:none;">
                                        <input type="file"   class="form-control" name="testi_img1" id="testi_img" value="" >
                                        <div class="col-md-12 alert-info">
                                         Upload .jpg .png only
                                        </div>
                                      </div>

                                    </div>
                                  </div>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Submit</button>
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

            $('#testimonial-form').validate();

            $("#testi_type").on("change", function () {
              var type = $(this).val();
              if(type==1){
                $(".testi_types").hide();
                $("#testi_text").show();
              }
              else if(type==2){
                $(".testi_types").hide();
                $("#testi_audio").show();
              }
            });           

        });//ready    

    </script>

@endsection