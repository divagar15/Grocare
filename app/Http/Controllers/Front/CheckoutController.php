<?php namespace App\Http\Controllers\Front;

require_once "mailchimp/MailChimp.php";
use \DrewM\MailChimp\MailChimp;

use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
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
use App\Models\BankDetails;
use App\Helper\FrontHelper;
use Session;
use Config;
use Hash;
use Mail;
use Auth;

class CheckoutController extends Controller {

	public function __construct()
    {
    	$getCountryCode = FrontHelper::getGeoLocation();
    	$this->activeCountry = Session::get('active_country');
    	$this->activeCurrency = Session::get('active_currency');
    	$this->activeRegion = Session::get('active_region');
    	$this->activeSymbol = Session::get('active_symbol');
        $this->activeShippingCharge = Session::get('active_shipping_charge');
        $this->activeMinimumAmount = Session::get('active_minimum_amount');
        $this->countries = Config::get('custom.country');
        $this->siteTitle = Config::get('custom.siteTitle');
        $this->mailChimpApikey = Config::get('custom.mailChimpApikey');
        $this->mailChimpSubscribeId = Config::get('custom.mailChimpSubscribeId');
        $this->mailChimpPurchaseId = Config::get('custom.mailChimpPurchaseId');
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

    public function checkout(Request $request)
    {
        
        
       // echo $phone;
        
        if($request->isMethod('post')){

            $orderSessionCheck = OrderSession::where('id',Session::get('order_id'))->where('order_status','!=',2)->count();
            $orderDetailsCheck = OrderDetails::where('oid',Session::get('order_id'))->whereNotNull('order_status')->where('delete_status',0)->count();
            if($orderSessionCheck==0 && $orderDetailsCheck!=0) {
                Session::forget('order_id');
                return redirect('cart');
            }

            //return $request->all();

            $create_account = $request->create_account;
            $ship_to = $request->ship_to;
            $password = $request->password;
            $email = $request->email;
            $phone = trim(str_replace(' ','',$request->phone));
            $semail = $request->semail;
            $sphone = trim(str_replace(' ','',$request->sphone));
            $dialCode = $request->dialCode;
            $sdialCode = $request->sdialCode;
	//echo $dialCode; echo "</br>".$sdialCode;die;
            //echo $phone; die();

            $billing_country = $request->billing_country;
            $billing_name = $request->billing_name;
            $billing_address_1 = $request->billing_address_1;
            $billing_address_2 = $request->billing_address_2;
            $billing_city = $request->billing_city;
            $billing_state = $request->billing_state;
            $billing_postcode = $request->billing_postcode;

            $shipping_country = $request->shipping_country;
            $shipping_name = $request->shipping_name;
            $shipping_address_1 = $request->shipping_address_1;
            $shipping_address_2 = $request->shipping_address_2;
            $shipping_city = $request->shipping_city;
            $shipping_state = $request->shipping_state;
            $shipping_postcode = $request->shipping_postcode;

           // $order_note = $request->order_note;
            // $courier_preference = $request->courier_preference;\

            $errorStatus = 0;

            $payment_type = $request->payment_type;

            if(empty($billing_country)) {
                $errorStatus++;
            }

            if(empty($billing_name)) {
                $errorStatus++;
            }

            if(empty($billing_address_1)) {
                $errorStatus++;
            }

            if(empty($billing_city)) {
                $errorStatus++;
            }

            if(empty($billing_state)) {
                $errorStatus++;
            }


            if(empty($billing_postcode) && Session::get('active_currency')=="INR") {
                $errorStatus++;
            }


                        if(empty($phone)) {
                $errorStatus++;
            }

            

             /*if(!is_numeric($phone)) {
                        $errorStatus++;
                    }*/

            

            if(empty($email)) {
                $errorStatus++;
            }

            


            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
              $errorStatus++;
            } 

            if(!empty($ship_to) && $ship_to==1) {


                    if(empty($shipping_country)) {
                        $errorStatus++;
                    }

                    if(empty($shipping_name)) {
                        $errorStatus++;
                    }

                    if(empty($shipping_address_1)) {
                        $errorStatus++;
                    }

                    if(empty($shipping_city)) {
                        $errorStatus++;
                    }

                    if(empty($shipping_state)) {
                        $errorStatus++;
                    }


                    if(empty($shipping_postcode) && Session::get('active_currency')=="INR") {
                        $errorStatus++;
                    }

                    if(empty($sphone)) {
                        $errorStatus++;
                    }

                    /*if(!is_numeric($sphone)) {
                        $errorStatus++;
                    }*/


                    if(empty($semail)) {
                        $errorStatus++;
                    }


                    if (filter_var($semail, FILTER_VALIDATE_EMAIL) === false) {
                      $errorStatus++;
                    } 

                   
            }




            if($errorStatus!=0) {

/*$orderCheck = OrderDetails::select('id','payment_hit_count')->where('oid',Session::get('order_id'))->first();
if(isset($orderCheck->id)) {
 $paymentHitCount = $orderCheck->payment_hit_count+1;
  
 $orderCountUpdate = OrderDetails::find($orderCheck->id);
 $orderCountUpdate->payment_hit_count = $paymentHitCount;
 $orderCountUpdate->save();

}*/

                return redirect()->back()->with('error_msg','Please fill out the all required fields marked with *')->withInput();
            }


            if(!empty($phone)) {

                $phone = ltrim($phone, '0');

                if (0 == strpos($phone, $dialCode)) {
                    $phone  = $dialCode.$phone;
                }

            } 

            if(!empty($sphone)) {

                $sphone = ltrim($sphone, '0');

                if (0 !== strpos($sphone, $sdialCode)) {
                    $sphone  = $sdialCode.$sphone;
                } 

            }



            //$orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();



            $subTotal = 0;

            $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->get();
            if(count($orderProducts)>0) {
                foreach ($orderProducts as $orderPro) {
                    $subTotal += round($orderPro->product_price)*$orderPro->product_qty;
                }
            }

            $shippingCharge = Session::get('active_shipping_charge');
            $minimumAmount = Session::get('active_minimum_amount');
            if($shippingCharge!=0.00 && ($minimumAmount==0.00 || $minimumAmount>$subTotal)) {
              $shippingCharge = round(Session::get('active_shipping_charge'));
              $grandTotal = round($shippingCharge)+$subTotal;
            } else {
              $shippingCharge = "";
              $grandTotal = $subTotal;
            }

            if($this->activeCurrency!='INR') {
                $currencyRates = CurrencyRates::where('from_currency',$this->activeCurrency)->where('delete_status',0)->first();
                if(isset($currencyRates->id)) {
                    $grandTotalInr = round($grandTotal/$currencyRates->rate);
                    $shippingChargeInr = round($shippingCharge/$currencyRates->rate);
                } else {
                    $grandTotalInr = $grandTotal;
                    $shippingChargeInr = $shippingCharge;
                }
            } else {
                $grandTotalInr = $grandTotal;
                $shippingChargeInr = $shippingCharge;
            }


            if(isset(Auth::user()->id)) {
                $uid = Auth::user()->id;
            } else {
                $uid = 0;
            }

            $this->activeRegion = Session::get('active_region');

            if(!isset($this->activeRegion) && empty($this->activeRegion)) {

                return redirect()->back()->withInput();

            }

            $orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();

            if(isset($orderCheck->id)) {
                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
            } else {

                $orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region',$this->activeRegion)->orderBy('order_details.id','DESC')->first();
//return $orderCheckNo;
                if(isset($orderCheckNo->invoice_no)) {
if($this->activeRegion==1) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
} else {
$orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region','!=',1)->orderBy('order_details.id','DESC')->first();
if(isset($orderCheckNo->invoice_no)) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
                }
}
                } else {
                    if($this->activeRegion==1) {
                        $invoiceNo = "I6001";
                    } else {
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

              //  echo $invoiceNo; die;

                /*$getCount = OrderSession::where('order_region',$this->activeRegion)->count();

               
                    $getCount++;
                
                if($this->activeRegion==1) {
                    $invoiceNo = "I".($getCount+5999);
                } else {
                    $invoiceNo = "EXPT".($getCount+400);
                }*/
               
                $orderDetailUpdate = new OrderDetails;
                $orderDetailUpdate->invoice_no = $invoiceNo;
            }


            $orderDetailUpdate->oid = Session::get('order_id');
            $orderDetailUpdate->fkcustomer_id = $uid;
            $orderDetailUpdate->subtotal = $subTotal;
            $orderDetailUpdate->shipping_charge = $shippingCharge;
            $orderDetailUpdate->shipping_charge_inr = $shippingChargeInr;
            $orderDetailUpdate->grand_total = $grandTotal;
            $orderDetailUpdate->grand_total_inr = $grandTotalInr;
            $orderDetailUpdate->save();


            /*$oDetails = OrderDetails::find($orderCheck->id);
            $oDetails->subtotal = $subTotal;
            $oDetails->shipping_charge = $shippingCharge;
            $oDetails->shipping_charge_inr = $shippingChargeInr;
            $oDetails->grand_total = $grandTotal;
            $oDetails->grand_total_inr = $grandTotalInr;
            $oDetails->save();*/



            $userId = 0;

            if(!isset(Auth::user()->id)) {

                if($create_account==1) {

                        $checkMail = User::where('email',$email)->where('user_type',2)->where('delete_status',0)->first();
                        if(isset($checkMail->id)) {
                            $userId = $checkMail->id;
                        } else {
                            $user = new User;
                            $user->name=trim($billing_name);
                            $user->email=$email;
                            $user->password=Hash::make($password);
                            $user->password_text=$password;
                            $user->user_type=2;
                            if($user->save()) {
                                $userId = $user->id;
                                $data['username'] = $email;
                                $data['password'] = $password;
                                $subject = 'Welcome to the World of Grocare India!';
                                Mail::send('emails.userReg', $data, function($message)
                                        use($email,$subject){
                                    $message->from('no-reply@grocare.com', 'GROCARE');
                                    $message->to($email)->subject($subject);
                                });
                            }
                        }

                }

            } else {
                $userId = Auth::user()->id;
            }

            $orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();

           // $orderAddress = new OrderAddress;

            $addressCheck = OrderAddress::select('id')->where('order_id',$orderCheck->id)->first();
            if(isset($addressCheck->id)) {
                $orderAddress = OrderAddress::find($addressCheck->id);
            } else {            
                $orderAddress = new OrderAddress;
            }

            $orderAddress->order_id = $orderCheck->id;
            $orderAddress->billing_name = $billing_name;
            $orderAddress->billing_email = $email;
            $orderAddress->billing_phone = $phone;
            $orderAddress->billing_dialcode = $dialCode;
            $orderAddress->billing_address1 = $billing_address_1;
            $orderAddress->billing_address2 = $billing_address_2;
            $orderAddress->billing_state = $billing_state;
            $orderAddress->billing_city = $billing_city;
            $orderAddress->billing_country = $billing_country;
            $orderAddress->billing_zip = $billing_postcode;

            if($ship_to==1) {
                $orderAddress->shipping_name = $shipping_name;
                $orderAddress->shipping_address1 = $shipping_address_1;
                $orderAddress->shipping_address2 = $shipping_address_2;
                $orderAddress->shipping_state = $shipping_state;
                $orderAddress->shipping_city = $shipping_city;
                $orderAddress->shipping_country = $shipping_country;
                $orderAddress->shipping_zip = $shipping_postcode;
                $orderAddress->shipping_email = $semail;
                $orderAddress->shipping_phone = $sphone;

                
            } else {
                $orderAddress->shipping_name = $billing_name;
                $orderAddress->shipping_address1 = $billing_address_1;
                $orderAddress->shipping_address2 = $billing_address_2;
                $orderAddress->shipping_state = $billing_state;
                $orderAddress->shipping_city = $billing_city;
                $orderAddress->shipping_country = $billing_country;
                $orderAddress->shipping_zip = $billing_postcode;
                $orderAddress->same_as_billing = 1;
                $orderAddress->shipping_email = $email;
                $orderAddress->shipping_phone = $phone;
            }

            //$orderAddress->order_note = $order_note;
            // $orderAddress->courier_preference = $courier_preference;
            $orderAddress->save();
			
			if($userId){
				$userAddress=UserAddress::where('user_id',$userId)->where('status',1)->first();
				if(empty($userAddress)){
					$userAddress=new UserAddress;
					$userAddress->user_id=$userId;
					$userAddress->name=$billing_name;
					$userAddress->address=$billing_address_1;
					$userAddress->address2=$billing_address_2;
					$userAddress->state=$billing_state;
					$userAddress->city=$billing_city;
					$userAddress->country=$billing_country;
					$userAddress->postcode=$billing_postcode;
					$userAddress->status=1;
					$userAddress->save();
				}
				
				$userAddress=UserAddress::where('user_id',$userId)->where('status',2)->first();
				if(empty($userAddress)){				
					$userAddress=new UserAddress;
					$userAddress->user_id=$userId;				
					 if($ship_to==1) {
						$userAddress->name = $shipping_name;
						$userAddress->address = $shipping_address_1;
						$userAddress->address2 = $shipping_address_2;
						$userAddress->state = $shipping_state;
						$userAddress->city = $shipping_city;
						$userAddress->country = $shipping_country;
						$userAddress->postcode = $shipping_postcode;
					} else {
						$userAddress->name = $billing_name;
						$userAddress->address = $billing_address_1;
						$userAddress->address2 = $billing_address_2;
						$userAddress->state = $billing_state;
						$userAddress->city = $billing_city;
						$userAddress->country = $billing_country;
						$userAddress->postcode = $billing_postcode;
					}
					
					$userAddress->status=2;
					$userAddress->save();
				}
			}


            $mailChimp = new MailChimp($this->mailChimpApikey);

            $list_id = $this->mailChimpSubscribeId;

            $result = $mailChimp->post("lists/".$list_id."/members", [
                            'email_address' => $email,
                            'status'        => 'subscribed',
                            'merge_fields' => ['FNAME'=>$billing_name, 'LNAME'=>''],
                        ]);

$list_id = $this->mailChimpPurchaseId;

            $result = $mailChimp->post("lists/".$list_id."/members", [
                            'email_address' => $email,
                            'status'        => 'subscribed',
                            'merge_fields' => ['FNAME'=>$billing_name, 'LNAME'=>''],
                        ]);

            

            if($payment_type==1) {

                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
                $orderDetailUpdate->fkcustomer_id = $userId;
                $orderDetailUpdate->payment_type = $payment_type;
                $orderDetailUpdate->save();

                Session::put('payment_id',$orderCheck->invoice_no);
                Session::put('payment_amount',$orderCheck->grand_total);
                Session::put('payment_name',$billing_name);
                Session::put('payment_address',$billing_address_1);
                Session::put('payment_city',$billing_city);
                Session::put('payment_state',$billing_state);
                Session::put('payment_zipcode',$billing_postcode);
                Session::put('payment_country',$billing_country);
                Session::put('payment_email',$email);
                Session::put('payment_phone',$phone);

                return redirect('payment-process');


            } else if($payment_type==2) {
                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
                $orderDetailUpdate->fkcustomer_id = $userId;
                $orderDetailUpdate->payment_type = $payment_type;
                $orderDetailUpdate->order_status = 1;
                $orderDetailUpdate->order_placed_date = date('Y-m-d H:i:s');
                $orderDetailUpdate->save();

                $orderSessionUpdate = OrderSession::find(Session::get('order_id'));
                $orderSessionUpdate->order_status = 2;
                $orderSessionUpdate->save();

                $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
                $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderBundleProducts = OrderBundleProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderDetail = OrderDetails::where('oid',Session::get('order_id'))->first();
                $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
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

                                $orderDetailUpdate = OrderDetails::find($orderDetail->id);
                                $orderDetailUpdate->order_email = 1;
                                $orderDetailUpdate->save();

                if(isset(Auth::user()->id)) {
                        $userUpate = User::find(Auth::user()->id);
                        $userUpate->order_session_id = "";
                        $userUpate->save();
                    }

                Session::forget('order_id');

                return redirect('checkout/order/'.$orderDetail->id)->with('success_msg','Your order is placed successfully');

            } else if($payment_type==3) {

                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
                $orderDetailUpdate->fkcustomer_id = $userId;
                $orderDetailUpdate->payment_type = $payment_type;
                $orderDetailUpdate->save();

                Session::put('payment_id',$orderCheck->invoice_no);
                Session::put('payment_amount',$orderCheck->grand_total);
                Session::put('payment_name',$billing_name);
                Session::put('payment_address',$billing_address_1);
                Session::put('payment_city',$billing_city);
                Session::put('payment_state',$billing_state);
                Session::put('payment_zipcode',$billing_postcode);
                Session::put('payment_country',$billing_country);
                Session::put('payment_email',$email);
                Session::put('payment_phone',$phone);

                return redirect('paypal-payment-process');


            }


            

        } else {
           // if(!Session::has('order_id')) {
                return redirect('cart');
           // }

            $subTotal = 0;

            $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->get();
            if(count($orderProducts)>0) {
                foreach ($orderProducts as $orderPro) {
                    $subTotal += round($orderPro->product_price)*$orderPro->product_qty;
                }
            }

            $shippingCharge = Session::get('active_shipping_charge');
            $minimumAmount = Session::get('active_minimum_amount');
            if($shippingCharge!=0.00 && ($minimumAmount==0.00 || $minimumAmount>$subTotal)) {
              $shippingCharge = round(Session::get('active_shipping_charge'));
              $grandTotal = round($shippingCharge)+$subTotal;
            } else {
              $shippingCharge = "";
              $grandTotal = $subTotal;
            }

            if($this->activeCurrency!='INR') {
                $currencyRates = CurrencyRates::where('from_currency',$this->activeCurrency)->where('delete_status',0)->first();
                if(isset($currencyRates->id)) {
                    $grandTotalInr = round($grandTotal/$currencyRates->rate);
                    $shippingChargeInr = round($shippingCharge/$currencyRates->rate);
                } else {
                    $grandTotalInr = $grandTotal;
                    $shippingChargeInr = $shippingCharge;
                }
            } else {
                $grandTotalInr = $grandTotal;
                $shippingChargeInr = $shippingCharge;
            }

            if(isset(Auth::user()->id)) {
                $uid = Auth::user()->id;
            } else {
                $uid = 0;
            }

            $orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();

            if(isset($orderCheck->id)) {
                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
            } else {

                $orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region',$this->activeRegion)->orderBy('order_details.id','DESC')->first();
//return $orderCheckNo;
                if(isset($orderCheckNo->invoice_no)) {
if($this->activeRegion==1) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
} else {
$orderCheckNo = OrderDetails::join('order_session as t2', 't2.id', '=', 'order_details.oid')
                                ->select('order_details.invoice_no')
                                ->where('t2.order_region','!=',1)->orderBy('order_details.id','DESC')->first();
if(isset($orderCheckNo->invoice_no)) {
                    $invoiceNo = ++$orderCheckNo->invoice_no;
                }
}
                } else {
                    if($this->activeRegion==1) {
                        $invoiceNo = "I6001";
                    } else {
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

              //  echo $invoiceNo; die;

                /*$getCount = OrderSession::where('order_region',$this->activeRegion)->count();

               
                    $getCount++;
                
                if($this->activeRegion==1) {
                    $invoiceNo = "I".($getCount+5999);
                } else {
                    $invoiceNo = "EXPT".($getCount+400);
                }*/
               
                $orderDetailUpdate = new OrderDetails;
                $orderDetailUpdate->invoice_no = $invoiceNo;
            }

            $orderDetailUpdate->oid = Session::get('order_id');
            $orderDetailUpdate->fkcustomer_id = $uid;
            $orderDetailUpdate->subtotal = $subTotal;
            $orderDetailUpdate->shipping_charge = $shippingCharge;
            $orderDetailUpdate->shipping_charge_inr = $shippingChargeInr;
            $orderDetailUpdate->grand_total = $grandTotal;
            $orderDetailUpdate->grand_total_inr = $grandTotalInr;
            $orderDetailUpdate->save();

            $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
            $orderDetail = OrderDetails::where('oid',Session::get('order_id'))->first();

            $couriers = Couriers::where('delete_status',0)->get();

            $billing_address = '';
            $shipping_address = '';

            if(isset(Auth::user()->id)) {
                $billing_address = UserAddress::where('user_id',Auth::user()->id)->where('status',1)->where('delete_status',0)->first();
                $shipping_address = UserAddress::where('user_id',Auth::user()->id)->where('status',2)->where('delete_status',0)->first();
            }
            $pageTitle = "Checkout | ".$this->siteTitle;
            return view('front/checkout/checkout')->with(array('pageTitle'=>$pageTitle,'orderSession'=>$orderSession, 'orderDetail'=>$orderDetail,
                   'countries'=>$this->countries, 'couriers'=>$couriers, 'billing_address'=>$billing_address, 
                   'shipping_address'=>$shipping_address));
        }
    }

    public function orderPage($id)
    {
        $orderDetail = OrderDetails::where('id',$id)->first();

        $orderSession = OrderSession::where('id',$orderDetail->oid)->first();
        $orderProducts = OrderProducts::where('oid',$orderDetail->oid)->orderBy('product_name','ASC')->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->orderBy('product_name','ASC')->get();
        $orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
        $bankDetails = BankDetails::where('id',1)->first();

        $pageTitle = "Thank You - Order Summary | ".$this->siteTitle;

        if($orderDetail->payment_type==1 || $orderDetail->payment_type==3) {
            return view('front/checkout/onlineOrder')->with(array('pageTitle'=>$pageTitle,'orderDetail'=>$orderDetail,'orderSession'=>$orderSession,
                   'orderProducts'=>$orderProducts,'orderBundleProducts'=>$orderBundleProducts,'orderAddress'=>$orderAddress,
                   'bankDetails'=>$bankDetails,'countries'=>$this->countries)); 
        } elseif($orderDetail->payment_type==2) {
            return view('front/checkout/bankOrder')->with(array('pageTitle'=>$pageTitle,'orderDetail'=>$orderDetail,'orderSession'=>$orderSession,
                   'orderProducts'=>$orderProducts,'orderBundleProducts'=>$orderBundleProducts,'orderAddress'=>$orderAddress,
                   'bankDetails'=>$bankDetails,'countries'=>$this->countries)); 
        }

    }

    public function orderTracking($string)
    {
        /*$request = array();
        $request['notification'] = array();*/
       // $request['tracking']['tracking_number'] = 'P35688726';
        // all the following value are optional
      //  $request['tracking']['slug'] = 'usps'; // AfterShip support auto detect courier, if you don't know which couriers is. just skip this line.
       // $request['tracking']['title'] = 'iPhone Case';
      //  $request['tracking']['order_id'] = '10001';
      //  $request['tracking']['order_id_path'] = 'http://www.grocare.com/order-tracking/P35688726';
       // $request['tracking']['customer_name'] ='Divagar';
       /* $request['notification']['emails'] = 'divagar.umm@gmail.com';
        $request['notification']['smses'] = '9994533083';*/
        //$request['tracking']['destination_country_iso3'] = 'IN';
         
       /* $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.aftership.com/v4/notifications/dtdc/P35688726');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'aftership-api-key: 0e5c06be-d7ba-458f-aaa0-8ac9b3b62ee8',
        'Content-Type: application/json',
        ));
         
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         
        $content = curl_exec($curl);
        curl_close($curl);
        echo $content;*/
        $pageTitle = "Order Tracking | ".$this->siteTitle;
        return view('front/checkout/orderTracking')->with(array('pageTitle'=>$pageTitle,'trackingNumber'=>$string));
    }

    public function checkMailchimp()
    {
        $email = 'test123@gmail.com';
        $billing_name = 'tester';
        $mailChimp = new MailChimp($this->mailChimpApikey);

            $list_id = $this->mailChimpSubscribeId;

            $result = $mailChimp->post("lists/".$list_id."/members", [
                            'email_address' => $email,
                            'status'        => 'subscribed',
                            'merge_fields' => ['FNAME'=>$billing_name, 'LNAME'=>''],
                        ]);

            var_dump($result);


            $list_id = $this->mailChimpPurchaseId;

            $result = $mailChimp->post("lists/".$list_id."/members", [
                            'email_address' => $email,
                            'status'        => 'subscribed',
                            'merge_fields' => ['FNAME'=>$billing_name, 'LNAME'=>''],
                        ]);

            var_dump($result);
    }

}