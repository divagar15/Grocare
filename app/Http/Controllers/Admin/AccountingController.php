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
 
class AccountingController extends Controller {
	
	public function __construct(){
    	$this->orderStatus = Config::get('custom.orderStatus');
    	$this->countries = Config::get('custom.country');
    	$this->paymentType = Config::get('custom.paymentType');
    	$this->currencies = Config::get('custom.currency');
	}

	public function index(Request $request){
		
		
		$fromDate = date('Y-m-01');
		$toDate = date('Y-m-d');
		
		if($request->isMethod('post')){	
			$fromDate = date('Y-m-d',strtotime($request->from_date));
			$toDate = date('Y-m-d',strtotime($request->to_date));
		}
		
		$amountMaharastra = 0;
		$amountRoi = 0;
		$amountInternational = 0;
		
		$orderCountMaharastra = 0;
		$orderCountRoi = 0;
		$orderCountInternational = 0;
		
		$shippingChargeMaharastra = 0;
		$shippingChargeRoi = 0;
		$shippingChargeInternational = 0;
		
		$productSale = array();
		$kitproductSale = array();
		
		
		$like1 = '40';
		$like2 = '44';
		$notlike1 = '403';



		$maharashtraArray = array('maharashtra','Maharashtra','MAHARASHTRA','Maharashtra ( IN-MH )','maharashtra ( IN-MH )',
			              'MAHARASHTRA ( IN-MH )');
		
		$salesMaharastra = OrderDetails::selectRaw('SUM(order_details.grand_total_inr) AS amount,COUNT(order_details.id) AS count,
		SUM(order_details.shipping_charge_inr) AS shippingCharge')//->join('order_address as t2', 'order_details.id', '=', 't2.order_id')
		->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
	   ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->whereIn('t2.billing_state',$maharashtraArray)*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%')->orWhere('t2.billing_zip','LIKE','%'.$like2.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%');
		})->first();

		$salesRoi = OrderDetails::selectRaw('SUM(order_details.grand_total_inr) AS amount,COUNT(order_details.id) AS count,
		SUM(order_details.shipping_charge_inr) AS shippingCharge')//->join('order_address as t2', 'order_details.id', '=', 't2.order_id')
		->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
	   ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->whereNotIn('t2.billing_state',$maharashtraArray)*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','NOT LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$like2.'%')->orWhere('t2.billing_zip','LIKE','%'.$notlike1.'%');
		})
		->first();
		

		$salesInternational = OrderDetails::selectRaw('SUM(order_details.grand_total_inr) AS amount,COUNT(order_details.id) AS count,
		SUM(order_details.shipping_charge_inr) AS shippingCharge')//->join('order_address as t2', 'order_details.id', '=', 't2.order_id')
		->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
	   ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','!=','IN')->first();



		$productsMaharastra = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->where('t2.billing_state','Maharashtra')*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%')->orWhere('t2.billing_zip','LIKE','%'.$like2.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%');
		})
		->where('t3.product_type',1)->groupBy('t3.product_id')->get();
		
		
		
		$bundleProductsMaharastra = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_bundle_products as t3', 'order_details.oid', '=', 't3.oid')
		//->join('order_products as t4', 't3.order_product_id', '=', 't4.id')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%')->orWhere('t2.billing_zip','LIKE','%'.$like2.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%');
		})->groupBy('t3.product_id')->get();
		
		if(isset($productsMaharastra) && !empty($productsMaharastra)) {
			foreach($productsMaharastra as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] += $price;
					$productSale[$pid][1]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] = $qty;
					$productSale[$pid][2]['price'] = 0;
					$productSale[$pid][2]['qty'] = 0;
					$productSale[$pid][3]['price'] = 0;
					$productSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
		if(isset($bundleProductsMaharastra) && !empty($bundleProductsMaharastra)) {
			foreach($bundleProductsMaharastra as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] += $price;
					$productSale[$pid][1]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] = $qty;
					$productSale[$pid][2]['price'] = 0;
					$productSale[$pid][2]['qty'] = 0;
					$productSale[$pid][3]['price'] = 0;
					$productSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
	    $productsRoi = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->where('t2.billing_state','Maharashtra')*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','NOT LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$like2.'%')->orWhere('t2.billing_zip','LIKE','%'.$notlike1.'%');
		})
		->where('t3.product_type',1)->groupBy('t3.product_id')->get();
		
		
		
		$bundleProductsRoi = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_bundle_products as t3', 'order_details.oid', '=', 't3.oid')
		//->join('order_products as t4', 't3.order_product_id', '=', 't4.id')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','NOT LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$like2.'%')->orWhere('t2.billing_zip','LIKE','%'.$notlike1.'%');
		})->groupBy('t3.product_id')->get();
		
		if(isset($productsRoi) && !empty($productsRoi)) {
			foreach($productsRoi as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][2]['price'] += $price;
					$productSale[$pid][2]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][2]['price'] = $price;
					$productSale[$pid][2]['qty'] = $qty;
					$productSale[$pid][1]['price'] = 0;
					$productSale[$pid][1]['qty'] = 0;
					$productSale[$pid][3]['price'] = 0;
					$productSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
		if(isset($bundleProductsRoi) && !empty($bundleProductsRoi)) {
			foreach($bundleProductsRoi as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][2]['price'] += $price;
					$productSale[$pid][2]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][2]['price'] = $price;
					$productSale[$pid][2]['qty'] = $qty;
					$productSale[$pid][1]['price'] = 0;
					$productSale[$pid][1]['qty'] = 0;
					$productSale[$pid][3]['price'] = 0;
					$productSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
		$productsInternational = OrderDetails::select('t3.product_id','t3.product_price','t3.product_qty','order_details.grand_total_inr','order_details.grand_total')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','!=','IN')->where('t3.product_type',1)->get();
		
		
		/*foreach($productsInternational as $int) {
			$rate = $int->grand_total_inr/$int->grand_total;
			$price = round(($int->product_price*$int->product_qty)*$rate);
			echo $price; die();
		}*/
		
		
		
		$bundleProductsInternational = OrderDetails::select('t3.product_id','t3.product_price','t3.product_qty','order_details.grand_total_inr','order_details.grand_total')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_bundle_products as t3', 'order_details.oid', '=', 't3.oid')
		//->join('order_products as t4', 't3.order_product_id', '=', 't4.id')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','!=','IN')->get();
		
		if(isset($productsInternational) && !empty($productsInternational)) {
			foreach($productsInternational as $pro) {
				$pid = $pro->product_id;
				$rate = $pro->grand_total_inr/$pro->grand_total;
				$qty = $pro->product_qty;
				$price = round(($pro->product_price*$pro->product_qty)*$rate);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][3]['price'] += $price;
					$productSale[$pid][3]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][3]['price'] = $price;
					$productSale[$pid][3]['qty'] = $qty;
					$productSale[$pid][1]['price'] = 0;
					$productSale[$pid][1]['qty'] = 0;
					$productSale[$pid][2]['price'] = 0;
					$productSale[$pid][2]['qty'] = 0;
				}
			}
		}
		
		
		if(isset($bundleProductsInternational) && !empty($bundleProductsInternational)) {
			foreach($bundleProductsInternational as $pro) {
				$pid = $pro->product_id;
				$rate = $pro->grand_total_inr/$pro->grand_total;
				$qty = $pro->product_qty;
				$price = round(($pro->product_price*$pro->product_qty)*$rate);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][3]['price'] += $price;
					$productSale[$pid][3]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][3]['price'] = $price;
					$productSale[$pid][3]['qty'] = $qty;
					$productSale[$pid][1]['price'] = 0;
					$productSale[$pid][1]['qty'] = 0;
					$productSale[$pid][2]['price'] = 0;
					$productSale[$pid][2]['qty'] = 0;
				}
			}
		}
		
		
		$kitproductsMaharastra = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->where('t2.billing_state','Maharashtra')*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%')->orWhere('t2.billing_zip','LIKE','%'.$like2.'%')->where('t2.billing_zip','NOT LIKE','%'.$notlike1.'%');
		})
		->where('t3.product_type',2)->groupBy('t3.product_id')->get();
		
		
		if(isset($kitproductsMaharastra) && !empty($kitproductsMaharastra)) {
			foreach($kitproductsMaharastra as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($kitproductSale[$pid])) {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][1]['price'] += $price;
					$kitproductSale[$pid][1]['qty'] += $qty;
				} else {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][1]['price'] = $price;
					$kitproductSale[$pid][1]['qty'] = $qty;
					$kitproductSale[$pid][2]['price'] = 0;
					$kitproductSale[$pid][2]['qty'] = 0;
					$kitproductSale[$pid][3]['price'] = 0;
					$kitproductSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
		$kitproductsRoi = OrderDetails::selectRaw('t3.product_id,SUM(t3.product_price*t3.product_qty) as price,SUM(t3.product_qty) as qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		/*->where('t2.billing_state','Maharashtra')*/->where('t2.billing_country','IN')
		->where(function($query)use($like1,$like2,$notlike1) {
			$query->where('t2.billing_zip','NOT LIKE','%'.$like1.'%')->where('t2.billing_zip','NOT LIKE','%'.$like2.'%')->orWhere('t2.billing_zip','LIKE','%'.$notlike1.'%');
		})
		->where('t3.product_type',2)->groupBy('t3.product_id')->get();
		
		
		
		
		
		if(isset($kitproductsRoi) && !empty($kitproductsRoi)) {
			foreach($kitproductsRoi as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->qty;
				$price = round($pro->price);
				if(isset($kitproductSale[$pid])) {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][2]['price'] += $price;
					$kitproductSale[$pid][2]['qty'] += $qty;
				} else {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][2]['price'] = $price;
					$kitproductSale[$pid][2]['qty'] = $qty;
					$kitproductSale[$pid][1]['price'] = 0;
					$kitproductSale[$pid][1]['qty'] = 0;
					$kitproductSale[$pid][3]['price'] = 0;
					$kitproductSale[$pid][3]['qty'] = 0;
				}
			}
		}
		
		
		$kitproductsInternational = OrderDetails::select('t3.product_id','t3.product_price','t3.product_qty','order_details.grand_total_inr','order_details.grand_total')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->where('t2.billing_country','!=','IN')->where('t3.product_type',2)->get();
		
		
		if(isset($kitproductsInternational) && !empty($kitproductsInternational)) {
			foreach($kitproductsInternational as $pro) {
				$pid = $pro->product_id;
				$rate = $pro->grand_total_inr/$pro->grand_total;
				$qty = $pro->product_qty;
				$price = round(($pro->product_price*$pro->product_qty)*$rate);
				if(isset($kitproductSale[$pid])) {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][3]['price'] += $price;
					$kitproductSale[$pid][3]['qty'] += $qty;
				} else {
					$kitproductSale[$pid]['product_id'] = $pid;
					$kitproductSale[$pid][3]['price'] = $price;
					$kitproductSale[$pid][3]['qty'] = $qty;
					$kitproductSale[$pid][1]['price'] = 0;
					$kitproductSale[$pid][1]['qty'] = 0;
					$kitproductSale[$pid][2]['price'] = 0;
					$kitproductSale[$pid][2]['qty'] = 0;
				}
			}
		}
		
		//return $kitproductSale;
		
//		echo '<pre>'; print_r($productSale); echo '</pre>';
		
		$products = Products::select('id','name')->where('delete_status',0)/*->where('product_type',1)*/->orderBy('id','DESC')->get();
		
		return view('admin.accounts.accounting')->with(array('fromDate'=>$fromDate,'toDate'=>$toDate,
		'products'=>$products,'salesMaharastra'=>$salesMaharastra,'salesRoi'=>$salesRoi,'salesInternational'=>$salesInternational,'productSale'=>$productSale,'kitproductSale'=>$kitproductSale));
		
		
		
		/*$amt = 0;
		if(isset($productsMaharastra) && !empty($productsMaharastra)) {
			foreach($productsMaharastra as $pro) {
				$amt += $pro->product_price*$pro->product_qty;
			}
		}
		
		if(isset($bundleProductsMaharastra) && !empty($bundleProductsMaharastra)) {
			foreach($bundleProductsMaharastra as $pro) {
				$amt += $pro->product_price*$pro->product_qty;
			}
		}
		
		echo $salesMaharastra->amount.'<br/>';
		
		return $amt;
		
		
		if(isset($productsMaharastra) && !empty($productsMaharastra)) {
			foreach($productsMaharastra as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->product_qty;
				$price = round($pro->product_price*$pro->product_qty);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] = $qty;
					$productSale[$pid][2]['price'] = $price;
					$productSale[$pid][2]['qty'] = $qty;
					$productSale[$pid][3]['price'] = $price;
					$productSale[$pid][3]['qty'] = $qty;
				}
			}
		}*/



		/*$bundleProductsMaharastra = OrderDetails::select('t3.product_id','t3.product_price','t3.product_qty')->join('order_address as t2', function ($q) {
		   $q->on('order_details.id', '=', 't2.order_id')
		   ->on('t2.updated_at', '=', DB::raw('(select max(updated_at) from order_address where order_id = t2.order_id)'));
		})
		->join('order_bundle_products as t3', 'order_details.oid', '=', 't3.oid')
	    ->whereRaw('DATE(order_details.order_placed_date) between "'.$fromDate.'" AND "'.$toDate.'"')
		->where('order_details.delete_status',0)->whereNotIn('order_details.order_status',['1','7','8'])
		->whereIn('t2.billing_state',$maharashtraArray)->where('t2.billing_country','IN')
		->get();
		
		if(isset($bundleProductsMaharastra) && !empty($bundleProductsMaharastra)) {
			foreach($bundleProductsMaharastra as $pro) {
				$pid = $pro->product_id;
				$qty = $pro->product_qty;
				$price = round($pro->product_price*$pro->product_qty);
				if(isset($productSale[$pid])) {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] += $qty;
				} else {
					$productSale[$pid]['product_id'] = $pid;
					$productSale[$pid][1]['price'] = $price;
					$productSale[$pid][1]['qty'] = $qty;
					$productSale[$pid][2]['price'] = $price;
					$productSale[$pid][2]['qty'] = $qty;
					$productSale[$pid][3]['price'] = $price;
					$productSale[$pid][3]['qty'] = $qty;
				}
			}
		}*/


		
								 
		//return $productSale;

		//echo $salesMaharastra->amount+$salesRoi->amount+$salesInternational->amount;
		
		
	
	}
	
}