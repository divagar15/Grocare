<?php namespace App\Http\Controllers\Front;

require_once('AfterShip/Exception/AftershipException.php');
require_once('AfterShip/Core/Request.php');
require_once('AfterShip/Couriers.php');
require_once('AfterShip/Trackings.php');
require_once('AfterShip/Notifications.php');
require_once('AfterShip/LastCheckPoint.php');

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
use App\Models\PendingPaymentEmail;
use AfterShip;
use Response;
use Input;
use Hash;
use Mail;
use Session;
use Config;

class CronController extends Controller {

	public function __construct()
    {
		$this->countries = Config::get('custom.country');
$this->afterShipKey = Config::get('custom.afterShipKey');
    }
	
	public function placingOrderEmail(){
$date = new \DateTime;
$date->modify('-24 hours');
$formatted_date = $date->format('Y-m-d H:i:s');
		$orderDetails=OrderDetails::select('order_details.oid','order_details.id','a.billing_name','a.billing_email','a.billing_phone')
									->join('order_address as a','a.order_id','=','order_details.id')
->where('order_details.order_completed_date','<=',$formatted_date)
				//->whereRaw('order_details. order_completed_date <= DATE_SUB(NOW(), INTERVAL 24 HOUR)')
									->where('order_details.order_status',6)
									->where('order_details.order_process_email',0)
									->where('order_details.delete_status',0)
									->get();		
		//echo count($orderDetails); die();
		if(count($orderDetails)>0){	
			foreach($orderDetails as $keys=>$values){
				$orderProducts=OrderProducts::select('order_products.diagnose_id','diagnosis.die_chart_content')
								->join('diagnosis','diagnosis.id','=','order_products.diagnose_id')
								->where('order_products.oid',$values->oid)
								->where('order_products.diagnose_id','!=',0)
								->where('diagnosis.die_chart_content','!=','')
								->get();
				foreach($orderProducts as $key=>$val){
										
					//mail the user details
					$die_chart_content=$val->die_chart_content;
					$email=$values->billing_email;
					$phone=$values->billing_phone;
					$name=$values->billing_name;
					$data['email'] = $email;
					$data['phone'] = $phone;
					$data['name'] = $name;
					$data['die_chart_content'] = $die_chart_content;
					$subject = 'Welcome to the World of Grocare India!';
					$mail=Mail::send('emails.orderProcessEmail', $data, function($message)
							use($email,$subject){
						$message->from('no-reply@grocare.com', 'Grocare');
						$message->to($email)->subject($subject);
					});
					if($mail){
						$orderDetails=OrderDetails::find($values->id);
						$orderDetails->order_process_email=1;
						$orderDetails->save();
					}
				}
			}
		}
	
	}
	public function checkOutLateEmail(){

$date = new \DateTime;
$date->modify('-1 hour');
$formatted_date = $date->format('Y-m-d H:i:s');
//echo $formatted_date;
		$orderSession=OrderSession::select('order_address.billing_email','order_address.billing_name','order_address.billing_phone','order_session.id')
									//->join('users','users.id','=','order_session.user_id')
		->join('order_details','order_details.oid','=','order_session.id')
		->join('order_address','order_address.order_id','=','order_details.id')
->where('order_session.updated_at','<=',$formatted_date)
									//->whereRaw('order_session.updated_at <= (NOW() - INTERVAL 1 HOUR)')
									//->where('order_session.user_id','!=',0)
									//->where('users.email','!=','')
									->where('order_session.order_status',1)
									->where('order_session.order_session_mail',0)
->where('order_details.payment_type','!=',0)
									->get();
		if(count($orderSession)>0){
			foreach($orderSession as $key=>$val){

				$orderProducts = OrderProducts::where('oid',$val->id)->get();
				$j=1;

				$itemName = '';

				if(isset($orderProducts) && !empty($orderProducts)) {
					foreach($orderProducts as $pro) {
						if($j==1) {
							$itemName = ucfirst($pro->product_name);
						} else {
							$itemName .= ', '.ucfirst($pro->product_name);
						}
					}
				}

				$email=$val->billing_email;
				$phone=$val->billing_phone;
				$name=$val->billing_name;
				$order_id=$val->id;
				$data['email'] = $email;
				$data['phone'] = $phone;
			       $data['name'] = $name;
			$data['order_id'] = $order_id;
			$data['itemName'] = $itemName;
				$subject = 'Grocare - Order Completion Pending';
				$mail=Mail::send('emails.checkOutLateMail', $data, function($message)
						use($email,$subject){
					$message->from('no-reply@grocare.com', 'Grocare');
					$message->to($email)->subject($subject);
				});
				if($mail){
					$orderSession=orderSession::find($order_id);
					$orderSession->order_session_mail=1;
					$orderSession->save();
				}
				
			}
		}
	}
	
	public function orderTrackingEmail(){
		if(date('H')<= 21 && date('H')>=9){
		
			$orderTracking=OrderTracking::select('order_tracking.id','order_tracking.tracking_number','a.billing_name','a.billing_email','a.billing_phone',
						   'a.shipping_address1','a.shipping_address2','a.shipping_city','a.shipping_state','a.shipping_country',
						   'a.shipping_zip','a.shipping_name','b.name as courier_name','b.slug as courier_slug','c.invoice_no')
										->join('order_address as a','a.order_id','=','order_tracking.order_id')
										->join('couriers as b','b.id','=','a.selected_courier')
										->join('order_details as c','c.id','=','order_tracking.order_id')
										->where('order_tracking.notify_status',0)
										->where('order_tracking.order_tracking_email',0)
										->get();
										
			if(count($orderTracking)>0){
				foreach($orderTracking as $key=>$val){
					$email=$val->billing_email;
					$phone=$val->billing_phone;
					$name=$val->billing_name;
					$tracking_number=$val->tracking_number;
					$id=$val->id;
					$data['email'] = $email;
					$data['phone'] = $phone;
					$data['name'] = $name;
					$data['tracking_number'] = $tracking_number;

					$notifications = new AfterShip\Notifications($this->afterShipKey);
					$response = $notifications->create($val->courier_slug, $tracking_number, array ());

					$data['shipping_name'] = $val->shipping_name;
					$data['address1'] = $val->shipping_address1;
					$data['address2'] = $val->shipping_address2;
					$data['city'] = $val->shipping_city;
					$data['state'] = $val->shipping_state;
					$data['country'] = $this->countries[$val->shipping_country];
					$data['zipcode'] = $val->shipping_zip;
					$data['courier_name'] = $val->courier_name;
					$data['invoice_no'] = $val->invoice_no;

					$subject = 'Grocare India - Track Your Order';
					$mail=Mail::send('emails.trackingEmail', $data, function($message)
							use($email,$subject){
						$message->from('no-reply@grocare.com', 'Grocare');
						$message->to($email)->subject($subject);
					});
					if($mail){
						$orderTracking=OrderTracking::find($id);
						$orderTracking->order_tracking_email=1;
						$orderTracking->save();
					}
					
				}
			}
		}
	}

	public function pendingPaymentEmail(){
		$orderDetails=OrderDetails::select('order_details.oid','order_details.id','order_details.payment_type','order_details.order_placed_date','order_details.order_status','order_details.invoice_no','a.billing_name','a.billing_email','a.billing_phone')
					->join('order_address as a','a.order_id','=','order_details.id')
					->where('order_details.delete_status',0)->where('order_details.order_status','<=',3)
					->where('order_details.payment_type',2)->groupBy('order_details.id')->get();


		$bankDetails = BankDetails::where('id',1)->first();
		$currentDate = date('Y-m-d');

		if(isset($orderDetails) && !empty($orderDetails)) {
			foreach ($orderDetails as $detail) {
				$date = date('Y-m-d',strtotime($detail->order_placed_date));
				if($date!=$currentDate) {
				$checkPendingPayment = PendingPaymentEmail::where('order_id',$detail->id)/*->whereRaw('DATE(date)="'.$currentDate.'"')*/->first();
					if(!isset($checkPendingPayment->id)) {

						$data = array();

						$email = $detail->billing_email;


						$data['bankDetails'] = $bankDetails;
						$data['name'] = $detail->billing_name;

//return $data;

						$subject = 'Grocare India - Order Payment Pending';
						$mail=Mail::send('emails.pendingPayment', $data, function($message)
								use($email,$subject){
							$message->from('no-reply@grocare.com', 'Grocare');
							$message->to($email)->subject($subject);
						});

						$pendingPayment = new PendingPaymentEmail;
						$pendingPayment->order_id = $detail->id;
						$pendingPayment->date = date('Y-m-d');
						$pendingPayment->save();


					}

				}
			}
		}

	}
	
	public function initiateSessionOrder($id){

$curDate = date('Y-m-d 23:59:59');
$pastDate = date('Y-m-d 00:00:00',strtotime('-7 days'));
//echo $curDate.'---'.$pastDate;
		$orderSessionCheck = OrderSession::where('id',$id)->where('order_status','!=',2)->whereBetween('created_at',array($pastDate,$curDate))->count();
		$orderDetailsCheck = OrderDetails::where('oid',$id)->whereNotNull('order_status')->where('delete_status',0)->count();

//echo $orderSessionCheck; die();
		if($orderSessionCheck!=0 && $orderDetailsCheck==0) {
			$orderSessionCheck = OrderSession::where('id',$id)->where('order_status','!=',2)->first();
			Session::put('order_id',$id);
			Session::put('active_country',strtoupper($orderSessionCheck->order_country));
		}
		return redirect('/cart');
	}
	
}