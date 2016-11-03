<?php namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderDetails;
use App\Models\UserAddress;
use App\Models\OrderSession;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Models\OrderAddress;
use App\Models\BankDetails;
use App\Models\Couriers;
use App\Models\OrderTracking;
use App\Models\OrderNotes;

use Redirect;
use Response;
use Input;
use Hash;
use Mail;
use Session;
use Config;
use Auth;

class ProfileController extends Controller {

	public function __construct()
    {
    	$this->countries = Config::get('custom.country');
    	if(!isset(Auth::user()->id)) {
    		//echo ; die();
    		return Redirect::to('login')->send();
    	}
    	$this->siteTitle = Config::get('custom.siteTitle');
		
    }
	
	public function myProfile()
	{
		
		$id=Auth::user()->id;
		$user=User::where('id',$id)->where('delete_status',0)->first();
		$orderDetails=OrderDetails::where('fkcustomer_id',$id)->where('delete_status',0)->get();
		$pageTitle = "Profile | ".$this->siteTitle;
		return view('front.profile.my-profile')->with(array('pageTitle'=>$pageTitle,'orderDetails'=>$orderDetails,'user'=>$user));
	}	
	public function editProfile(Request $request){	
			
		$id=Auth::user()->id;	
		if($request->isMethod('post')){
			
			// $email=trim($request->email);
			$display_name=trim($request->display_name);
			$password=trim($request->password);
			// $user=User::where('email',$email)->where('delete_status',0)->first();
			// if($user){
				// return redirect('/register')->with('error_msg',' Email Id Already Exists! Please try again.');
			// }
			$user = User::find($id);
			$user->name=trim($request->username);
			// $user->email=$email;
			if($password){
				$user->password=Hash::make($password);
				$user->password_text=$password;
			}
			$user->display_name=$display_name;
			$user->gender=trim($request->gender);
			$user->age=trim($request->age);
			$user->facebook_url=trim($request->facebook_url);
			$user->twitter_url=trim($request->twitter_url);
			$user->google_url=trim($request->google_url);
			$user->website_url=trim($request->website_url);
			$user->user_type=2;
			
				$path = 'public/uploads/user/'.$id.'/';
				if (!file_exists($path)) {
						mkdir($path, 0755);
				 } 
				$image = $request->file('image');
				if($image!='') { 
					$newname = str_random(5);
					$ext = $image->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;

					if($image->move($path, $filename)){
						copy($path . $filename, $path . $newFilename);	
						$user->image=$newFilename;
					}
					
				}
				
				if($user->save()){				
					return redirect('/edit-profile')->with('success_msg','Your Profile Successfully Updated.');
				}else{
					return redirect('/edit-profile')->with('error_msg','Your Profile Updation has Failed.');
				}
			}else{
				$pageTitle = "Profile | ".$this->siteTitle;
				$user=User::where('id',$id)->where('delete_status',0)->first();
				return view('front/profile/edit-profile')->with(array('pageTitle'=>$pageTitle,'user'=>$user));			
			}
	}
	public function editAddress(Request $request){	
				
		$id=Auth::user()->id;
		if($request->isMethod('post')){
			$address='';
			if($request->billing_submitBtn=='billing'){				
				$billingAddress=UserAddress::where('user_id',$id)->where('status',1)->where('delete_status',0)->first();
				if($billingAddress){
					$userAddress=UserAddress::find($billingAddress->id);
				}else{
					$userAddress=new UserAddress;
				}
				$userAddress->user_id=$id;
				$userAddress->name=trim($request->billing_name);
				$userAddress->address=trim($request->billing_address_1);
				$userAddress->address2=trim($request->billing_address_2);
				$userAddress->city=trim($request->billing_city);
				$userAddress->state=trim($request->billing_state);
				$userAddress->country=trim($request->billing_country);
				$userAddress->postcode=trim($request->billing_postcode);
				$userAddress->status=1;
				$address='Billing';
				
			}else if($request->shipping_submitBtn=='shipping'){
				$shippingAddress=UserAddress::where('user_id',$id)->where('status',2)->where('delete_status',0)->first();
				if($shippingAddress){
					$userAddress=UserAddress::find($shippingAddress->id);
				}else{
					$userAddress=new UserAddress;
				}
				$userAddress->user_id=$id;
				$userAddress->name=trim($request->shipping_name);
				$userAddress->address=trim($request->shipping_address_1);
				$userAddress->address2=trim($request->shipping_address_2);
				$userAddress->city=trim($request->shipping_city);
				$userAddress->state=trim($request->shipping_state);
				$userAddress->country=trim($request->shipping_country);
				$userAddress->postcode=trim($request->shipping_postcode);
				$userAddress->status=2;
				$address='Shipping';
			}
			// echo '<pre>';print_r(Input::all());echo '</pre>';exit;
		
				
				if($userAddress->save()){				
					return redirect('/edit-address')->with('success_msg','Your '.$address.' Address Successfully Updated.');
				}else{									
					return redirect('/edit-address')->with('error_msg','Your '.$address.' Your Address Updation has Failed.');
				}
			}else{
				$billingAddress=UserAddress::where('user_id',$id)->where('status',1)->where('delete_status',0)->first();
				$shippingAddress=UserAddress::where('user_id',$id)->where('status',2)->where('delete_status',0)->first();
				$user=User::where('id',$id)->where('delete_status',0)->first();
				$pageTitle = "Address Details | ".$this->siteTitle;
				return view('front/profile/edit-address')->with(array('pageTitle'=>$pageTitle,'user'=>$user,'billingAddress'=>$billingAddress,'shippingAddress'=>$shippingAddress,'countries'=>$this->countries));			
			}
	}
	
    public function orderDetails($id)
    {		
		
		$user_id=Auth::user()->id;	
		$user=User::where('id',$user_id)->where('delete_status',0)->first();
        $orderDetail = OrderDetails::where('id',$id)->first();
        $orderSession = OrderSession::where('id',$orderDetail->oid)->first();
        $orderProducts = OrderProducts::where('oid',$orderDetail->oid)->orderBy('product_name','ASC')->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->orderBy('product_name','ASC')->get();
        $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
        $orderTracking_data = OrderTracking::where('order_id',$orderDetail->id)->get();
        $orderNotes = OrderNotes::where('order_id',$orderDetail->id)->get();
		$couriers='';
		if($orderAddress){
			$couriers=Couriers::where('id',$orderAddress->selected_courier)->first();
		}
        $bankDetails = BankDetails::where('id',1)->first();
		$orderTracking=array();
		foreach($orderTracking_data as $key=>$val){
			$orderTracking[]=$val->tracking_number;
		}
		$pageTitle = "Order Details | ".$this->siteTitle;
		return view('front/profile/order_details')->with(array('pageTitle'=>$pageTitle,'user'=>$user,'orderDetail'=>$orderDetail,'orderSession'=>$orderSession,
                   'orderProducts'=>$orderProducts,'orderBundleProducts'=>$orderBundleProducts,'orderAddress'=>$orderAddress,
                   'bankDetails'=>$bankDetails,'countries'=>$this->countries,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes)); 

    }
    public function cancelOrder($id)
    {
		
			
		$orderDetails=OrderDetails::where('id',$id)->whereIn('order_status',['0','1','2'])->where('delete_status',0)->first();
		if($orderDetails){
			$order=OrderDetails::find($id);
			$order->order_status=5;
			$order->cancelled_by=2;
			if($order->save()){
				return redirect('/my-profile')->with('success_msg','Your Order Successfully Cancelled.');
			}else{
				return redirect('/my-profile')->with('error_msg','Your Order Cancellation has Failed.');
			}
			
		}else{
			return redirect('/my-profile')->with('error_msg','Your Order Cancellation has Failed.');
		}

    } 
	public function shoppingCart(){
		
		
		$orderSession = '';
        $orderProducts = array();
		$user=array();
        if(Session::has('order_id')) {
			$user_id=Auth::user()->id;	
			$user=User::where('id',$user_id)->where('delete_status',0)->first();
            $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
            $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
        }
        $pageTitle = "Shopping Cart | ".$this->siteTitle;
        return view('front/profile/shopping_cart')->with(array('pageTitle'=>$pageTitle,'orderSession'=>$orderSession, 'orderProducts'=>$orderProducts,'user'=>$user));
	}


}