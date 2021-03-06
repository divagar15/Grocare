<?php 
namespace App\Http\Controllers\Front;

require_once "mailchimp/MailChimp.php";
use \DrewM\MailChimp\MailChimp;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\OrderDetails;
use App\Models\Testimonials;
use App\Models\ContactUs;
use App\Models\Newsletter;
use App\Models\Diagnosis;
use App\Models\Products;
use App\Models\Media;
use App\Models\Cms;
use App\Models\CmsBulletins;
use App\Models\CmsAboutTaglinks;
use App\Models\CmsFaq;
use App\Models\CmsDistribute;
use App\Models\Scholarship;
use App\Models\Seo;
use App\Helper\FrontHelper;
use Response;
use Input;
use Hash;
use Mail;
use Session;
use Config;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
		$getCountryCode = FrontHelper::getGeoLocation();
    	$this->activeCountry = Session::get('active_country');
    	$this->activeCurrency = Session::get('active_currency');
    	$this->activeRegion = Session::get('active_region');
    	$this->activeSymbol = Session::get('active_symbol');
		$this->siteTitle = Config::get('custom.siteTitle');
		$this->mailChimpApikey = Config::get('custom.mailChimpApikey');
		$this->mailChimpSubscribeId = Config::get('custom.mailChimpSubscribeId');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		// $simpleProducts = Products::where('product_type',1)->where('delete_status',0)->where('did_you_know','!=','')->orderByRaw('RAND()')->take(5)->get();
		$simpleProducts = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id','products.did_you_know','products.product_slug','products.name')->where('products.product_type','=',1)->where('products.delete_status','=',0)->where('did_you_know','!=','')
                       ->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.enable',1)->orderByRaw('RAND()')->take(5)->get();
					   
	   $diagnosisDetail = Diagnosis::select('diagnosis.id','diagnosis.did_you_know','diagnosis.diagnosis_slug','diagnosis.name')->where('delete_status',0)->where('did_you_know','!=','')->orderByRaw('RAND()')->take(5)->get();
		
		$testimonials = Testimonials::where('type','!=',2)->where('delete_status',0)->where('homepage_view',1)->orderByRaw('RAND()')->take(10)->get();
		$orderDetails=OrderDetails::select('id')->where('delete_status',0)->whereNotIn('order_status',['7','8'])->orWhereNull('order_status')->where('delete_status',0)->get();
		$happy_customers=16023;
		$count=count($orderDetails);
		if($count>0){
			$happy_customers=$happy_customers+$count;
		}
		
		$diagnosis = Diagnosis::select('id','name','diagnosis_slug')->where('delete_status',0)->get();
		
		$pageTitle = "Grocare - Self Diagnosis & Personal Healthcare - Ayurvedic Treatment";

		$meta_description = "Grocare is successful in arriving with result oriented solutions to overcome  distress to lead a healthier life.It is very keen in rendering cure and relief with its cost effective medicines.";
		
		$seoDetails = Seo::where('page',0)->first();
		
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}

		

		
		return view('front/index')->with(array('pageTitle'=>$pageTitle,'meta_description'=>$meta_description,'testimonials'=>$testimonials,'diagnosis'=>$diagnosis,'simpleProducts'=>$simpleProducts,'happy_customers'=>$happy_customers,'diagnosisDetail'=>$diagnosisDetail));
	}
	public function searchAilment(Request $request)
	{
		if($request->isMethod('post')){
			$search_ailment=trim($request->search_ailment);
			if($search_ailment){
				return redirect('/diagnose/'.$search_ailment);				
			}else{				
				return redirect('/');
			}
		}
	}

	public function searchResults(Request $request)
	{				
		$query = $request->q;
		if(empty($query)) {
			return redirect('/');
		}
		return view('front/searchResults')->with(array('query'=>$query));
	}

	public function changeLocation($string)
	{
		if(!empty($string)) {
			if(Session::get('active_country')!=strtoupper($string)) {
				Session::forget('order_id');
			}
			Session::put('active_country',strtoupper($string));
		}

		//return redirect('/');
		return Redirect::back();
	}

	public function aboutUs()
	{
		$pageTitle = "About Us | ".$this->siteTitle;
		$meta_description = '';
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$aboutus = $Cms['aboutus'];				
		
		$seoDetails = Seo::where('page',1)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
		
		$enval = json_decode(stripslashes($aboutus));
		$cms_eval = json_decode($enval);	
		$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get();	
		return view('front/aboutUs')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval,'cms_taglinks'=>$cms_taglinks,'meta_description'=>$meta_description));
	}

	public function selfDiagnosis()
	{
		return view('front/selfDiagnosis');
	}

	public function selfDiagnosisDetail()
	{
		return view('front/selfDiagnosisDetail');
	}

	public function products()
	{
		return view('front/products');
	}

	public function productDetail()
	{
		return view('front/productDetail');
	}

	public function medicineKit()
	{
		return view('front/medicineKit');
	}

	public function store()
	{
return redirect('products');
		//return view('front/store');
	}

	public function contact(Request $request){
		if($request->isMethod('post')){
			$name=trim($request->name);
			$email=trim($request->email);
			$phone=trim($request->phone);
			$message=trim($request->message);
			$diagnosis_id=trim($request->diagnosis_id);
			$diagnosis_name=trim($request->diagnosis_name);
			$contactUs=new ContactUs;
			$contactUs->name=$name;
			$contactUs->email=$email;
			$contactUs->phone=$phone;
			$contactUs->message=$message;
			$contactUs->diagnosis_id=$diagnosis_id;
			$contactUs->diagnosis_name=$diagnosis_name;
			
			$data['email'] = $email;
			$data['phone'] = $phone;
			$data['name'] = $name;
			$data['messages'] = $message;
			$data['diagnosis_id'] = $diagnosis_id;
			$data['diagnosis_name'] = $diagnosis_name;
			
			$subject = 'Grocare Contact Details';
			
			
			if($contactUs->save()){
$mail=Mail::send('emails.contactUs', $data, function($message)
					use($email,$name,$subject){
				$message->from($email, ucfirst($name));
				$message->replyTo($email, ucfirst($name));
				$message->to('info@grocare.com')->subject($subject);
				//$message->to('divagar.umm@gmail.com')->subject($subject);
			});
				return redirect('/contact')->with('success_msg','Your Contact Successfully Saved.');
			}else{
				return redirect('/contact')->with('error_msg','Your Contact Save has Error!, Please Try Again!');
			}
		}else{		
			$pageTitle = "Contact | ".$this->siteTitle;	
			$meta_description = '';
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$contactlist = $Cms['contactlist'];				
			
			$enval = json_decode(stripslashes($contactlist));
			$cms_eval = json_decode($enval);		

		$seoDetails = Seo::where('page',3)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}			
			
			return view('front/contact')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval,'meta_description'=>$meta_description));
		}
	}
	public function media(){
		$media=Media::where('delete_status',0)->get();
		$pageTitle = "Media | ".$this->siteTitle;	
		return view('front.media')->with(array('pageTitle'=>$pageTitle,'media'=>$media));
	}

	public function newsletter(Request $request){
		if($request->isMethod('post')){

			$email=trim($request->email);
			$name=trim($request->name);

			$mailChimp = new MailChimp($this->mailChimpApikey);

			$list_id = $this->mailChimpSubscribeId;

			$result = $mailChimp->post("lists/".$list_id."/members", [
			                'email_address' => $email,
			                'status'        => 'pending',
			                'merge_fields' => ['FNAME'=>$name, 'LNAME'=>''],
			            ]);

			$status = $result['status'];

			if($status=='pending') {

				
				$newsletter=new Newsletter;
				$newsletter->name=trim($request->name);
				$newsletter->email=$email;
				$newsletter->save();

				return "success";

			} else {
				return "error";
			}
			
			/*$data['email'] = $email;
			$data['name'] = trim($request->name);
			$subject = 'Welcome to the World of Grocare India!';
			$mail=Mail::send('emails.newsletter', $data, function($message)
					use($email,$subject){
				$message->from('no-reply@grocare.com', 'GROCARE');
				$message->to($email)->subject($subject);
			});*/
			
			// if($mail){
				//return redirect('/');
			// }
		}
	}


	public function cart()
	{
		return view('front/cart');
	}

	public function checkout()
	{
		return view('front/checkout');
	}
	public function cancellationRefundPolicy()
	{
		$pageTitle = "Cancellation & Refund Policy | ".$this->siteTitle;	
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);
		return view('front/cancellationRefundPolicy')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function faq()
	{
		$pageTitle = "FAQ | ".$this->siteTitle;	
		$meta_description = '';
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$faq = $Cms['faq'];
		$enval = json_decode(stripslashes($faq));
		$cms_eval = json_decode($enval);
		$CmsFaq = CmsFaq::where('active_status',1)->get();
		$seoDetails = Seo::where('page',6)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
		return view('front/faq')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval,'CmsFaq'=>$CmsFaq,'meta_description'=>$meta_description));
	}
	public function customerPolicy()
	{
		$pageTitle = "Customer Policy | ".$this->siteTitle;	
		
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);	
		
		return view('front/customerPolicy')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function shippingDeliveryPolicy()
	{
		$pageTitle = "Shipping & Delivery | ".$this->siteTitle;	
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];	
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);
		
		return view('front/shippingDeliveryPolicy')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function disclaimer()
	{
		$pageTitle = "Disclaimer | ".$this->siteTitle;	
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);
		return view('front/disclaimer')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function termsConditions()
	{
		$pageTitle = "Terms Of Use | ".$this->siteTitle;
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);
		return view('front/termsConditions')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function privacyPolicy()
	{
		$pageTitle = "Privacy Policy | ".$this->siteTitle;
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$supportlist = $Cms['supportlist'];
		$enval = json_decode(stripslashes($supportlist));
		$cms_eval = json_decode($enval);
		return view('front/privacyPolicy')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval));
	}
	public function technicalBulletins()
	{
//Log::info('Invalid name');
		$pageTitle = "Technical Bulletins | ".$this->siteTitle;	
		$meta_description = '';
		$cms_bulletins = CmsBulletins::where('active_status',1)->get();
		//$count = count($cms_bulletins);
		
		$seoDetails = Seo::where('page',5)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}	
		
		return view('front/technicalBulletins')->with(array('pageTitle'=>$pageTitle,'cms_bulletins'=>$cms_bulletins,'meta_description'=>$meta_description));
	}
	public function distribute()
	{
		$pageTitle = "Distribute | ".$this->siteTitle;	
		$meta_description = '';
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$distribute = $Cms['distribute'];
		$enval = json_decode(stripslashes($distribute));
		$cms_eval = json_decode($enval);
		$CmsDistribute = CmsDistribute::where('active_status',1)->get();
		
		$seoDetails = Seo::where('page',4)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}	
		
		return view('front/distribute')->with(array('pageTitle'=>$pageTitle,'cms_eval'=>$cms_eval,'CmsDistribute'=>$CmsDistribute,'meta_description'=>$meta_description));
	}


	public function scholarship()
	{
		$pageTitle = "Scholarship | ".$this->siteTitle;
		$meta_description = '';
		
		$seoDetails = Seo::where('page',9)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
		return view('front/scholarship')->with(array('pageTitle'=>$pageTitle, 'meta_description'=>$meta_description));
	}


	public function scholarshipApplication(Request $request){
		if($request->isMethod('post')){

			
			$firstname=trim($request->firstname);
			$lastname=trim($request->lastname);
			$email=trim($request->email);
			$phone=trim($request->phone);
			$college=trim($request->college);
			$major=trim($request->major);
			$description=trim($request->description);


				
			$scholarship=new Scholarship;
			$scholarship->firstname=$firstname;
			$scholarship->lastname=$lastname;
			$scholarship->email=$email;
			$scholarship->phone=$phone;
			$scholarship->college=$college;
			$scholarship->major=$major;
			$scholarship->description=$description;
			
			if($scholarship->save()) {

				$name = $firstname.' '.$lastname;

				$data['firstname'] = $firstname;
				$data['lastname'] = $lastname;
				$data['email'] = $email;
				$data['phone'] = $phone;
				$data['college'] = $college;
				$data['major'] = $major;
				$data['description'] = $description;
				
				$subject = 'Scholarship Application';
				$mail=Mail::send('emails.scholarship', $data, function($message)
						use($email,$name,$subject){
					$message->from($email, ucfirst($name));
					$message->replyTo($email, ucfirst($name));
					$message->to('scholarships@grocare.com')->subject($subject);
					//$message->to('divagar.umm@gmail.com')->subject($subject);
				});

				return redirect('/scholarship')->with('success_msg','Application submitted successfully.');

			} else {
				return redirect('/scholarship')->with('success_msg','Please try later');
			}

			/*$data['email'] = $email;
			$data['name'] = trim($request->name);
			$subject = 'Welcome to the World of Grocare India!';
			$mail=Mail::send('emails.newsletter', $data, function($message)
					use($email,$subject){
				$message->from('no-reply@grocare.com', 'GROCARE');
				$message->to($email)->subject($subject);
			});*/
			
			// if($mail){
				//return redirect('/');
			// }
		} else {
			return redirect('/scholarship');
		}
	}

}
