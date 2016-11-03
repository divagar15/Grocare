<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderDetails;
use App\Models\Products;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;

use DB;
use Hash;
use Auth;
use Session;

class AdminController extends Controller {
	
	public function __construct(){
		DB::enableQueryLog();
	}
    
    public function login(Request $request){
    	if(isset(Auth::user()->id)){
    		if(Auth::user()->user_type==1) { 
            	return redirect('admin/dashboard');
            } elseif(Auth::user()->user_type==3) {
            	return redirect('admin/order-tracking');
            }
        }
        if($request->isMethod('post')){

        	$email = $request->email;
			$password = $request->password;

			$checkUser = User::where('email',$email)->where('user_type','=',1)->where('delete_status',0)->first();
			if(isset($checkUser->id)) {
				if(Hash::check($password, $checkUser->password)) {
					Session::put('uid',$checkUser->id);
					Session::put('user_name',$checkUser->name);
					Session::put('user_email',$checkUser->email);
					Session::put('user_phone',$checkUser->mobile);
					Session::put('user_type',$checkUser->user_type);

					return redirect('admin/dashboard');
					
				} else {
					return redirect('admin')->with('error_msg','Invalid Password');
				}
			} else {
				return redirect('admin')->with('error_msg','Invalid E-Mail Address');
			}
            
        } else {
            return view('admin.index'); 
        }
    }


    public function dashboard(){
		
		$fromDate=date('Y-m-01');
		$toDate  =date('Y-m-d');

		$fromTime = strtotime($fromDate);
	    $toTime = strtotime($toDate);
	    $datediff = $toTime - $fromTime;
	    $difference = abs(floor(($datediff/(60*60*24))))+1;
		
		$todayOrderDetails=OrderDetails::selectRaw('SUM(grand_total_inr) AS today_sales_amount')->whereRaw('DATE(order_placed_date)="'.date('Y-m-d').'"')->where('delete_status',0)->whereNotIn('order_status',['1','7','8'])->first();
		
		$monthOrderDetails=OrderDetails::selectRaw('SUM(grand_total_inr) AS month_sales_amount')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('delete_status',0)->whereNotIn('order_status',['1','7','8'])->first();
		
		$productDetails=OrderDetails::selectRaw('oid')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('delete_status',0)->whereNotIn('order_status',['7','8'])->get();
		
		$yearOrderDetails=OrderDetails::selectRaw('SUM(grand_total_inr) AS year_sales_amount')->whereRaw('YEAR(order_placed_date)="'.date('Y').'"')->where('delete_status',0)->whereNotIn('order_status',['1','7','8'])->first();
		
		$newOrders=OrderDetails::selectRaw('COUNT(id) AS new_orders')->where('payment_type','!=',0)->where('delete_status',0)->whereNotNull('order_status')->whereIn('order_status',['0','1','2'])->first();

		$processingOrders=OrderDetails::selectRaw('COUNT(id) AS processing_orders')->where('payment_type','!=',0)->where('delete_status',0)->whereIn('order_status',['3'])->first();
		
		$shipOrders=OrderDetails::selectRaw('COUNT(id) AS ship_orders')->where('payment_type','!=',0)->where('delete_status',0)->whereIn('order_status',['4'])->first();
		
		$registeredCustomers = OrderDetails::select('id')->where('delete_status',0)->where('fkcustomer_id','!=',0)->distinct()->count(["fkcustomer_id"]); 

		$guestCustomers = OrderDetails::select('order_details.id')->join('order_address as t2', 'order_details.id', '=', 't2.order_id')
						  ->where('order_details.delete_status',0)->where('order_details.fkcustomer_id','=',0)->distinct()->count(["t2.billing_email"]); 
			
		$oid=array();
		foreach($productDetails as $key=>$val){			
			$oid[]=$val->oid;
		}
		$orderProducts=array();$orderBundleProducts=array();
		if(count($oid)>0){
			$orderProducts=OrderProducts::select('product_id','product_name')->whereIn('oid',$oid)->get();
			$orderBundleProducts=OrderBundleProducts::select('product_id','product_name')->whereIn('oid',$oid)->get();			
		}

		//echo '<pre>'; print_r($orderProducts); echo '</pre>'; die();
		
		$orderProducts_id=array();
		foreach($orderProducts as $key=>$val){	
			$orderProducts_id[]=$val->product_name;
		}
		
		$orderBundleProducts_id=array();
		foreach($orderBundleProducts as $key=>$val){	
			$orderProducts_id[]=$val->product_name;
		}
		
		$today_sales_amount='0';
		if(count($todayOrderDetails)>0){
			if(!empty($todayOrderDetails->today_sales_amount)) {
				$today_sales_amount=round($todayOrderDetails->today_sales_amount);			
			}
		}
		$month_sales_amount='0';
		if(count($monthOrderDetails)>0){
			if(!empty($monthOrderDetails->month_sales_amount)) {
				$month_sales_amount=round($monthOrderDetails->month_sales_amount);	
			}		
		}
		$averageSales = round($month_sales_amount/$difference);
		$year_sales_amount='0';
		if(count($yearOrderDetails)>0){
			if(!empty($yearOrderDetails->year_sales_amount)) {
				$year_sales_amount=round($yearOrderDetails->year_sales_amount);	
			}		
		}
		$new_orders='';
		if(count($newOrders)>0){
			$new_orders=$newOrders->new_orders;			
		}
		$processing_orders='';
		if(count($processingOrders)>0){
			$processing_orders=$processingOrders->processing_orders;			
		}
		$ship_orders='';
		if(count($shipOrders)>0){
			$ship_orders=$shipOrders->ship_orders;			
		}
		$top_selling_product='';$product_name='';
		$c = array_count_values($orderProducts_id); 
		$topSellingProductName = '';
		$topSellingProductCount = '';
	//	return $c;
		if($c){
			$top_selling_product = array_search(max($c), $c);		
			$topSellingProductName = $top_selling_product;
			if(isset($c[$top_selling_product])) {
				$topSellingProductCount = $c[$top_selling_product];
			}
		}
		/*if($top_selling_product){
			$productName=Products::select('name')->where('id',$top_selling_product)->first();	
			$product_name=$productName->name;
		}*/


		
    	return view('admin/dashboard')->with(array('today_sales_amount'=>$today_sales_amount,'month_sales_amount'=>$month_sales_amount,
    		'year_sales_amount'=>$year_sales_amount,'processing_orders'=>$processing_orders,'ship_orders'=>$ship_orders,
    		'new_orders'=>$new_orders,'registeredCustomers'=>$registeredCustomers,'guestCustomers'=>$guestCustomers,
    		'topSellingProductName'=>$topSellingProductName,'topSellingProductCount'=>$topSellingProductCount,
    		'averageSales'=>$averageSales));
    }

    public function logout(){
    	Session::flush();
        Auth::logout();
        return redirect('/admin');
    }
}