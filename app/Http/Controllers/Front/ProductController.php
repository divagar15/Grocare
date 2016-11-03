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
use App\Models\Seo;
use App\Helper\FrontHelper;
use Log;
use Session;
use Config;

class ProductController extends Controller {

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

	public function index()
	{
        $activeRegion = Session::get('active_region');
        /*$activeRegion = Session::get('active_region');
        if($activeRegion == ''){
         FrontHelper::getGeoLocation();
        }*/
        //Log::info('Active Region: '.$activeRegion);
        $productList = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price')
                       ->where('products.product_type','=',1)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id','=',$activeRegion)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->get();
       //return $productList;
        $pageTitle = "Store | ".$this->siteTitle;
		$meta_description = '';
		$seoDetails = Seo::where('page',7)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
		return view('front/product/list')->with(array('pageTitle'=>$pageTitle,'productList'=>$productList,'symbol'=>$this->activeSymbol,'meta_description'=>$meta_description));
	}

    public function productDetail($string)
    {
        $productCheck = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id')->where('products.product_type','=',1)->where('products.delete_status','=',0)
                       ->where('products.product_slug',$string)->where('products.website_visible',1)->where('t2.fkregion_id',$this->activeRegion)
                       ->where('products.stock_status',1)->where('t2.enable',1)->first();
        if(isset($productCheck->id)) {
            $pid = $productCheck->id;
        } else {
            return redirect('products');
        }

        $productDetail = Products::where('id',$pid)->first();
		
        $productRegion = ProductRegion::where('fkproduct_id',$pid)->where('fkregion_id',$this->activeRegion)->first();
        $productCourse = ProductCourse::join('courses as t2', 't2.id', '=', 'product_course.fkcourse_id')
                         ->select('product_course.*','t2.course_name')->where('fkproductregion_id',$productRegion->id)->get();
        $productTiles  = ProductTiles::where('fkproduct_id',$pid)->get();
        //return $productCourse;
		if($productDetail->seo_title!='') {
			$pageTitle = $productDetail->seo_title;
		} else {
			$pageTitle = ucwords($productDetail->name)." | ".$this->siteTitle;
		}
		$meta_description = $productDetail->meta_description; 
        return view('front/product/detail')->with(array('pageTitle'=>$pageTitle,'productDetail'=>$productDetail, 'productRegion'=>$productRegion,
        'productCourse'=>$productCourse,'productTiles'=>$productTiles,
	    'meta_description'=>$meta_description));
    }
    public function previewProductDetail($string)
    {
        $productCheck = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id')->where('products.product_type','=',1)->where('products.delete_status','=',0)
                       ->where('products.product_slug',$string)->where('products.website_visible',1)->where('t2.fkregion_id',$this->activeRegion)
                       ->where('products.stock_status',1)->where('t2.enable',1)->first();
        if(isset($productCheck->id)) {
            $pid = $productCheck->id;
        } else {
            return redirect('products');
        }

        $productDetail = Products::where('id',$pid)->first();
        $productRegion = ProductRegion::where('fkproduct_id',$pid)->where('fkregion_id',$this->activeRegion)->first();
        $productCourse = ProductCourse::join('courses as t2', 't2.id', '=', 'product_course.fkcourse_id')
                         ->select('product_course.*','t2.course_name')->where('fkproductregion_id',$productRegion->id)->get();
        $productTiles  = ProductTiles::where('fkproduct_id',$pid)->get();
        //return $productCourse;
        $pageTitle = ucwords($productDetail->name)." | ".$this->siteTitle;
        return view('front/product/previewDetail')->with(array('pageTitle'=>$pageTitle,'productDetail'=>$productDetail, 'productRegion'=>$productRegion,
               'productCourse'=>$productCourse,'productTiles'=>$productTiles));
    }

    public function productContent($string)
    {
        $productCheck = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->select('products.id')->where('products.product_type','=',1)->where('products.delete_status','=',0)
                       ->where('products.product_slug',$string)->where('products.website_visible',1)->where('t2.fkregion_id',$this->activeRegion)
                       ->where('products.stock_status',1)->where('t2.enable',1)->first();
        if(isset($productCheck->id)) {
            $pid = $productCheck->id;
        } else {
            return redirect('products');
        }

        $productDetail = Products::where('id',$pid)->first();
        $productRegion = ProductRegion::where('fkproduct_id',$pid)->where('fkregion_id',$this->activeRegion)->first();
        $productCourse = ProductCourse::join('courses as t2', 't2.id', '=', 'product_course.fkcourse_id')
                         ->select('product_course.*','t2.course_name')->where('fkproductregion_id',$productRegion->id)->get();
        $productTiles  = ProductTiles::where('fkproduct_id',$pid)->get();
        //return $productCourse;
        $pageTitle = ucwords($productDetail->name)." Contents | ".$this->siteTitle;
        return view('front/product/content')->with(array('pageTitle'=>$pageTitle,'productDetail'=>$productDetail, 'productRegion'=>$productRegion,
               'productCourse'=>$productCourse,'productTiles'=>$productTiles));
    }

    public function medicineKit()
    {
        $productList = Products::join('product_region as t2', 't2.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis_products as t3', 't3.fkproduct_id', '=', 'products.id')
                       ->join('diagnosis as t4', 't3.fkdiagnosis_id', '=', 't4.id')
                       ->select('products.id','products.name','products.short_description','products.feature_image','products.product_slug','t2.regular_price','t2.sales_price','t4.name as diagnosis_name','t4.diagnosis_slug','t4.id as did')
                       ->where('products.product_type','=',2)->where('products.delete_status','=',0)->where('products.website_visible',1)
                       ->where('products.stock_status',1)->where('t2.fkregion_id',$this->activeRegion)->where('t2.enable',1)
                       ->orderBy('products.name','ASC')->groupBy('t3.fkproduct_id')->get();
       // return $productList;
        $pageTitle = "Medicine Kit | ".$this->siteTitle;
        return view('front/product/medicine-kit')->with(array('pageTitle'=>$pageTitle,'productList'=>$productList,'symbol'=>$this->activeSymbol));
    }

}