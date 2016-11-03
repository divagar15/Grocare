@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Add Courier Login User</h1></div>



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

                          
                            <form id="courier-form" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                                <!--   <div class="form-group">
                                    <label class="col-sm-3 control-label">Courier</label>
                                    <div class="col-sm-4">

                                     <select name="courier" id="courier" class="chosen-select" required>
                                        <option value=""></option>                                        
                                        @if(isset($couriers) && !empty($couriers))
                                          @foreach($couriers as $courier)
                                            <option value="{{$courier->id}}">{{ucwords($courier->name)}}</option>
                                          @endforeach 
                                        @endif
                                      </select> 
                                    </div>
                                  </div> -->



                                <!--   <div class="form-group">
                                    <label class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-4">
                                    <input type="text" autocomplete="off" name="name" id="name" required class="form-control"> 
                                    </div>
                                  </div> -->

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Login ID</label>
                                    <div class="col-sm-4">
                                    <input type="hidden" name="courier" id="courier" value="4">
                                    <input type="text" autocomplete="off" name="email" id="email" required class="form-control"> 
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-4">
                                    <input type="password" autocomplete="off" name="password" id="password" required class="form-control"> 
                                    </div>
                                  </div>


                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">Confirm Password</label>
                                    <div class="col-sm-4">
                                    <input type="password" autocomplete="off" name="cpassword" id="cpassword" equalTo="#password" required class="form-control"> 
                                    </div>
                                  </div>


                                  <div class="form-group" style="margin-top:20px;">
                                    <div class="col-sm-7 col-sm-offset-4">
                                      <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                      <a href="{{URL::to('admin/courier-login/list')}}" class="btn btn-default btn-sm">Cancel</a>
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

            $('#courier-form').validate();
       

        });   

    </script>

@endsection