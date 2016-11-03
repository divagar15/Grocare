<?php namespace App\Helper;
use Auth;
use Session;
use DB;
use Mail;
use Config;
use App\Models\Courses;
use App\Models\ProductCourse;
use App\Models\Products;
use App\Models\ProductRegion;
use App\Models\ProductBundle;
use App\Models\DiagnosisProducts;
use App\Models\DiagnosisProductsContent;
use App\Models\OrderSession;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Models\OrderDetails;
use App\Models\MailContentsTracking;

class AdminHelper {
    public static function checkLoginStatus() {
        
        if(isset(Auth::user()->id)) {
          return true;
        } else {
          return false;
        }
        
    }

    public static function getCourseRegions($regId) {
    	$getProductCourses = ProductCourse::join('courses as t2', 'product_course.fkcourse_id', '=', 't2.id')
				      		 ->select('product_course.*','t2.course_name')
				      		 ->where('product_course.fkproductregion_id',$regId)
				      		 ->get();
		return $getProductCourses;
    }

    public static function getRegionProducts($regId) {
    	$getRegionProducts = Products::join('product_region as t2', 'products.id', '=', 't2.fkproduct_id')
				      		 ->select('products.id','t2.sku_name as name')
				      		 ->where('t2.fkregion_id',$regId)
                             ->where('products.delete_status',0)
				      		 //->where('t2.enable',1)
				      		 ->where('products.product_type',1)
				      		 ->get();
		return $getRegionProducts;
    }
	  public static function getCustomerProduct($regId) {
    	$getProducts = OrderProducts::where('oid',$regId)->get();
		return $getProducts;
    }

    public static function getDiagnosisProducts($regId) {
        $getRegionProducts = Products::join('product_region as t2', 'products.id', '=', 't2.fkproduct_id')
                             ->select('products.id','products.name','products.product_type')
                             ->where('t2.fkregion_id',$regId)
                             ->where('products.delete_status',0)
                             /*->where('products.product_type',1)
                             ->where('products.product_type',2)*/
                             ->get();
        return $getRegionProducts;
    }

    public static function getDiagnosisProductsSelected($did,$regId) {
        $getDiagnosisProductsSelected = DiagnosisProducts::join('diagnosis_products_content as t2', 'diagnosis_products.id', '=', 't2.fkdiagnosisproduct_id')
                                        ->join('products as t3', 't2.product_id', '=', 't3.id')
                                        ->join('product_region as t4', 't4.fkproduct_id', '=', 't3.id')
                                        ->select('diagnosis_products.fkproduct_id','t2.product_id', 't2.product_content','t4.sku_name as name')
                                        ->where('diagnosis_products.fkdiagnosis_id',$did)
										->where('diagnosis_products.fkregion_id',$regId)
										->where('t4.fkregion_id',$regId)
                                        ->get();
        return $getDiagnosisProductsSelected;

    }

     public static function getRegionBundleProducts($regId) {
    	$getRegionBundleProducts = ProductBundle::join('courses as t2', 'product_bundle.fkcourse_id', '=', 't2.id')
    						 ->join('products as t3', 'product_bundle.fksimpleproduct_id', '=', 't3.id')
				      		 ->select('product_bundle.*','t2.course_name','t3.name')
				      		 ->where('product_bundle.fkbundleproductregion_id',$regId)
				      		// ->where('t2.enable',1)
				      		// ->where('products.product_type',1)
				      		 ->get();
		return $getRegionBundleProducts;
    }

    public static function getKitProducts($oid,$pid){
        $getKitProducts = OrderBundleProducts::where('oid',$oid)->where('order_product_id',$pid)->get();
        return $getKitProducts;
    }

        public static function sendDietChart($id){

        $orderDetails=OrderDetails::select('order_details.oid','order_details.id','a.billing_name','a.billing_email','a.billing_phone')
                                    ->join('order_address as a','a.order_id','=','order_details.id')
//->where('order_details.order_completed_date','<=',$formatted_date)
                //->whereRaw('order_details. order_completed_date <= DATE_SUB(NOW(), INTERVAL 24 HOUR)')
                                  //  ->where('order_details.order_status',6)
                                    ->where('order_details.id',$id)
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
                    $subject = 'Grocare India - Diet chart';
                    $mail=Mail::send('emails.orderProcessEmail', $data, function($message)
                            use($email,$subject){
                        $message->from('no-reply@grocare.com', 'Grocare');
                        $message->to($email)->subject($subject);
                    });
                    if($mail){
                        $orderDetails=OrderDetails::find($values->id);
                        $orderDetails->order_process_email=1;
                        $orderDetails->save();


                        $mailContentsTracking=new MailContentsTracking;
                        $mailContentsTracking->order_id=$values->id;
                        $mailContentsTracking->email_content_id=0;
                        $mailContentsTracking->type = 1;
                        $mailContentsTracking->save();
                    }
                }
            }
        }

    }

}