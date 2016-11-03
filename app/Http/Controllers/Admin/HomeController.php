<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\Courses;
use App\Models\Couriers;
use App\Models\Products;
use App\Models\ProductCourse;
use App\Models\ProductRegion;
use App\Models\ProductImages;
use App\Models\ProductTiles;
use App\Models\ProductBundle;
use App\Models\OrderSession;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Models\OrderDetails;
use App\Models\OrderAddress;
use App\Models\OrderNotes;
use App\Models\OrderTracking;
use App\Models\Newsletter;
use App\Models\ContactUs;
use App\Models\Media;
use App\Models\TrackingIds;
use Session;
use Response;
use Config;
use Hash;
use Mail;
use DB;

class HomeController extends Controller {

	public function __construct()
    {
    	$this->orderStatus = Config::get('custom.orderStatus');
    	$this->countries = Config::get('custom.country');
    	$this->paymentType = Config::get('custom.paymentType');
    	$this->currencies = Config::get('custom.currency');
    }

    public function newsletter(){
		$newsletter=Newsletter::where('delete_status',0)->get();		
		return view('admin.newsletter')->with('newsletter',$newsletter);
	}
    public function media(){
		$media=Media::where('delete_status',0)->get();		
		return view('admin.media.media')->with(array('media'=>$media));
	}
    public function addMedia(Request $request){
		
		if($request->isMethod('post')){
			$title=trim($request->title);
			$link=trim($request->link);
			$media=new Media;
			$media->title=$title;
			$media->link=$link;
			if($media->save()){
				return redirect('/admin/edit-media/'.$media->id)->with('success_msg','Media Successfully Updated.');
			}else{
				return redirect('/admin/edit-media/'.$media->id)->with('error_msg','Media Update has Failed.');
			}
		}else{
			return view('admin.media.addMedia');
		}
	}
    public function editMedia(Request $request,$id){
		
		if($request->isMethod('post')){
			$title=trim($request->title);
			$link=trim($request->link);
			$media=Media::find($id);
			$media->title=$title;
			$media->link=$link;
			if($media->save()){
				return redirect('/admin/edit-media/'.$id)->with('success_msg','Media Successfully Updated.');
			}else{
				return redirect('/admin/edit-media/'.$id)->with('error_msg','Media Update has Failed.');
			}
		}else{
			$media=Media::where('id',$id)->where('delete_status',0)->first();		
			return view('admin.media.editMedia')->with(array('media'=>$media));		
		}
	}
    public function deleteMedia($id){
		$media=Media::find($id);
		$media->delete_status=1;
		if($media->save()){
			return redirect('/admin/media')->with('success_msg','Media Successfully Deleted.');
		}else{
			return redirect('/admin/media')->with('error_msg','Media Delete has Failed.');
		}
	}
    public function deleteNewsletter($id){
		$newsletter=Newsletter::find($id);
		$newsletter->delete_status=1;
		if($newsletter->save()){
			return redirect('/admin/newsletter')->with('success_msg','Newsletter Successfully Deleted.');
		}else{
			return redirect('/admin/newsletter')->with('error_msg','Newsletter Delete has Failed.');
		}
	}
    public function contactUs(){
		$contactUs=ContactUs::where('delete_status',0)->get();		
		return view('admin.contactUs')->with('contactUs',$contactUs);
	}
    public function deleteContactUs($id){
		$contactUs=ContactUs::find($id);
		$contactUs->delete_status=1;
		if($contactUs->save()){
			return redirect('/admin/contact-us')->with('success_msg','Contact Successfully Deleted.');
		}else{
			return redirect('/admin/contact-us')->with('error_msg','Contact Delete has Failed.');
		}
	}

	public function trackingIds(Request $request){
		if($request->isMethod('post')){
			$trackingID=TrackingIds::find(1);
			$trackingID->whole_site=trim($request->whole_site);
			$trackingID->thankyou_page=trim($request->thankyou_page);
			$trackingID->save();
			return redirect('/admin/tracking-ids')->with('success_msg','Tracking ID Successfully Updated.');
		} else {
			$trackingId=TrackingIds::first();		
			return view('admin.trackingId')->with('trackingId',$trackingId);
		}
	}
	
}
