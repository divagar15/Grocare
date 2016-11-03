<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserNote;
use App\Models\OrderDetails;
use App\Models\Products;
use App\Models\OrderProducts;
use App\Models\Couriers;
use App\Models\OrderBundleProducts;
use App\Helper\AdminHelper;
use DB;
use Hash;
use Auth;
use Session;
use Config;
use Excel;
use Redirect;
 
class ReportsController extends Controller {
	
	public function __construct(){
    	$this->orderStatus = Config::get('custom.orderStatus');
    	$this->countries = Config::get('custom.country');
    	$this->paymentType = Config::get('custom.paymentType');
    	$this->currencies = Config::get('custom.currency');
	}

	public function order(Request $request){
		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d');

		$type = $request->type;

		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
			$type = $request->ptype;
		}

		if($type=='today') {
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d');
		} else if($type=='month') {
			$fromDate = date('Y-m-01');
			$toDate = date('Y-m-d');
		} else if($type=='year') {
			$fromDate = date('Y-01-01');
			$toDate = date('Y-m-d');
		}

		 $fromTime = strtotime($fromDate);
	     $toTime = strtotime($toDate);
	     $datediff = $toTime - $fromTime;
	     $difference = abs(floor(($datediff/(60*60*24))))+1;

	     //echo $difference; die();

	     $totalSales = 0;
	     $salesAmount = 0;
		 $shippingCharge = 0;
	     $internationalOrders = 0;

	     $chartResponse = array();
		 $chartResponseInternational = array();

	     if($difference>31) {


	     	$orderDetails = OrderDetails::selectRaw('SUM(grand_total_inr) AS sales_amount,SUM(shipping_charge_inr) AS shipping_charge,count(id) AS total_orders,order_placed_date')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->groupBy(DB::raw('YEAR(order_placed_date), MONTH(order_placed_date)'))->get();
	    // 	return $orderDetails;


			     if(isset($orderDetails) && !empty($orderDetails)) {
			     	foreach($orderDetails as $detail) {
			     		$day = date('M-y',strtotime($detail->order_placed_date));
			     		$oDate = date('m',strtotime($detail->order_placed_date));

			     		$totalInternationOrders = OrderDetails::whereRaw('MONTH(order_placed_date) = "'.$oDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->where('payment_type',3)->count();
			     	//	return $totalInternationOrders;
			     		$internationalOrders += $totalInternationOrders;
			     		$sales = round($detail->sales_amount-$detail->shipping_charge);
			     		$shipping_charge = round($detail->shipping_charge);
			     		$orderPerDay = $detail->total_orders;

			     		$m = date('m',strtotime($detail->order_placed_date));
			     		$y = date('Y',strtotime($detail->order_placed_date));

			     		$numberOfDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);

			     		//return $numberOfDays;

			     		$monthAverageSale = round(round($detail->sales_amount)/$numberOfDays);


			     		$salesAmount += round($detail->sales_amount);
			     		$shippingCharge += $shipping_charge;
			     		$totalSales += round($detail->sales_amount);

			     		$chartResponse[] = array('year'=>$day,'sales'=>$sales, 'orderPerDay'=>$orderPerDay,'totalInternationOrders'=>$totalInternationOrders,'monthAverageSale'=>$monthAverageSale);
			     	}
			     }

				    $ts1 = strtotime($fromDate);
					$ts2 = strtotime($toDate);

					$year1 = date('Y', $ts1);
					$year2 = date('Y', $ts2);

					$month1 = date('m', $ts1);
					$month2 = date('m', $ts2);

					$monthdiff = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;

			     	$averageSales = round($salesAmount/$monthdiff);

	     } else {

			     $orderDetails = OrderDetails::selectRaw('SUM(grand_total_inr) AS sales_amount,SUM(shipping_charge_inr) AS shipping_charge,count(id) AS total_orders,order_placed_date')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->groupBy(DB::raw('CAST(order_placed_date AS DATE)'))->get();

			     if(isset($orderDetails) && !empty($orderDetails)) {
			     	foreach($orderDetails as $detail) {
			     		$day = date('d-M',strtotime($detail->order_placed_date));
			     		$oDate = date('Y-m-d',strtotime($detail->order_placed_date));

			     		$totalInternationOrders = OrderDetails::whereRaw('DATE(order_placed_date) = "'.$oDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->where('payment_type',3)->count();

			     		$internationalOrders += $totalInternationOrders;
			     		$sales = round($detail->sales_amount-$detail->shipping_charge);
			     		$shipping_charge = round($detail->shipping_charge);
			     		$orderPerDay = $detail->total_orders;


			     		$salesAmount += round($detail->sales_amount);
			     		$shippingCharge += $shipping_charge;
			     		$totalSales += round($detail->sales_amount);

			     		$chartResponse[] = array('year'=>$day,'sales'=>$sales, 'orderPerDay'=>$orderPerDay,'totalInternationOrders'=>$totalInternationOrders);
			     	}
			     }

			     $averageSales = round($salesAmount/$difference);

			 }

	    //return $chartResponse;

/*

		

		$chartResponse[] = array('year'=>'01-Mar','sales'=>1500, 'shippingCharge'=>150);
		$chartResponse[] = array('year'=>'02-Mar','sales'=>12500, 'shippingCharge'=>1520);
		$chartResponse[] = array('year'=>'03-Mar','sales'=>12500, 'shippingCharge'=>1550);
		$chartResponse[] = array('year'=>'04-Mar','sales'=>14500, 'shippingCharge'=>2150);
		$chartResponse[] = array('year'=>'05-Mar','sales'=>19500, 'shippingCharge'=>3150);
		$chartResponse[] = array('year'=>'06-Mar','sales'=>15600, 'shippingCharge'=>5150);*/



		$response = json_encode($chartResponse);
		
		$orderSource=OrderDetails::join('order_session as t2', 'order_details.oid', '=', 't2.id')->select('order_details.id','t2.referrer')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])->get();
		
		$sourceArray = array();
		
		if(isset($orderSource) && !empty($orderSource)) {
			foreach($orderSource as $source) {
				if(empty($source->referrer)) {
					if(isset($sourceArray['unknown'])) {
					  $sourceArray['unknown'] = $sourceArray['unknown']+1;
				  } else {
					  $sourceArray['unknown'] = 1;
				  }
				  continue;
				}
				$pieces = parse_url($source->referrer);
			    $domain = isset($pieces['host']) ? $pieces['host'] : '';
			    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
				  //return $regs['domain'];
				  if(isset($sourceArray[$regs['domain']])) {
					  $sourceArray[$regs['domain']] = $sourceArray[$regs['domain']]+1;
				  } else {
					  $sourceArray[$regs['domain']] = 1;
				  }
			    } else {
					if(isset($sourceArray['unknown'])) {
					  $sourceArray['unknown'] = $sourceArray['unknown']+1;
				  } else {
					  $sourceArray['unknown'] = 1;
				  }
				}
			}
		}
		
	//	return $sourceArray;

		$orderPlaced=OrderDetails::select('id')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->count();

		$simpleProduct=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->selectRaw('SUM(t2.product_qty) AS qty')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',1)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

		$bundleProduct=OrderDetails::join('order_bundle_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->selectRaw('SUM(t2.product_qty) AS qty')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

		$itemsPurchased = 0;

		if(count($simpleProduct)>0){
			if(!empty($simpleProduct->qty)) {
				$itemsPurchased=round($simpleProduct->qty);	
			}		
		}

		if(count($bundleProduct)>0){
			if(!empty($bundleProduct->qty)) {
				$itemsPurchased+=round($bundleProduct->qty);	
			}		
		}
		
	//	echo '<pre>'; print_r($response); echo '</pre>'; die;

		return view('admin.reports.order')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate,'response'=>$response,
			   'salesAmount'=>$salesAmount,'shippingCharge'=>$shippingCharge,'averageSales'=>$averageSales,
			   'orderPlaced'=>$orderPlaced,'itemsPurchased'=>$itemsPurchased,'difference'=>$difference, 'source'=>$sourceArray));
	}
	
	public function filterCountry(Request $request){
		//return $request->all();
		$fromDate = date('Y-m-d',strtotime($request->from_date));
		$toDate = date('Y-m-d',strtotime($request->to_date));
		
		$country =  $request->country;
		
		 $fromTime = strtotime($fromDate);
	     $toTime = strtotime($toDate);
	     $datediff = $toTime - $fromTime;
	     $difference = abs(floor(($datediff/(60*60*24))))+1;

	     //echo $difference; die();
		 //return $country;

	     $totalSales = 0;
	     $salesAmount = 0;
		 $shippingCharge = 0;
	     $internationalOrders = 0;

	     $chartResponse = array();
		 
		 if($difference>31) {


	     	$orderDetails = OrderDetails::join('order_session as t1', 't1.id', '=', 'order_details.oid')->selectRaw('SUM(order_details.grand_total_inr) AS sales_amount,SUM(order_details.shipping_charge_inr) AS shipping_charge,count(order_details.id) AS total_orders,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])->where('t1.order_country',$country)->groupBy(DB::raw('YEAR(order_details.order_placed_date), MONTH(order_details.order_placed_date)'))->get();
	     //	return $orderDetails;


			     if(isset($orderDetails) && !empty($orderDetails)) {
			     	foreach($orderDetails as $detail) {
			     		$day = date('M-y',strtotime($detail->order_placed_date));
			     		$oDate = date('m',strtotime($detail->order_placed_date));

			     		$totalInternationOrders = OrderDetails::whereRaw('MONTH(order_placed_date) = "'.$oDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->where('payment_type',3)->count();
			     	//	return $totalInternationOrders;
			     		$internationalOrders += $totalInternationOrders;
			     		$sales = round($detail->sales_amount-$detail->shipping_charge);
			     		$shipping_charge = round($detail->shipping_charge);
			     		$orderPerDay = $detail->total_orders;

			     		$m = date('m',strtotime($detail->order_placed_date));
			     		$y = date('Y',strtotime($detail->order_placed_date));

			     		$numberOfDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);

			     		//return $numberOfDays;

			     		$monthAverageSale = round(round($detail->sales_amount)/$numberOfDays);


			     		$salesAmount += round($detail->sales_amount);
			     		$shippingCharge += $shipping_charge;
			     		$totalSales += round($detail->sales_amount);

			     		$chartResponse[] = array('year'=>$day,'sales'=>$sales, 'orderPerDay'=>$orderPerDay,'totalInternationOrders'=>$orderPerDay,'monthAverageSale'=>$monthAverageSale);
			     	}
			     }

				    $ts1 = strtotime($fromDate);
					$ts2 = strtotime($toDate);

					$year1 = date('Y', $ts1);
					$year2 = date('Y', $ts2);

					$month1 = date('m', $ts1);
					$month2 = date('m', $ts2);

					$monthdiff = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;

			     	$averageSales = round($salesAmount/$monthdiff);

	     } else {

			     $orderDetails = OrderDetails::join('order_session as t1', 't1.id', '=', 'order_details.oid')->selectRaw('SUM(order_details.grand_total_inr) AS sales_amount,SUM(order_details.shipping_charge_inr) AS shipping_charge,count(order_details.id) AS total_orders,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])->where('t1.order_country',$country)->groupBy(DB::raw('CAST(order_details.order_placed_date AS DATE)'))->get();

			     if(isset($orderDetails) && !empty($orderDetails)) {
			     	foreach($orderDetails as $detail) {
			     		$day = date('d-M',strtotime($detail->order_placed_date));
			     		$oDate = date('Y-m-d',strtotime($detail->order_placed_date));

			     		$totalInternationOrders = OrderDetails::whereRaw('DATE(order_placed_date) = "'.$oDate.'"')->where('delete_status',0)->whereNotNull('order_status')->whereNotIn('order_status',['1','7','8'])->where('payment_type',3)->count();

			     		$internationalOrders += $totalInternationOrders;
			     		$sales = round($detail->sales_amount-$detail->shipping_charge);
			     		$shipping_charge = round($detail->shipping_charge);
			     		$orderPerDay = $detail->total_orders;


			     		$salesAmount += round($detail->sales_amount);
			     		$shippingCharge += $shipping_charge;
			     		$totalSales += round($detail->sales_amount);

			     		$chartResponse[] = array('year'=>$day,'sales'=>$sales, 'orderPerDay'=>$orderPerDay,'totalInternationOrders'=>$orderPerDay);
			     	}
			     }

			     $averageSales = round($salesAmount/$difference);

			 }
			 
			 $response = json_encode($chartResponse);
			 
			 return $response;
	}


	public function customer(Request $request){
		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d');

		$type = $request->type;

		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
			$type = $request->ptype;
		}

		if($type=='today') {
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d');
		} else if($type=='month') {
			$fromDate = date('Y-m-01');
			$toDate = date('Y-m-d');
		} else if($type=='year') {
			$fromDate = date('Y-01-01');
			$toDate = date('Y-m-d');
		}

		 $fromTime = strtotime($fromDate);
	     $toTime = strtotime($toDate);
	     $datediff = $toTime - $fromTime;
	     $difference = abs(floor(($datediff/(60*60*24))))+1;

	     $newSignups = User::select('id')->whereRaw('DATE(created_at) between "'.$fromDate.'" AND "'.$toDate.'"')->where('user_type',2)->where('delete_status',0)->count();

	     $customerOrders = OrderDetails::select('id')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('fkcustomer_id','!=',0)->where('delete_status',0)->whereNotIn('order_status',['7','8'])->count();

	     $guestOrders = OrderDetails::select('id')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('fkcustomer_id','=',0)->where('delete_status',0)->whereNotIn('order_status',['7','8'])->count();

	     $orderDetailArray = array();

	     $customerOrdersDetail = OrderDetails::selectRaw('COUNT(id) AS cid,order_placed_date')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('fkcustomer_id','!=',0)->where('delete_status',0)->whereNotIn('order_status',['7','8'])->groupBy(DB::raw('DATE(order_placed_date)'))->orderBy('order_placed_date','ASC')->get();

	     if(isset($customerOrdersDetail) && !empty($customerOrdersDetail)) {
	     	foreach ($customerOrdersDetail as $customerDetail) {
	     		$day = date('d-M',strtotime($customerDetail->order_placed_date));
	     		$orderDetailArray[$day]['customer_order'] = $customerDetail->cid;
	     	}
	     }


	     $guestOrdersDetail = OrderDetails::selectRaw('COUNT(id) AS cid,order_placed_date')->whereRaw('DATE(order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')->where('fkcustomer_id','=',0)->where('delete_status',0)->whereNotIn('order_status',['7','8'])->groupBy(DB::raw('DATE(order_placed_date)'))->orderBy('order_placed_date')->orderBy('order_placed_date','ASC')->get();

	     if(isset($guestOrdersDetail) && !empty($guestOrdersDetail)) {
	     	foreach ($guestOrdersDetail as $guestDetail) {
	     		$day = date('d-M',strtotime($guestDetail->order_placed_date));
	     		$orderDetailArray[$day]['guest_order'] = $guestDetail->cid;
	     	}
	     }


	    // return $orderDetailArray;

	    $chartResponse = array();

	    if(!empty($orderDetailArray)) {
	    	foreach ($orderDetailArray as $key => $value) {
	    		$custOrder = 0;
	    		$gueOrder = 0;
	    		if(isset($value['customer_order'])) {
	    			$custOrder = $value['customer_order'];
	    		}
	    		if(isset($value['guest_order'])) {
	    			$gueOrder = $value['guest_order'];
	    		}
	    		$chartResponse[] = array('year'=>$key,'customer_order'=>$custOrder, 'guest_order'=>$gueOrder);
	    	}
	    }

	   /* $chartResponse[] = array('year'=>'01-Mar','customer_order'=>1500, 'guest_order'=>150);
		$chartResponse[] = array('year'=>'02-Mar','customer_order'=>12500, 'guest_order'=>1520);
		$chartResponse[] = array('year'=>'03-Mar','customer_order'=>12500, 'guest_order'=>1550);*/


		$response = json_encode($chartResponse);
		
		$allCustomers = User::
			where('users.user_type',2)
			->where('users.delete_status',0)
			 ->leftJoin('order_details as t1', 't1.fkcustomer_id', '=', 'users.id')
	 		 ->selectRaw('users.*,count(t1.id) as numorder')
			->groupBy('users.id')
		
			->get();
		 

	     return view('admin.reports.customer')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate,'customerOrders'=>$customerOrders,
	     	    'guestOrders'=>$guestOrders,'newSignups'=>$newSignups,'response'=>$response,'allCustomers'=>$allCustomers));

	 }
	 public function export(Request $request){
		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d');
 
		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
			
			$results=OrderDetails::where('order_details.delete_status',0)
			->join('order_session as ods','ods.id','=','order_details.oid')
			->join('order_address as odads','odads.order_id','=','order_details.id');
			 
		$results=$results->whereBetween('order_details.order_placed_date',[$fromDate, $toDate]);
			$results=$results->select('order_details.*','ods.order_symbol','odads.billing_name','odads.billing_email','odads.billing_phone','odads.billing_address1','odads.billing_address2','odads.billing_state','odads.billing_city','odads.billing_country','odads.billing_zip','odads.shipping_name','odads.shipping_email','odads.shipping_phone','odads.shipping_address1','odads.shipping_address2','odads.shipping_state','odads.shipping_city','odads.shipping_country','odads.shipping_zip')
					->orderby('order_details.id','asc')
					->groupBy('order_details.id')
					->get();
					
					 
					
$paytype=array("1"=>"Online EBS Payment", "2"=>"Direct Bank Transfer", "3"=>"Online Paypal Payment");
			  if(sizeof($results)>0)
				{
					 
					$data[0][]='Invoice number';
					$data[0][]='Grand Total';
					$data[0][]='Grand Total in INR';
					$data[0][]='Shipping Charge';
					$data[0][]='Shipping Charge in INR';
					$data[0][]='Discount Amount';
					$data[0][]='Discount Amount in INR';
					$data[0][]='Discount Comment';
					$data[0][]='Refund Amount';
					$data[0][]='Payment Type';					
					$data[0][]='Billing Name';
					$data[0][]='Billing Email';
					$data[0][]='Billing Phone';
					$data[0][]='Billing Address1';
					$data[0][]='Billing Address2';
					$data[0][]='Billing State';
					$data[0][]='Billing City';
					$data[0][]='Billing Country';
					$data[0][]='Billing zip';
					$data[0][]='Shipping Name';
					$data[0][]='Shipping Email';
					$data[0][]='Shipping Phone';
					$data[0][]='Shipping Address1';
					$data[0][]='Shipping Address2';
					$data[0][]='Shipping State';
					$data[0][]='Shipping City';
					$data[0][]='Shipping Country';
					$data[0][]='Shipping zip';
					$data[0][]='Product Name';
					$data[0][]='Price';
					$data[0][]='Qty';
					$data[0][]='Amount';
			 		
					$i=1;
					 $array_key=array();
					foreach($results as $result)
					{
$paytypes="";							
if($result->payment_type=1 || $result->payment_type=2 || $result->payment_type=3)
{
	$paytypes=$paytype[$result->payment_type];
}	
					
					    
						$data[$i][]=$result->invoice_no;
						$data[$i][]=$result->order_symbol.' '.$result->grand_total;
						$data[$i][]='Rs. '.$result->grand_total_inr;
						$data[$i][]=$result->order_symbol.' '.$result->shipping_charge;
						$data[$i][]='Rs. '.$result->shipping_charge_inr;
						$data[$i][]=$result->order_symbol.' '.$result->discount_amount;
						$data[$i][]='Rs. '.$result->discount_amount_inr;
						$data[$i][]=$result->discount_comment;
						$data[$i][]=$result->refund_amount;
						$data[$i][]=$paytypes;
						$data[$i][]=$result->billing_name;
						$data[$i][]=$result->billing_email;
						$data[$i][]=$result->billing_phone;
						$data[$i][]=$result->billing_address1;
						$data[$i][]=$result->billing_address2;
						$data[$i][]=$result->billing_state;
						$data[$i][]=$result->billing_city;
						$data[$i][]=$result->billing_country;
						$data[$i][]=$result->billing_zip;
						$data[$i][]=$result->shipping_name;
						$data[$i][]=$result->shipping_email;
						$data[$i][]=$result->shipping_phone;
						$data[$i][]=$result->shipping_address1;
						$data[$i][]=$result->shipping_address2;
						$data[$i][]=$result->shipping_state;
						$data[$i][]=$result->shipping_city;
						$data[$i][]=$result->shipping_country;
						$data[$i][]=$result->shipping_zip;

						$product_results = OrderProducts::where('oid', $result->id)
                            ->get();
							if(sizeof($product_results)>0)
				{
							foreach($product_results as $product_result)
					    {
                            if (in_array($product_result->oid, $array_key))
                              {
                               
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                                $data[$i][]=' ';
                              }
                            array_push($array_key,$product_result->oid);
$kitflag="";
if($product_result->product_type==2)
{
	$kitflag="(Kit)";
}	
$tot=($product_result->product_price*$product_result->product_qty);							
                           $data[$i][]=$product_result->product_name.$kitflag;
                           $data[$i][]=$product_result->product_price;
                           $data[$i][]=$product_result->product_qty;
                           $data[$i][]=round($tot, 2);
                           $i++;
                        } 
                  }
				  else
				  {
					    $i++;
				  }


						
						 
					}
				Excel::create('Order Report on '.date('d-M-Y h-i-s A'), function($excel) use($data) {
					    $excel->sheet('Order Report', function($sheet) use($data) {
					        $sheet->fromArray($data, null, 'A1', false, false)
					        	  ->setAutoFilter();
					    });
					})->export('xls');					
				
				}	
			 
		}

		return view('admin.reports.export')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate));
	}
	 public function exportcustomer(Request $request){
		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d'); 
		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
		}
		$customers = User::where('user_type',2)->where('delete_status',0)->get();
		
		
		
		  if(sizeof($customers)>0)
				{ 
					$data[0][]='Name';
					$data[0][]='Email ID';
					$data[0][]='Gender & Age';
					$data[0][]='Rating';
					$data[0][]='Facebook Url';
					$data[0][]='Twitter Url';
					$data[0][]='Google Url';
					$data[0][]='Website Url';
					$data[0][]='Last Order Item';
					
					$data[0][]='Billing Name';
					$data[0][]='Billing Address1';
					$data[0][]='Billing Address2';
					$data[0][]='Billing State';
					$data[0][]='Billing City';
					$data[0][]='Billing Country';
					$data[0][]='Billing zip';
					
					$data[0][]='Shipping Name';
					$data[0][]='Shipping Address1';
					$data[0][]='Shipping Address2';
					$data[0][]='Shipping State';
					$data[0][]='Shipping City';
					$data[0][]='Shipping Country';
					$data[0][]='Shipping zip';
					
					$data[0][]='Note Title';
					$data[0][]='Note Description';

					$i=1;
					 $array_key=array();
					foreach($customers as $result)
					{
						$gender = '';
						$genderAge = '';
						if(strtolower($result->gender)=='male') {
							$gender = 'Male';
						} elseif(strtolower($result->gender)=='female') {
							$gender = 'Female';
						}

						$data[$i][]=$result->name;
						$data[$i][]=$result->email;
						if($gender!='') {
							if($result->age!='') {
								$genderAge = $gender.' / '.$result->age;
							} else {
								$genderAge = $gender;
							}
						} else {
							$genderAge = $result->age;
						}
						$data[$i][]=$genderAge;
						$data[$i][]=$result->rating;
						$data[$i][]=$result->facebook_url;
						$data[$i][]=$result->twitter_url;
						$data[$i][]=$result->google_url;
						$data[$i][]=$result->website_url;
 
$orderdetails=OrderDetails::where('order_details.delete_status',0)->where('order_details.fkcustomer_id',$result->id)->select('order_details.*')->orderby('order_details.id','desc')->first();	
$cust_prd="";
if($orderdetails)
{
	$getProducts = AdminHelper::getCustomerProduct($orderdetails->id); 
	if(!empty($getProducts))
	{
	foreach($getProducts as $prdval)
	{
		 $cust_prd.=	$prdval->product_name.',';
	}	
	}
	 $cust_prd=rtrim($cust_prd,',');
}
					
						$data[$i][]=$cust_prd;
		
// biiling and shiping 
	$billingname=""; 
	$billingAddress1="";
	$billingAddress2="";
	$billingState="";
	$billingCity="";
	$billingCountry="";
	$billingzip="";
	
	$shippingname=""; 
	$shippingAddress1="";
	$shippingAddress2="";
	$shippingState="";
	$shippingCity="";
	$shippingCountry="";
	$shippingzip="";
	
	 

$cus_add = UserAddress::where('delete_status',0)->where('user_id',$result->id)->orderby('id','desc')->get();	
if(isset($cus_add) && !empty($cus_add))
{
	$t=0;
foreach($cus_add as $bilvals)
{
if($t==0 && $bilvals->status==1)
{
	$billingname=$bilvals->name; 
	$billingAddress1=$bilvals->address;
	$billingAddress2=$bilvals->address2;
	$billingState=$bilvals->state;
	$billingCity=$bilvals->city;
	$billingCountry=$bilvals->country;
	$billingzip=$bilvals->postcode;
$t++;	
}
}

$t=0;
foreach($cus_add as $bilvals)
{
if($t==0 && $bilvals->status==2)
{
	$shippingname=$bilvals->name; 
	$shippingAddress1=$bilvals->address;
	$shippingAddress2=$bilvals->address2;
	$shippingState=$bilvals->state;
	$shippingCity=$bilvals->city;
	$shippingCountry=$bilvals->country;
	$shippingzip=$bilvals->postcode;
	
 
	
$t++;	
}
}

}	
						$data[$i][]=$billingname;
						$data[$i][]=$billingAddress1;
						$data[$i][]=$billingAddress2;
						$data[$i][]=$billingState;
						$data[$i][]=$billingCity;
						$data[$i][]=$billingCountry;
						$data[$i][]=$billingzip;
						
						$data[$i][]=$shippingname;
						$data[$i][]=$shippingAddress1;
						$data[$i][]=$shippingAddress2;
						$data[$i][]=$shippingState;
						$data[$i][]=$shippingCity;
						$data[$i][]=$shippingCountry;
						$data[$i][]=$shippingzip;

$cus_note = UserNote::where('delete_status',0)->where('user_id',$result->id)->orderby('id','desc')->get();						
if(sizeof($cus_note)>0)
				{
							foreach($cus_note as $note_val)
					    {
                            if (in_array($note_val->user_id, $array_key))
                              {
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';
								$data[$i][]=' ';	
							  }
							   array_push($array_key,$note_val->user_id);
							   $data[$i][]=$note_val->note_title;
							   $data[$i][]=$note_val->note_description;
							   $i++;
						}
				}
				else
				  {
					    $i++;
				  }				
						
							  
						
						
// biiling and shiping 		
						$i++;
					}	
						Excel::create('Customer Report on '.date('d-M-Y h-i-s A'), function($excel) use($data) {
					    $excel->sheet('Customer Report', function($sheet) use($data) {
					        $sheet->fromArray($data, null, 'A1', false, false)
					        	  ->setAutoFilter();
					    });
					})->export('xls');	
				}
				else
				{
		return Redirect::to('/admin/reports/customer')->send();
				}					
		
	 

	 }	
	public function viewCustomer($id,Request $request){

        if($request->isMethod('post')){	
		
			$allowed    = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF'); 
					$error = 0;
				$note_image = $request->file('note_image');
				if($note_image!='') {  
				$extension  = $note_image->getClientOriginalExtension();
				if(!in_array($extension,$allowed)) {   
				$error = 1;
				}
				$imagedata = getimagesize($note_image);
				$width = $imagedata[0];
				$height = $imagedata[1];
				if($width!=1920 || $height!=900){
				//$error = 1;
				}
				}
				
			if($error==1){
                
                    return Redirect::back()->withInput()->with('error_msg','Please upload valid file.');
                }  
			else{
				
				
				if($request->note_id)
				{
					$add =UserNote::find($request->note_id);
				}
				else
				{
						$add =new UserNote;
				}
			
			$add->user_id = $id;
			$add->note_title = $request->note_title;
			$add->note_description = $request->note_description;
		 
			$note_image = $request->file('note_image');
				if($note_image!='') {   
				$destination = 'public/uploads/note_image/';
				$newname = str_random(5);
				$ext = $note_image->getClientOriginalExtension();
				$filename = $newname.'-original.'.$ext;
				$newFilename = $newname.'.'.$ext;
				if($note_image->move($destination, $filename)){
				copy($destination . $filename, $destination . $newFilename);
			 						$add->note_image = $newFilename;
				}
				}
			$add->save();
			if($request->note_id)
				{
					return Redirect::back()->withInput()->with('true_msg','successfully updated.');
				}
				else
				{
						return Redirect::back()->withInput()->with('true_msg','successfully added.');
				}
		 
			
			}
			
		}
	 
		 
	 	$customers = User::where('user_type',2)->where('delete_status',0)->where('id',$id)->first();
	 	$cus_add = UserAddress::where('delete_status',0)->where('user_id',$id)->orderby('id','desc')->get();
	 	$cus_note = UserNote::where('delete_status',0)->where('user_id',$id)->orderby('id','desc')->get();
		
			$orderdetails=OrderDetails::where('order_details.delete_status',0)->where('order_details.fkcustomer_id',$id)
			->join('order_session as ods','ods.id','=','order_details.oid')
			->join('order_address as odads','odads.order_id','=','order_details.id') 
			->select('order_details.*','ods.order_symbol','odads.billing_name','odads.billing_email','odads.billing_phone','odads.billing_address1','odads.billing_address2','odads.billing_state','odads.billing_city','odads.billing_country','odads.billing_zip','odads.shipping_name','odads.shipping_email','odads.shipping_phone','odads.shipping_address1','odads.shipping_address2','odads.shipping_state','odads.shipping_city','odads.shipping_country','odads.shipping_zip')
					->orderby('order_details.id','desc')
					->groupBy('order_details.id')
					->get();
		
		 $couriers = Couriers::where('delete_status',0)->get(); 
		 
			return view('admin.reports.viewcustomer')->with(array('customers'=>$customers,'orderdetails'=>$orderdetails,'couriers'=>$couriers,'cus_add'=>$cus_add,'cus_note'=>$cus_note));  
			 
		 
	}
public function deleteNote($id,Request $request){

 
	 
		$upds = UserNote::find($id);
		$upds->delete_status =1;
		if($upds->save())
		{
			return Redirect::back()->withInput()->with('true_msg','successfully deleted.');
		}	
		 
		 
		 
}	
public function postrating(Request $request){

        if($request->isMethod('post')){	
		$rating=$request->rating;
		$user_id=$request->user_id;
		$upds = User::find($user_id);
		$upds->rating = $rating;
		if($upds->save())
		{
			echo "Success";
		}
		}
		 
}
/*    public function reports(Request $request){
					
		$fromDate=date('Y-m-01');$from_date=date('01-m-Y');
		$toDate  =date('Y-m-t');$to_date=date('t-m-Y');
		if($request->isMethod('post')){				
			$from_date=$request->from_date;
			$to_date=$request->to_date;	
			if($from_date){
				$fromDate=date('Y-m-d',strtotime($from_date));
			}
			if($to_date){
				$toDate=date('Y-m-d',strtotime($to_date));
			}
			
		}
		
			$couriers = Couriers::where('delete_status',0)->get();
			$products = Products::where('delete_status',0)->get();			

			$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
								->join('order_address as t3','order_details.id','=','t3.order_id')
								->select('order_details.id','order_details.invoice_no','order_details.grand_total_inr as grand_total','order_details.payment_type','order_details.shipping_charge',
									'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
									't3.billing_name','t3.billing_email','t3.billing_phone','t3.billing_address1',
									't3.billing_address2','t3.billing_state','t3.billing_city','t3.billing_country','t3.billing_zip','t3.shipping_name','t3.shipping_email','t3.shipping_phone','t3.shipping_address1',
									't3.shipping_address2','t3.shipping_state','t3.shipping_city','t3.shipping_country','t3.shipping_zip')
									->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"');
									
			$orderDetails = $orderDetails->where('order_details.delete_status',0)->orderBy('order_details.id','DESC')->get();

        


        return view('admin.reports.reports')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'countries'=>$this->countries,'couriers'=>$couriers,'products'=>$products,'paymentType'=>$this->paymentType,'from_date'=>$from_date,'to_date'=>$to_date));
    
    }
    public function reportsExport(Request $request){
					
		$fromDate=date('Y-m-01');$from_date=date('01-m-Y');
		$toDate  =date('Y-m-t');$to_date=date('t-m-Y');
		if($request->isMethod('post')){				
			$from_date=$request->from_date;
			$to_date=$request->to_date;	
			if($from_date){
				$fromDate=date('Y-m-d',strtotime($from_date));
			}
			if($to_date){
				$toDate=date('Y-m-d',strtotime($to_date));
			}
			
		}
		
			$couriers = Couriers::where('delete_status',0)->get();
			$products = Products::where('delete_status',0)->get();			

			$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
								->join('order_address as t3','order_details.id','=','t3.order_id')
								->select('order_details.id','order_details.invoice_no','order_details.grand_total_inr as grand_total','order_details.payment_type','order_details.shipping_charge',
									'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
									't3.billing_name','t3.billing_email','t3.billing_phone','t3.billing_address1',
									't3.billing_address2','t3.billing_state','t3.billing_city','t3.billing_country','t3.billing_zip','t3.shipping_name','t3.shipping_email','t3.shipping_phone','t3.shipping_address1',
									't3.shipping_address2','t3.shipping_state','t3.shipping_city','t3.shipping_country','t3.shipping_zip')
									->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"');
									
			$orderDetails = $orderDetails->where('order_details.delete_status',0)->orderBy('order_details.id','DESC')->get();

        
    
    }*/

    public function inventory(Request $request){
		$fromDate = date('Y-m-d');
		$toDate = date('Y-m-d');
		$productId = '';

		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
			$productId = $request->product;
		}

			$fromTime = strtotime($fromDate);
	        $toTime = strtotime($toDate);
	        $datediff = $toTime - $fromTime;
	        $difference = abs(floor(($datediff/(60*60*24))))+1;

	        $chartResponse = array();

	        $productArray = array();
	        $totalSales = 0;
	        $averageSales = 0;

		$simpleProductList = Products::select('id','name','stock_status')->where('delete_status',0)->where('product_type',1)->orderBy('id','DESC')->get();
		$kitProductList = Products::select('id','name','stock_status')->where('delete_status',0)->where('product_type',2)->orderBy('id','DESC')->get();

		if(empty($productId)) {

			$totalProductsAvailable = Products::where('delete_status',0)->where('product_type',1)->count();
			$totalKitsAvailable = Products::where('delete_status',0)->where('product_type',2)->count();

			$simpleProduct=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->selectRaw('SUM(t2.product_qty) AS qty')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',1)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

			$bundleProduct=OrderDetails::join('order_bundle_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->selectRaw('SUM(t2.product_qty) AS qty')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

			$productQuantitiesSold = 0;

			if(count($simpleProduct)>0){
				if(!empty($simpleProduct->qty)) {
					$productQuantitiesSold=round($simpleProduct->qty);	
				}		
			}

			if(count($bundleProduct)>0){
				if(!empty($bundleProduct->qty)) {
					$productQuantitiesSold+=round($bundleProduct->qty);	
				}		
			}

			$kitProduct=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->selectRaw('SUM(t2.product_qty) AS qty')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',2)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

			$kitQuantitiesSold = 0;

			if(count($kitProduct)>0){
				if(!empty($kitProduct->qty)) {
					$kitQuantitiesSold=round($kitProduct->qty);	
				}		
			}

			$simpleProductDetail=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',1)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->groupBy('t2.product_id')->get();

			$bundleProductDetail=OrderDetails::join('order_bundle_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->groupBy('t2.product_id')->get();

			$simpleProductListing = array();
			$topProductSellerName = '';
			$topProductSellerQty = 0;
			$lowProductSellerName = '';
			$lowProductSellerQty = 0;

			if(isset($simpleProductDetail) && !empty($simpleProductDetail)) {
				foreach($simpleProductDetail as $simple) {
					$pid = $simple->id;
					$pname = $simple->name;
					$pqty = $simple->qty;
					if(isset($simpleProductListing[$pid])) {
						$simpleProductListing[$pid]['qty'] += $pqty;
					} else {
						$simpleProductListing[$pid]['id'] = $pid;
						$simpleProductListing[$pid]['name'] = $pname;
						$simpleProductListing[$pid]['qty'] = $pqty;
					}

					/*$actualQty = $simpleProductListing[$pid]['qty'];
					if($actualQty>=$topProductSellerQty) {
						$topProductSellerQty = $actualQty;
						$topProductSellerName = $pname;
					}
					if($actualQty<=$lowProductSellerQty || $lowProductSellerQty==0) {
						$lowProductSellerQty = $actualQty;
						$lowProductSellerName = $pname;
					}*/
				}
			}


			if(isset($bundleProductDetail) && !empty($bundleProductDetail)) {
				foreach($bundleProductDetail as $bundle) {
					$pid = $bundle->id;
					$pname = $bundle->name;
					$pqty = $bundle->qty;
					if(isset($simpleProductListing[$pid])) {
						$simpleProductListing[$pid]['qty'] += $pqty;
					} else {
						$simpleProductListing[$pid]['id'] = $pid;
						$simpleProductListing[$pid]['name'] = $pname;
						$simpleProductListing[$pid]['qty'] = $pqty;
					}

					/*$actualQty = $simpleProductListing[$pid]['qty'];
					if($actualQty>=$topProductSellerQty) {
						$topProductSellerQty = $actualQty;
						$topProductSellerName = $pname;
					}

					if($actualQty<=$lowProductSellerQty || $lowProductSellerQty==0) {
						$lowProductSellerQty = $actualQty;
						$lowProductSellerName = $pname;
					}*/
				}
			}


			$kitProductDetail=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',2)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->groupBy('t2.product_id')->get();

			//return $kitProductList;

			/*$simpleProductMaximum=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->select(DB::raw('SUM(t2.product_qty) as qty'),DB::raw('MAX(qty),t2.product_name'))->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t2.product_type',1)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->first();

			return $simpleProductMaximum;*/

			return view('admin.reports.inventory')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate,'productId'=>$productId,
				   'totalProductsAvailable'=>$totalProductsAvailable,'totalKitsAvailable'=>$totalKitsAvailable,
				   'productQuantitiesSold'=>$productQuantitiesSold,'kitQuantitiesSold'=>$kitQuantitiesSold,
				   'simpleProduct'=>$simpleProductListing,'kitProduct'=>$kitProductDetail,'simpleProductList'=>$simpleProductList,
				   'kitProductList'=>$kitProductList,'difference'=>$difference));

		} else {



	        if($difference>31) {

	        	$simpleProductDetail=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t3.id',$productId)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->groupBy(DB::raw('YEAR(order_placed_date), MONTH(order_placed_date)'))->get();

				$bundleProductDetail=OrderDetails::join('order_bundle_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->where('t3.id',$productId)->groupBy(DB::raw('YEAR(order_placed_date), MONTH(order_placed_date)'))->get();

				if(isset($simpleProductDetail) && !empty($simpleProductDetail)) {
			     	foreach($simpleProductDetail as $detail) {

			     		$day = date('M-y',strtotime($detail->order_placed_date));
			     		$qty = $detail->qty;

			     		$productArray[$day] = $qty;

			     	}
			     }

			     if(isset($bundleProductDetail) && !empty($bundleProductDetail)) {
			     	foreach($bundleProductDetail as $detail) {

			     		$day = date('M-y',strtotime($detail->order_placed_date));
			     		$qty = $detail->qty;

			     		if(isset($productArray[$day])) {

			     			$productArray[$day] += $qty;

			     		} else {

			     			$productArray[$day] = $qty;

			     		}

			     	}
			     }

			     if(isset($productArray) && !empty($productArray)) {
			     	foreach ($productArray as $key => $value) {

			     		$m = date('m',strtotime($key));
			     		$y = date('Y',strtotime($key));

			     		$numberOfDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);
			     		$totalSales += $value;

			     		$chartResponse[] = array('year'=>$key,'sales'=>$value);
			     		//return $numberOfDays;
			     	}
			     }


			     	$ts1 = strtotime($fromDate);
					$ts2 = strtotime($toDate);

					$year1 = date('Y', $ts1);
					$year2 = date('Y', $ts2);

					$month1 = date('m', $ts1);
					$month2 = date('m', $ts2);

					$monthdiff = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;

			     	$averageSales = round($totalSales/$monthdiff);

				//return $productArray;

	        } else {

	        	$simpleProductDetail=OrderDetails::join('order_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('t3.id',$productId)->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->groupBy(DB::raw('CAST(order_placed_date AS DATE)'))->get();

				$bundleProductDetail=OrderDetails::join('order_bundle_products as t2', 'order_details.oid', '=', 't2.oid')
					   ->join('products as t3', 't2.product_id', '=', 't3.id')
					   ->selectRaw('SUM(t2.product_qty) AS qty,t3.name,t3.id,order_details.order_placed_date')->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
					   ->where('order_details.delete_status',0)->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['1','7','8'])
					   ->where('t3.id',$productId)->groupBy(DB::raw('CAST(order_placed_date AS DATE)'))->get();

				if(isset($simpleProductDetail) && !empty($simpleProductDetail)) {
			     	foreach($simpleProductDetail as $detail) {

			     		$day = date('d-M',strtotime($detail->order_placed_date));
			     		$qty = $detail->qty;

			     		$productArray[$day] = $qty;

			     	}
			     }

			     if(isset($bundleProductDetail) && !empty($bundleProductDetail)) {
			     	foreach($bundleProductDetail as $detail) {

			     		$day = date('d-M',strtotime($detail->order_placed_date));
			     		$qty = $detail->qty;

			     		if(isset($productArray[$day])) {

			     			$productArray[$day] += $qty;

			     		} else {

			     			$productArray[$day] = $qty;

			     		}

			     	}
			     }

			     if(isset($productArray) && !empty($productArray)) {
			     	foreach ($productArray as $key => $value) {

			     		$totalSales += $value;

			     		$chartResponse[] = array('year'=>$key,'sales'=>$value);
			     		//return $numberOfDays;
			     	}
			     }


			     	$averageSales = round($totalSales/$difference);

	        }

	        $response = json_encode($chartResponse);

	      //  return $kitProductList;


	        return view('admin.reports.inventory')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate,'productId'=>$productId,
				   'simpleProductList'=>$simpleProductList, 'kitProductList'=>$kitProductList,'difference'=>$difference,
				   'totalSales'=>$totalSales,'averageSales'=>$averageSales,'response'=>$response));




		}

		

	}
}