<?php namespace App\Helper;
use Auth;
use Session;
use DB;
use Mail;
use Log;
use Config;
use App\Models\Courses;
use App\Models\Products;
use App\Models\ProductCourse;
use App\Models\ProductRegion;
use App\Models\ProductImages;
use App\Models\ProductTiles;
use App\Models\ProductBundle;
use App\Models\Diagnosis;
use App\Models\DiagnosisBlock;
use App\Models\DiagnosisProducts;
use App\Models\DiagnosisProductsContent;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\OrderSession;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Models\TrackingIds;
use App\Models\UserLog;

class FrontHelper {

    public static function getGeoLocation(){
    	$activeCountry = 'IN';
    	$activeCurrency = 'INR';
    	$activeRegion = '';
    	$activeCurrencySymbol = '';
        $activeShippingCharge = '';
        $activeMinimumAmount = '';
    	//$ip = $_SERVER['REMOTE_ADDR'];

        $ip = getenv('HTTP_CLIENT_IP')?:
        getenv('HTTP_X_FORWARDED_FOR')?:
        getenv('HTTP_X_FORWARDED')?:
        getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
        getenv('REMOTE_ADDR');
        //echo $ip;
        //die();

        if(empty($ip)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

if(Session::has('active_country')) {

            $activeCountry = Session::get('active_country');

        } else {

if(isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
        $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"]; //from cloudflare headers

$activeCountry = strtoupper($country_code);

}
        

       /* $ipDetails = (unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip)));
        if(isset($ipDetails['geoplugin_status']) && $ipDetails['geoplugin_status']==200){
        	$activeCountry = $ipDetails['geoplugin_countryCode'];
        }*/
        
        
        
        

}
	//Log::info('Active Country: '.$activeCountry);
        $getRegion = Regions::whereRaw("countries regexp '[[:<:]]".$activeCountry."[[:>:]]'")->where('delete_status',0)->first();
       // Log::info('getRegion: '.$getRegion);
        if(isset($getRegion->id)) {
        	$activeRegion = $getRegion->id;
        	$activeCurrency = $getRegion->currency;
            $activeShippingCharge = $getRegion->shipping_charge;
            $activeMinimumAmount = $getRegion->minimum_amount;
        }
        $getSymbol = CurrencyRates::where('from_currency',$activeCurrency)->where('delete_status',0)->first(); 

        if(!Session::has('user_log_id')) {
            $userLog = new UserLog;
            $userLog->ip_address = $ip;
            $userLog->country = $activeCountry;
            $userLog->currency = $activeCurrency;
            $userLog->save();
            $userLogId = $userLog->id;
            Session::put('user_log_id',$userLogId);
        }

		Session::put('active_country',$activeCountry);
        Session::put('active_currency',$activeCurrency);
        Session::put('active_region',$activeRegion);
        Session::put('active_symbol',$getSymbol->symbol);
        Session::put('active_shipping_charge',$activeShippingCharge);
        Session::put('active_minimum_amount',$activeMinimumAmount);

if(isset($_SERVER['HTTP_REFERER']) && !Session::has('referrer')) {
			Session::put('referrer',$_SERVER['HTTP_REFERER']);
		}

    }

    public static function getOrderTotal(){
        $total = 0;
        if(Session::has('order_id')) {
            $getProducts = OrderProducts::select(DB::Raw('SUM(product_qty*product_price) as amt'))->where('oid',Session::get('order_id'))->first();
            $total = $getProducts->amt;
            $shippingCharge = Session::get('active_shipping_charge');
            $minimumAmount = Session::get('active_minimum_amount');
            if($shippingCharge!=0.00 && ($minimumAmount==0.00 || $minimumAmount>$total) && !empty($total)) {
              $shippingCharge = round(Session::get('active_shipping_charge'));
              $total = round($shippingCharge)+$total;
            } else {
              $shippingCharge = "";
              $total = $total;
            }
        }
        return $total;
    }

    public static function getKitProducts($oid,$pid){
        $getKitProducts = OrderBundleProducts::where('oid',$oid)->where('order_product_id',$pid)->orderBy('product_name','ASC')->get();
        return $getKitProducts;
    }

    public static function getKitDetails($id){

        $diagnosisPrice = ProductRegion::where('fkregion_id',Session::get('active_region'))->where('fkproduct_id',$id)->first();
                /*if(!empty($diagnosisPrice->sales_price)&&$diagnosisPrice->sales_price!=0.00) {
                    $displayPrice = $diagnosisPrice->sales_price;
                } else {
                    $displayPrice = $diagnosisPrice->regular_price;
                }
                $singlePrice = round($displayPrice);*/
                $displayPrice =0.00;
                $singlePrice = 0.00;
                $diagnosisCourse = ProductBundle::join('courses as t2', 'product_bundle.fkcourse_id', '=', 't2.id')
                                   ->select('product_bundle.*','t2.course_name','t2.rate_multiply')
                                   ->where('product_bundle.fkbundleproductregion_id',$diagnosisPrice->id)
                                   ->groupBy('product_bundle.fkcourse_id')->orderBy('product_bundle.fkcourse_id','ASC')->get();
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
                            $productPrice = ProductRegion::where('fkproduct_id',$diagonCourFirst->fksimpleproduct_id)->where('fkregion_id',Session::get('active_region'))->first();
                            

                            $qty = $diagonCourFirst->quantity;
                            if(!empty($productPrice->sales_price) && $productPrice->sales_price!=0.00) {
                                $price = round($productPrice->sales_price);
                            } else {
                                $price = round($productPrice->regular_price);
                            }

                            $displayPrice += $qty*$price;

                        }
                    }





                            break;
                        }
                    }
                }
                
                         
                $singlePrice = round($displayPrice);

                $response['displayPrice'] = $displayPrice;
                $response['displayCourse'] = $displayCourse;
                $response['diagnosisCourse'] = $diagnosisCourse;

                return $response;
    }

     public static function getTrackingID($type){
        $trackingId=TrackingIds::first();              
        if($type==2) {
            return $trackingId->thankyou_page;
        } else {
            return $trackingId->whole_site;
        }
    }

    public static function getActiveCountries(){
        $countriesList = array();
        $countries = Config::get('custom.country');
        $getRegion = Regions::where('delete_status',0)->get();
        if(isset($getRegion) && !empty($getRegion)) {
            foreach($getRegion as $reg) {
                $activeCountries = explode(',',$reg->countries);
                if(!empty($activeCountries)) {
                    foreach($activeCountries as $key=>$value) {
                        $countriesList[$value] = $countries[$value];
                    }
                }
            }
        }
asort($countriesList);
        return $countriesList;
    }

}