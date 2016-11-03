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
use Hash;
use DB;
use Config;
use Auth;

class CourierController extends Controller {

	public function __construct()
    {
        $this->orderStatus = Config::get('custom.orderStatus');
        $this->countries = Config::get('custom.country');
        $this->paymentType = Config::get('custom.paymentType');
        $this->currencies = Config::get('custom.currency');
        $this->afterShipKey = Config::get('custom.afterShipKey');
    	$this->enabledCurrency = new EnabledCurrency;
    }

    public function courierList(Request $request)
    {
        $courierList = User::join('couriers as t2', 't2.id', '=', 'users.courier_id')
                       ->select('users.id','users.name','users.email','t2.name as courierName')->where('users.user_type',3)
                       ->where('users.delete_status',0)->get();
        return view('admin/courier/list')->with(array('courierList'=>$courierList));
    }


    public function add(Request $request){
        if($request->isMethod('post')){
            $name = trim($request->name);
            $email = trim($request->email);
            $password = trim($request->password);
            $courier = $request->courier;

            $checkEmail = User::where('email',$email)->where('delete_status',0)->first();
            if(isset($checkEmail->id)) {
                return redirect('admin/courier-login/add')->with('error_msg','Login ID already exists.');
            }

            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password_text = $password;
            $user->password = Hash::make($password);
            $user->courier_id = $courier;
            $user->user_type = 3;

            if($user->save()){
                return redirect('admin/courier-login/list')->with('success_msg','User Added Successfully'); 
            }
            else{
                return redirect('admin/courier-login/add')->with('error_msg','User could not be added. Please try again');
            }

        }
        else{
            $couriers = Couriers::where('delete_status',0)->get();
            return view('admin/courier/add')->with(array('couriers'=>$couriers));
        }
    }

    public function edit($id,Request $request){
        if($request->isMethod('post')){
            $name = trim($request->name);
            $email = trim($request->email);
            $courier = $request->courier;
            $change_password = trim($request->change_password);

            $checkEmail = User::where('email',$email)->where('delete_status',0)->where('id','!=',$id)->first();
            if(isset($checkEmail->id)) {
                return redirect('admin/courier-login/edit/'.$id)->with('error_msg','Login ID already exists.');
            }

            $user = User::find($id);
            $user->name = $name;
            $user->email = $email;
            if($change_password==1) {
                $password = trim($request->password);
                $user->password_text = $password;
                $user->password = Hash::make($password);
            }
            $user->courier_id = $courier;

            if($user->save()){
                return redirect('admin/courier-login/list')->with('success_msg','User Upated Successfully'); 
            }
            else{
                return redirect('admin/courier-login/edit/'.$id)->with('error_msg','User could not be updated. Please try again');
            }

        }
        else{
            $couriers = Couriers::where('delete_status',0)->get();
            $courierUser = User::where('id',$id)->where('user_type',3)->where('delete_status',0)->first();
            return view('admin/courier/edit')->with(array('couriers'=>$couriers,'courierUser'=>$courierUser));
        }
    }


    public function delete($id)
    {
        $deleteUser = User::find($id);
        $deleteUser->delete_status=1;
        if($deleteUser->save()) {
            return redirect('admin/courier-login/list')->with('success_msg','User Deleted Successfully'); 
        }
    }



    public function orderTracking(Request $request)
    {

        if($request->isMethod('post')){

$courierId = 4;

            $trackingNumber1 = $request->trackingNumber1;
            $trackingNumber2 = $request->trackingNumber2;

            if(!empty($trackingNumber1)) {

                foreach ($trackingNumber1 as $key => $value) {

                    if(!empty($value)) {

                        $splitValues = explode('_', $key);
                        if($splitValues[1]!=0) {

                            $orderTracking = OrderTracking::find($splitValues[1]);
                            $orderTracking->tracking_number = $value;
                            $orderTracking->entered_by = Auth::user()->id;
                            $orderTracking->save();

                        } else {

                            $orderTracking = new OrderTracking;
                            $orderTracking->tracking_number = $value;
                            $orderTracking->order_id = $splitValues[0];
                            $orderTracking->entered_by = Auth::user()->id;
                            $orderTracking->save();

                        }

$updateOrderAddress = DB::table('order_address')->where('order_id',$splitValues[0])->update(array('selected_courier'=>$courierId));

            $orderAddress = OrderAddress::where('order_id',$splitValues[0])->first();
            $courier = Couriers::where('id',$orderAddress->selected_courier)->first();

                        $dataTime = date('Y-m-d H:i:s');
                        $orderUpdate = OrderDetails::find($splitValues[0]);
                        $orderUpdate->order_status = 6;
                        $orderUpdate->order_completed_date = $dataTime;
                        $orderUpdate->save();

$trackings = new AfterShip\Trackings($this->afterShipKey);
                    $tracking_info = array(
                        'slug'    => $courier->slug,
                        'title'   => trim($value),
                        'emails'  => $orderAddress->billing_email,
                        'smses'   => $orderAddress->billing_phone
                    );
                    $response = $trackings->create(trim($value), $tracking_info);

                    }

                }

            }


            if(!empty($trackingNumber2)) {

                foreach ($trackingNumber2 as $key => $value) {

                    if(!empty($value)) {
                        $splitValues = explode('_', $key);
                        if($splitValues[1]!=0) {

                            $orderTracking = OrderTracking::find($splitValues[1]);
                            $orderTracking->tracking_number = $value;
                            $orderTracking->entered_by = Auth::user()->id;
                            $orderTracking->save();

                        } else {

                            $orderTracking = new OrderTracking;
                            $orderTracking->tracking_number = $value;
                            $orderTracking->order_id = $splitValues[0];
                            $orderTracking->entered_by = Auth::user()->id;
                            $orderTracking->save();

                        }

$updateOrderAddress = DB::table('order_address')->where('order_id',$splitValues[0])->update(array('selected_courier'=>$courierId));

            $orderAddress = OrderAddress::where('order_id',$splitValues[0])->first();
            $courier = Couriers::where('id',$orderAddress->selected_courier)->first();

                        $dataTime = date('Y-m-d H:i:s');
                        $orderUpdate = OrderDetails::find($splitValues[0]);
                        $orderUpdate->order_status = 6;
                        $orderUpdate->order_completed_date = $dataTime;
                        $orderUpdate->save();

$trackings = new AfterShip\Trackings($this->afterShipKey);
                    $tracking_info = array(
                        'slug'    => $courier->slug,
                        'title'   => trim($value),
                        'emails'  => $orderAddress->billing_email,
                        'smses'   => $orderAddress->billing_phone
                    );
                    $response = $trackings->create(trim($value), $tracking_info);

                    }
                }

            }

            return redirect('admin/order-tracking')->with('success_msg','Orders Updated Successfully');

        } else {

        $orderDetails = OrderDetails::join('order_session as t2','order_details.oid','=','t2.id')
                        ->join('order_address as t3','order_details.id','=','t3.order_id')
                        ->leftJoin('order_products as t4','order_details.oid','=','t4.oid')
                        ->select('order_details.id','order_details.oid','order_details.invoice_no','order_details.grand_total','order_details.grand_total_inr','order_details.payment_type',
                          'order_details.fkcustomer_id','order_details.order_status','order_details.order_placed_date','t2.order_symbol',
                          't3.billing_name','t3.shipping_name','t3.shipping_email','t3.shipping_phone','t3.shipping_address1',
                          't3.shipping_address2','t3.shipping_state','t3.shipping_city','t3.shipping_country','t3.shipping_zip',
                          DB::raw("COUNT(t4.id) as items"));/*->where('order_details.order_status','<=',3)*/



        $orderDetails = $orderDetails->whereNotNull('order_details.order_status')->whereIn('order_details.order_status',['4'])->where('order_details.delete_status',0)->groupBy('t4.oid')->orderBy('order_details.id','DESC')->get();

        $orderTracking = array();
        $oid = array();

        if(isset($orderDetails) && !empty($orderDetails)) {
            foreach ($orderDetails as $detail) {
                $oid[] = $detail->id;
            }

            $orderTracking = OrderTracking::whereIn('order_id',$oid)->get();
        }

        //return $orderTracking;

        $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();

        $inrSymbol = CurrencyRates::select('symbol')->where('from_currency','INR')->first();

       // return $enabledCurrency;


        return view('admin/courier/orderTracking')->with(array('orderDetails'=>$orderDetails, 'orderStatus'=>$this->orderStatus,
               'countries'=>$this->countries,'paymentType'=>$this->paymentType,
               'enabledCurrency'=>$enabledCurrency,'currencies'=>$this->currencies,'inrSymbol'=>$inrSymbol,
               'orderTracking'=>$orderTracking));

        }
    }


    public function viewOrderTracking(Request $request,$id,$id2)
    {
        if($request->isMethod('post')){

            $courierId = Auth::user()->courier_id;

             $counters = $request->counters;

            $updateOrderAddress = DB::table('order_address')->where('order_id',$id2)->update(array('selected_courier'=>$courierId));

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
                        $tracking->entered_by = Auth::user()->id;
                        $tracking->save();

                    } else {

                        $tracking = new OrderTracking;
                        $tracking->tracking_number = trim($request->$trackingNumber);
                        $tracking->order_id = $id2;
                        $tracking->products = $selectedProduct;
                        $tracking->entered_by = Auth::user()->id;
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

                 return redirect('admin/order-tracking/'.$id.'/'.$id2)->with('success_msg','Order Updated Successfully');



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
            
            $mailContentsTrackList=mailContentsTracking::selectRaw('m.title')
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


            return view('admin/courier/viewOrderTracking')->with(array('simpleProducts'=>$simpleProducts,'bundleProducts'=>$bundleProducts,
                   'currencySelected'=>$currencySelected,'getRegion'=>$getRegion,'getSymbol'=>$getSymbol,'countries'=>$this->countries,
                   'currencies'=>$this->currencies,'orderSession'=>$orderSession,'orderDetail'=>$orderDetail,
                   'orderAddress'=>$orderAddress,'orderProducts'=>$orderProducts,'customers'=>$customers,'couriers'=>$couriers,'orderTracking'=>$orderTracking,'orderNotes'=>$orderNotes,
                   'inrSymbol'=>$inrSymbol,'orderStatus'=>$this->orderStatus,'paymentType'=>$this->paymentType,'mailContents'=>$mailContents,
                   'mailContentsTrackList'=>$mailContentsTrackList,'pastOrderCount'=>$pastOrderCount));
        }
    }


    public function removeTracking($id,$tid)
    {
        $deleteTracking = OrderTracking::where('id',$tid)->delete();
        if($deleteTracking) {

                $orderSession = OrderDetails::select('oid')->where('id',$id)->first();
                return redirect('admin/order-tracking/'.$orderSession->oid.'/'.$id)->with('success_msg','Courier Tracking Number Removed Successfully');
        }
    }

}