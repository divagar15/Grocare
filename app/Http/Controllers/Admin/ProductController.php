<?php namespace App\Http\Controllers\Admin;

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
use Hash;
use Auth;
use Session;
use Config;
use Image;

class ProductController extends Controller {

    public function __construct()
    {
        $this->countries = Config::get('custom.country');
        $this->currencies = Config::get('custom.currency');
        $this->productType = Config::get('custom.productType');
        $this->stockStatus = Config::get('custom.stockStatus');
        $this->enabledCurrency = new EnabledCurrency;
        $this->currencyRates   = new CurrencyRates;
        $this->regions         = new Regions;
        $this->courses         = new Courses;
        $this->products        = new Products;
    }

    public function index(Request $request){
        $products = $this->products->getProductList();
        return view('admin/products/list')->with(array('products'=>$products, 'productType'=>$this->productType,
               'stockStatus'=>$this->stockStatus));
    }
    public function simpleList(Request $request){
        $products = $this->products->getSimpleProductList();
        return view('admin/products/simpleList')->with(array('products'=>$products,'stockStatus'=>$this->stockStatus));
    }
    public function bundleList(Request $request){
        $products = $this->products->getBundleProductList();
        return view('admin/products/bundleList')->with(array('products'=>$products,'stockStatus'=>$this->stockStatus));
    }

    public function addSimpleProduct(Request $request){

        if($request->isMethod('post')){
               
               
                $product_type = $request->product_type;
                $name = strtolower($request->name);
				$seo_title = $request->seo_title;
				$meta_description = $request->meta_description;
                $short_description = $request->short_description;
                $did_you_know = $request->did_you_know;
                $key_ingredients = $request->key_ingredients;
                $stock_status = $request->stock_status;
                $set_inventory_limit = $request->set_inventory_limit;
                $stock_limit = '';
                $stock_available = '';
                if($set_inventory_limit==1) {
                    $stock_limit = $request->stock_limit;
                    $stock_available = $request->stock_available;
                }
                $product_base_currency = $request->product_base_currency;
                $regular_price = $request->regular_price;
                $sale_price = $request->sale_price;
                $region = $request->region;
                $regularprice = $request->regularprice;
                $saleprice = $request->saleprice;
                $sku_name = $request->sku_name;
                $title = $request->title;
                $description = $request->description;
                $imagetype = $request->imagetype;
                $normalimage = $request->file('normal_image');
                $bannerimage = $request->file('banner_image');
                $visible_site = 2;
                $visible_store = 2;
                $visibleSite = $request->visible_site;
                $visibleStore = $request->visible_store;

                if($visibleSite==1) {
                    $visible_site = 1;
                } 

                if($visibleStore==1) {
                    $visible_store = 1;
                }


                $slug = Products::getSlug($name);

                $productsInsert = new Products;
                $productsInsert->product_slug = $slug;
                $productsInsert->product_type = $product_type;
                $productsInsert->name = trim($name);
				$productsInsert->seo_title = trim($seo_title);
				$productsInsert->meta_description = trim($meta_description);
                $productsInsert->short_description = trim($short_description);
                $productsInsert->did_you_know = trim($did_you_know);
                $productsInsert->key_ingredients = trim($key_ingredients);
                $productsInsert->stock_limit = trim($stock_limit);
                $productsInsert->stock_available = trim($stock_available);
                $productsInsert->stock_status = trim($stock_status);
                $productsInsert->website_visible = trim($visible_site);
                $productsInsert->store_visible = trim($visible_store);
                $productsInsert->base_currency = trim($product_base_currency);
                $productsInsert->regular_price = trim($regular_price);
                $productsInsert->sales_price = trim($sale_price);
                $productsInsert->save();

                $productId = $productsInsert->id;
                $product_slug = $productsInsert->product_slug;

            if(!empty($region)) {
                    foreach ($region as $key => $value) { 
                        $enable = 'enable_region_'.$value;
                        $enableReg = $request->$enable;
                        if($enableReg=='') {
                            $enableRegion=1;
                        } else {
                            $enableRegion = 2;
                        }
                        $productRegion = new ProductRegion;
                        $productRegion->fkproduct_id = $productId;
                        $productRegion->fkregion_id = $value;
                        $productRegion->regular_price = trim($regularprice[$key]);
                        $productRegion->sales_price = trim($saleprice[$key]);
                        $productRegion->sku_name = trim($sku_name[$key]);
                        $productRegion->enable = $enableRegion;
                        $productRegion->save();

                        $productRegionId = $productRegion->id;

                        $coursesValue = 'courses_'.$value;
                        $courses = $request->$coursesValue;

                        if(!empty($courses)) {
                            foreach ($courses as $key1 => $value1) {
                                $qtyValue = 'course_qty_'.$value.'_'.$value1;
                                $qty = $request->$qtyValue;
                                if(!empty($qty)) {
                                    $productCourse = new ProductCourse;
                                    $productCourse->fkproductregion_id = $productRegionId;
                                    $productCourse->fkcourse_id = $value1;
                                    $productCourse->quantity = trim($qty);
                                    $productCourse->save();
                                }
                            }
                        }

                    }
                }

                $path = 'public/uploads/products/'.$productId.'/';
                    if (!file_exists($path)) {
                            mkdir($path, 0755);
                     } 

                     $file = $request->file('featured_image');
                                        if($file!='') {   
                                                $newname = "feature_image".str_random(7).$productId;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                                                 $orgFilename = $newname.'-original.'.$ext;
                     
                                                if($file->move($path, $newFilename)){	
												/* copy($path.$newFilename, $path.$newFilename);	 */ 											
													$img = Image::make($path.$newFilename)->fit(250, 200)
															/*->resize(null, 250, function ($constraint) {
																$constraint->aspectRatio();
															})*/
															->save($path.$newFilename);    
														/*$image = new \Eventviva\ImageResize($path . $newFilename);
														$image->resizeToBestFit(250, 250);*/
														// $image->save('image2.jpg');
                                                    // $image = new \Eventviva\ImageResize($path . $newFilename);
                                                    // $image->resize(250, 200);
                                                   // $image->save($path . $newFilename);
                                                }
												
                                                $productsUpdate = Products::find($productId);
                                                $productsUpdate->feature_image = $newFilename;
                                              //  $productsUpdate->feature_image_original = $orgFilename;
                                                $productsUpdate->save();
                                        }


                                        $file = $request->file('feature_image_original');
                                        if($file!='') {   
                                                $newname = "feature_image_original".str_random(7).$productId;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){   
                                                    $productsUpdate = Products::find($productId);
                                                    $productsUpdate->feature_image_original = $newFilename;
                                                    $productsUpdate->save();
                                                }
                                                
                                                
                                        }

            if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];
                        $imageType = $imagetype[$key];

                        if($value!='' || $desc!='' || $imagetype!='') {

                            $newFilename = '';

                            if($imageType==1) {
                                if(isset($normalimage[$key])) {
                                    $file = $normalimage[$key];
                                        if($file!='') {   
                                                $newname = str_random(7).$key;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.'-original.'.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                }
                                        }   

                                }

                            } else if($imageType==2) {

                                if(isset($bannerimage[$key])) {
                                    $file = $bannerimage[$key];
                                        if($file!='') {   
                                                $newname = str_random(7).$key;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.'-original.'.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                }
                                        }   

                                }

                            }

                        
                            $productTiles = new ProductTiles;
                            $productTiles->fkproduct_id = $productId;
                            $productTiles->title = $value;
                            $productTiles->description = $desc;
                            $productTiles->image_type = $imageType;
                            $productTiles->image = $newFilename;
                            $productTiles->save();

                        }
                    }
                }
				


            // return redirect('admin/catalog/product/list')->with('success_msg','Product Added Successfully'); 
			return redirect('admin/catalog/product/simple/edit/'.$productId)->with(array('previewSlug'=>$product_slug,'success_msg'=>'Product Added Successfully'));



        } else {
            $courses = $this->courses->getCourses();
            $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            $regions = $this->regions->getRegionsCurrency();
            $currencyRates   = $this->currencyRates->getCurrencyRates();
            return view('admin/products/addSimpleProduct')->with(array('productType'=>$this->productType,
               'stockStatus'=>$this->stockStatus,'courses'=>$courses,'currencies'=>$this->currencies,
               'enabledCurrencies'=>$enabledCurrencies,'enabledCurrency'=>$enabledCurrency,'regions'=>$regions,
               'currencyRates'=>$currencyRates));
        }

    }

    public function postSimpleProduct(Request $request){
        if($request->isMethod('post')){

            //return "success";

            //echo '<pre>'; print_r($request->all()); echo '</pre>'; 

            $error = 0;
            $imagefile = $request->file('file');
            if($imagefile!=''){
                foreach($imagefile as $file){
                    $imagedata = getimagesize($file);
                    $width = $imagedata[0];
                    $height = $imagedata[1];
                    if($width<650 || $height<400){
                        $error = 1;
                    }
                }
            }

            if($error==1){
                return 'error';
            } else {

                $product_type = $request->product_type;
                $name = $request->name;
                $short_description = $request->short_description;
                //$key_ingredients = $request->key_ingredients;
                $stock_status = $request->stock_status;
                $set_inventory_limit = $request->set_inventory_limit;
                $stock_limit = '';
                $stock_available = '';
                if($set_inventory_limit==1) {
                    $stock_limit = $request->stock_limit;
                    $stock_available = $request->stock_available;
                }
                $courses = $request->courses;
                $product_base_currency = $request->product_base_currency;
                $regular_price = $request->regular_price;
                $sale_price = $request->sale_price;
                $region = $request->region;
                $regularprice = $request->regularprice;
                $saleprice = $request->saleprice;
                $title = $request->title;
                $description = $request->description;
                $visible_site = 2;
                $visible_store = 2;
                $visibleSite = $request->visible_site;
                $visibleStore = $request->visible_store;

                if($visibleSite==1) {
                    $visible_site = 1;
                } 

                if($visibleStore==1) {
                    $visible_store = 1;
                }

               /* var_dump($title);
                var_dump($description);

                die();*/

                $slug = Products::getSlug($name);

                $productsInsert = new Products;
                $productsInsert->product_slug = $slug;
                $productsInsert->product_type = $product_type;
                $productsInsert->name = trim($name);
                $productsInsert->short_description = trim($short_description);
                //$productsInsert->key_ingredients = trim($key_ingredients);
                $productsInsert->stock_limit = trim($stock_limit);
                $productsInsert->stock_available = trim($stock_available);
                $productsInsert->stock_status = trim($stock_status);
                $productsInsert->website_visible = trim($visible_site);
                $productsInsert->store_visible = trim($visible_store);
                $productsInsert->base_currency = trim($product_base_currency);
                $productsInsert->regular_price = trim($regular_price);
                $productsInsert->sales_price = trim($sale_price);
                $productsInsert->save();

                $productId = $productsInsert->id;

                if(!empty($courses)) {
                    foreach ($courses as $key => $value) {
                        $qty = 'course_qty_'.$value;
                        $productCourse = new ProductCourse;
                        $productCourse->fkproduct_id = $productId;
                        $productCourse->fkcourse_id = $value;
                        $productCourse->quantity = trim($request->$qty);
                        $productCourse->save();
                    }
                }

                if(!empty($region)) {
                    foreach ($region as $key => $value) {
                        $enable = 'enable_region_'.$value;
                        $enableReg = $request->$enable;
                        if($enableReg=='') {
                            $enableRegion=1;
                        } else {
                            $enableRegion = 2;
                        }
                        $productRegion = new ProductRegion;
                        $productRegion->fkproduct_id = $productId;
                        $productRegion->fkregion_id = $value;
                        $productRegion->regular_price = trim($regularprice[$key]);
                        $productRegion->sales_price = trim($saleprice[$key]);
                        $productRegion->enable = $enableRegion;
                        $productRegion->save();
                    }
                }

                /*if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];

                        if($value!='' && $desc!='') {
                        
                            $productTiles = new ProductTiles;
                            $productTiles->fkproduct_id = $productId;
                            $productTiles->title = $value;
                            $productTiles->description = $desc;
                            $productTiles->save();

                        }
                    }
                }*/


                $coverImage = array();
                $image_count = $request->image_count;
                for($j=1; $j<=$image_count; $j++){
                    
                    $icover = 'cover_image_'.$j;
                    $setAsCover = $request->$icover;
                    
                       if(isset($setAsCover)){
                            $coverImage[] = 1;
                       }else{
                            $coverImage[] = 2;
                       }
  
                }

                $path = 'public/uploads/products/'.$productId.'/';
                    if (!file_exists($path)) {
                            mkdir($path, 0755);
                     } 

                    $lpath = 'public/uploads/products/'.$productId.'/large/';
                    if (!file_exists($lpath)) {
                            mkdir($lpath, 0755);
                     } 

                     $tpath = 'public/uploads/products/'.$productId.'/thumb/';
                    if (!file_exists($tpath)) {
                            mkdir($tpath, 0755);
                     } 


                $imagefile = $request->file('file');
                if($imagefile!=''){
                    if(is_array($imagefile) && !empty($imagefile)){   
                        $i = 0;
                        foreach($imagefile as $file){
                            $destination1 = $lpath;
                            $destination2 = $tpath;
                            $newname = str_random(10);
                            $ext = $file->getClientOriginalExtension();
                            $filename = $newname.'.'.$ext;

                            if($file->move($destination1, $filename)){
                                //foreach ($types as $key => $type) {                    
                                    $newFilename = $newname.'.'.$ext;
                                    copy($destination1 . $filename, $destination1 . $newFilename);
                                    $image = new \Eventviva\ImageResize($destination1 . $newFilename);
                                    $image->resize(250, 150);
                                    $image->save($destination2 . $newFilename);
                                //}
                                $productImages = new ProductImages;
                                $productImages->fkproduct_id = $productId;
                                $productImages->image_name = $filename;
                                $productImages->feature_image = $coverImage[$i];
                                $productImages->save();
                            }
                            $i++;
                        }
                    }
                }

            }

            return $productId;

            //echo '<pre>'; print_r($request->all()); echo '</pre>'; die();
        }
    }


    public function editSimpleProduct($id,Request $request){

        if($request->isMethod('post')){

            $pid = $id;


                $name = strtolower($request->name);
				$seo_title = $request->seo_title;
				$meta_description = $request->meta_description;
                $short_description = $request->short_description;
                $did_you_know = $request->did_you_know;
                $key_ingredients = $request->key_ingredients;
                $stock_status = $request->stock_status;
                $set_inventory_limit = $request->set_inventory_limit;
                $stock_limit = '';
                $stock_available = '';
                if($set_inventory_limit==1) {
                    $stock_limit = $request->stock_limit;
                    $stock_available = $request->stock_available;
                }
                $courses = $request->courses;
                $product_base_currency = $request->product_base_currency;
                $regular_price = $request->regular_price;
                $sale_price = $request->sale_price;
                $sku_name = $request->sku_name;
                $region = $request->region;
                $regularprice = $request->regularprice;
                $saleprice = $request->saleprice;
                $tiles = $request->tiles;
                $title = $request->title;
                $description = $request->description;
                $imagetype = $request->imagetype;
                $normalimage = $request->file('normal_image');
                $bannerimage = $request->file('banner_image');
                $visible_site = 2;
                $visible_store = 2;
                $visibleSite = $request->visible_site;
                $visibleStore = $request->visible_store;

                if($visibleSite==1) {
                    $visible_site = 1;
                } 

                if($visibleStore==1) {
                    $visible_store = 1;
                }


                $product_slug = $request->product_slug;
        
            $slugCount = Products::whereRaw("product_slug REGEXP '^{$product_slug}(-[0-9]*)?$'")->where('id','!=',$id)->where('delete_status',0)->count();
            
            $slugValue =  ($slugCount > 0) ? "{$product_slug}-{$slugCount}" : $product_slug;



               // $slug = Products::getSlug($name);

                $productsUpdate = Products::find($id);
                $old_title = $productsUpdate->name;
                /*if($old_title!=$name){
                    $productsUpdate->product_slug = $slug;
                }*/
             //   $productsUpdate->product_type = $product_type;
                $productsUpdate->name = trim($name);
				$productsUpdate->seo_title = trim($seo_title);
				$productsUpdate->meta_description = trim($meta_description);
                $productsUpdate->product_slug = $slugValue;
                $productsUpdate->short_description = trim($short_description);
                $productsUpdate->did_you_know = trim($did_you_know);
                $productsUpdate->key_ingredients = trim($key_ingredients);
                $productsUpdate->stock_limit = trim($stock_limit);
                $productsUpdate->stock_available = trim($stock_available);
                $productsUpdate->stock_status = trim($stock_status);
                $productsUpdate->website_visible = trim($visible_site);
                $productsUpdate->store_visible = trim($visible_store);
                $productsUpdate->base_currency = trim($product_base_currency);
                $productsUpdate->regular_price = trim($regular_price);
                $productsUpdate->sales_price = trim($sale_price);
                $productsUpdate->save();

                $productId = $id;
                $product_slug = $productsUpdate->product_slug;

                $path = 'public/uploads/products/'.$productId.'/';

                $file = $request->file('featured_image');

                                        if($file!='') {   
                                                $newname = "feature_image".str_random(7).$productId;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                                                 $orgFilename = $newname.'-original.'.$ext;
												
                                                if($file->move($path, $newFilename)){	
												/* copy($path.$newFilename, $path.$newFilename);*/												
													$img = Image::make($path.$newFilename)->fit(250, 200)
															/*->resize(250, 250, function ($constraint) {
																$constraint->aspectRatio();
																$constraint->upsize();
															})*/
															->save($path.$newFilename);    
														/*$image = new \Eventviva\ImageResize($path . $newFilename);
														$image->resizeToBestFit(250, 250);*/
														// $image->save('image2.jpg');
                                                    // $image = new \Eventviva\ImageResize($path . $newFilename);
                                                    // $image->resize(250, 200);
                                                   // $image->save($path . $newFilename);
                                                }

                                                $productsUpdate = Products::find($productId);
                                                $productsUpdate->feature_image = $newFilename;
                                              //  $productsUpdate->feature_image_original = $orgFilename;
                                                $productsUpdate->save();
                                        }

                                        $file = $request->file('feature_image_original');
                                        if($file!='') {   
                                                $newname = "feature_image_original".str_random(7).$productId;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){   
                                                    $productsUpdate = Products::find($productId);
                                                    $productsUpdate->feature_image_original = $newFilename;
                                                    $productsUpdate->save();
                                                }
                                                
                                                
                                        }

                
                if(!empty($region)) {
                    foreach ($region as $key => $value) {
                        $enable = 'enable_region_'.$value;
                        $enableReg = $request->$enable;
                        if($enableReg=='') {
                            $enableRegion=1;
                        } else {
                            $enableRegion = 2;
                        }

                        $coursesValue = 'courses_'.$value;
                        $courses = $request->$coursesValue;

                        $checkRegion = ProductRegion::where('fkproduct_id',$productId)->where('fkregion_id',$value)->first();

                        if(isset($checkRegion->id)) {
                            $productRegion = ProductRegion::find($checkRegion->id);
                            $productRegion->fkproduct_id = $productId;
                            $productRegion->fkregion_id = $value;
                            $productRegion->regular_price = trim($regularprice[$key]);
                            $productRegion->sales_price = trim($saleprice[$key]);
                            $productRegion->sku_name = trim($sku_name[$key]);
                            $productRegion->enable = $enableRegion;
                            $productRegion->save();
                            $productRegionId = $checkRegion->id;
                        } else {
                            $productRegion = new ProductRegion;
                            $productRegion->fkproduct_id = $productId;
                            $productRegion->fkregion_id = $value;
                            $productRegion->regular_price = trim($regularprice[$key]);
                            $productRegion->sales_price = trim($saleprice[$key]);
                            $productRegion->sku_name = trim($sku_name[$key]);
                            $productRegion->enable = $enableRegion;
                            $productRegion->save();
                            $productRegionId = $productRegion->id;
                        }

                        if(!empty($courses)) {
                            $courseCheckAvailable = array();
                            foreach ($courses as $key1 => $value1) {
                                $qtyValue = 'course_qty_'.$value.'_'.$value1;
                                $qty = $request->$qtyValue;
                                if(!empty($qty)) {

                                    $checkProductCourse = ProductCourse::where('fkproductregion_id',$productRegionId)->where('fkcourse_id',$value1)->first();

                                    if(isset($checkProductCourse->id)) {

                                        $productCourse = ProductCourse::find($checkProductCourse->id);
                                        $productCourse->fkproductregion_id = $productRegionId;
                                        $productCourse->fkcourse_id = $value1;
                                        $productCourse->quantity = trim($qty);
                                        $productCourse->save();


                                    } else {

                                        $productCourse = new ProductCourse;
                                        $productCourse->fkproductregion_id = $productRegionId;
                                        $productCourse->fkcourse_id = $value1;
                                        $productCourse->quantity = trim($qty);
                                        $productCourse->save();

                                    }

                                    $courseCheckAvailable[] = $value1;

                                }
                            }

                            if(!empty($courseCheckAvailable)) {
                                $delProCourse = ProductCourse::select('id')->where('fkproductregion_id',$productRegionId)->whereNotIn('fkcourse_id',$courseCheckAvailable)->get();
                                if(count($delProCourse)>0){
                                    $delProCourse = ProductCourse::where('fkproductregion_id',$productRegionId)->whereNotIn('fkcourse_id',$courseCheckAvailable)->delete();
                                }
                            } else {
                                $delProCourse = ProductCourse::select('id')->where('fkproductregion_id',$productRegionId)->delete();
                            }

                        } else {
                            $delProCourse = ProductCourse::select('id')->where('fkproductregion_id',$productRegionId)->delete();
                        }


                    }
                }

               

            if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];
                        $imageType = $imagetype[$key];

                        if($value!='' || $desc!='' || $imagetype!='') {

                            $newFilename = '';

                            if($imageType==1) {
                                if(isset($normalimage[$key])) {
                                    $file = $normalimage[$key];
                                        if($file!='') {   
                                                $newname = str_random(7).$key;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.'-original.'.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                }
                                        }   

                                }

                            } else if($imageType==2) {

                                if(isset($bannerimage[$key])) {
                                    $file = $bannerimage[$key];
                                        if($file!='') {   
                                                $newname = str_random(7).$key;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.'-original.'.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                }
                                        }   

                                }

                            }

                            if(isset($tiles[$key])) {

                                $productTiles = ProductTiles::find($tiles[$key]);
                                $productTiles->fkproduct_id = $pid;
                                $productTiles->title = $value;
                                $productTiles->description = $desc;
                                $productTiles->image_type = $imageType;
                                if($imageType!=1 && $imageType!=2) {
                                    $productTiles->image = '';
                                }else if($newFilename!='') {
                                    $productTiles->image = $newFilename;
                                }
                                $productTiles->save();

                            } else {
                        
                                $productTiles = new ProductTiles;
                                $productTiles->fkproduct_id = $pid;
                                $productTiles->title = $value;
                                $productTiles->description = $desc;
                                $productTiles->image_type = $imageType;
                                $productTiles->image = $newFilename;
                                $productTiles->save();

                            }

                        }
                    }
                }

			return redirect('admin/catalog/product/simple/edit/'.$id)->with(array('previewSlug'=>$product_slug,'success_msg'=>'Product Updated Successfully'));
            // return redirect('admin/catalog/product/simple/edit/'.$id)->with('success_msg','Product Updated Successfully'); 

        } else {

           // $courses = $this->courses->getCoursesProduct($id);
            $courses = $this->courses->getCourses();
            $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            $regions = $this->regions->getRegionsProductCurrency($id);
            $currencyRates   = $this->currencyRates->getCurrencyRates();

           // return $regions;

            $product = Products::where('id',$id)->first();
            $productTiles = ProductTiles::where('fkproduct_id',$id)->get();

            return view('admin/products/editSimpleProduct')->with(array('productType'=>$this->productType,
               'stockStatus'=>$this->stockStatus,'currencies'=>$this->currencies,'courses'=>$courses,
               'enabledCurrencies'=>$enabledCurrencies,'enabledCurrency'=>$enabledCurrency,'regions'=>$regions,
               'currencyRates'=>$currencyRates,'product'=>$product,'productTiles'=>$productTiles));
        }

    }



    public function postEditSimpleProduct($id,Request $request){
        if($request->isMethod('post')){

            //return "success";

            //echo '<pre>'; print_r($request->all()); echo '</pre>'; 

            $error = 0;
            $imagefile = $request->file('file');
            if($imagefile!=''){
                foreach($imagefile as $file){
                    $imagedata = getimagesize($file);
                    $width = $imagedata[0];
                    $height = $imagedata[1];
                    if($width<650 || $height<400){
                        $error = 1;
                    }
                }
            }

            if($error==1){
                return 'error';
            } else {

               // $product_type = $request->product_type;


                /*if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];

                        if($value!='' && $desc!='') {
                        
                            $productTiles = new ProductTiles;
                            $productTiles->fkproduct_id = $productId;
                            $productTiles->title = $value;
                            $productTiles->description = $desc;
                            $productTiles->save();

                        }
                    }
                }*/

                $productId = $id;

                $albImg = array();
                $ucount = $request->update_image_count;
                for($u=1; $u<=$ucount; $u++){
                    $imgId = 'image_id_'.$u;
                    $image_id = $request->$imgId;
                    
                    
                    $icover = 'cover_image_'.$u;
                    $setAsCover = $request->$icover;
                    if(isset($image_id)){
                        $albImg[] = $image_id;
                        if(isset($setAsCover)){
                            $coverImage = 1;
                        }else{
                            $coverImage = 2;
                        }
                        $images = ProductImages::find($image_id);
                        $images->feature_image = $coverImage;
                        $images->save();
                    }
                    
                }


                $delImg = ProductImages::where('fkproduct_id',$productId)->whereNotIn('id',$albImg)->get();
                if(count($delImg)>0){
                    /*foreach($delImg as $rem){
                        $oldImage = $rem->image;
                        if($oldImage!=''){
                            $unlink_path = 'public/gallery/'.$oldImage;
                            if(file_exists($unlink_path)) {
                                unlink($unlink_path);
                            }
                        }
                    }*/
                    $delImg = ProductImages::where('fkproduct_id',$productId)->whereNotIn('id',$albImg)->delete();
                }


                $coverImage = array();
                $image_count = $request->image_count;
                $icount = $request->image_count;
                for($j=++$ucount; $j<=$icount; $j++){
                    
                    $icover = 'cover_image_'.$j;
                    $setAsCover = $request->$icover;
                    
                       if(isset($setAsCover)){
                            $coverImage[] = 1;
                       }else{
                            $coverImage[] = 2;
                       }
  
                }

                $path = 'public/uploads/products/'.$productId.'/';
                    if (!file_exists($path)) {
                            mkdir($path, 0755);
                     } 

                    $lpath = 'public/uploads/products/'.$productId.'/large/';
                    if (!file_exists($lpath)) {
                            mkdir($lpath, 0755);
                     } 

                     $tpath = 'public/uploads/products/'.$productId.'/thumb/';
                    if (!file_exists($tpath)) {
                            mkdir($tpath, 0755);
                     } 


                $imagefile = $request->file('file');
                if($imagefile!=''){
                    if(is_array($imagefile) && !empty($imagefile)){   
                        $i = 0;
                        foreach($imagefile as $file){
                            $destination1 = $lpath;
                            $destination2 = $tpath;
                            $newname = str_random(10);
                            $ext = $file->getClientOriginalExtension();
                            $filename = $newname.'.'.$ext;

                            if($file->move($destination1, $filename)){
                                //foreach ($types as $key => $type) {                    
                                    $newFilename = $newname.'.'.$ext;
                                    copy($destination1 . $filename, $destination1 . $newFilename);
                                    $image = new \Eventviva\ImageResize($destination1 . $newFilename);
                                    $image->resize(250, 150);
                                    $image->save($destination2 . $newFilename);
                                //}
                                $productImages = new ProductImages;
                                $productImages->fkproduct_id = $productId;
                                $productImages->image_name = $filename;
                                $productImages->feature_image = $coverImage[$i];
                                $productImages->save();
                            }
                            $i++;
                        }
                    }
                }

            }

            return $productId;

            //echo '<pre>'; print_r($request->all()); echo '</pre>'; die();
        }
    }

    public function deleteProduct($id){
        $deleteProduct = Products::find($id);
        $deleteProduct->delete_status = 1;
        if($deleteProduct->save()) {
            return redirect('admin/catalog/product/list')->with('success_msg','Product Deleted Successfully'); 
        } else {
            return redirect('admin/catalog/product/list')->with('success_msg','Please try again later'); 
        }
    }

    public function deleteBlock($id){
        $deleteTiles = ProductTiles::where('id',$id)->delete();
        return "success";
    }


    public function calculatePrice(Request $request){
        $totalPrice = 0.00;
        //echo '<pre>'; print_r($request->all()); echo '</pre>';
		$data = array();
		$requestData = $request->data;
		parse_str($request->data, $data);

       // echo '<pre>'; print_r($data); echo '</pre>'; die;

        $regId = $request->regId; 

        $currencyRates   = $this->currencyRates->getCurrencyRates();
        $products = $data['products_'.$regId];
        $courses = $data['courses_'.$regId];
        $product_course = $data['product_course'];
        $product_base_currency = $data['currencies'.$regId];
        $regularprice = 0.00;
        $salesprice = 0.00;
		//echo '<pre>'; print_r($courses); print_r($products); print_r($product_course); print_r($product_base_currency); echo '</pre>';
      //  die();
        if(!empty($products)) {
            foreach ($products as $key => $value) {
               /* $checkProduct = Products::select('id','base_currency','regular_price','sales_price')->where('id',$value)->first();*/
$checkProduct = ProductRegion::where('fkproduct_id',$value)->where('fkregion_id',$regId)->first();
$getReg = Regions::where('id',$regId)->first();
                if(isset($checkProduct->fkproduct_id)) {
                    $quantity = 0;
                    if(!empty($courses)) {
                        $j=0;
                        foreach ($courses as $key1 => $value1) {
                            if($j==0) {
                                foreach ($product_course as $key2 => $value2) {
                                    $splitKey = explode('_', $key2);
                                    if($splitKey[0]==$regId && $splitKey[1]==$value1 && $splitKey[2]==$checkProduct->fkproduct_id) {
                                        $quantity = $value2;
                                    }
                                }
                            }
                            $j++;
                        }
                    }

//return $getReg->currency;
					
                    $regularCheckPrice = $quantity*$checkProduct->regular_price;
                    $salesCheckPrice = $quantity*$checkProduct->sales_price;
                    $regularprice += $regularCheckPrice;
                    $salesprice += $salesCheckPrice;
                    /*if($getReg->currency==$product_base_currency) {
                        $regularprice += $regularCheckPrice;
                        $salesprice += $salesCheckPrice;
                    } else {
                        if($getReg->currency=='INR') {
                      //alert($('.regularprice').val());
                          foreach($currencyRates as $rates) {
                            if($getReg->currency==$rates->from_currency) {
                                $rprice = $regularCheckPrice*$rates->rate;
                                $sprice = $salesCheckPrice*$rates->rate;

                                $regularprice += round($rprice,2);
                                $salesprice += round($sprice,2);

                            }
                            
                          }
                        } else {
                          $inrRValue = '';
                          $inrSValue = '';
                          foreach($currencyRates as $rates) {
                            if($rates->from_currency==$getReg->currency) {
                              $inrRValue = round(($regularCheckPrice/$rates->rate),2);
                              $inrSValue = round(($salesCheckPrice/$rates->rate),2);
                            }
                          }
                          foreach($currencyRates as $rates) {
                            if($product_base_currency==$rates->from_currency) {
                                $rprice = $inrRValue*$rates->rate;
                                $sprice = $inrSValue*$rates->rate;

                               // echo $checkProduct->regular_price.'-'.$rprice."-".$inrRValue;

                                $regularprice += round($rprice,2);
                                $salesprice += round($sprice,2);
                          }
                        }
                    }
                }*/
            }
        }
    }
        $response['regularprice']=round($regularprice);
        $response['salesprice']=round($salesprice);

        return $response;
    }


    public function getImages($id){
        $images = ProductImages::where('fkproduct_id',$id)->get();
        return Response::json($images->toArray(),200);
    }

    public function addBundleProduct(Request $request){

        if($request->isMethod('post')){

            //echo '<pre>'; print_r($request->all()); echo '</pre>';

           // $qty = $request->product_course;

          //  $qtyValue = 'product_course[1_1_26]';
                                      //  $qty = $request->$qtyValue;

                                      //  echo $qty['1_1_26']; die();

            $product_type = 2;
            $name = strtolower($request->name);
            $short_description = $request->short_description;
            $stock_status = $request->stock_status;
                $set_inventory_limit = $request->set_inventory_limit;
                $stock_limit = '';
                $stock_available = '';
                if($set_inventory_limit==1) {
                    $stock_limit = $request->stock_limit;
                    $stock_available = $request->stock_available;
                }
            //$courses = $request->courses;
            //$products = $request->products;
            $product_course = $request->product_course;
            $product_base_currency = $request->product_base_currency;
            $regular_price = $request->regular_price;
            $sale_price = $request->sale_price;
            $region = $request->region;
            $regularprice = $request->regularprice;
            $saleprice = $request->saleprice;
            $visible_site = 2;
            $visibleSite = $request->visible_site;

            if($visibleSite==1) {
                $visible_site = 1;
            } 

            //echo $product_course['1_1_26']; die();
     //       $selectedCourse = implode(',', $courses);
     //       $selectedProducts = implode(',', $products);

            $slug = Products::getSlug($name);

                $productsInsert = new Products;
                $productsInsert->product_slug = $slug;
                $productsInsert->product_type = $product_type;
                //$productsInsert->simple_products = $selectedProducts;
                //$productsInsert->courses = $selectedCourse;
                $productsInsert->name = trim($name);
                $productsInsert->short_description = trim($short_description);
                $productsInsert->stock_limit = trim($stock_limit);
                $productsInsert->stock_available = trim($stock_available);
                $productsInsert->stock_status = trim($stock_status);
                $productsInsert->website_visible = trim($visible_site);
                $productsInsert->base_currency = trim($product_base_currency);
                //$productsInsert->regular_price = trim($regular_price);
               // $productsInsert->sales_price = trim($sale_price);
                $productsInsert->save();

                $productId = $productsInsert->id;

/*                if(!empty($product_course)) {
                    foreach ($product_course as $key => $value) {
                        $splitKey = explode('_', $key);
                        $productBundle = new ProductBundle;
                        $productBundle->fkbundleproduct_id = $productId;
                        $productBundle->fkcourse_id = $splitKey[0];
                        $productBundle->fksimpleproduct_id = $splitKey[1];
                        $productBundle->quantity = $value;
                        $productBundle->save();
                    }
                }
*/

                if(!empty($region)) {
                    foreach ($region as $key => $value) {
                        $enable = 'enable_region_'.$value;
                        $enableReg = $request->$enable;
                        if($enableReg=='') {
                            $enableRegion=1;
                        } else {
                            $enableRegion = 2;
                        }
                        $productRegion = new ProductRegion;
                        $productRegion->fkproduct_id = $productId;
                        $productRegion->fkregion_id = $value;
                        $productRegion->regular_price = trim($regularprice[$key]);
                        $productRegion->sales_price = trim($saleprice[$key]);
                        $productRegion->enable = $enableRegion;
                        $productRegion->save();

                        $productRegionId = $productRegion->id;

                        $coursesValue = 'courses_'.$value;
                        $courses = $request->$coursesValue;

                        $productValue = 'products_'.$value;
                        $products = $request->$productValue;

                      //  var_dump($product_course);


                        if(!empty($courses)) {
                            foreach ($courses as $key1 => $value1) {
                                if(!empty($products)) {
                                    foreach ($products as $key2 => $value2) {
                                        //echo $product_course[$value.'_'.$value1.'_'.$value2];
                                        /*$qtyValue = 'product_course['.$value.'_'.$value1.'_'.$value2.']';
                                        $qty = $request->$qtyValue;*/
                                        if(isset($product_course[$value.'_'.$value1.'_'.$value2])) {
                                            $qty = $product_course[$value.'_'.$value1.'_'.$value2];
                                            $productBundle = new ProductBundle;
                                            $productBundle->fkbundleproductregion_id = $productRegionId;
                                            $productBundle->fksimpleproduct_id = $value2;
                                            $productBundle->fkcourse_id = $value1;
                                            $productBundle->quantity = trim($qty);
                                            $productBundle->save();
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
              //  die();

            return redirect('admin/catalog/product/list')->with('success_msg','Product Added Successfully'); 


        } else {
            $courses = $this->courses->getCourses();
            $simpleProducts = Products::where('product_type',1)->where('delete_status',0)->get();
            $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            $regions = $this->regions->getRegionsCurrency();
            $currencyRates   = $this->currencyRates->getCurrencyRates();
            //return $simpleProducts;
            return view('admin/products/addBundleProduct')->with(array('productType'=>$this->productType,
                   'stockStatus'=>$this->stockStatus, 'simpleProducts'=>$simpleProducts, 'courses'=>$courses,
                   'currencies'=>$this->currencies,'enabledCurrencies'=>$enabledCurrencies,'enabledCurrency'=>$enabledCurrency,
                   'regions'=>$regions,'currencyRates'=>$currencyRates));
        }

    }



    public function editBundleProduct($id,Request $request){

        if($request->isMethod('post')){

            $name = strtolower($request->name);
            $short_description = $request->short_description;
            $stock_status = $request->stock_status;
                $set_inventory_limit = $request->set_inventory_limit;
                $stock_limit = '';
                $stock_available = '';
                if($set_inventory_limit==1) {
                    $stock_limit = $request->stock_limit;
                    $stock_available = $request->stock_available;
                }
            //$courses = $request->courses;
            //$products = $request->products;
            $product_course = $request->product_course;
            $product_base_currency = $request->product_base_currency;
            $regular_price = $request->regular_price;
            $sale_price = $request->sale_price;
            $region = $request->region;
            $regularprice = $request->regularprice;
            $saleprice = $request->saleprice;
            $visible_site = 2;
            $visibleSite = $request->visible_site;

            if($visibleSite==1) {
                $visible_site = 1;
            } 

        //    $selectedCourse = implode(',', $courses);
        //    $selectedProducts = implode(',', $products);

            $slug = Products::getSlug($name);

                $productUpdate = Products::find($id);
                $old_title = $productUpdate->name;
                if($old_title!=$name){
                    $productUpdate->product_slug = $slug;
                }
            //    $productUpdate->simple_products = $selectedProducts;
           //     $productUpdate->courses = $selectedCourse;
                $productUpdate->name = trim($name);
                $productUpdate->short_description = trim($short_description);
                $productUpdate->stock_limit = trim($stock_limit);
                $productUpdate->stock_available = trim($stock_available);
                $productUpdate->stock_status = trim($stock_status);
                $productUpdate->website_visible = trim($visible_site);
                $productUpdate->base_currency = trim($product_base_currency);
                $productUpdate->regular_price = trim($regular_price);
                $productUpdate->sales_price = trim($sale_price);
                $productUpdate->save();

                $productId = $id;

                $courseId = array();

/*                if(!empty($product_course)) {
                    foreach ($product_course as $key => $value) {

                     $splitKey = explode('_', $key);

                       $checkBundle = ProductBundle::where('fkbundleproduct_id',$productId)->where('fkcourse_id',$splitKey[0])->where('fksimpleproduct_id',$splitKey[1])->first();

                       if(isset($checkBundle->id)) {

                            $productBundle = ProductBundle::find($checkBundle->id);
                            $productBundle->quantity = $value;
                            $productBundle->save();
                            $courseId[] = $checkBundle->id;

                       } else {
                        
                            $productBundle = new ProductBundle;
                            $productBundle->fkbundleproduct_id = $productId;
                            $productBundle->fkcourse_id = $splitKey[0];
                            $productBundle->fksimpleproduct_id = $splitKey[1];
                            $productBundle->quantity = $value;
                            $productBundle->save();
                            $courseId[] = $productBundle->id;

                        }
                    }

                    $delBundle = ProductBundle::where('fkbundleproduct_id',$productId)->whereNotIn('id',$courseId)->get();
                    if(count($delBundle)>0){
                        $delBundle = ProductBundle::where('fkbundleproduct_id',$productId)->whereNotIn('id',$courseId)->delete();
                    }
                }*/


                if(!empty($region)) {
                    foreach ($region as $key => $value) {
                        $enable = 'enable_region_'.$value;
                        $enableReg = $request->$enable;
                        if($enableReg=='') {
                            $enableRegion=1;
                        } else {
                            $enableRegion = 2;
                        }

                        $checkRegion = ProductRegion::where('fkproduct_id',$productId)->where('fkregion_id',$value)->first();

                        if(isset($checkRegion->id)) {
                            $productRegion = ProductRegion::find($checkRegion->id);
                            $productRegion->fkproduct_id = $productId;
                            $productRegion->fkregion_id = $value;
                            $productRegion->regular_price = trim($regularprice[$key]);
                            $productRegion->sales_price = trim($saleprice[$key]);
                            $productRegion->enable = $enableRegion;
                            $productRegion->save();
                            $productRegionId = $checkRegion->id;
                        } else {
                            $productRegion = new ProductRegion;
                            $productRegion->fkproduct_id = $productId;
                            $productRegion->fkregion_id = $value;
                            $productRegion->regular_price = trim($regularprice[$key]);
                            $productRegion->sales_price = trim($saleprice[$key]);
                            $productRegion->enable = $enableRegion;
                            $productRegion->save();
                            $productRegionId = $productRegion->id;
                        }

                        $coursesValue = 'courses_'.$value;
                        $courses = $request->$coursesValue;

                        $productValue = 'products_'.$value;
                        $products = $request->$productValue;


                        if(!empty($courses)) {
                            $courseCheckAvailable = array();
                            
                            foreach ($courses as $key1 => $value1) {
                                if(!empty($products)) {
                                    $productCheckAvailable = array();
                                    foreach ($products as $key2 => $value2) {
                                        if(isset($product_course[$value.'_'.$value1.'_'.$value2])) {
                                            $qty = $product_course[$value.'_'.$value1.'_'.$value2];

                                            $checkProductBundle = ProductBundle::where('fkbundleproductregion_id',$productRegionId)->where('fksimpleproduct_id',$value2)->where('fkcourse_id',$value1)->first();

                                            if(isset($checkProductBundle->id)) {

                                                $productBundle = ProductBundle::find($checkProductBundle->id);
                                                $productBundle->fkbundleproductregion_id = $productRegionId;
                                                $productBundle->fksimpleproduct_id = $value2;
                                                $productBundle->fkcourse_id = $value1;
                                                $productBundle->quantity = trim($qty);
                                                $productBundle->save();

                                            } else {

                                                $productBundle = new ProductBundle;
                                                $productBundle->fkbundleproductregion_id = $productRegionId;
                                                $productBundle->fksimpleproduct_id = $value2;
                                                $productBundle->fkcourse_id = $value1;
                                                $productBundle->quantity = trim($qty);
                                                $productBundle->save();

                                            }

                                            $courseCheckAvailable[$value1] = $value1;
                                            $productCheckAvailable[$value2] = $value2;
                                        }
                                    }
                                } else {
                                    $delProCourse = ProductBundle::where('fkbundleproductregion_id',$productRegionId)->delete();
                                }
                            }

                            if(!empty($courseCheckAvailable)) {
                                //var_dump($productCheckAvailable); var_dump($courseCheckAvailable);
                                $delProCourse = ProductBundle::select('id')->where('fkbundleproductregion_id',$productRegionId)->whereNotIn('fkcourse_id',$courseCheckAvailable)->get();
                                if(count($delProCourse)>0){
                                   //echo "string";
                                    $delProCourse = ProductBundle::where('fkbundleproductregion_id',$productRegionId)->whereNotIn('fkcourse_id',$courseCheckAvailable)->delete();
                                }
                                 //die();
                            }

                            if(!empty($courseCheckAvailable) && !empty($productCheckAvailable)) {

                                $delProCourse = ProductBundle::select('id')->where('fkbundleproductregion_id',$productRegionId)->whereIn('fkcourse_id',$courseCheckAvailable)->whereNotIn('fksimpleproduct_id',$productCheckAvailable)->get();
                                if(count($delProCourse)>0){
                                   //echo "string";
                                    $delProCourse = ProductBundle::where('fkbundleproductregion_id',$productRegionId)->whereIn('fkcourse_id',$courseCheckAvailable)->whereNotIn('fksimpleproduct_id',$productCheckAvailable)->delete();
                                }

                            }

                        } else {
                            $delProCourse = ProductBundle::where('fkbundleproductregion_id',$productRegionId)->delete();
                        }

                    }
                }


            return redirect('admin/catalog/product/bundle/edit/'.$id)->with('success_msg','Product Updated Successfully'); 


        } else {
            $courses = $this->courses->getCourses();
            $simpleProducts = Products::where('product_type',1)->where('delete_status',0)->get();
            $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            $regions = $this->regions->getRegionsProductCurrency($id);
            $currencyRates   = $this->currencyRates->getCurrencyRates();
            $product = Products::where('id',$id)->first();
           // $productBundles = ProductBundle::where('fkbundleproduct_id',$id)->orderBy('fkcourse_id','ASC')->get();
           // return $regions;
            return view('admin/products/editBundleProduct')->with(array('productType'=>$this->productType,
                   'stockStatus'=>$this->stockStatus, 'simpleProducts'=>$simpleProducts, 'courses'=>$courses,
                   'currencies'=>$this->currencies,'enabledCurrencies'=>$enabledCurrencies,'enabledCurrency'=>$enabledCurrency,
                   'regions'=>$regions,'currencyRates'=>$currencyRates,'product'=>$product));
        }

    }


}