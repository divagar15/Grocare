@extends('admin.layout.tpl')
@section('customCss')
<link rel="stylesheet" href="{{URL::asset('public/admin/css/plugins/datatables/jquery.dataTables.css')}}" />
<style>
.textaddress{
text-align: left !important;
}
#form-four .form-group, #form-primary .form-group {
	padding: 10px 0;
}
.noteimage{
	 height: 80px;
    width: 80px;
}
</style>
@endsection
@section('content')     	
<div class="page-header"><h1>Customer Details</h1></div>
@if(Session::has('true_msg')) 
                        <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                        {{Session::get('true_msg')}}
                        </div> <!-- /.alert -->
                    @endif 
					 @if(Session::has('error_msg'))
                        <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                        {{Session::get('error_msg')}}
                        </div> <!-- /.alert -->
                    @endif
<div class="warper container-fluid">
<div class="row">
                <div class="col-sm-12">


                <form class="form form-horizontal" id="category-form" style="margin-bottom: 0;" method="post" novalidate="novalidate">

                      <div class="form-group">
                        <label class="col-md-2 control-label"  >Name</label>
                        <span class="col-md-3 control-label showvals"  >{{$customers->name}}</span>
                      </div>
					  <div class="form-group">
                        <label class="col-md-2 control-label" >Email ID</label>
                        <span class="col-md-3 control-label" >{{$customers->email}}</span>
                      </div>
                      <div class="form-group">
                        <label class="col-md-2 control-label" >Gender</label>
                        <span class="col-md-3 control-label" >@if(strtolower($customers->gender)=='male') {{"Male"}} @elseif(strtolower($customers->gender)=='female') {{"Female"}} @endif</span>
                      </div>
					   <div class="form-group">
                        <label class="col-md-2 control-label" >Age</label>
                        <span class="col-md-3 control-label" >{{$customers->age}}</span>
                      </div>
					  @if(!empty($customers->facebook_url)) 
					   <div class="form-group">
                        <label class="col-md-2 control-label" >Facebook Url</label>
                        <span class="col-md-3 control-label" >{{$customers->facebook_url}}</span>
                      </div>
					  @endif
					  @if(!empty($customers->twitter_url))
					    <div class="form-group">
                        <label class="col-md-2 control-label" >Twitter Url</label>
                        <span class="col-md-3 control-label" >{{$customers->twitter_url}}</span>
                      </div>
					    @endif
					  @if(!empty($customers->google_url))
					   <div class="form-group">
                        <label class="col-md-2 control-label" >Google Url</label>
                        <span class="col-md-3 control-label" >{{$customers->google_url}}</span>
                      </div>
					    @endif
					  @if(!empty($customers->website_url))
					  <div class="form-group">
                        <label class="col-md-2 control-label" >Website Url</label>
                        <span class="col-md-3 control-label" >{{$customers->website_url}}</span>
                      </div>
					   @endif
					   
<?php $i=1; 
$cust_prd="";?>
							
@if(isset($orderdetails) && !empty($orderdetails))
@foreach($orderdetails as $vals)
@if($i==1)
<?php
$getProducts = App\Helper\AdminHelper::getCustomerProduct($vals->id); 
$cust_prd="";
if(!empty($getProducts))
{
foreach($getProducts as $prdval)
{
	 $cust_prd.=	$prdval->product_name.',';
}	
}
 $cust_prd=rtrim($cust_prd,',');
if($cust_prd!="")
{
$i++;	
}
?> 
 
@endif
@endforeach
@endif	   
					<div class="form-group">
                        <label class="col-md-2 control-label" >Last Order Item</label>
                        <span class="col-md-3 control-label" >{{$cust_prd}}</span>
                      </div>
					  
						<div class="form-group">
							<label class="col-md-2 control-label" >Rating</label>
							<div class="col-md-3 control-label">
							<div id="star" data-rating="{{$customers->rating}}" ></div> <input type="hidden" name="rating_star" id="rating_star"  value="{{$customers->rating}}"  />
							</div>
						</div>
					 
 	<div class="row">
            
            	<div class="col-md-6">
                	<div class="panel panel-primary">
                      <div class="panel-heading">Billing Address</div>
                      <div class="panel-body"> 
<?php 
$t=0;
?>
@if(isset($cus_add) && !empty($cus_add))
@foreach($cus_add as $bilvals)
@if($t==0 && $bilvals->status==1)
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Name</label>
                        <span class="col-md-8 control-label textaddress"    >{{$bilvals->name}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Address 1</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->address}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Address 2</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->address2}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >City</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->city}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >State</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->state}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Country</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->country}}</span>
	</div>
		 <div class="form-group">
                        <label class="col-md-3 control-label" >Post Code</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->postcode}}</span>
	</div>
 
<?php 
$t++;
?>
@endif	
@endforeach
@endif	
	  
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                	<div class="panel panel-danger">
                      <div class="panel-heading">Shipping Address</div>
                      <div class="panel-body">
                      <?php 
$t=0;
?>
@if(isset($cus_add) && !empty($cus_add))
@foreach($cus_add as $bilvals)
@if($t==0 && $bilvals->status==2)
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Name</label>
                        <span class="col-md-8 control-label textaddress"    >{{$bilvals->name}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Address 1</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->address}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Address 2</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->address2}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >City</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->city}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >State</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->state}}</span>
	</div>
	 <div class="form-group">
                        <label class="col-md-3 control-label" >Country</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->country}}</span>
	</div>
		 <div class="form-group">
                        <label class="col-md-3 control-label" >Post Code</label>
                        <span class="col-md-8 control-label textaddress" >{{$bilvals->postcode}}</span>
	</div>
 
<?php 
$t++;
?>
@endif	
@endforeach
@endif	
                      </div>
                    </div>
                </div>
            
            </div>			 
					 
					</form> 
<h2>Note Details</h2> 
<div class="row">
<div class="col-sm-12">
<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal"  data-target="#form-primary">
                            Add Note
                                            </a>
											<div id="notediv" style="display:none">Tamil g</div>
</div>
 
	 <div class="modal fade" id="form-primary" role="dialog">
             		 <div class="modal-dialog">
               
                    <div class="modal-content">
                      <form class="form-horizontal group-border-dashed"   autocomplete="off" enctype="multipart/form-data"  id="assign-form" method="POST">
						 
                      <div class="modal-header" style="padding-bottom: 0px !important;">
                        <h3>Add Note</h3>
                      
                      </div>
                      <div class="modal-body form" style="padding-top: 0px !important;">
						
						<div class="form-group">							
							<div class="col-sm-12" style="padding:3; margin:3;">
								<label class="col-sm-2">Title &nbsp;</label>
								<div class="col-sm-8">
                                      <input type="text" name="note_title" id="note_title" class="form-control" value="" required >
                                    </div>
							</div>
						</div>
						<div class="form-group">							
						<div class="col-sm-12" style="padding:3; margin:3;">
						<label class="col-sm-2">Image &nbsp;</label>
						<div class="col-sm-8">
						<input type="file" name="note_image" id="note_image" class="form-control" value="">
						</div>
						</div>
						</div>
						<div class="form-group">							
						<div class="col-sm-12" style="padding:3; margin:3;">
						<label class="col-sm-2">Description &nbsp;</label>
						<div class="col-sm-8">
						<textarea name="note_description" id="note_description" class="form-control" ></textarea>
						</div>
						</div>
						</div>						
						 
                      </div>
                      <div class="modal-footer" style="margin-top: 0px !important;">
                        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-flat">Proceed</button>
                      </div>						
                      </form>
                    </div>
					</div>	
						</div>
						
						<div class="responsive-table">
                            <div class="scrollable-area">
                             <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable1">
                                <thead>
                                  <tr>
                                    <th>S.No.</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                    
                                  </tr>
                                </thead>
                            <tbody>
 <?php $i=1; ?>							
							@if(isset($cus_note) && !empty($cus_note))

                                  @foreach($cus_note as $vals)
 
                                    <tr>
                                      <td>{{$i}}</td>
                                      <td id="title_{{$i}}">{{$vals->note_title}}</td>
                                      <td>
									  @if($vals->note_image!="")
									  <img class="noteimage"  src="{{URL::asset('public/uploads/note_image/'.$vals->note_image.'')}}" />
									  @endif
									  </td>
                                      <td id="description_{{$i}}">{{$vals->note_description}}</td>
                                      
								 <td>
								 <span style="display:none" id="noteid_{{$i}}">{{$vals->id}}</span>
								
                                          <a class="btn btn-xs btn-info showeditnote"  href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="modal" data-val="{{$i}}"  data-target="#form-primaryedit" >Edit</a> 
                                          <a class="btn btn-xs btn-danger" href="javascript:void(0)" onclick="deleteNote({{$vals->id}});">Delete</a>
                                      </td>
								
                                    </tr>

                                    <?php $i++; ?>

                                  @endforeach

                                @endif
							</tbody>
                              </table>
                            </div>
                          </div>
						  
						<!-- note edit start -->  
						<div class="modal fade" id="form-primaryedit" role="dialog">
             		 <div class="modal-dialog">
               
                    <div class="modal-content">
                      <form class="form-horizontal group-border-dashed"   autocomplete="off" enctype="multipart/form-data"  id="assign-formedit" method="POST">
						 <input type="hidden" name="note_id" id="note_id" class="form-control"  >
                      <div class="modal-header" style="padding-bottom: 0px !important;">
                        <h3>Edit Note</h3>
                      
                      </div>
                      <div class="modal-body form" style="padding-top: 0px !important;">
						
						<div class="form-group">							
							<div class="col-sm-12" style="padding:3; margin:3;">
								<label class="col-sm-2">Title &nbsp;</label>
								<div class="col-sm-8">
                                      <input type="text" name="note_title" id="editnote_title" class="form-control" value="" required >
                                    </div>
							</div>
						</div>
						<div class="form-group">							
						<div class="col-sm-12" style="padding:3; margin:3;">
						<label class="col-sm-2">Image &nbsp;</label>
						<div class="col-sm-8">
						<input type="file" name="note_image"   class="form-control" value="">
						</div>
						</div>
						</div>
						<div class="form-group">							
						<div class="col-sm-12" style="padding:3; margin:3;">
						<label class="col-sm-2">Description &nbsp;</label>
						<div class="col-sm-8">
						<textarea name="note_description" id="editnote_description" class="form-control" ></textarea>
						</div>
						</div>
						</div>						
						 
                      </div>
                      <div class="modal-footer" style="margin-top: 0px !important;">
                        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-flat">Proceed</button>
                      </div>						
                      </form>
                    </div>
					</div>	
						</div>
						<!-- note edit end -->  
						  
</div>
					
                     
					 <h2>Order Details</h2> 
					  <div class="row">
                        <div class="col-sm-12">
                          <div class="responsive-table">
                            <div class="scrollable-area">
                             <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="basic-datatable">
                                <thead>
                                  <tr>
                                    <th>S.No.</th>
                                    <th>Invoice No</th>
                                    <th>Ship To</th>
                                    <th>Grand Total</th>
                                    <th>Discount Amount</th>
                                    
                                    <th>Product</th>
                                  </tr>
                                </thead>
                            <tbody>
 <?php $i=1; ?>							
							@if(isset($orderdetails) && !empty($orderdetails))

                                  @foreach($orderdetails as $vals)
								  
<?php
$getProducts = App\Helper\AdminHelper::getCustomerProduct($vals->id); 
$cust_prd="";
if(!empty($getProducts))
{
	foreach($getProducts as $prdval)
	{
	$cust_prd.=	$prdval->product_name.',';
	}	
}
$cust_prd=rtrim($cust_prd,',');
?> 
                                    <tr>
                                      <td>{{$i}}</td>
                                      <td>{{$vals->invoice_no}}</td>
									  <td>
{{ucwords($vals->shipping_name)}},
{{$vals->shipping_address1}},@if(!empty($vals->shipping_address2)){{$vals->shipping_address2}},@endif
{{$vals->shipping_city}},{{$vals->shipping_state}},@if(!empty($vals->shipping_country) && isset($countries[$vals->shipping_country])){{$countries[$vals->shipping_country]}},@endif{{$vals->shipping_zip}}
@if(!empty($vals->shipping_email))<br/><strong>E:</strong><i> {{$vals->shipping_email}}</i>@endif
@if(!empty($vals->shipping_phone))<br/><strong>Ph:</strong><i> {{$vals->shipping_phone}}</i>@endif
</td>
                                      <td>Rs.{{$vals->grand_total_inr}}</td>
                                      <td>Rs.{{$vals->discount_amount_inr}}</td>
                                    
                                      <td>{{$cust_prd}}</td>
                                       
								
                                    </tr>

                                    <?php $i++; ?>

                                  @endforeach

                                @endif
							</tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
					  
                      
                      
					  
                  
                </div>
              </div>
</div>

@endsection
@section('customJs')
<script type='text/javascript' src="{{URL::asset('public/admin/js/jquery.raty.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('public/admin/js/jquery.niftymodals/js/jquery.modalEffects.js')}}"></script>  
<script type="text/javascript">
 
 
$(document).on("click",".showeditnote",function(e){
var noteid = $(this).data('val');
var title = $("#title_"+noteid).html();
$("#editnote_title").val(title);

var description = $("#description_"+noteid).html();
$("#editnote_description").val(description);

var note_id = $("#noteid_"+noteid).html();
$("#note_id").val(note_id);

});
			// This code does NOT belong the plugin. See the example code at the bottom of this page.
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-194992347-3']);
			_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
			
			
				$(function() {
var	paths="{{URL::asset('public/admin/doc/img/')}}";

var	pathc="{{URL::asset('public/admin/doc/imgc/')}}";
 
$('#star').raty({
 // cancel: true,
  path: pathc,
   score: function() {
				        return $(this).attr('data-rating');
				    }
}); 
$("#star > img").click(function(){
				var score =$('#star').raty('score');
			$('#rating_star').val(score);	//record clicked
			var rating=0; 
				if($('#rating_star').val()=="")
				{
					var rating=0;
				}
				else
				{
					var rating=$('#rating_star').val()
				}
var user_id="{{$customers->id}}";
$.ajax({
   type: "POST",
   url: "{{URL::to('/admin/reports/customer/postrating')}}",
  data: {  
   "_token": "{{ csrf_token() }}",
   "rating": rating,
   "user_id": user_id
	},
   cache: false,
   success: function(result)
      { 	
	  }
      });		
			});
			
	}); 
		</script>
		
<script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/DT_bootstrap.js')}}"></script>
  <script src="{{URL::asset('public/admin/js/plugins/datatables/jquery.dataTables-conf.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/DevExpressChartJS/dx.chartjs.js')}}"></script>

<script src="{{URL::asset('public/admin/js/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js')}}"></script>
<script src="{{URL::asset('public/admin/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.js')}}"></script>
   
<script>
function deleteNote(id){
			var confirmMsg = confirm('Are you sure want to delete this note?');
            if(confirmMsg) {
              window.location.href = "{{URL::to('admin/reports/customer/deleteNote/')}}/"+id;
            }
		}


$(document).ready(function (){
	
$('#assign-form').validate();
$('#assign-formedit').validate();

    $('#basic-datatable1').dataTable();
  $('.md-trigger').modalEffects();
$('.wysihtml').wysihtml5();

});
</script>
@endsection