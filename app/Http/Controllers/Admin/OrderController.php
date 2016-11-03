<?php namespace App\Http\Controllers\Admin;

require_once('AfterShip/Exception/AftershipException.php');
require_once('AfterShip/Core/Request.php');
require_once('AfterShip/Couriers.php');
require_once('AfterShip/Trackings.php');
require_once('AfterShip/Notifications.php');
require_once('AfterShip/LastCheckPoint.php');

use Response;
use AfterShip;
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
use App\Models\BankDetails;
use App\Models\MailContents;
use App\Models\MailContentsTracking;
use App\Models\User;
use App\Helper\AdminHelper;

use Session;
use Config;
use Hash;
use Mail;
use DB;

class OrderController extends Controller {

	public function __construct()
    {
    	$this->orderStatus = Config::get('custom.orderStatus');
    	$this->countries = Config::get('custom.country');
    	$this->paymentType = Config::get('custom.paymentType');
    	$this->currencies = Config::get('custom.currency');
        $this->afterShipKey = Config::get('custom.afterShipKey');
        //$trackings = new AfterShip\Trackings($this->afterShipKey);
        $this->enabledCurrency = new EnabledCurrency;
    }

    public function addOrder(Request $request)
    {
        if($request->isMethod('post')){

       //     echo '<pre>'; print_r($request->all()); echo '</pre>'; 

            $products = $request->product;
            $prices = $request->price;
            $quantity = $request->quantity;
            $total_price = $request->total_price;
            $counter = $request->counter;

            $subtotal = $request->subtotal;
            $shipping_charge = $request->shipping_charge;
            $total = $request->total;
            $payment_type = $request->payment_type;
            $discount_amount = $request->discount_amount;
            $discount_comment = $request->discount_comment;

            $userId = $request->customer;
            $email = $request->email;
            $phone = $request->phone;
            $billing_name = $request->billing_name;
            $billing_address1 = $request->billing_address1;
            $billing_address2 = $request->billing_address2;
            $billing_city = $request->billing_city;
            $billing_state = $request->billing_state;
            $billing_country = $request->billing_country;
            $billing_zip = $request->billing_zip;
            $shipping_name = $request->shipping_name;
            $shipping_address1 = $request->shipping_address1;
            $shipping_address2 = $request->shipping_address2;
            $shipping_city = $request->shipping_city;
            $shipping_state = $request->shipping_state;
            $shipping_country = $request->shipping_country;
            $shipping_zip = $request->shipping_zip;
            $semail = $request->semail;
            $sphone = $request->sphone;

            $order_note = $request->order_note;
            $note_type = $request->note_type;

            $region = $request->region;
            $currency = $request->currency;
            $symbol = $request->symbol;

            $ip = $_SERVER['REMOTE_ADDR'];

            $orderSession = new OrderSession;
            if($userId!=0) {
                $orderSession->user_id = $userId;
            }
            $orderSession->order_region = $region;
            $orderSession->order_country = $billing_country;
            $orderSession->order_currency = $currency;
            $orderSession->order_symbol = $symbol;
            $orderSession->order_ip_address = $ip;
            $orderSession->order_by = 1;
            $orderSession->save();

            $orderSessionId = $orderSession->id;

            if(isset($products) && !empty($products)) {
                foreach ($products as $key => $value) {
                    $productId = $value;
                    $price = $prices[$key];
                    $qty = $quantity[$key];
                    $totalPrices = $total_price[$key];

                    $getProductType = Products::select('product_type')->where('id',$productId)->first();

                    if($getProductType->product_type==1) {

                        $getProduct = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                          ->select('products.id','t2.sku_name as name')->where('products.id',$productId)
                          ->where('t2.fkregion_id',$region)->where('products.product_type',1)->first();
                        
                        $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',$orderSessionId)->where('product_type',1)->where('product_id',$productId)->first();


                        /*if(isset($checkProduct->id)) {
                            $pqty = $checkProduct->product_qty+$qty;

                            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_qty = $pqty;
                            $orderProducts->save();
                        } else {*/
                            $orderProducts = new OrderProducts;
                            $orderProducts->oid = $orderSessionId;
                            $orderProducts->product_type=1;
                            $orderProducts->product_id = $productId;
                            $orderProducts->product_name = $getProduct->name;
                            $orderProducts->product_price = $price;
                            $orderProducts->product_qty = $qty;
                            $orderProducts->ordered_from = 'admin';
                            $orderProducts->save();
                       // }


                    } else if($getProductType->product_type==2) {


                        $getProduct = Products::select('id','name')->where('id',$productId)->where('product_type',2)->first();
                        $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',$orderSessionId)->where('product_type',2)->where('product_id',$productId)->first();
                       /* if(isset($checkProduct->id)) {

                            $selectedCourses = Courses::where('id',$checkProduct->product_course)->first();

                            $actualQty = $qty+$checkProduct->product_qty;

                            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_course = $checkProduct->product_course;
                            $orderProducts->product_qty = $actualQty;
                            $orderProducts->save();

                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$checkProduct->product_course)->get();

                            $totalPrice = 0.00;

                            $difference = ($actualQty/$selectedCourses->rate_multiply);

                            if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                                foreach($diagnosisCourse as $diagonCour) {
                                    $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$region)->first();
                                    
                                    $orderBundlePro = OrderBundleProducts::where('oid',$orderSessionId)->where('product_id',$diagonCour->fksimpleproduct_id)->where('order_product_id',$checkProduct->id)->where('bundle_product_id',$productId)->first();

                                 //   $qty = $actualQty*$diagonCour->quantity;

                                    $bqty = $difference*$diagonCour->quantity;

                                    if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                        $bprice = round($productPrice->sales_price);
                                    } else {
                                        $bprice = round($productPrice->regular_price);
                                    }
            //echo $price; die();
                                    $totalPrice += $diagonCour->quantity*$price;
                                    
                                    $orderBundleProducts = OrderBundleProducts::find($orderBundlePro->id);
                                    $orderBundleProducts->product_price = $bprice;
                                    $orderBundleProducts->product_qty = $bqty;
                                    $orderBundleProducts->save();
                                }
                            }

                            $totalPrice = round($totalPrice/$product_qty);

            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_price = $totalPrice;
                            $orderProducts->save();
                            


                        } else {*/

                            $diagnosisPrices = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();

                $diagnosisCourses = ProductBundle::join('courses as t2', 'product_bundle.fkcourse_id', '=', 't2.id')
                                   ->select('product_bundle.fkcourse_id','t2.id','t2.rate_multiply')
                                   ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrices->id)
                                   ->groupBy('product_bundle.fkcourse_id')->orderBy('product_bundle.fkcourse_id','ASC')->first();

                $product_course = $diagnosisCourses->fkcourse_id;

                            $orderProducts = new OrderProducts;
                            $orderProducts->oid = $orderSessionId;
                            $orderProducts->product_type=2;
                            $orderProducts->product_id = $productId;
                            $orderProducts->product_course = $product_course;
                            $orderProducts->product_name = $getProduct->name;
                            $orderProducts->product_qty = $qty;
                            $orderProducts->product_price = $price;
                            $orderProducts->ordered_from = 'admin';
                            $orderProducts->save();

                            $opid = $orderProducts->id;

                            $selectedCourses = Courses::where('id',$product_course)->first();

                             $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$product_course)->get();

                                           $firstDiagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',1)->first();

                            if($currency!='INR') {

                                
                                if(isset($firstDiagnosisCourse->id)) {

                                    $selCourse = ($selectedCourses->rate_multiply);

                                    $difference = ($qty/$selCourse);

                                } else {

                                    $selCourse = ($selectedCourses->rate_multiply/2);

                                    $difference = ($qty/$selCourse);

                                }

                            } else {
                                $difference = ($qty/$selectedCourses->rate_multiply);
                            }

                         //   echo $difference;

                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$product_course)->get();

                            $totalPrice = 0.00;



                            if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                                foreach($diagnosisCourse as $diagonCour) {
                                    $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$region)->first();
                                    //$qty = $product_qty*$diagonCour->quantity;
                                    $bqty = $difference*$diagonCour->quantity;
                                  //  echo $bqty; die();
                                    if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                        $bprice = round($productPrice->sales_price);
                                    } else {
                                        $bprice = round($productPrice->regular_price);
                                    }

                                    $totalPrice += $diagonCour->quantity*$bprice;
                                    
                                    $orderBundleProducts = new OrderBundleProducts;
                                    $orderBundleProducts->oid = $orderSessionId;
                                    $orderBundleProducts->order_product_id = $opid;
                                    $orderBundleProducts->product_id = $diagonCour->fksimpleproduct_id;
                                    $orderBundleProducts->product_name = $productPrice->sku_name;
                                    $orderBundleProducts->bundle_product_id = $productId;
                                    $orderBundleProducts->product_price = $bprice;
                                    $orderBundleProducts->product_qty = $bqty;
                                    $orderBundleProducts->save();
                                }
                            }

                           // echo $totalPrice; die();

                           /*  $totalPrice = round($totalPrice/$qty);

                            $orderProducts = OrderProducts::find($opid);
                            $orderProducts->product_price = $totalPrice;
                            $orderProducts->save();*/

                       // }


                    }

                }
            }


            if($currency!='INR') {
                $currencyRates = CurrencyRates::where('from_currency',$currency)->where('delete_status',0)->first();
                if(isset($currencyRates->id)) {
                    $grandTotalInr = round($total/$currencyRates->rate);
                    $shippingChargeInr = round($shipping_charge/$currencyRates->rate);
                    $discountAmountInr = round($discount_amount/$currencyRates->rate);
                } else {
                    $grandTotalInr = $total;
                    $shippingChargeInr = $shipping_charge;
                    $discountAmountInr = $discount_amount;
                }
            } else {
                $grandTotalInr = $total;
                $shippingChargeInr = $shipping_charge;
                $discountAmountInr = $discount_amount;
            }

            $date = date('Y-m-d H:i:s');

            $orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region',$region)->orderBy('order_details.id','DESC')->first();

                if(isset($orderCheckNo->invoice_no)) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
                } else {
                    if($region==1) {
                        $invoiceNo = "I6001";
                    } else {
                       // $invoiceNo = "EXPT401";
$orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region','!=',1)->orderBy('order_details.id','DESC')->first();
if(isset($orderCheckNo->invoice_no)) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
                } else {
                        $invoiceNo = "EXPT401";
}
                    }
                }


            /*$getCount = OrderSession::where('order_region',$region)->count();
            $getCount++;
            if($region==1) {
                $invoiceNo = "I".($getCount+5999);
            } else {
                $invoiceNo = "EXPT".($getCount+400);
            }*/
            //$invoiceNo = "#".$region.$getCount;
            $orderDetailUpdate = new OrderDetails;
            $orderDetailUpdate->invoice_no = $invoiceNo;
            $orderDetailUpdate->payment_type = $payment_type;
            $orderDetailUpdate->oid = $orderSessionId;
            $orderDetailUpdate->fkcustomer_id = $userId;
            $orderDetailUpdate->subtotal = $subtotal;
            $orderDetailUpdate->shipping_charge = $shipping_charge;
            $orderDetailUpdate->shipping_charge_inr = $shippingChargeInr;
            $orderDetailUpdate->discount_amount = $discount_amount;
            $orderDetailUpdate->discount_amount_inr = $discountAmountInr;
            $orderDetailUpdate->discount_comment = $discount_comment;
            $orderDetailUpdate->grand_total = $total;
            $orderDetailUpdate->grand_total_inr = $grandTotalInr;
            $orderDetailUpdate->order_status = 0;
            $orderDetailUpdate->order_placed_date = $date;
            $orderDetailUpdate->save();

            $orderDetailId = $orderDetailUpdate->id;

            $ship_to = $request->same_as_billing;

            $orderAddress = new OrderAddress;
            $orderAddress->order_id = $orderDetailId;
            $orderAddress->billing_name = $billing_name;
            $orderAddress->billing_email = $email;
            $orderAddress->billing_phone = $phone;
            $orderAddress->billing_address1 = $billing_address1;
            $orderAddress->billing_address2 = $billing_address2;
            $orderAddress->billing_state = $billing_state;
            $orderAddress->billing_city = $billing_city;
            $orderAddress->billing_country = $billing_country;
            $orderAddress->billing_zip = $billing_zip;

            if($ship_to!=1) {
                $orderAddress->shipping_name = $shipping_name;
                $orderAddress->shipping_address1 = $shipping_address1;
                $orderAddress->shipping_address2 = $shipping_address2;
                $orderAddress->shipping_state = $shipping_state;
                $orderAddress->shipping_city = $shipping_city;
                $orderAddress->shipping_country = $shipping_country;
                $orderAddress->shipping_zip = $shipping_zip;
                $orderAddress->shipping_email = $semail;
                $orderAddress->shipping_phone = $sphone;
                
            } else {
                $orderAddress->shipping_name = $billing_name;
                $orderAddress->shipping_address1 = $billing_address1;
                $orderAddress->shipping_address2 = $billing_address2;
                $orderAddress->shipping_state = $billing_state;
                $orderAddress->shipping_city = $billing_city;
                $orderAddress->shipping_country = $billing_country;
                $orderAddress->shipping_zip = $billing_zip;
                $orderAddress->same_as_billing = $ship_to;
                $orderAddress->shipping_email = $email;
                $orderAddress->shipping_phone = $phone;
            }

            $orderAddress->save();

            if(!empty($order_note)) {
                $orderNote = new OrderNotes;
                $orderNote->order_id = $orderDetailId;
                $orderNote->notes = $order_note;
                $orderNote->type = $note_type;
                $orderNote->save();
            }


                $orderDetailUpdate = OrderDetails::find($orderDetailId);
                $orderDetailUpdate->fkcustomer_id = $userId;
                $orderDetailUpdate->payment_type = 2;
                $orderDetailUpdate->order_status = 1;
                $orderDetailUpdate->order_placed_date = date('Y-m-d H:i:s');
                $orderDetailUpdate->save();

                $orderSessionUpdate = OrderSession::find($orderSessionId);
                $orderSessionUpdate->order_status = 2;
                $orderSessionUpdate->save();

                $orderSession = OrderSession::where('id',$orderSessionId)->first();
                $orderProducts = OrderProducts::where('oid',$orderSessionId)->orderBy('product_name','ASC')->get();
                $orderBundleProducts = OrderBundleProducts::where('oid',$orderSessionId)->orderBy('product_name','ASC')->get();
                $orderDetail = OrderDetails::where('oid',$orderSessionId)->first();
                $orderAddress = OrderAddress::where('order_id',$orderDetailId)->first();
                $bankDetails = BankDetails::where('id',1)->first();

                $data['orderSession'] = $orderSession;
                $data['orderProducts'] = $orderProducts;
                $data['orderBundleProducts'] = $orderBundleProducts;
                $data['orderDetail'] = $orderDetail;
                $data['orderAddress'] = $orderAddress;
                $data['bankDetails'] = $bankDetails;
                $data['countries'] = $this->countries;

                $subject = 'Your Grocare India Order Receipt';
                                Mail::send('emails.orderBankTransfer', $data, function($message)
                                        use($email,$subject){
                                    $message->from('no-reply@grocare.com', 'Grocare');
									$message->cc('info@grocare.com');
                                    $message->bcc('neethicommunicate@gmail.com');
                                    $message->bcc('ummemark@gmail.com');
                                    $message->to($email)->subject($subject);
                                });

                                $orderDetailUpdate = OrderDetails::find($orderDetailId);
                                $orderDetailUpdate->order_email = 1;
                                $orderDetailUpdate->save();

            return redirect('admin/orders/edit-ordered-items/'.$orderSessionId.'/'.$orderDetailId)->with('success_msg','Your order is placed successfully');
            //return redirect('admin/orders/process/'.$orderDetailId)->with('success_msg','Your order is placed successfully');

          // die();
        
        } else {
            $currencySelected = $request->currency;
            $getRegion = Regions::where("currency",$currencySelected)->where('delete_status',0)->first();
            $getSymbol = CurrencyRates::where('from_currency',$currencySelected)->where('delete_status',0)->first();

            $simpleProducts = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price')
                       ->where('products.product_type','=',1)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$getRegion->id)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->get();

            $bundleProducts = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis_products as t3', 't3.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis as t4', 't3.fkdiagnosis_id', '=', 't4.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price','t4.name as diagnosis_name','t4.diagnosis_slug','t4.id as did')
                       ->where('products.product_type','=',2)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$getRegion->id)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->groupBy('t3.fkproduct_id')->get();

            $customers = User::select('id','name','email')->where('user_type',2)->where('delete_status',0)->get();

            return view('admin/orders/add')->with(array('simpleProducts'=>$simpleProducts,'bundleProducts'=>$bundleProducts,
                   'currencySelected'=>$currencySelected,'getRegion'=>$getRegion,'getSymbol'=>$getSymbol,'countries'=>$this->countries,
                   'currencies'=>$this->currencies,'customers'=>$customers,'paymentType'=>$this->paymentType));

            //echo '<pre>'; print_r($bundleProducts); echo '</pre>';
            //echo $currencySelected;
        }
    }

    public function editOrderedItems(Request $request,$id,$id2)
    {
        if($request->isMethod('post')){

            $products = $request->product;
            $prices = $request->price;
            $quantity = $request->quantity;
            $total_price = $request->total_price;
            $counter = $request->counter;

            $subtotal = $request->subtotal;
            $shipping_charge = $request->shipping_charge;
            $total = $request->total;
            $discount_amount = $request->discount_amount;
            $discount_comment = $request->discount_comment;

            $region = $request->region;
            $currency = $request->currency;
            $symbol = $request->symbol;

            $orderSessionId = $id;
			
			$packed_file = $request->file('packed_file');
			
			if($packed_file!='') {					
				if(substr($packed_file->getMimeType(), 0, 5) == 'image') {
					$newname = str_random(5);
					$ext = $packed_file->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;
					$destination = 'public/uploads/packed_images/';
					if($packed_file->move($destination, $filename)){
						copy($destination . $filename, $destination . $newFilename);
						$packed_order_image = OrderDetails::where('id',$id2)
											->update(['packed_order_image' => $newFilename]);
					}	
				}				 			
			} 
			
         


            if(isset($products) && !empty($products)) {
                foreach ($products as $key => $value) {
                    $productId = $value;
                    $price = $prices[$key];
                    $qty = $quantity[$key];
                    $totalPrices = $total_price[$key];

                    $getProductType = Products::select('product_type')->where('id',$productId)->first();

                    if($getProductType->product_type==1) {

                        $getProduct = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                          ->select('products.id','t2.sku_name as name')->where('products.id',$productId)
                          ->where('t2.fkregion_id',$region)->where('products.product_type',1)->first();
                        
                        $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',$orderSessionId)->where('product_type',1)->where('product_id',$productId)->first();


                        if(isset($checkProduct->id)) {

                            if($checkProduct->product_qty==$qty) {
                                continue;
                            }

                            $pqty = $qty;

                            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_qty = $pqty;
                            $orderProducts->save();
                        } else {
                            $orderProducts = new OrderProducts;
                            $orderProducts->oid = $orderSessionId;
                            $orderProducts->product_type=1;
                            $orderProducts->product_id = $productId;
                            $orderProducts->product_name = $getProduct->name;
                            $orderProducts->product_price = $price;
                            $orderProducts->product_qty = $qty;
                            $orderProducts->ordered_from = 'admin';
                            $orderProducts->save();
                        }


                    } else if($getProductType->product_type==2) {


                        $getProduct = Products::select('id','name')->where('id',$productId)->where('product_type',2)->first();
                        $checkProduct = OrderProducts::select('id','product_price','product_qty','product_course')->where('oid',$orderSessionId)->where('product_type',2)->where('product_id',$productId)->first();
                        if(isset($checkProduct->id)) {

                            if($checkProduct->product_qty==$qty) {
                                continue;
                            }

                            $selectedCourses = Courses::where('id',$checkProduct->product_course)->first();

                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$checkProduct->product_course)->get();

                            $firstDiagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',1)->first();

                           // $actualQty = $qty+$checkProduct->product_qty;

                            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_course = $checkProduct->product_course;
                            $orderProducts->product_qty = $qty;
                            $orderProducts->product_price = $price;
                            $orderProducts->save();

                           /* if($currency!='INR') {

                                if(isset($firstDiagnosisCourse->id)) {

                                    $selCourse = ($selectedCourses->rate_multiply);

                                    $difference = ($qty/$selCourse);

                                } else {

                                    $selCourse = ($selectedCourses->rate_multiply/2);

                                    $difference = ($qty/$selCourse);

                                }

                            } else {
                                $difference = ($qty/$selectedCourses->rate_multiply);
                            }*/

                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$checkProduct->product_course)->get();

                            $totalPrice = 0.00;

                            //$difference = ($actualQty/$selectedCourses->rate_multiply);

                            if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                                foreach($diagnosisCourse as $diagonCour) {
                                    $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$region)->first();
                                    
                                    $orderBundlePro = OrderBundleProducts::where('oid',$orderSessionId)->where('product_id',$diagonCour->fksimpleproduct_id)->where('order_product_id',$checkProduct->id)->where('bundle_product_id',$productId)->first();

                                 //   $qty = $actualQty*$diagonCour->quantity;

                                    $bqty = $qty*$diagonCour->quantity;

                                    if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                        $bprice = round($productPrice->sales_price);
                                    } else {
                                        $bprice = round($productPrice->regular_price);
                                    }
            //echo $price; die();
                                    $totalPrice += $diagonCour->quantity*$price;
                                    
                                    $orderBundleProducts = OrderBundleProducts::find($orderBundlePro->id);
                                    $orderBundleProducts->product_price = $bprice;
                                    $orderBundleProducts->product_qty = $bqty;
                                    $orderBundleProducts->save();
                                }
                            }

                         /*   $totalPrice = round($totalPrice/$product_qty);

            $orderProducts = OrderProducts::find($checkProduct->id);
                            $orderProducts->product_price = $totalPrice;
                            $orderProducts->save();*/
                            


                        } else {

                            $diagnosisPrices = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();

                $diagnosisCourses = ProductBundle::join('courses as t2', 'product_bundle.fkcourse_id', '=', 't2.id')
                                   ->select('product_bundle.fkcourse_id','t2.id','t2.rate_multiply')
                                   ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrices->id)
                                   ->groupBy('product_bundle.fkcourse_id')->orderBy('product_bundle.fkcourse_id','ASC')->first();

                $product_course = $diagnosisCourses->fkcourse_id;

                            $orderProducts = new OrderProducts;
                            $orderProducts->oid = $orderSessionId;
                            $orderProducts->product_type=2;
                            $orderProducts->product_id = $productId;
                            $orderProducts->product_course = $product_course;
                            $orderProducts->product_name = $getProduct->name;
                            $orderProducts->product_qty = $qty;
                            $orderProducts->product_price = $price;
                            $orderProducts->ordered_from = 'admin';
                            $orderProducts->save();

                            $opid = $orderProducts->id;

                            $selectedCourses = Courses::where('id',$product_course)->first();


                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$product_course)->get();

                            $firstDiagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                               ->select('product_bundle.*','t2.name')
                               ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                               ->where('product_bundle.fkcourse_id',1)->first();


                            /*if($currency!='INR') {

                                if(isset($firstDiagnosisCourse->id)) {

                                    $selCourse = ($selectedCourses->rate_multiply);

                                    $difference = ($qty/$selCourse);

                                } else {

                                    $selCourse = ($selectedCourses->rate_multiply/2);

                                    $difference = ($qty/$selCourse);

                                }

                            } else {
                                $difference = ($qty/$selectedCourses->rate_multiply);
                            }*/

                         //   echo $difference;

                            $diagnosisPrice = ProductRegion::where('fkregion_id',$region)->where('fkproduct_id',$productId)->first();
                        
                            $diagnosisCourse = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                           ->select('product_bundle.*','t2.name')
                                           ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                           ->where('product_bundle.fkcourse_id',$product_course)->get();

                            $totalPrice = 0.00;



                            if(isset($diagnosisCourse) && !empty($diagnosisCourse)) {
                                foreach($diagnosisCourse as $diagonCour) {
                                    $productPrice = ProductRegion::where('fkproduct_id',$diagonCour->fksimpleproduct_id)->where('fkregion_id',$region)->first();
                                    //$qty = $product_qty*$diagonCour->quantity;
                                    $bqty = $qty*$diagonCour->quantity;
                                  //  echo $bqty; die();
                                    if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                        $bprice = round($productPrice->sales_price);
                                    } else {
                                        $bprice = round($productPrice->regular_price);
                                    }

                                    $totalPrice += $diagonCour->quantity*$bprice;
                                    
                                    $orderBundleProducts = new OrderBundleProducts;
                                    $orderBundleProducts->oid = $orderSessionId;
                                    $orderBundleProducts->order_product_id = $opid;
                                    $orderBundleProducts->product_id = $diagonCour->fksimpleproduct_id;
                                    $orderBundleProducts->product_name = $productPrice->sku_name;
                                    $orderBundleProducts->bundle_product_id = $productId;
                                    $orderBundleProducts->product_price = $bprice;
                                    $orderBundleProducts->product_qty = $bqty;
                                    $orderBundleProducts->save();
                                }
                            }

                           // echo $totalPrice; die();

                           /*  $totalPrice = round($totalPrice/$qty);

                            $orderProducts = OrderProducts::find($opid);
                            $orderProducts->product_price = $totalPrice;
                            $orderProducts->save();*/

                        }


                    }

                }
            }


            if($currency!='INR') {
                $currencyRates = CurrencyRates::where('from_currency',$currency)->where('delete_status',0)->first();
                if(isset($currencyRates->id)) {
                    $grandTotalInr = round($total/$currencyRates->rate);
                    $shippingChargeInr = round($shipping_charge/$currencyRates->rate);
                    $discountAmountInr = round($discount_amount/$currencyRates->rate);
                } else {
                    $grandTotalInr = $total;
                    $shippingChargeInr = $shipping_charge;
                    $discountAmountInr = $discount_amount;
                }
            } else {
                $grandTotalInr = $total;
                $shippingChargeInr = $shipping_charge;
                $discountAmountInr = $discount_amount;
            }

            $status = $request->order_status;
            $userId = $request->customer;
            $refund_amount = $request->refund_amount;
            $payment_type = $request->payment_type;

            $orderDetailUpdate = OrderDetails::find($id2);
            $oldStatus = $orderDetailUpdate->order_status;
            $orderDetailUpdate->fkcustomer_id = $userId;
            $orderDetailUpdate->payment_type = $payment_type;
            $orderDetailUpdate->subtotal = $subtotal;
            $orderDetailUpdate->shipping_charge = $shipping_charge;
            $orderDetailUpdate->shipping_charge_inr = $shippingChargeInr;
            $orderDetailUpdate->discount_amount = $discount_amount;
            $orderDetailUpdate->discount_amount_inr = $discountAmountInr;
            $orderDetailUpdate->discount_comment = $discount_comment;
            $orderDetailUpdate->grand_total = $total;
            $orderDetailUpdate->grand_total_inr = $grandTotalInr;
            $orderDetailUpdate->refund_amount = $refund_amount;
            if($oldStatus!=$status) {
                $orderDetailUpdate->order_status = $status;
                $dataTime = date('Y-m-d H:i:s');
                if($status==3) {
                    $orderDetailUpdate->order_process_date = $dataTime;
                } else if($status==6) {
                    $orderDetailUpdate->order_completed_date = $dataTime;
                } else if($status==7) {
                    $orderDetailUpdate->order_cancelled_date = $dataTime;
                    $orderDetailUpdate->cancelled_by = 1;
                } else if($status==5) {
                    $orderDetailUpdate->order_refund_date = $dataTime;
                }
            }
            $orderDetailUpdate->save();

            $email = $request->email;
            $phone = $request->phone;
            $billing_name = $request->billing_name;
            $billing_address1 = $request->billing_address1;
            $billing_address2 = $request->billing_address2;
            $billing_city = $request->billing_city;
            $billing_state = $request->billing_state;
            $billing_country = $request->billing_country;
            $billing_zip = $request->billing_zip;
            $shipping_name = $request->shipping_name;
            $shipping_address1 = $request->shipping_address1;
            $shipping_address2 = $request->shipping_address2;
            $shipping_city = $request->shipping_city;
            $shipping_state = $request->shipping_state;
            $shipping_country = $request->shipping_country;
            $shipping_zip = $request->shipping_zip;
            $semail = $request->semail;
            $sphone = $request->sphone;
            $ship_to = $request->same_as_billing;
            $courier = $request->courier;

            $orderAdd = OrderAddress::where('order_id',$id2)->first();

            $orderAddress =  OrderAddress::find($orderAdd->id);
            $orderAddress->billing_name = $billing_name;
            $orderAddress->billing_email = $email;
            $orderAddress->billing_phone = $phone;
            $orderAddress->billing_address1 = $billing_address1;
            $orderAddress->billing_address2 = $billing_address2;
            $orderAddress->billing_state = $billing_state;
            $orderAddress->billing_city = $billing_city;
            $orderAddress->billing_country = $billing_country;
            $orderAddress->billing_zip = $billing_zip;
            $orderAddress->selected_courier = $courier;

            if($ship_to!=1) {
                $orderAddress->shipping_name = $shipping_name;
                $orderAddress->shipping_address1 = $shipping_address1;
                $orderAddress->shipping_address2 = $shipping_address2;
                $orderAddress->shipping_state = $shipping_state;
                $orderAddress->shipping_city = $shipping_city;
                $orderAddress->shipping_country = $shipping_country;
                $orderAddress->shipping_zip = $shipping_zip;
                $orderAddress->shipping_email = $semail;
                $orderAddress->shipping_phone = $sphone;
                
            } else {
                $orderAddress->shipping_name = $billing_name;
                $orderAddress->shipping_address1 = $billing_address1;
                $orderAddress->shipping_address2 = $billing_address2;
                $orderAddress->shipping_state = $billing_state;
                $orderAddress->shipping_city = $billing_city;
                $orderAddress->shipping_country = $billing_country;
                $orderAddress->shipping_zip = $billing_zip;
                $orderAddress->same_as_billing = $ship_to;
                $orderAddress->shipping_email = $email;
                $orderAddress->shipping_phone = $phone;
            }

            $orderAddress->save();

            $counters = $request->counters;

            $orderAddress = OrderAddress::where('order_id',$id2)->first();
        $courier = Couriers::where('id',$orderAddress->selected_courier)->first();

            for($i=1;$i<=$counters;$i++) {

                $tid = "tid_".$i;
                $trackingNumber = "tracking_number_".$i;
                $pro = "product_".$i;

                $products = $request->$pro;
                $selectedProduct = '';
                if(!empty($products)) {
                    $selectedProduct = implode(',', $products);
                }

               // return $products;
               // return $request->$tid;

                if($request->$trackingNumber!='') {


                    if($request->$tid!=0) {

                        $tracking = OrderTracking::find($request->$tid);
                        $tracking->tracking_number = trim($request->$trackingNumber);
                        $tracking->order_id = $id2;
                        $tracking->products = $selectedProduct;
                        $tracking->save();

                    } else {

                        $tracking = new OrderTracking;
                        $tracking->tracking_number = trim($request->$trackingNumber);
                        $tracking->order_id = $id2;
                        $tracking->products = $selectedProduct;
                        $tracking->save();

                    }

                    $trackings = new AfterShip\Trackings($this->afterShipKey);
                    $tracking_info = array(
                        'slug'    => $courier->slug,
                        'title'   => trim($request->$trackingNumber),
                        'emails'  => $orderAddress->billing_email,
                        'smses'   => $orderAddress->billing_phone
                    );
                    $response = $trackings->create(trim($request->$trackingNumber), $tracking_info);

                  //  var_dump($response); die();

                }

            }

            $order_note = $request->order_note;
            $note_type = $request->type;

            if(!empty($order_note)) {
                $orderNote = new OrderNotes;
                $orderNote->order_id = $id2;
                $orderNote->notes = $order_note;
                $orderNote->type = $note_type;
                $orderNote->save();
            }


            if($status==6 && $oldStatus!=$status) {
                $sendDietChart = AdminHelper::sendDietChart($id2);
            }

            return redirect('admin/orders/edit-ordered-items/'.$id.'/'.$id2)->with('success_msg','Order Details Updated Successfully');

            /*if($status<=4) {
                return redirect('admin/orders/process/'.$id2)->with('success_msg','Order Items Edited Successfully');
            }

            if($status==6) {
                return redirect('admin/orders/completed/'.$id2)->with('success_msg','Order Items Edited Successfully');
            }

            if($status==7) {
                return redirect('admin/orders/cancelled/'.$id2)->with('success_msg','Order Items Edited Successfully');
            }

            if($status==5) {
                return redirect('admin/orders/refunded/'.$id2)->with('success_msg','Order Items Edited Successfully');
            }*/



        } else {

            $orderSession = OrderSession::where('id',$id)->first();
            $orderDetail = OrderDetails::where('id',$id2)->first();
            $orderAddress = OrderAddress::where('order_id',$id2)->first();



            $orderProducts = OrderProducts::where('oid',$id)->get();

            $currencySelected = $orderSession->order_currency;
            $getRegion = Regions::where("currency",$currencySelected)->where('delete_status',0)->first();
            $getSymbol = CurrencyRates::where('from_currency',$currencySelected)->where('delete_status',0)->first();

            $simpleProducts = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price')
                       ->where('products.product_type','=',1)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$getRegion->id)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->get();

            $bundleProducts = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis_products as t3', 't3.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis as t4', 't3.fkdiagnosis_id', '=', 't4.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price','t4.name as diagnosis_name','t4.diagnosis_slug','t4.id as did')
                       ->where('products.product_type','=',2)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$getRegion->id)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->groupBy('t3.fkproduct_id')->get();

            $customers = User::select('id','name')->where('user_type',2)->where('delete_status',0)->get();

            $couriers = Couriers::where('delete_status',0)->get();
            $orderTracking = OrderTracking::where('order_id',$id2)->get();
            $orderNotes = OrderNotes::where('order_id',$id2)->get();
            $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();
			
			$mailContents=MailContents::selectRaw('id,title,subject')->where('delete_status',0)->get();
			
			$mailContentsTrackList=MailContentsTracking::selectRaw('m.title')
															->join('mail_contents as m','m.id','=','mail_content_tracking.email_content_id')
															->where('mail_content_tracking.order_id',$id2)
															->where('mail_content_tracking.delete_status',0)
															->where('m.delete_status',0)
															->get();

            $pastOrderCount = 0;

            if($orderDetail->fkcustomer_id!=0) {
                $pastOrderCount = OrderDetails::where('fkcustomer_id',$orderDetail->fkcustomer_id)->where('id','!=',$id2)->where('delete_status',0)->count();
            }

            //return $orderDetail->fkcustomer_id;


            return view('admin/orders/edit')->with(array('simpleProducts'=>$simpleProducts,'bundleProducts'=>$bundleProducts,
                   'currencySelected'=>$currencySelected,'getRegion'=>$getRegion,'getSymbol'=>$getSymbol,'countries'=>$this->countries,
                   'currencies'=>$this->currencies,'orderSession'=>$orderSession,'orderDetail'=>$orderDetail,
                   'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts,'customers'=>$customers,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
                   'inrSymbol'=>$inrSymbol,'orderStatus'=>$this->orderStatus,'paymentType'=>$this->paymentType,'mailContents'=>$mailContents,
                   'mailContentsTrackList'=>$mailContentsTrackList,'pastOrderCount'=>$pastOrderCount));
        }
    }


    public function deleteItems($id,$id2,$id3)
    {
        $orderSession = OrderSession::where('id',$id)->first();
        $orderDetail = OrderDetails::where('id',$id2)->first();

        $checkProduct = OrderProducts::where('id',$id3)->first();
        $deleteProduct = OrderProducts::where('id',$id3)->delete();
        if($checkProduct->product_type==2) {
            $deleteBundleProduct = OrderBundleProducts::where('order_product_id',$id3)->delete();
        } 

        $productPrice = round($checkProduct->product_price*$checkProduct->product_qty);
        $subtotal = $orderDetail->subtotal-$productPrice;
        $grandTotal = $subtotal+$orderDetail->shipping_charge;

        if($orderSession->order_currency!='INR') {
                $currencyRates = CurrencyRates::where('from_currency',$orderSession->order_currency)->where('delete_status',0)->first();
                if(isset($currencyRates->id)) {
                    $grandTotalInr = round($grandTotal/$currencyRates->rate);
                } else {
                    $grandTotalInr = $grandTotal;
                }
            } else {
                $grandTotalInr = $grandTotal;
            }


            $orderDetailUpdate = OrderDetails::find($id2);
            $orderDetailUpdate->subtotal = $subtotal;
            $orderDetailUpdate->grand_total = $grandTotal;
            $orderDetailUpdate->grand_total_inr = $grandTotalInr;
            $orderDetailUpdate->save();

        return redirect('admin/orders/edit-ordered-items/'.$id.'/'.$id2)->with('success_msg','Item removed successfully');

    }

    public function checkEmail($id)
    {
        $orderSession = OrderSession::where('id',$id)->first();
        $orderProducts = OrderProducts::where('oid',$id)->orderBy('product_name','ASC')->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$id)->orderBy('product_name','ASC')->get();
        $orderDetail = OrderDetails::where('oid',$id)->first();
        $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
        $bankDetails = BankDetails::where('id',1)->first();

                $data['orderSession'] = $orderSession;
                $data['orderProducts'] = $orderProducts;
                $data['orderBundleProducts'] = $orderBundleProducts;
                $data['orderDetail'] = $orderDetail;
                $data['orderAddress'] = $orderAddress;
                $data['bankDetails'] = $bankDetails;
                $data['countries'] = $this->countries;

        $email = 'divagar.umm@gmail.com';

        $subject = 'You Grocare India order receipt';
                                Mail::send('emails.orderOnlineTransfer', $data, function($message)
                                        use($email,$subject){
                                    $message->from('no-reply@grocare.com', 'GROCARE');
                                    $message->to($email)->subject($subject);
                                });

        return view('emails.orderOnlineTransfer')->with(array('orderSession'=>$orderSession, 'orderProducts'=>$orderProducts,
               'orderBundleProducts'=>$orderBundleProducts, 'orderDetail'=>$orderDetail, 'orderAddress'=>$orderAddress,
               'bankDetails'=>$bankDetails,'countries'=>$this->countries));
    }

    public function getCustomerDetails(Request $request,$id)
    {
        if($request->isMethod('post')){
            $response = array();
            $userdetails = User::select('id','email')->where('id',$id)->first();
            $billingAddress = DB::table('user_address')->where('user_id',$id)->where('status',1)->first();
			if(!isset($billingAddress->id)) {
				$prevOrder = OrderDetails::select('t2.*')->join('order_address as t2', 'order_details.id', '=', 't2.order_id')->where('order_details.delete_status',0)
				->whereNotIn('order_details.order_status',['1','7','8'])->where('order_details.fkcustomer_id',$id)->orderBy('order_details.id','DESC')->first();
				if(isset($prevOrder->id)) {
					$billingAddress = array();
					$billingAddress['name'] = $prevOrder->billing_name;
					$billingAddress['address'] = $prevOrder->billing_address1;
					$billingAddress['address2'] = $prevOrder->billing_address2;
					$billingAddress['city'] = $prevOrder->billing_city;
					$billingAddress['state'] = $prevOrder->billing_state;
					$billingAddress['country'] = $prevOrder->billing_country;
					$billingAddress['postcode'] = $prevOrder->billing_zip;
				}
			}
            $shippingAddress = DB::table('user_address')->where('user_id',$id)->where('status',2)->first();
			if(!isset($shippingAddress->id)) {
				$prevOrder = OrderDetails::select('t2.*')->join('order_address as t2', 'order_details.id', '=', 't2.order_id')->where('order_details.delete_status',0)
				->whereNotIn('order_details.order_status',['1','7','8'])->where('order_details.fkcustomer_id',$id)->orderBy('order_details.id','DESC')->first();
				if(isset($prevOrder->id)) {
					$shippingAddress = array();
					$shippingAddress['name'] = $prevOrder->shipping_name;
					$shippingAddress['address'] = $prevOrder->shipping_address1;
					$shippingAddress['address2'] = $prevOrder->shipping_address2;
					$shippingAddress['city'] = $prevOrder->shipping_city;
					$shippingAddress['state'] = $prevOrder->shipping_state;
					$shippingAddress['country'] = $prevOrder->shipping_country;
					$shippingAddress['postcode'] = $prevOrder->shipping_zip;
				}
			}
			
			$details = array();
			$details['email'] = $userdetails->email;
			$details['phone'] = '';
			$details['sphone'] = '';
			$prevOrder = OrderDetails::select('t2.*')->join('order_address as t2', 'order_details.id', '=', 't2.order_id')->where('order_details.delete_status',0)
				->whereNotIn('order_details.order_status',['1','7','8'])->where('order_details.fkcustomer_id',$id)->orderBy('order_details.id','DESC')->first();
			if(isset($prevOrder->id)) {
				$details['phone'] = $prevOrder->billing_phone;
				$details['sphone'] = $prevOrder->shipping_phone;
			}
			
			
            $response['details'] = $details;
            $response['billingAddress'] = $billingAddress;
            $response['shippingAddress'] = $shippingAddress;
            return $response;
        }
    }

    public function getProduct(Request $request,$id)
    {
        if($request->isMethod('post')){
            $response = array();
            $orderProducts = OrderProducts::where('oid',$id)->get();
            $orderBundleProducts = OrderBundleProducts::where('oid',$id)->get();
            $response['orderProducts'] = $orderProducts;
            $response['orderBundleProducts'] = $orderBundleProducts;
            return $response;
        }
    }

    public function checkProductPrice(Request $request,$id)
    {
        if($request->isMethod('post')){
            $price = 0;
            $regionId = $request->region;
            $checkProduct = Products::select('id','product_type')->where('id',$id)->where('delete_status','=',0)->where('website_visible',1)
                            ->where('stock_status',1)->first();
            if($checkProduct->product_type==1) {
                $productList = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id','t2.regular_price','t2.sales_price')
                       ->where('products.product_type','=',1)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$regionId)->where('t2.enable',1)->where('products.id',$id)
                       ->orderBy('products.name','ASC')->first();
                      // return $productList;
                     //  var_dump($productList);
                if(isset($productList->id)) {

                    if($productList->sales_price!=0.00) {
                        $price = round($productList->sales_price);
                    } else {
                        $price = round($productList->regular_price);
                    }
                }
                
            } else {

                $diagnosisPrice = ProductRegion::where('fkregion_id',$regionId)->where('fkproduct_id',$id)->first();

                $diagnosisCourse = ProductBundle::join('courses as t2', 'product_bundle.fkcourse_id', '=', 't2.id')
                                   ->select('product_bundle.*','t2.course_name','t2.rate_multiply')
                                   ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                   ->groupBy('product_bundle.fkcourse_id')->orderBy('product_bundle.fkcourse_id','ASC')->get();
                
                $singlePrice = 0.00;

                if(!empty($diagnosisCourse)) {
                    $k=1;
                    foreach($diagnosisCourse as $diaCour) {
                        if($k==1) {
                            $displayCourse = $diaCour->course_name;
                            
                            $diagnosisCourseFirst = ProductBundle::join('products as t2', 'product_bundle.fksimpleproduct_id', '=', 't2.id')
                                   ->select('product_bundle.*','t2.name')
                                   ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                   ->where('product_bundle.fkcourse_id',$diaCour->fkcourse_id)->get();



                    if(isset($diagnosisCourseFirst) && !empty($diagnosisCourseFirst)) {
                        foreach($diagnosisCourseFirst as $diagonCourFirst) {
                            $productPrice = ProductRegion::where('fkproduct_id',$diagonCourFirst->fksimpleproduct_id)->where('fkregion_id',$regionId)->first();
                            
                            $qty = $diagonCourFirst->quantity;
                            if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                $sprice = round($productPrice->sales_price);
                            } else {
                                $sprice = round($productPrice->regular_price);
                            }

                            $singlePrice += $qty*$sprice;

                        }
                    }





                            break;
                        }
                    }
                }

                $price = $singlePrice;

            }

            return $price;
            
        }
    }

    public function all(Request $request)
    {
        $type = $request->type;
        if($request->isMethod('post')){
            $productSelected = $request->product;
            $countrySelected = $request->country;
            $courierSelected = $request->courier;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $tracking_number = $request->tracking_number;
        } else {
            $productSelected = '';
            $countrySelected = '';
            $courierSelected = '';
            $fromdate = '';
            $todate = '';
            $tracking_number = '';
        }

        if(!empty($fromdate) && !empty($todate)) {
            $fromdate = date('Y-m-d 00:00:00',strtotime($fromdate));
            $todate = date('Y-m-d 23:59:59',strtotime($todate));
        } else {
            $fromdate = date('Y-m-d 00:00:00', strtotime('today - 30 days'));
            $todate = date('Y-m-d 23:59:59');
        }

        $couriers = Couriers::where('delete_status',0)->get();
        $products = Products::where('delete_status',0)->get();

        $productArray = array();

        if(!empty($productSelected)) {
            $productArray[0] = 0;
            $checkProduct = OrderProducts::select('oid')->where('product_id',$productSelected)->get();
            if(isset($checkProduct) && !empty($checkProduct)) {
                foreach($checkProduct as $checkPro) {
                    $productArray[$checkPro->oid] = $checkPro->oid;
                }
            }
            $checkBundleProduct = OrderBundleProducts::select('oid')->where('product_id',$productSelected)->get();
            if(isset($checkBundleProduct) && !empty($checkBundleProduct)) {
                foreach($checkBundleProduct as $checkBundlePro) {
                    $productArray[$checkBundlePro->oid] = $checkBundlePro->oid;
                }
            }
        }

        $orderTrackingID = 0;
        if(!empty($tracking_number)) {
            $orderTracking = OrderTracking::where('tracking_number',$tracking_number)->first();
            if(isset($orderTracking->id)) {
                $orderTrackingID = $orderTracking->order_id;
            }
        }

      // return $orderTrackingID;

        $orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
                        ->join('order_address as t3','order_details.id','=','t3.order_id')
                        ->leftJoin('order_products as t4','order_details.oid','=','t4.oid')
                        ->leftJoin('mail_content_tracking as t5','order_details.id','=','t5.order_id')
                        ->select('order_details.id','order_details.oid','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type','order_details.order_email',
                          'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
                          't3.billing_name','t3.shipping_name','t3.shipping_email','t3.shipping_phone','t3.shipping_address1',
                          't3.shipping_address2','t3.shipping_state','t3.shipping_city','t3.shipping_country','t3.shipping_zip',
                          DB::raw("COUNT(t4.id) as items"),DB::raw("COUNT(t5.id) as dietChartCount"));/*->where('order_details.order_status','<=',3)*/

        if(!empty($countrySelected)) {
            $orderDetails = $orderDetails->where('t2.order_country',$countrySelected);
        }

        if(!empty($courierSelected)) {
            $orderDetails = $orderDetails->where('t3.selected_courier',$courierSelected);
        }

        if(!empty($productSelected) && !empty($productArray)) {
            $orderDetails = $orderDetails->whereIn('order_details.oid',$productArray);
        }

        if(!empty($tracking_number)) {
            $orderDetails = $orderDetails->where('order_details.id',$orderTrackingID);
        }

        if($type=='new') {
            $orderDetails = $orderDetails->whereIn('order_details.order_status',['0','1','2']);
        } else if($type=='processing') {
            $orderDetails = $orderDetails->whereIn('order_details.order_status',['3']);
        } else if($type=='shipped') {
            $orderDetails = $orderDetails->whereIn('order_details.order_status',['4']);
        }
//->whereRaw('DATE(order_placed_date)="'.date('Y-m-d').'"')
        if(!empty($fromdate) && !empty($todate)) {
       //     $orderDetails = $orderDetails->whereRaw('DATE(order_details.created_at)="'.$date.'"');
            $orderDetails = $orderDetails->whereBetween('order_details.order_placed_date',array($fromdate,$todate));
        } /*else {
$fdate = date('Y-m-01 00:00:00');
$tdate = date('Y-m-d 23:59:59');
$orderDetails = $orderDetails->whereBetween('order_details.order_placed_date',array($fdate,$tdate));
}*/

        $orderDetails = $orderDetails->whereNotNull('order_details.order_status')->whereNotIn('order_details.order_status',['7','8'])->where('order_details.delete_status',0)->groupBy('t4.oid')->orderBy('order_details.order_placed_date','DESC')->get();

        //return $orderDetails;

        $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();

        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

       // return $enabledCurrency;


        return view('admin/orders/all')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'countries'=>$this->countries,'couriers'=>$couriers,'products'=>$products,'productSelected'=>$productSelected,
               'courierSelected'=>$courierSelected,'countrySelected'=>$countrySelected,'paymentType'=>$this->paymentType,
               'enabledCurrency'=>$enabledCurrency,'currencies'=>$this->currencies,'inrSymbol'=>$inrSymbol,'fromdate'=>$fromdate,'todate'=>$todate,
               'tracking_number'=>$tracking_number));
    }

    public function processing()
    {
    	$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
    					->join('order_address as t3','order_details.id','=','t3.order_id')
    					->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.payment_type',
    					  'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
    					  't3.billing_name')
    					->where('order_details.delete_status',0)->where('order_details.order_status','<=',3)->get();
    	//return $orderDetail;
    	return view('admin/orders/process')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus));
    }

    public function completed()
    {
    	$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
    					->join('order_address as t3','order_details.id','=','t3.order_id')
    					->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.payment_type',
    					  'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
    					  't3.billing_name')
    					->where('order_details.delete_status',0)->where('order_details.order_status','=',4)->get();
    	//return $orderDetail;
    	return view('admin/orders/completed')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus));
    }

    public function refunded()
    {
    	$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
    					->join('order_address as t3','order_details.id','=','t3.order_id')
    					->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.payment_type',
    					  'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
    					  't3.billing_name')
    					->where('order_details.delete_status',0)->where('order_details.order_status','=',6)->get();
    	//return $orderDetail;
    	return view('admin/orders/refunded')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus));
    }

    public function trash(Request $request)
    {
        if($request->isMethod('post')){
            $date = $request->date;
        } else {
            $date = '';
        }

        if(!empty($date)) {
            $date = date('Y-m-d',strtotime($date));
        } else {
            $date = '';
        }
    	$orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
    					->join('order_address as t3','order_details.id','=','t3.order_id')
    					->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type',
    					  'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
    					  't3.billing_name')
    					->where('order_details.delete_status',1);

        if(!empty($date)) {
            $orderDetails = $orderDetails->whereRaw('DATE(order_details.created_at)="'.$date.'"');
        }

        $orderDetails = $orderDetails->groupBy('t3.order_id')->get();
    	//return $orderDetails;
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();
    	return view('admin/orders/trash')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'inrSymbol'=>$inrSymbol,'date'=>$date));
    }

        public function incomplete(Request $request)
    {
        $date = new \DateTime;
$date->modify('-1 hour');
$formatted_date = $date->format('Y-m-d H:i:s');
    
    if($request->isMethod('post')){
            $date = $request->date;
        } else {
            $date = '';
        }

        if(!empty($date)) {
            $date = date('Y-m-d',strtotime($date));
        } else {
            $date = '';
        }

        $orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
                        ->join('order_address as t3','order_details.id','=','t3.order_id')
                        ->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type',
                          'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
                          't3.billing_name')//->where('order_details.order_status',NULL)
                        ->where(function($query) {  
                                $query->where('order_details.order_status',NULL)->orWhereIn('order_details.order_status',['7','8']);
                        })
->where('t2.updated_at','<=',$formatted_date)
                        ->where('order_details.delete_status',0);

        if(!empty($date)) {
            $orderDetails = $orderDetails->whereRaw('DATE(order_details.created_at)="'.$date.'"');
        }

       $orderDetails = $orderDetails->groupBy('t3.order_id')->orderBy('order_details.id','DESC')->get();
       // return $orderDetails;
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();
        return view('admin/orders/incomplete')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'inrSymbol'=>$inrSymbol,'date'=>$date));
    }


    public function hanging(Request $request)
    {

            if($request->isMethod('post')){
            $date = $request->date;
        } else {
            $date = '';
        }

        if(!empty($date)) {
            $date = date('Y-m-d',strtotime($date));
        } else {
            $date = '';
        }

        $orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
                        ->join('order_address as t3','order_details.id','=','t3.order_id')
                        ->select('order_details.id','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type',
                          'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
                          't3.billing_name')//->where('order_details.order_status',NULL)
                        ->where(function($query) {  
                                $query->where('order_details.order_status',NULL)->orWhereIn('order_details.order_status',['7','8']);
                        })
                        ->where('order_details.delete_status',0)->where('order_details.payment_type','!=',0);


       $orderDetails = $orderDetails->groupBy('t3.order_id')->orderBy('order_details.id','DESC')->get();
       // return $orderDetails;
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();
        return view('admin/orders/hanging')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'inrSymbol'=>$inrSymbol,'date'=>$date));
    }


        public function viewPreviousOrders($id,$id2)
    {
         



      // return $orderTrackingID;

        $orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
                        ->join('order_address as t3','order_details.id','=','t3.order_id')
                        ->leftJoin('order_products as t4','order_details.oid','=','t4.oid')
                        ->select('order_details.id','order_details.oid','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type',
                          'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
                          't3.billing_name','t3.shipping_name','t3.shipping_email','t3.shipping_phone','t3.shipping_address1',
                          't3.shipping_address2','t3.shipping_state','t3.shipping_city','t3.shipping_country','t3.shipping_zip',
                          DB::raw("COUNT(t4.id) as items"));/*->where('order_details.order_status','<=',3)*/

        $orderDetails = $orderDetails->where('order_details.fkcustomer_id',$id)/*->where('order_details.id','!=',$id2)*/
                        /*->whereNotNull('order_details.order_status')*/
                        ->where('order_details.delete_status',0)->groupBy('t4.oid')->orderBy('order_details.id','DESC')->get();

        //return $orderDetails;

        $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();

        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        $customer = User::where('id',$id)->first();

        //return $orderDetail;
        return view('admin/orders/previousOrders')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
              'enabledCurrency'=>$enabledCurrency,'inrSymbol'=>$inrSymbol,'countries'=>$this->countries,'currencies'=>$this->currencies,
              'paymentType'=>$this->paymentType,'customer'=>$customer));
    }

    public function viewProcess($id)
    {
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)->where('order_status','<=',4)->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/all');
    	}
    	$orderSession = OrderSession::where('id',$orderDetail->oid)->first();
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	$orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();



        return view('admin/orders/viewProcess')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
        	  'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
        	  'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
        	  'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }

    public function viewCompleted($id)
    {
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)->where('order_status','=',6)->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/all');
    	}
    	$orderSession = OrderSession::where('id',$orderDetail->oid)->first();
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	$orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        return view('admin/orders/viewCompleted')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
        	  'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
        	  'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
        	  'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }

    public function viewRefunded($id)
    {
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)->where('order_status','=',5)->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/all');
    	}
    	$orderSession = OrderSession::where('id',$orderDetail->oid)->first();
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	$orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        return view('admin/orders/viewRefunded')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
        	  'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
        	  'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
        	  'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }

    public function viewCancelled($id)
    {
        $orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)->where('order_status','=',7)
                      ->orWhere('id',$id)->where('delete_status',0)->where('order_status','=',8)->first();
        if(!isset($orderDetail->id)) {
            return redirect('admin/orders/all');
        }
        $orderSession = OrderSession::where('id',$orderDetail->oid)->first();
        $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
        $orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        return view('admin/orders/viewCancelled')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
              'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
              'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
              'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }


    public function viewTrash($id)
    {
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',1)->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/trash');
    	}
    	$orderSession = OrderSession::where('id',$orderDetail->oid)->first();
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	$orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        return view('admin/orders/viewTrash')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
        	  'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
        	  'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
        	  'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }


    public function viewIncomplete($id)
    {
        $orderDetail = OrderDetails::where('id',$id)->where(function($query) {  
                                $query->where('order_status',NULL)->orWhereIn('order_status',['7','8']);
                        })->where('delete_status',0)->first();
        if(!isset($orderDetail->id)) {
            return redirect('admin/orders/incomplete');
        }
        $orderSession = OrderSession::where('id',$orderDetail->oid)->first();
        $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
        $orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

        //return $orderProducts;

        return view('admin/orders/viewIncomplete')->with(array('orderDetail'=>$orderDetail, 'orderStatus'=>$this->orderStatus,
              'orderSession'=>$orderSession, 'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts, 
              'orderBundleProducts'=>$orderBundleProducts,'countries'=>$this->countries,'paymentType'=>$this->paymentType,
              'currencies'=>$this->currencies,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
              'inrSymbol'=>$inrSymbol));

    }

    public function changeStatus($id,Request $request)
    {
    	$status = $request->order_status;
    	$refund_amount = $request->refund_amount;

    	$changeStatus = OrderDetails::find($id);
    	$oldStatus = $changeStatus->order_status;
    	if($oldStatus!=$status) {
    		$changeStatus->order_status = $status;
    		$changeStatus->refund_amount = $refund_amount;
    		$dataTime = date('Y-m-d H:i:s');
    		if($status==3) {
    			$changeStatus->order_process_date = $dataTime;
    		} else if($status==6) {
    			$changeStatus->order_completed_date = $dataTime;
    		} else if($status==7) {
    			$changeStatus->order_cancelled_date = $dataTime;
    			$changeStatus->cancelled_by = 1;
    		} else if($status==5) {
    			$changeStatus->order_refund_date = $dataTime;
    		}
    	}
    	$changeStatus->save();

    	if($status<=4) {
    		return redirect('admin/orders/process/'.$id)->with('success_msg','Order Status Updated Successfully');
    	}

    	if($status==6) {
            $sendDietChart = AdminHelper::sendDietChart($id);
    		return redirect('admin/orders/completed/'.$id)->with('success_msg','Order Status Updated Successfully');
    	}

    	if($status==7) {
    		return redirect('admin/orders/cancelled/'.$id)->with('success_msg','Order Status Updated Successfully');
    	}

    	if($status==5) {
    		return redirect('admin/orders/refunded/'.$id)->with('success_msg','Order Status Updated Successfully');
    	}

    }

    public function updateCourier($id,Request $request)
    {
    	$courier = $request->courier;
    	$page = $request->page;

    	if($courier!='') {
    		$updateCourier = DB::table('order_address')->where('order_id',$id)->update(array('selected_courier'=>$courier));
    	}

    	if($page=='process') {
			return redirect('admin/orders/process/'.$id)->with('success_msg','Courier Details Updated Successfully');
    	}

    	if($page=='refunded') {
			return redirect('admin/orders/refunded/'.$id)->with('success_msg','Courier Details Updated Successfully');
    	}

        if($page=='completed') {
            return redirect('admin/orders/completed/'.$id)->with('success_msg','Courier Details Updated Successfully');
        }
    }

    public function updateTracking($id,Request $request)
    {
    	$counter = $request->counter;
    	$page = $request->page;

        $orderAddress = OrderAddress::where('order_id',$id)->first();
        $courier = Couriers::where('id',$orderAddress->selected_courier)->first();

            for($i=1;$i<=$counter;$i++) {

                $tid = "tid_".$i;
                $trackingNumber = "tracking_number_".$i;
                $pro = "product_".$i;

                $products = $request->$pro;
                $selectedProduct = '';
                if(!empty($products)) {
                	$selectedProduct = implode(',', $products);
                }

               // return $products;

                if($request->$trackingNumber!='') {


                    if($request->$tid!=0) {

                        $tracking = OrderTracking::find($request->$tid);
                        $tracking->tracking_number = trim($request->$trackingNumber);
                        $tracking->order_id = $id;
                        $tracking->products = $selectedProduct;
                        $tracking->save();

                    } else {

                        $tracking = new OrderTracking;
                        $tracking->tracking_number = trim($request->$trackingNumber);
                        $tracking->order_id = $id;
                        $tracking->products = $selectedProduct;
                        $tracking->save();

                    }

                    $trackings = new AfterShip\Trackings($this->afterShipKey);
                    $tracking_info = array(
                        'slug'    => $courier->slug,
                        'title'   => trim($request->$trackingNumber),
                        'emails'  => $orderAddress->billing_email,
                        'smses'   => $orderAddress->billing_phone
                    );
                    $response = $trackings->create(trim($request->$trackingNumber), $tracking_info);

                  //  var_dump($response); die();

                }

            }

        if($page=='process') {
			return redirect('admin/orders/process/'.$id)->with('success_msg','Courier Tracking Number Updated Successfully');
    	}

    	if($page=='refunded') {
			return redirect('admin/orders/refunded/'.$id)->with('success_msg','Courier Tracking Number Updated Successfully');
    	}

        if($page=='completed') {
            return redirect('admin/orders/completed/'.$id)->with('success_msg','Courier Tracking Number Updated Successfully');
        }


    }

    public function removeTracking($string,$id,$tid)
    {
//echo $string; die();
    	$deleteTracking = OrderTracking::where('id',$tid)->delete();
    	if($deleteTracking) {
    		if($string=='process') {
				return redirect('admin/orders/process/'.$id)->with('success_msg','Courier Tracking Number Removed Successfully');
	    	}

	    	if($string=='refunded') {
				return redirect('admin/orders/refunded/'.$id)->with('success_msg','Courier Tracking Number Removed Successfully');
	    	}

            if($string=='completed') {
                return redirect('admin/orders/completed/'.$id)->with('success_msg','Courier Tracking Number Removed Successfully');
            }

            if($string=='edit' || $string=='edit-ordered-items') {
                $orderSession = OrderDetails::select('oid')->where('id',$id)->first();
                return redirect('admin/orders/edit-ordered-items/'.$orderSession->oid.'/'.$id)->with('success_msg','Courier Tracking Number Removed Successfully');
            }
    	}
    }

    public function applyAction(Request $request)
    {
        $action = $request->action;
        $page = $request->page;
        $checkOrder = $request->checkOrder;
        if($action=='trash') {
            if(!empty($checkOrder)) {
                foreach ($checkOrder as $key => $value) {
                    $deleteOrder = OrderDetails::find($value);
                    $deleteOrder->delete_status=1;
                    $deleteOrder->save();
                }
            }
        }
        if($action=='delete') {
            if(!empty($checkOrder)) {
                foreach ($checkOrder as $key => $value) {
                    $deleteOrder = OrderDetails::find($value);
                    $deleteOrder->delete_status=2;
                    $deleteOrder->save();
                }
            }
        }
        if($action=='successful') {
            if(!empty($checkOrder)) {
                foreach ($checkOrder as $key => $value) {
                    $orderDetail = OrderDetails::find($value);
                    $oid = $orderDetail->oid;
                    $date = $orderDetail->updated_at;
                    $orderDetail->order_status = 0;
                    $orderDetail->order_placed_date = $date;
                    $orderDetail->delete_status = 0;
                    if($orderDetail->save()) {
                        $orderSessionUpdate = OrderSession::find($oid);
                        $orderSessionUpdate->order_status = 2;
                        $orderSessionUpdate->save();
                    }
                }
            }
        }

        if($page=='all') {
            return redirect('admin/orders/all')->with('success_msg','Action Applied Successfully');
        } else if($page=='trash') {
            return redirect('admin/orders/trash')->with('success_msg','Action Applied Successfully');
        } else if($page=='incomplete') {
            return redirect('admin/orders/incomplete')->with('success_msg','Action Applied Successfully');
        }
    }

    public function addNote($id,Request $request)
    {
    	$orderNote = new OrderNotes;
    	$orderNote->order_id = $id;
    	$orderNote->notes = $request->order_note;
    	$orderNote->type = $request->type;
    	$orderNote->save();

    	$page = $request->page;

    	if($page=='process') {
			return redirect('admin/orders/process/'.$id)->with('success_msg','Order Note Updated Successfully');
    	}

    	if($page=='refunded') {
			return redirect('admin/orders/refunded/'.$id)->with('success_msg','Order Note Updated Successfully');
    	}

        if($page=='completed') {
            return redirect('admin/orders/completed/'.$id)->with('success_msg','Order Note Updated Successfully');
        }
    }


    public function removeNote($string,$id,$tid)
    {
    	$deleteNote = OrderNotes::where('id',$tid)->delete();
    	if($deleteNote) {
    		if($string=='process') {
				return redirect('admin/orders/process/'.$id)->with('success_msg','Order Note Removed Successfully');
	    	}

	    	if($string=='refunded') {
				return redirect('admin/orders/refunded/'.$id)->with('success_msg','Order Note Removed Successfully');
	    	}

            if($string=='completed') {
                return redirect('admin/orders/completed/'.$id)->with('success_msg','Order Note Removed Successfully');
            }

            if($string=='edit') {
                $orderSession = OrderDetails::select('oid')->where('id',$id)->first();
                return redirect('admin/orders/edit-ordered-items/'.$orderSession->oid.'/'.$id)->with('success_msg','Order Note Removed Successfully');
            }
    	}
    }


    public function successfulOrder($id)
    {
        $date = date('Y-m-d H:i:s');
        $orderDetail = OrderDetails::find($id);
        $oid = $orderDetail->oid;        
        $orderDetail->order_status = 0;
        $orderDetail->order_placed_date = $date;
        if($orderDetail->save()) {
            $orderSessionUpdate = OrderSession::find($oid);
            $orderSessionUpdate->order_status = 2;
            $orderSessionUpdate->save();
            return redirect('admin/orders/incomplete')->with('success_msg','Order Moved Successfully');
        }
    }


        public function trashSuccessfulOrder($id,Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $orderDetail = OrderDetails::find($id);
        $oid = $orderDetail->oid;
        $orderDetail->order_status = 0;
        $orderDetail->order_placed_date = $date;
        $orderDetail->delete_status = 0;
        if($orderDetail->save()) {
            $orderSessionUpdate = OrderSession::find($oid);
            $orderSessionUpdate->order_status = 2;
            $orderSessionUpdate->save();
            return redirect('admin/orders/trash')->with('success_msg','Order Moved Successfully');
        }
    }


    public function deleteOrder($id)
    {
        $deleteOrder = OrderDetails::find($id);
        $deleteOrder->delete_status=1;
        if($deleteOrder->save()) {
            return redirect('admin/orders/trash')->with('success_msg','Order Moved to Trash Successfully');
        }
    }

    public function deleteOrderPermanently($id)
    {
        $deleteOrder = OrderDetails::find($id);
        $deleteOrder->delete_status=2;
        if($deleteOrder->save()) {
            return redirect('admin/orders/trash')->with('success_msg','Order Deleted Successfully');
        }
    }


    public function addressChange($id,Request $request)
    {
        $same_as_billing = $request->same_as_billing;
        $type = $request->type;
        $name = $request->name;
        $address = $request->address;
        $address2 = $request->address2;
        $city = $request->city;
        $state = $request->state;
        $country = $request->country;
        $postal = $request->postal;
        $email = $request->email;
        $phone = $request->phone;
        $paymentType = $request->paymentType;
        $transaction_id = $request->transaction_id;

        $getOrderAddress = OrderAddress::where('order_id',$id)->first();

        $orderAddress = OrderAddress::find($getOrderAddress->id);

        if($same_as_billing==1) {
            $orderAddress->shipping_name = $orderAddress->billing_name;
            $orderAddress->shipping_address1 = $orderAddress->billing_address1;
            $orderAddress->shipping_address2 = $orderAddress->billing_address2;
            $orderAddress->shipping_state = $orderAddress->billing_state;
            $orderAddress->shipping_city = $orderAddress->billing_city;
            $orderAddress->shipping_country = $orderAddress->billing_country;
            $orderAddress->shipping_zip = $orderAddress->billing_zip;
            $orderAddress->same_as_billing = 1;
        } else {

            if($type==1) {
                $orderAddress->billing_name = trim($name);
                $orderAddress->billing_email = trim($email);
                $orderAddress->billing_phone = trim($phone);
                $orderAddress->billing_address1 = trim($address);
                $orderAddress->billing_address2 = trim($address2);
                $orderAddress->billing_state = trim($state);
                $orderAddress->billing_city = trim($city);
                $orderAddress->billing_country = trim($country);
                $orderAddress->billing_zip = trim($postal);
            } else {
                $orderAddress->shipping_name = trim($name);
                $orderAddress->shipping_address1 = trim($address);
                $orderAddress->shipping_address2 = trim($address2);
                $orderAddress->shipping_state = trim($state);
                $orderAddress->shipping_city = trim($city);
                $orderAddress->shipping_country = trim($country);
                $orderAddress->shipping_zip = trim($postal);
            }

        }

        $orderAddress->save();

        $orderDetail = OrderDetails::find($id);
        $orderDetail->payment_type = $paymentType;
        $orderDetail->direct_transaction_id = $transaction_id;
        $orderDetail->save();

        return $id;

    }

    public function resendOrderReceipt($id,$id2)
    {
        $orderSession = OrderSession::where('id',$id)->first();
        $orderProducts = OrderProducts::where('oid',$id)->orderBy('product_name','ASC')->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$id)->orderBy('product_name','ASC')->get();
        $orderDetail = OrderDetails::where('oid',$id)->first();
        $orderAddress = OrderAddress::where('order_id',$id2)->first();
        $bankDetails = BankDetails::where('id',1)->first();

                $data['orderSession'] = $orderSession;
                $data['orderProducts'] = $orderProducts;
                $data['orderBundleProducts'] = $orderBundleProducts;
                $data['orderDetail'] = $orderDetail;
                $data['orderAddress'] = $orderAddress;
                $data['bankDetails'] = $bankDetails;
                $data['countries'] = $this->countries;
                $email = $orderAddress->billing_email;
                $subject = 'Your Grocare India Order Receipt';

                if($orderDetail->payment_type==2) {

                    
                                Mail::send('emails.orderBankTransfer', $data, function($message)
                                        use($email,$subject){
                                    $message->from('no-reply@grocare.com', 'Grocare');
                                    $message->cc('info@grocare.com');
                                    $message->bcc('neethicommunicate@gmail.com');
                                    $message->bcc('ummemark@gmail.com');
                                    $message->to($email)->subject($subject);
                                });

                } else {

                                Mail::send('emails.orderOnlineTransfer', $data, function($message)
                                        use($email,$subject){
                                    $message->from('no-reply@grocare.com', 'Grocare');
                                    $message->cc('info@grocare.com');
                                    $message->bcc('neethicommunicate@gmail.com');
                                    $message->bcc('ummemark@gmail.com');
                                    $message->to($email)->subject($subject);
                                });

                }

                $orderDetailUpdate = OrderDetails::find($orderDetail->id);
                $orderDetailUpdate->order_email = 1;
                $orderDetailUpdate->save();


                return redirect('admin/orders/edit-ordered-items/'.$id.'/'.$id2)->with('success_msg','Order Receipt Sent Successfully');
    }

}