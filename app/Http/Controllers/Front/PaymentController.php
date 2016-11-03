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

class PaymentController extends Controller {


    public function __construct(){
      $this->channel     = Config::get('custom.channel');
      $this->mode     = Config::get('custom.mode');
      $this->accountId     = Config::get('custom.accountId');
      $this->secretKey     = Config::get('custom.secretKey');
      $this->actionURL     = Config::get('custom.actionURL');
      $this->responeURL     = Config::get('custom.responeURL');
      $this->hashing_method     = 'sha512';
      $this->activeCountry = Session::get('active_country');
      $this->activeCurrency = Session::get('active_currency');
      $this->activeRegion = Session::get('active_region');
      $this->activeSymbol = Session::get('active_symbol');
      $this->activeShippingCharge = Session::get('active_shipping_charge');
      $this->activeMinimumAmount = Session::get('active_minimum_amount');
      $this->countries = Config::get('custom.country');
      $this->paypalURL = Config::get('custom.paypalURL');
      $this->paypalId = Config::get('custom.paypalId');
      $this->paypalSuccessURL = Config::get('custom.paypalSuccessURL');
      $this->paypalCancelURL = Config::get('custom.paypalCancelURL');
      $this->siteTitle = Config::get('custom.siteTitle');
    }


    public function paymentProcess(Request $request){  

    	$secureHash = '';
    	$actionURL = '';

    	if($request->isMethod('post')){ 

    		$hashData = $this->secretKey;

    		ksort($_POST);
			foreach ($_POST as $key => $value){
				if (strlen($value) > 0) {
					$hashData .= '|'.$value;
				}
			}
			if (strlen($hashData) > 0) {
				$amt = Session::get('payment_amount');
				$refNo = Session::get('payment_id');
				//$secureHash = strtoupper(hash($this->hashing_method, $hashData));
				$string = "$this->secretKey|$this->accountId|$amt|$refNo|$this->responeURL|$this->mode";
				$secureHash = md5($string);
				//$secureHash = MD5($this->secretKey|$this->accountId|Session::get('payment_amount')|Session::get('payment_id')|$this->responeURL|$this->mode);
			}

			$actionURL = $this->actionURL;

			//echo $secureHash; die();

    	} 

        $pageTitle = "Payment Process | ".$this->siteTitle;
    		

    	return view('front/checkout/payment')->with(array('pageTitle'=>$pageTitle,'secureHash'=>$secureHash, 'actionURL'=>$actionURL, 'channel'=>$this->channel,
    		   'mode'=>$this->mode,'accountId'=>$this->accountId,'secretKey'=>$this->secretKey, 'currency'=>$this->activeCurrency,
    		   'actionURL'=>$actionURL,'responeURL'=>$this->responeURL,'hashing_method'=>$this->hashing_method));
    	
    }

    public function paymentResponse(Request $request) {
    //	echo Session::get('payment_id')."--".Session::get('payment_amount')."--".Session::get('order_id');
    //	echo '<pre>'; print_r($request->session()->all()); print_r($request->all()); echo '</pre>'; 

    //	if($request->isMethod('post')){ 

    		$orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();

    		$response_code = $request->ResponseCode;
    		$response_message = $request->ResponseMessage;
    		$payment_id = $request->PaymentID;
    		$transaction_id = $request->TransactionID;
    		$request_id  = $request->RequestID;

    		if($response_code==0) {
    			$oStatus = 2;
    			$status = 0;
    		} else {
    			$oStatus = 3;
    			$status = 7;
    		}

    		$orderSessionUpdate = OrderSession::find(Session::get('order_id'));
            $orderSessionUpdate->order_status = $oStatus;
            $orderSessionUpdate->save();

            	$orderDetailUpdate = OrderDetails::find($orderCheck->id);
                $orderDetailUpdate->order_status = $status;
                $orderDetailUpdate->order_placed_date = date('Y-m-d H:i:s');
                $orderDetailUpdate->order_status = $status;
                $orderDetailUpdate->response_code = $response_code;
                $orderDetailUpdate->response_message = $response_message;
                $orderDetailUpdate->payment_id = $payment_id;
                $orderDetailUpdate->transaction_id = $transaction_id;
                $orderDetailUpdate->request_id = $request_id;
                $orderDetailUpdate->save();

                $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
                $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderBundleProducts = OrderBundleProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderDetail = OrderDetails::where('oid',Session::get('order_id'))->first();
                $orderAddress = OrderAddress::where('order_id',$orderCheck->id)->first();
                $bankDetails = BankDetails::where('id',1)->first();

                $data['orderSession'] = $orderSession;
                $data['orderProducts'] = $orderProducts;
                $data['orderBundleProducts'] = $orderBundleProducts;
                $data['orderDetail'] = $orderDetail;
                $data['orderAddress'] = $orderAddress;
                $data['bankDetails'] = $bankDetails;
                $data['countries'] = $this->countries;
                $email = Session::get('payment_email');

                

                Session::forget('payment_id');
                Session::forget('payment_amount');
                Session::forget('payment_name');
                Session::forget('payment_address');
                Session::forget('payment_city');
                Session::forget('payment_state');
                Session::forget('payment_zipcode');
                Session::forget('payment_country');
                Session::forget('payment_email');
                Session::forget('payment_phone');
                

                if($status==0) {

$subject = 'Your Grocare India Order Receipt';
                                Mail::send('emails.orderOnlineTransfer', $data, function($message)
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

                	return redirect('checkout/order/'.$orderDetail->id)->with('success_msg','Payment made successfully');

                } else {

                    $orderSessionUpdate = OrderSession::find(Session::get('order_id'));
                    $orderSessionUpdate->order_status = 3;
                    $orderSessionUpdate->save();

                    Session::forget('order_id');

                	return redirect('cart')->with('error_msg','Payment Failed. Please try again');

                }

    	//}

    	//die();
    }


    //Paypal Payment Process


    public function paypalPaymentProcess(){  

        $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
        $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();

        $orderDetail = OrderDetails::where('oid',Session::get('order_id'))->first();

        $pageTitle = "Payment Process | ".$this->siteTitle;

        return view('front/checkout/paypalPayment')->with(array('pageTitle'=>$pageTitle,'currency'=>$this->activeCurrency, 'paypalURL'=>$this->paypalURL,
               'paypalId'=>$this->paypalId, 'paypalSuccessURL'=>$this->paypalSuccessURL, 'paypalCancelURL'=>$this->paypalCancelURL,
               'orderSession'=>$orderSession, 'orderProducts'=>$orderProducts, 'orderDetail'=>$orderDetail));
        
    }


    public function paypalPaymentSuccess(Request $request) {
    //  echo Session::get('payment_id')."--".Session::get('payment_amount')."--".Session::get('order_id');
    //  echo '<pre>'; print_r($request->session()->all()); print_r($request->all()); echo '</pre>'; 

    //  if($request->isMethod('post')){ 

            $orderCheck = OrderDetails::where('oid',Session::get('order_id'))->first();

            /*$response_code = $request->ResponseCode;
            $response_message = $request->ResponseMessage;
            $payment_id = $request->PaymentID;
            $transaction_id = $request->TransactionID;
            $request_id  = $request->RequestID;*/

           // if($response_code==0) {
                $oStatus = 2;
                $status = 0;
           /* } else {
                $oStatus = 3;
                $status = 7;
            }*/

            $orderSessionUpdate = OrderSession::find(Session::get('order_id'));
            $orderSessionUpdate->order_status = $oStatus;
            $orderSessionUpdate->save();

                $orderDetailUpdate = OrderDetails::find($orderCheck->id);
                $orderDetailUpdate->order_status = $status;
                $orderDetailUpdate->order_placed_date = date('Y-m-d H:i:s');
                //$orderDetailUpdate->order_status = $status;
                /*$orderDetailUpdate->response_code = $response_code;
                $orderDetailUpdate->response_message = $response_message;
                $orderDetailUpdate->payment_id = $payment_id;
                $orderDetailUpdate->transaction_id = $transaction_id;
                $orderDetailUpdate->request_id = $request_id;*/
                $orderDetailUpdate->save();

                $orderSession = OrderSession::where('id',Session::get('order_id'))->first();
                $orderProducts = OrderProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderBundleProducts = OrderBundleProducts::where('oid',Session::get('order_id'))->orderBy('product_name','ASC')->get();
                $orderDetail = OrderDetails::where('oid',Session::get('order_id'))->first();
                $orderAddress = OrderAddress::where('order_id',$orderCheck->id)->first();
                $bankDetails = BankDetails::where('id',1)->first();

                $data['orderSession'] = $orderSession;
                $data['orderProducts'] = $orderProducts;
                $data['orderBundleProducts'] = $orderBundleProducts;
                $data['orderDetail'] = $orderDetail;
                $data['orderAddress'] = $orderAddress;
                $data['bankDetails'] = $bankDetails;
                $data['countries'] = $this->countries;
                $email = Session::get('payment_email');

                $subject = 'Your Grocare India Order Receipt';
                                Mail::send('emails.orderOnlineTransfer', $data, function($message)
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

                Session::forget('payment_id');
                Session::forget('payment_amount');
                Session::forget('payment_name');
                Session::forget('payment_address');
                Session::forget('payment_city');
                Session::forget('payment_state');
                Session::forget('payment_zipcode');
                Session::forget('payment_country');
                Session::forget('payment_email');
                Session::forget('payment_phone');
                

             //   if($status==0) {

                    if(isset(Auth::user()->id)) {
                        $userUpate = User::find(Auth::user()->id);
                        $userUpate->order_session_id = "";
                        $userUpate->save();
                    }

                    Session::forget('order_id');

                    return redirect('checkout/order/'.$orderDetail->id)->with('success_msg','Payment made successfully');

               /* } else {

                    Session::forget('order_id');

                    return redirect('cart')->with('error_msg','Payment Failed. Please try again');

                }*/

        //}

        //die();
    }

    public function paypalPaymentCancel(Request $request) {

        $orderSessionUpdate = OrderSession::find(Session::get('order_id'));
        $orderSessionUpdate->order_status = 3;
        $orderSessionUpdate->save();

                Session::forget('payment_id');
                Session::forget('payment_amount');
                Session::forget('payment_name');
                Session::forget('payment_address');
                Session::forget('payment_city');
                Session::forget('payment_state');
                Session::forget('payment_zipcode');
                Session::forget('payment_country');
                Session::forget('payment_email');
                Session::forget('payment_phone');

        Session::forget('order_id');

        return redirect('cart')->with('error_msg','Payment Cancelled. Please try again');

    }


}