<?php namespace App\Http\Controllers\Front;

use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\Courses;
use App\Models\Products;
use App\Models\ProductCourse;
use App\Models\ProductRegion;
use App\Models\ProductImages;
use App\Models\ProductTiles;
use App\Models\ProductBundle;
use App\Models\OrderSession;
use App\Models\OrderDetails;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Helper\FrontHelper;
use Session;
use Auth;
use Config;

class CartController extends Controller {

	public function __construct()
    {
    	//$getCountryCode = FrontHelper::getGeoLocation();
    	$this->activeCountry = Session::get('active_country');
    	$this->activeCurrency = Session::get('active_currency');
    	$this->activeRegion = Session::get('active_region');
    	$this->activeSymbol = Session::get('active_symbol');
        $this->activeShippingCharge = Session::get('active_shipping_charge');
        $this->activeMinimumAmount = Session::get('active_minimum_amount');
        $this->siteTitle = Config::get('custom.siteTitle');
    	/*echo Session::get('active_country').'<br/>';
    	echo Session::get('active_currency').'<br/>';
    	echo Session::get('active_region').'<br/>';
    	echo Session::get('active_symbol').'<br/>';
    	die();*/
        //Session::put('order_id',1);
        if(isset(Auth::user()->id) && Session::get('order_id')) {
            $userUpate = User::find(Auth::user()->id);
            $userUpate->order_session_id = Session::get('order_id');
            $userUpate->save();
        }
    }

    public function cart(Request $request)
    {
        $orderSession = '';
        $orderProducts = array();
        if(Session::has('order_id')) {
			$orderSessionCheck = OrderSession::where('id',Session::get('order_id'))->where('order_status','!=',2)->count();
			$orderDetailsCheck = OrderDetails::where('oid',Session::get('order_id'))->whereNotNull('order_status')->where('delete_status',0)->count();
			if($orderSessionCheck==0 && $orderDetailsCheck!=0) {
				Session::forget('order_id');
			} else {
				$orderSession = OrderSession::where('id',Session::get('order_id'))->first();
				$orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
			}
        }
        $pageTitle = "Cart | ".$this->siteTitle;
        return view('front/checkout/cart')->with(array('pageTitle'=>$pageTitle,'orderSession'=>$orderSession, 'orderProducts'=>$orderProducts));
    }

    public function addToCart(Request $request)
    {
        $product_type = $request->product_type;
        $product_qty = $request->product_qty;
        $product_course = $request->product_course;
        $product_id = $request->product_id;
        $product_price = $request->product_price;
        $diagnose_id = $request->diagnosis_id;
        $diagnose_name = $request->diagnosis_name;
        $ordered_from = $request->ordered_from;
        $course_start = $request->course_start;
        $add_type = $request->add_type;

        $ip = $_SERVER['REMOTE_ADDR'];

$userAgent = '';

if(isset($_SERVER['HTTP_USER_AGENT'])) {

$userAgent = $_SERVER['HTTP_USER_AGENT'];

}

        if(Session::has('order_id')) {
			
            $orderSession = OrderSession::find(Session::get('order_id'));
            if(isset(Auth::user()->id)) {
                $orderSession->user_id = Auth::user()->id;
            }
            $orderSession->order_region = $this->activeRegion;
            $orderSession->order_country = $this->activeCountry;
            $orderSession->order_currency = $this->activeCurrency;
            $orderSession->order_symbol = $this->activeSymbol;
            $orderSession->order_ip_address = $ip;
$orderSession->order_user_agent = $userAgent;
            $orderSession->save();

        } else {

            $orderSession = new OrderSession;
            if(isset(Auth::user()->id)) {
                $orderSession->user_id = Auth::user()->id;
            }
            $orderSession->order_region = $this->activeRegion;
            $orderSession->order_country = $this->activeCountry;
            $orderSession->order_currency = $this->activeCurrency;
            $orderSession->order_symbol = $this->activeSymbol;
            $orderSession->order_ip_address = $ip;
$orderSession->order_user_agent = $userAgent;
            $orderSession->save();

            $orderSessionId = $orderSession->id;
            Session::put('order_id',$orderSessionId);

        }

        if($product_type==1) {

            $getProduct = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                          ->select('products.id','t2.sku_name as name')->where('products.id',$product_id)
                          ->where('t2.fkregion_id',$this->activeRegion)->where('products.product_type',1)->first();
            $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',Session::get('order_id'))->where('product_type',1)->where('product_id',$product_id)->first();

            if($product_course==0) {
                if(isset($checkProduct->id)) {
                    if($checkProduct->product_course==0) {
                        $qty = $checkProduct->product_qty+$product_qty;
                    } else {
                        $qty = $checkProduct->product_qty+$product_qty;
                    }
                    $orderProducts = OrderProducts::find($checkProduct->id);
                    $orderProducts->product_qty = $qty;
                   // $orderProducts->diagnose_id = $diagnose_id;
                   // $orderProducts->diagnose_name = $diagnose_name;
                    $orderProducts->save();
                } else {
                    $orderProducts = new OrderProducts;
                    $orderProducts->oid = Session::get('order_id');
                    $orderProducts->product_type=1;
                    $orderProducts->product_id = $product_id;
                    $orderProducts->product_name = $getProduct->name;
                    $orderProducts->product_price = $product_price;
                    $orderProducts->product_qty = $product_qty;
                    $orderProducts->ordered_from = $ordered_from;
                    $orderProducts->save();
                }
            } else {
                if(isset($checkProduct->id)) {
                    if($checkProduct->product_course==0) {
                        $qty = $checkProduct->product_qty+$product_qty;
                    } else {
                        $qty = $checkProduct->product_qty+$product_qty;
                    }
                    $orderProducts = OrderProducts::find($checkProduct->id);
                    $orderProducts->product_course=$product_course;
                    $orderProducts->product_qty = $qty;
                    $orderProducts->diagnose_id = $diagnose_id;
                    $orderProducts->diagnose_name = $diagnose_name;
                    $orderProducts->save();
                } else {
                    $orderProducts = new OrderProducts;
                    $orderProducts->oid = Session::get('order_id');
                    $orderProducts->product_type=1;
                    $orderProducts->product_course=$product_course;
                    $orderProducts->product_id = $product_id;
                    $orderProducts->product_name = $getProduct->name;
                    $orderProducts->product_price = $product_price;
                    $orderProducts->product_qty = $product_qty;
                    $orderProducts->diagnose_id = $diagnose_id;
                    $orderProducts->diagnose_name = $diagnose_name;
                    $orderProducts->ordered_from = $ordered_from;
                    $orderProducts->save();
                }
            }

        } else if($product_type==2) {

            $getProduct = Products::select('id','name')->where('id',$product_id)->where('product_type',2)->first();
            $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',Session::get('order_id'))->where('product_type',2)->where('product_id',$product_id)->first();
            if(isset($checkProduct->id)) {

                /*if(Session::get('active_currency')!='INR' && $course_start==0) {
                    $product_qty = $product_qty/2;
                }*/
                    /*$selectedCourses = Courses::where('id',$product_course)->where('id',2)->first();
                } else {*/

                    $selectedCourses = Courses::where('id',$product_course)->first();

                //}

                $actualQty = $product_qty+$checkProduct->product_qty;

               // $difference = ($actualQty-);

                $orderProducts = OrderProducts::find($checkProduct->id);
                $orderProducts->product_course = $product_course;
                $orderProducts->product_qty = $actualQty;
                $orderProducts->save();

                $diagnosisPrice = ProductRegion::where('fkregion_id',$this->activeRegion)->where('fkproduct_id',$product_id)->first();
            
                $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',$product_course)->get();

                $totalPrice = 0.00;

                /*if(Session::get('active_currency')!='INR' && $course_start==0) {

                    $selCourse = ($selectedCourses->rate_multiply/2);

                    $difference = ($actualQty/$selCourse);

                } else {
                    $difference = ($actualQty/$selectedCourses->rate_multiply);
                }*/

            //    echo $difference;

                if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                    foreach($diagnosisCourse as $diagonCour) {
                        $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$this->activeRegion)->first();
                        
                        $orderBundlePro = OrderBundleProducts::where('oid',Session::get('order_id'))->where('product_id',$diagonCour->fksimpleproduct_id)->where('order_product_id',$checkProduct->id)->where('bundle_product_id',$product_id)->first();

                     //   $qty = $actualQty*$diagonCour->quantity;

                        $qty = $actualQty*$diagonCour->quantity;

                      //  echo $diagonCour->quantity; die();

                        if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                            $price = round($productPrice->sales_price);
                        } else {
                            $price = round($productPrice->regular_price);
                        }
//echo $price; die();
                        $totalPrice += $diagonCour->quantity*$price;
                        
                        $orderBundleProducts = OrderBundleProducts::find($orderBundlePro->id);
                        $orderBundleProducts->product_price = $price;
                        $orderBundleProducts->product_qty = $qty;
                        $orderBundleProducts->save();
                    }
                }

                //$totalPrice = round($totalPrice/$product_qty);

$orderProducts = OrderProducts::find($checkProduct->id);
                $orderProducts->product_price = $totalPrice;
                $orderProducts->save();
                


            } else {

                /* if(Session::get('active_currency')!='INR' && $course_start==0) {
                    $product_qty = $product_qty/2;
                }*/

                $orderProducts = new OrderProducts;
                $orderProducts->oid = Session::get('order_id');
                $orderProducts->product_type=2;
                $orderProducts->product_id = $product_id;
                $orderProducts->product_course = $product_course;
                $orderProducts->product_name = $getProduct->name;
                $orderProducts->product_qty = $product_qty;
                $orderProducts->diagnose_id = $diagnose_id;
                $orderProducts->diagnose_name = $diagnose_name;
                $orderProducts->ordered_from = $ordered_from;
                $orderProducts->save();

                $opid = $orderProducts->id;

                $diagnosisPrice = ProductRegion::where('fkregion_id',$this->activeRegion)->where('fkproduct_id',$product_id)->first();
            
                $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',$product_course)->get();

                $totalPrice = 0.00;

                if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                    foreach($diagnosisCourse as $diagonCour) {
                        $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$this->activeRegion)->first();
                        //$qty = $product_qty*$diagonCour->quantity;
                        $qty = $diagonCour->quantity;
                        if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                            $price = round($productPrice->sales_price);
                        } else {
                            $price = round($productPrice->regular_price);
                        }

                        $totalPrice += $diagonCour->quantity*$price;
                        
                        $orderBundleProducts = new OrderBundleProducts;
                        $orderBundleProducts->oid = Session::get('order_id');
                        $orderBundleProducts->order_product_id = $opid;
                        $orderBundleProducts->product_id = $diagonCour->fksimpleproduct_id;
                        $orderBundleProducts->product_name = $productPrice->sku_name;
                        $orderBundleProducts->bundle_product_id = $product_id;
                        $orderBundleProducts->product_price = $price;
                        $orderBundleProducts->product_qty = $qty;
                        $orderBundleProducts->save();
                    }
                }

                //echo $product_qty.'----'.$totalPrice; die();

               //  $totalPrice = round($totalPrice/$product_qty);

                $orderProducts = OrderProducts::find($opid);
                $orderProducts->product_price = $totalPrice;
                $orderProducts->save();

            }
        }

        if($add_type==1) {

            $getOrderTotal = FrontHelper::getOrderTotal(); 

            $response['total'] = round($getOrderTotal);
 
            return $response;

        } else {

            return redirect('cart');

        }


    }

    public function updateCart(Request $request)
    {

       // return $request->all();

        $counter = $request->counter;

        for($i=1;$i<$counter;$i++) {

            $ptype = "product_type".$i;
            $pid = "product_id".$i;
            $pcourse = "product_course".$i;
            $pprice = "product_price".$i;
            $pquantity = "quantity".$i;
            $originalQuantity = "originalQuantity".$i;

            $product_type = $request->$ptype;
            $product_id = $request->$pid;
            $product_course = $request->$pcourse;
            $product_price = $request->$pprice;
            $product_qty = $request->$pquantity;
            $original_quantity = $request->$originalQuantity;

        if($product_qty!=$original_quantity) {

            if($product_type==1) {

               /* if($product_qty<$original_quantity) {
                    $product_qty = -($product_qty);
                }*/

                $getProduct = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                          ->select('products.id','t2.sku_name as name')->where('products.id',$product_id)
                          ->where('t2.fkregion_id',$this->activeRegion)->where('products.product_type',1)->first();
                $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',Session::get('order_id'))->where('product_type',1)->where('product_id',$product_id)->first();

                if($product_course==0) {
                    if(isset($checkProduct->id)) {
                        if($checkProduct->product_course==0) {
                            $qty = $product_qty;
                        } else {
                            $qty = $product_qty;
                        }
                        $orderProducts = OrderProducts::find($checkProduct->id);
                        $orderProducts->product_qty = $qty;
                        $orderProducts->save();
                    } else {
                        $orderProducts = new OrderProducts;
                        $orderProducts->oid = Session::get('order_id');
                        $orderProducts->product_type=1;
                        $orderProducts->product_id = $product_id;
                        $orderProducts->product_name = $getProduct->name;
                        $orderProducts->product_price = $product_price;
                        $orderProducts->product_qty = $product_qty;
                        $orderProducts->save();
                    }
                } else {
                    if(isset($checkProduct->id)) {
                        if($checkProduct->product_course==0) {
                            $qty = $product_qty;
                        } else {
                            $qty = $product_qty;
                        }
                        $orderProducts = OrderProducts::find($checkProduct->id);
                        $orderProducts->product_course=$product_course;
                        $orderProducts->product_qty = $qty;
                        $orderProducts->save();
                    } else {
                        $orderProducts = new OrderProducts;
                        $orderProducts->oid = Session::get('order_id');
                        $orderProducts->product_type=1;
                        $orderProducts->product_course=$product_course;
                        $orderProducts->product_id = $product_id;
                        $orderProducts->product_name = $getProduct->name;
                        $orderProducts->product_price = $product_price;
                        $orderProducts->product_qty = $product_qty;
                        $orderProducts->save();
                    }
                }

            }   else if($product_type==2) {

            $getProduct = Products::select('id','name')->where('id',$product_id)->where('product_type',2)->first();
            $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',Session::get('order_id'))->where('product_type',2)->where('product_id',$product_id)->first();
            if(isset($checkProduct->id)) {


                $diagnosisPrice = ProductRegion::where('fkregion_id',$this->activeRegion)->where('fkproduct_id',$product_id)->first();
            
                $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',$product_course)->get();

                $firstDiagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',1)->first();

                

                $courses = Courses::where('id',$product_course)->first();

                $actualQty = $product_qty;

                /*if($product_qty==0) {
                    if(Session::get('active_currency')!='INR') {
                        if(isset($firstDiagnosisCourse->id)) {
                            $actualQty = round($courses->rate_multiply);
                        } else {
                            $actualQty = round($courses->rate_multiply/2);
                        }
                    } else {
                        $actualQty = $courses->rate_multiply;
                    }
                } else {
                    $actualQty = $product_qty;                    
                } *//*else if($product_qty<=$courses->rate_multiply) {
                    $actualQty = $courses->rate_multiply;
                }  else {

$checkDivision = floor($product_qty/$courses->rate_multiply);
$actualQty = $checkDivision*$courses->rate_multiply;
$checkModulo = floor($product_qty%$courses->rate_multiply);
if($checkModulo>0) {
$actualQty = $actualQty+$courses->rate_multiply;
}
                }*/

                //echo $actualQty; die();

                $orderProducts = OrderProducts::find($checkProduct->id);
                $orderProducts->product_qty = $actualQty;
                $orderProducts->save();

                

                $totalPrice = 0.00;

                /*if(Session::get('active_currency')!='INR') {
                    if(isset($firstDiagnosisCourse->id)) {
                        $difference = ($actualQty/round($courses->rate_multiply));
                    } else {
                        $difference = ($actualQty/round($courses->rate_multiply/2));
                    }
                    
                } else {
                    $difference = ($actualQty/$courses->rate_multiply);
                }*/

                if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                    foreach($diagnosisCourse as $diagonCour) {
                        $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$this->activeRegion)->first();
                        
                        $orderBundlePro = OrderBundleProducts::where('oid',Session::get('order_id'))->where('product_id',$diagonCour->fksimpleproduct_id)->where('order_product_id',$checkProduct->id)->where('bundle_product_id',$product_id)->first();

                      //  $qty = $actualQty*$diagonCour->quantity;

                        $qty = $actualQty*$diagonCour->quantity;

                        if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                            $price = round($productPrice->sales_price);
                        } else {
                            $price = round($productPrice->regular_price);
                        }

                        $totalPrice += $qty*$price;
                        
                        $orderBundleProducts = OrderBundleProducts::find($orderBundlePro->id);
                        $orderBundleProducts->product_price = $price;
                        $orderBundleProducts->product_qty = $qty;
                        $orderBundleProducts->save();
                    }
                }
                


            } /*else {
                $orderProducts = new OrderProducts;
                $orderProducts->oid = Session::get('order_id');
                $orderProducts->product_type=2;
                $orderProducts->product_id = $product_id;
                $orderProducts->product_name = $getProduct->name;
                $orderProducts->product_qty = $product_qty;
                $orderProducts->save();

                $opid = $orderProducts->id;

                $diagnosisPrice = ProductRegion::where('fkregion_id',$this->activeRegion)->where('fkproduct_id',$product_id)->first();
            
                $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',$product_course)->get();

                $totalPrice = 0.00;

                if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                    foreach($diagnosisCourse as $diagonCour) {
                        $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$this->activeRegion)->first();
                        $qty = $product_qty*$diagonCour->quantity;
                        if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                            $price = round($productPrice->sales_price);
                        } else {
                            $price = round($productPrice->regular_price);
                        }

                        $totalPrice += $qty*$price;
                        
                        $orderBundleProducts = new OrderBundleProducts;
                        $orderBundleProducts->oid = Session::get('order_id');
                        $orderBundleProducts->order_product_id = $opid;
                        $orderBundleProducts->product_id = $diagonCour->fksimpleproduct_id;
                        $orderBundleProducts->product_name = $diagonCour->name;
                        $orderBundleProducts->bundle_product_id = $product_id;
                        $orderBundleProducts->product_price = $price;
                        $orderBundleProducts->product_qty = $qty;
                        $orderBundleProducts->save();
                    }
                }

                $orderProducts = OrderProducts::find($opid);
                $orderProducts->product_price = $totalPrice;
                $orderProducts->save();

            }*/
        }

        }

        }

        return redirect('cart')->with('success_msg','Cart Updated Successfully');
        

    }

    public function deleteProduct($id)
    {
        $deleteProduct = OrderProducts::where('id',$id)->delete();
        $deleteBundleProduct = OrderBundleProducts::where('order_product_id',$id)->delete();
        return redirect('cart')->with('success_msg','Product Removed Successfully');
    }


}