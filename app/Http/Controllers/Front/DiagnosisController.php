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
use App\Models\Diagnosis;
use App\Models\DiagnosisBlock;
use App\Models\DiagnosisProducts;
use App\Models\DiagnosisProductsContent;
use App\Models\Testimonials;
use App\Models\Seo;
use App\Helper\FrontHelper;
use Session;
use Config;

class DiagnosisController extends Controller {

	public function __construct()
    {
    	$getCountryCode = FrontHelper::getGeoLocation();
    	$this->activeCountry = Session::get('active_country');
    	$this->activeCurrency = Session::get('active_currency');
    	$this->activeRegion = Session::get('active_region');
    	$this->activeSymbol = Session::get('active_symbol');
    	$this->siteTitle = Config::get('custom.siteTitle');
    	/*echo Session::get('active_country').'<br/>';
    	echo Session::get('active_currency').'<br/>';
    	echo Session::get('active_region').'<br/>';
    	echo Session::get('active_symbol').'<br/>';
    	die();*/
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $diagnosis = Diagnosis::select('id','name','diagnosis_slug','disease_short_description','disease_description')->where('delete_status',0);
        if(!empty($search)) {
            $diagnosis = $diagnosis->where('name','LIKE',$search.'%');
        }
        $diagnosis = $diagnosis->orderBy('name','ASC')->get();
        $diagnosisList = Diagnosis::select('id','name','diagnosis_slug')->where('delete_status',0)->get();
        $pageTitle = "Self Diagnosis | ".$this->siteTitle;
		$meta_description = '';
		$seoDetails = Seo::where('page',8)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
        return view('front/diagnose/list')->with(array('pageTitle'=>$pageTitle,'diagnosis'=>$diagnosis,'diagnosisList'=>$diagnosisList,'meta_description'=>$meta_description));
    }

    public function selfDiagnosisDetail($string)
    {
        $activeRegion = Session::get('active_region');
		$activeCountry = Session::get('active_country');
		//return $activeCountry;
        $diagnosisDetail = Diagnosis::where('delete_status',0)->where('diagnosis_slug',$string)->first();
        if(!isset($diagnosisDetail->id)){
        	return redirect('diagnose');
        }
        $meta_keywords = $diagnosisDetail->meta_keywords;
        $meta_description = $diagnosisDetail->meta_description; 
        $diagnosisBlock  = DiagnosisBlock::where('fkdiagnosis_id',$diagnosisDetail->id)->get();
        $diagnosisProduct = DiagnosisProducts::join('diagnosis_products_content as t2', 't2.fkdiagnosisproduct_id', '=', 'diagnosis_products.id')
                            ->join('products as t3', 't2.product_id', '=', 't3.id')
                            ->select('diagnosis_products.id as rid','diagnosis_products.product_type','t2.product_id',
                             't2.product_content','t3.name','t3.feature_image','t3.feature_image','t3.feature_image_original','t3.key_ingredients','t3.product_slug')
                            ->where('diagnosis_products.fkdiagnosis_id',$diagnosisDetail->id)
                            ->where('diagnosis_products.fkregion_id',$activeRegion)->get();
        $diagnosisProductType = DiagnosisProducts::select('fkproduct_id','product_type')
                                ->where('diagnosis_products.fkdiagnosis_id',$diagnosisDetail->id)
                                ->where('diagnosis_products.fkregion_id',$activeRegion)->first();
        $diagnosisPrice = array();
        $diagnosisCourse = array();
        $displayPrice = '';
        $displayCourse = '';
        $singlePrice = '';
		if(count($diagnosisProductType)>0){
			if($diagnosisProductType->product_type==1) {
				$diagnosisPrice = ProductRegion::where('fkregion_id',$activeRegion)->where('fkproduct_id',$diagnosisProductType->fkproduct_id)->first();
				$diagnosisCourse = ProductCourse::join('courses as t2', 'product_course.fkcourse_id', '=', 't2.id')
								   ->select('product_course.*','t2.course_name')
								   ->where('product_course.fkproductregion_id',$diagnosisPrice->id)
								   ->orderBy('product_course.fkcourse_id','ASC')->get();
				if(!empty($diagnosisPrice->sales_price)&&$diagnosisPrice->sales_price!=0.00) {
					$displayPrice = $diagnosisPrice->sales_price;
				} else {
					$displayPrice = $diagnosisPrice->regular_price;
				}
				$singlePrice = round($displayPrice);
				if(!empty($diagnosisCourse)) {
					$k=1;
					foreach($diagnosisCourse as $diaCour) {
						if($k==1) {
							$displayCourse = $diaCour->course_name;
							$displayPrice = round(($displayPrice*$diaCour->quantity));
							break;
						}
					}
				}
			} else if($diagnosisProductType->product_type==2) {
				$diagnosisPrice = ProductRegion::where('fkregion_id',$activeRegion)->where('fkproduct_id',$diagnosisProductType->fkproduct_id)->first();
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
							$productPrice = ProductRegion::where('fkproduct_id',$diagonCourFirst->fksimpleproduct_id)->where('fkregion_id',$activeRegion)->first();
							

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
		   
			}
		}	

        $shippingCharge = Regions::where('id',$activeRegion)->first();
        $testimonials = Testimonials::where('testi_for',$diagnosisDetail->id)->where('disease_view','!=',1)->orderByRaw('RAND()')->get();
       //return $shippingCharge;
        if($diagnosisDetail->seo_title!='') {
        	$pageTitle = $diagnosisDetail->seo_title;
        } else {
        	$pageTitle = ucwords($diagnosisDetail->name)." | ".$this->siteTitle;
        }
        return view('front/diagnose/detail')->with(array('pageTitle'=>$pageTitle,'diagnosisDetail'=>$diagnosisDetail, 'diagnosisBlock'=>$diagnosisBlock,
               'diagnosisProduct'=>$diagnosisProduct, 'diagnosisPrice'=>$diagnosisPrice, 'diagnosisCourse'=>$diagnosisCourse,
               'displayPrice'=>$displayPrice, 'displayCourse'=>$displayCourse,'symbol'=>$this->activeSymbol,
               'shippingCharge'=>$shippingCharge, 'testimonials'=>$testimonials, 'diagnosisProductType'=>$diagnosisProductType,
               'singlePrice'=>$singlePrice, 'meta_keywords'=>$meta_keywords, 'meta_description'=>$meta_description, 'activeCountry'=>$activeCountry));
    }
    public function previewSelfDiagnosisDetail($string)
    {
        $diagnosisDetail = Diagnosis::where('delete_status',0)->where('diagnosis_slug',$string)->first();
        $diagnosisBlock  = DiagnosisBlock::where('fkdiagnosis_id',$diagnosisDetail->id)->get();
        $diagnosisProduct = DiagnosisProducts::join('diagnosis_products_content as t2', 't2.fkdiagnosisproduct_id', '=', 'diagnosis_products.id')
                            ->join('products as t3', 't2.product_id', '=', 't3.id')
                            ->select('diagnosis_products.id as rid','diagnosis_products.product_type','t2.product_id',
                             't2.product_content','t3.name','t3.feature_image','t3.key_ingredients','t3.product_slug')
                            ->where('diagnosis_products.fkdiagnosis_id',$diagnosisDetail->id)
                            ->where('diagnosis_products.fkregion_id',$activeRegion)->get();
        $diagnosisProductType = DiagnosisProducts::select('fkproduct_id','product_type')
                                ->where('diagnosis_products.fkdiagnosis_id',$diagnosisDetail->id)
                                ->where('diagnosis_products.fkregion_id',$activeRegion)->first();
        $diagnosisPrice = array();
        $diagnosisCourse = array();
        $displayPrice = '';
        $displayCourse = '';
        $singlePrice = '';
		if(count($diagnosisProductType)>0){
			if($diagnosisProductType->product_type==1) {
				$diagnosisPrice = ProductRegion::where('fkregion_id',$activeRegion)->where('fkproduct_id',$diagnosisProductType->fkproduct_id)->first();
				$diagnosisCourse = ProductCourse::join('courses as t2', 'product_course.fkcourse_id', '=', 't2.id')
								   ->select('product_course.*','t2.course_name')
								   ->where('product_course.fkproductregion_id',$diagnosisPrice->id)
								   ->orderBy('product_course.fkcourse_id','ASC')->get();
				if(!empty($diagnosisPrice->sales_price)&&$diagnosisPrice->sales_price!=0.00) {
					$displayPrice = $diagnosisPrice->sales_price;
				} else {
					$displayPrice = $diagnosisPrice->regular_price;
				}
				$singlePrice = round($displayPrice);
				if(!empty($diagnosisCourse)) {
					$k=1;
					foreach($diagnosisCourse as $diaCour) {
						if($k==1) {
							$displayCourse = $diaCour->course_name;
							$displayPrice = round(($displayPrice*$diaCour->quantity));
							break;
						}
					}
				}
			} else if($diagnosisProductType->product_type==2) {
				$diagnosisPrice = ProductRegion::where('fkregion_id',$activeRegion)->where('fkproduct_id',$diagnosisProductType->fkproduct_id)->first();
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
							$productPrice = ProductRegion::where('fkproduct_id',$diagonCourFirst->fksimpleproduct_id)->where('fkregion_id',$activeRegion)->first();
							

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
		   
			}
		}	

        $shippingCharge = Regions::where('id',$activeRegion)->first();
        $testimonials = Testimonials::where('testi_for',$diagnosisDetail->id)->where('disease_view','!=',1)->where('type','!=',2)->orderByRaw('RAND()')->get();
       	$pageTitle = ucwords($diagnosisDetail->name)." | ".$this->siteTitle;
        return view('front/diagnose/previewDetail')->with(array('pageTitle'=>$pageTitle,'diagnosisDetail'=>$diagnosisDetail, 'diagnosisBlock'=>$diagnosisBlock,
               'diagnosisProduct'=>$diagnosisProduct, 'diagnosisPrice'=>$diagnosisPrice, 'diagnosisCourse'=>$diagnosisCourse,
               'displayPrice'=>$displayPrice, 'displayCourse'=>$displayCourse,'symbol'=>$this->activeSymbol,
               'shippingCharge'=>$shippingCharge, 'testimonials'=>$testimonials, 'diagnosisProductType'=>$diagnosisProductType,
               'singlePrice'=>$singlePrice));
    }

}