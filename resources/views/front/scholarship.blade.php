@extends('front.layout.tpl')
@section('customCss')
    <style>
	   
	    h1, h2, h3, h4, h5, h6 { font-family:'lato-light';font-weight: 600;}
        input, textarea {border-radius:0 !important;}
		h1 {letter-spacing: 1px; color: #333;font-size:30px;text-transform: capitalize; margin: 0px 115px;text-align:center;}
		h3 {font-size: 24px;text-transform: capitalize;}
		label { display: inline-block;font-size: 13px; font-family: 'lato-light';letter-spacing: 1px;color: #555;
         margin-bottom: 10px; cursor: pointer; font-weight: 700; text-transform: uppercase;}
        .scholar {padding: 15px 0;}
		.submit {border-radius: 5px;background: #555551;font-size: 15px;display: inline-block;margin: 2px 0;
		 border: none;font-weight: 700;}
		.footer, .info-btn, .links, .links p a, .submit, .submit:hover {color: #fff;}
		.info-btn, .submit {padding: 17px;width: 99%;text-decoration: none;	font-family:'lato-light';
		 text-align: center;}
		.submit:hover {opacity: .9;cursor: pointer;}
        input.error, textarea.error, select.error {background: #fff !important;border: 2px solid #e42c3e !important;}
		.banner-content {margin-top:20px;}
	    .banner img { display: block; margin: 0 auto;}
	    .banner{margin-top:10px;}
	
        @media only screen and (max-width: 750px){
			.submit {font-size: 25px;}
			h1{margin: 0px 24px !important;}
			.banner img {height:auto !important;}
		}
    </style>

@endsection

@section('content')


<div class="container">



    <div class="row col-sm-12 col-xs-12 " style="margin-top:10px;">

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
        <div class="row col-sm-12 col-xs-12 scholar">
             <h1> <i> the grocare Scholarship Programme </i> </h1>
	    </div>
      
    </div>
	
	<div class="container no-padding">
    <div class="banner">
        <img src="{{URL::asset('public/front/images/scholarship.jpg')}}"/>
	</div>
</div>
	
	<div class="row col-sm-12 col-xs-12 banner-content">
	   <p>Grocare is a manufacturer and exporter of herbal (ayurvedic) medicines for chronic lifestyle diseases. Grocare prides itself in creating products that offer natural treatments for a wide range of diseases which can be found on <a href="www.grocare.com/diagnose" target="_blank">www.grocare.com/diagnose</a> </p>
	   <p>The benefit of Grocare Products over any other pharma company is that they are completely herbal thereby they have no side effects, they are research driven and are only launched after a tedious process that takes years for approval. The key point about Grocare is that it aims to establish a direct contact with the patient which other companies do not do. This way Grocare can directly oversee the benefit of the medicines on the patients and guide them towards a healthy lifestyle. </p>
	   <p> Grocare works with various doctors, vaidyas and institutions to innovate and produce the most effective and affordable treatments for chronic lifestyle diseases. </p>
	   <p> To encourage the awareness of Ayurveda, Grocare is presenting The Grocare Scholarship Programme to exceptional young students who genuinely strive at making a difference in the healthcare segment.</p>
	   <p>The Grocare Scholarship Programme is offering a scholarship in the amount of <strong>$1000</strong> to graduate students interested in medical related research with an interest in Natural medicine / Ayurveda. The student should also demonstrate involvement with community service and academic achievement; defray the costs of obtaining their education. </p>
	</div>
	 <div class="row col-sm-12 col-xs-12">
	     <div class="col-sm-8 col-xs-12">
		    <div class="sub">
            <h3>Criteria</h3>
			<p>- Applicant should be current graduate student. Incoming, Full-time and part-time students are eligible, but you need to be currently enrolled to apply. To verify enrollment, please email your transcript to scholarships@grocare.com with your name in the subject line. </p>
			<p> Incoming students can submit their acceptance letter, documents and their future college attendance.</p>
			<p> - Interested in medical related research with an interest in Ayurveda.</p>
			<p> - Any previously awarded scholarships or monetary prizes have no bearing on this scholarship award.</p>
			</div>
			
			<div class="sub">
			<h3> How to Apply</h3>
			<p>- Applicants will need to submit the scholarship application on this page. </p>
			<p> - The application includes an essay of 500 to 700 words. In addition, applicants need to email their transcript AND acceptance letter (in English) to scholarships@grocare.com with their name in subject line.</p>
			<p>- A letter of recommendation (in English) from a professor belonging to previous university or college. This should be emailed directly to us from the professors email id on scholarships@grocare.com </p>
			<p>- Scan of Student ID and Photographs should also to be emailed </p>
			<p> - Any additional certificates will be given extra credit</p>
			</div>
			
			<div class="sub">
            <h3> Deadline for Submission</h3>
			<p> The annual scholarship is open for submission every year from July 15th. Deadline for application submission for this year is <strong>December 15th, 2016</strong>.</p>
			</div>
			
			<div class="sub">
            <h3> Award Announcement</h3>
			<p>The total award amount is <strong>$1000</strong>. The winner will be announced by January 7th, 2017 on this page. Additionally, scholarships winners will be contacted directly by email at the time of the announcement. The scholarship will be sent directly to the student/ schools financial aid office.</p>
			</div>

			<div class="sub"> 
			 <h3> Selection Process and Notification</h3>
			 <p> Selection is based solely on the essays submitted. The essay should be structured in 3 sections. What are the challenges in research and care of patients with lifestyle disease? </p>
			 <h4> How is Ayurveda / Natural Medicine beneficial to Lifestyle diseases?</h4>
			 <p> What has been/ is going to be your contribution to helping people with Lifestyle disease? Additional: Your experience with Ayurveda & Lifestyle diseases: Essays will be reviewed by a panel of independent judges.</p>
			</div>

			<div class="sub"> 
			 <h3> Additional Details</h3>
			 <p>The scholarship funds awarded are to be used for tuition and books only. Once awarded, a bank transfer will be done to the students bank account/ Schools financial aid office. Scholarship is non-renewable and is only good for one semester cycle. Recipients are only eligible to receive the scholarship once during their lifetime. </p>
			</div>

			<div class="sub"> 
			 <h3>Mission Statement </h3>
			 <p> Grocare’s mission is to help peoplecure theirlifestyle diseases by providing Ayurvedic medicines for the same. Grocare is the only pharma company in the world which provides such treatments that need to be consumed only for a particular time. Other medicines need to consume throughout life and do not provide a complete cure. Grocare aims at curing diseases within a span of 6 months to 1 year and helping improve the quality of patient’s life. </p>
			</div>

			<div class="sub"> 
			 <h3>Questions </h3>
			 <p> Contact: Amit Goel </p>
			 <p> Email: <a href="mailto:scholarships@grocare.com" target="_blank">scholarships@grocare.com</a> </p>
			</div>
			<div class="sub"> 
			 <h3>Privacy </h3>
			 <p> The website Administrator may use your personal information only for the purpose of awarding the scholarship to the winner, offering the Scholarship, and any reporting responsibility to taxing authorities and your participation in our Scholarship constitutes your consent on the matter.</p>
			</div>
			
			
		 </div>
	     <div class="col-sm-4 col-xs-12">
            <div class="scholar-register">
			    <h3> <i>the grocare Scholarship Application </i> </h3>
			   <form id="scholarform"class="form-horizontal" action="{{URL::to('scholarship-application')}}" method="POST" role="form" >
				    <div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label> FIRST NAME * </label>
						    <input class="form-control" type="text" name="firstname"  required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label> LAST NAME * </label>
						    <input class="form-control" type="text" name="lastname"  required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label> EMAIL ADDRESS* </label>
						    <input class="form-control" type="email" name="email"  required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label> PHONE NUMBER*</label>
						    <input class="form-control" type="text" name="phone" number="true" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label>WHICH UNIVERSITY OR COLLEGE DO YOU CURRENTLY ATTEND* </label>
						    <input class="form-control" type="text" name="college"  required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label> WHAT IS YOUR FOCUS OF STUDY(OR MAJOR)?* </label>
						    <input class="form-control" type="text" name="major"  required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12 col-xs-12 ">
						    <label>TELL US WHY YOU DESERVE THIS SCHOLARSHIP?</label>
						    <textarea class="form-control" type="text" name="description" rows="6" cols="30"> </textarea>
							<!--<div> The essay portion of your scholarship should be approximately 250-400 words.</div>-->
						</div>
					</div>
					
				    <div class="form-group">
					    <div class="col-sm-12 col-xs-12">
					        <button type="submit" class="submit"> Submit</button>
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
	$(document).ready(function() {
		$('#scholarform').validate();
	});
	</script>

@endsection
