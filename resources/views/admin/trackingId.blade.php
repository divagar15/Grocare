@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Tracking ID</h1></div>



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

                          
                            <form id="tracking-form" class="form-horizontal" role="form" method="post">

                                  
                                  

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">For Website all pages</label>
                                    <div class="col-sm-9">
                                        <textarea name="whole_site" id="whole_site" style="height:200px;" class="  form-control">{{$trackingId->whole_site}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">For Thank you pages</label>
                                    <div class="col-sm-9">
                                        <textarea name="thankyou_page" id="thankyou_page"  style="height:200px;" class="  form-control">{{$trackingId->thankyou_page}}</textarea>
                                    </div>
                                  </div>

                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-5">
                                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                      <a href="{{URL::to('admin/tracking-ids')}}" class="btn btn-default btn-sm">Cancel</a>
                                    </div>
                                  </div>

                          </form>
                        </div>
                    </div>
                 </div>
            
            </div>

@endsection

@section('customJs')
    
    <script src="{{URL::asset('public/admin/js/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
    <script src="{{URL::asset('public/admin/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.js')}}"></script>

    <script src="{{URL::asset('public/admin/js/plugins/ckeditor/ckeditor.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            $('#tracking-form').validate();

        });//ready    

    </script>

@endsection