<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title></title>
<!-- 	<link href='http://fonts.googleapis.com/css?family=Lato:700' rel='stylesheet' type='text/css'>
 -->
	<style>

		 body,td {
		 	font-family: 'Helvetica';
			font-size:15px;
		}		
				
		.page-break {
			page-break-after: always;
		}

	</style>
</head>
<body>
<div class="warper container-fluid"   style="width:85%;margin:0 auto; padding-top:-42px;">


            
            
                
                <div class="row" style="padding-top:-15px;">
				 <table width="100%">
				 
					<tr>
						<td style="vertical-align:top;margin:0px;text-align:right;"> 
							<span style="margin:0px;"><a href="" style="color:#333;font-size:9px !important;">Contents : @if($orderAddress->shipping_country=='IN') Herbal Medicines @else Dietary Supplements @endif</a></span>
							<br/>
						</td>
					</tr>					
					<tr>
						<td style="vertical-align:top;margin:0px;text-align:left;"> 
							<div class="col-md-6 shipping-address"   style="color:#222; font-size:11.5px; padding-top:-12px;">
							 <p> To:
							 <strong> {{strtoupper($orderAddress->shipping_name)}}</strong></p>
							 <p style="padding-top:-13px;">  {{ucfirst($orderAddress->shipping_address1)}} @if($orderAddress->shipping_address2!=''){{",".ucfirst($orderAddress->shipping_address2).","}}@endif</p>
							 <p style="padding-top:-13px;"> <strong> {{ucfirst($orderAddress->shipping_city)}} - {{$orderAddress->shipping_zip}}</strong>, {{ucfirst($orderAddress->shipping_state)}}</p>
							 @if($orderAddress->shipping_country!='IN') <p style="padding-top:-13px;">  {{ucfirst($countries[$orderAddress->shipping_country])}}</p> @endif
							 <p style="padding-top:-13px; font-size:8px;"><i><strong>Phone: </strong> {{$orderAddress->shipping_phone}}</i>
 @if($orderAddress->shipping_country!='IN') 
&nbsp;|&nbsp;<i><strong>Email: </strong> {{$orderAddress->shipping_email}}</i>
@endif
							 </p>
							
<!-- 							 <p style="padding-top:-12px; font-size:9px;"><i><strong>Email : </strong> {{$orderAddress->shipping_email}}</i></p>
 -->							 
							</div>
                        </td>
                
              </tr>
					<tr>
						<td style="text-align:left;">
							<div class="col-md-6 grocare-address"   style="color:#111; font-size:7.5px; margin-top:-20px;">
								<p><a href="" style="color:#000:">From: </a>Grocare India - "Shivalik", Plot No. 14, Gangadham Market Yard,</p> 
								<p style="padding-top:-6px;">Pune 411037, India | Tel : 98221 00031</p>
<!-- 								<p style="padding-top:-6px;">	 </p>
 -->						       
							</div>
						</td>
					</tr>
					<tr>
						<td style="text-align:right;font-size:9px;"><p style="padding-top:-34px;"><strong>www.grocare.com</strong></p></td>
					</tr>
			  </table>
			  
                    </div>
                </div>
</body>
</html>