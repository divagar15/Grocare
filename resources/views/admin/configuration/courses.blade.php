@extends('admin.layout.tpl')
@section('content')     	
<div class="page-header"><h1>Courses</h1></div>



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
                          
                            <form id="course-form" class="form-horizontal" role="form" method="post">

                                  <table class="table table-striped no-margn" style="margin-bottom:20px;">
                                      <thead>
                                        <tr>
                                          <th width="90%">Course Name</th>
                                          <th width="10%">Action</th>
                                        </tr>
                                      </thead>

                                      <tbody id="courseDetails">

                                        @if(isset($courses) && !empty($courses))

                                          @foreach($courses as $course)

                                            <tr>
                                              <td>
                                                <input type="hidden" name="cid_{{$i}}" value="{{$course->id}}" />
                                                <input type="text" value="{{$course->course_name}}" class="form-control" required name="course_{{$i}}" id="course_{{$i}}" autocomplete="off" />
                                              </td>
                                              <td><a href="javascript:void(0)" data-type="normal" data-id="{{$course->id}}" class="removeCourse btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>
                                            </tr>

                                            <?php $i++; ?>

                                          @endforeach

                                        @endif

                                      </tbody>

                                      <tfoot>
                                        <tr>
                                          <td colspan="4">
                                            <button type="button" class="btn btn-xs btn-info" id="addCourse">
                                              <i class="fa fa-plus"></i> Add Course
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
                                      <a href="{{URL::to('admin/configuration/courses')}}" class="btn btn-default btn-sm">Cancel</a>
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

            $('#course-form').validate();


            $("#addCourse").on("click", function () {
              var counter = Number($('#counter').val());
              counter = counter+1;

              var html = '<tr id="row_'+counter+'">';

              html += '<td><input type="hidden" name="cid_'+counter+'" value="0" /><input type="text" class="form-control" required name="course_'+counter+'" id="course_'+counter+'" autocomplete="off" /></td>';
              html += '<td><a href="javascript:void(0)" data-type="dynamic" data-id="'+counter+'" class="removeCourse btn btn-xs btn-danger"><i class="fa fa-close"></i> Remove </a></td>';

              html += '</tr>';

              $('#courseDetails').append(html);
              $("#counter").val(counter);
              

            });

            $(document).on('click',".removeCourse",function (){   
                var type = $(this).data('type');
                var id   = $(this).data('id');
                if(type=='dynamic') { 
                  $("#row_"+id).remove();
                } else {
                  var confirmMsg = confirm('Are you sure want to remove this course?');
                  if(confirmMsg) {
                    window.location = "{{URL::to('admin/configuration/delete-course')}}/"+id;
                  }
                }
            });

        });
    </script>

@endsection