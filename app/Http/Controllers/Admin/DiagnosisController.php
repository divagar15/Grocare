<?php namespace App\Http\Controllers\Admin;

use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Products;
use App\Models\ProductBundle;
use App\Models\Diagnosis;
use App\Models\DiagnosisBlock;
use App\Models\DiagnosisProducts;
use App\Models\DiagnosisProductsContent;
use App\Models\Regions;
use Hash;
use Auth;
use Session;
use Config;

class DiagnosisController extends Controller {

    public function __construct()
    {
        $this->countries = Config::get('custom.country');
        $this->currencies = Config::get('custom.currency');
        $this->productType = Config::get('custom.productType');
        $this->stockStatus = Config::get('custom.stockStatus');
        $this->products    = new Products;
        $this->productBundle    = new ProductBundle;
        $this->diagnosis   = new Diagnosis;
        $this->regions         = new Regions;
    }

    public function index(){
        $diagnosis = $this->diagnosis->getDiagnosis();
       // $productBundles = $this->products->where('delete_status',0)->where('product_type',2)->get();
        return view('admin/diagnosis/list')->with(array('diagnosis'=>$diagnosis));
    }

    public function addDiagnosis(Request $request){
        if($request->isMethod('post')){
            //return $request->all();
            
            
            $name = strtolower($request->name);
            $diagnosis_title = $request->diagnosis_title;
            $disease_description = $request->disease_description;
            $disease_short_description = $request->disease_short_description;
            $how_to_heal_naturally = $request->how_to_heal_naturally;
            $how_is_it_caused = $request->how_is_it_caused;           
            $how_it_works = $request->how_it_works;			
			$did_you_know = $request->did_you_know;
            $ad_link = $request->ad_link;
            $no_side_effect = $request->no_side_effect;
            $no_added_steroids = $request->no_added_steroids;
            $fda_approved = $request->fda_approved;
            $worldwide_shipping = $request->worldwide_shipping;
            $research_based = $request->research_based;
            $no_dietary_restrictions = $request->no_dietary_restrictions;
            $note = $request->note;
            //content settings
            $title = $request->title;
            $description = $request->description;
            $region = $request->region;
            $diet_chart_content = $request->diet_chart_content;
            $diet_chart_visible = $request->diet_chart_visible;

            $seo_title = $request->seo_title;
            $meta_keywords = $request->meta_keywords;
            $meta_description = $request->meta_description;

            $diet_chart = $request->file('diet_chart');

            $slug = Diagnosis::getSlug($name);

            $diagnosisInsert = new Diagnosis;
            $diagnosisInsert->diagnosis_slug = $slug;
            $diagnosisInsert->name = trim($name);
            $diagnosisInsert->title = trim($diagnosis_title);
            $diagnosisInsert->seo_title = trim($seo_title);
            $diagnosisInsert->meta_keywords = trim($meta_keywords);
            $diagnosisInsert->meta_description = trim($meta_description);
            $diagnosisInsert->how_it_works = trim($how_it_works);
            $diagnosisInsert->disease_short_description = trim($disease_short_description);
            $diagnosisInsert->disease_description = trim($disease_description);
            $diagnosisInsert->how_to_heal_naturally = trim($how_to_heal_naturally);
            $diagnosisInsert->how_is_it_caused = trim($how_is_it_caused);
            $diagnosisInsert->how_it_works = trim($how_it_works);
            $diagnosisInsert->did_you_know = trim($did_you_know);
            $diagnosisInsert->ad_link = trim($ad_link);
            $diagnosisInsert->no_side_effect = $no_side_effect;
            $diagnosisInsert->no_added_steroids = $no_added_steroids;
            $diagnosisInsert->fda_approved = $fda_approved;
            $diagnosisInsert->worldwide_shipping = $worldwide_shipping;
            $diagnosisInsert->research_based = $research_based;
            $diagnosisInsert->no_dietary_restrictions = $no_dietary_restrictions;
            $diagnosisInsert->diet_chart_visible = $diet_chart_visible;
            $diagnosisInsert->die_chart_content = trim($diet_chart_content);
            $diagnosisInsert->note = trim($note);
            $diagnosisInsert->save();
            $diagnosisId = $diagnosisInsert->id;
            $diagnosis_slug = $diagnosisInsert->diagnosis_slug;

            $region = $request->region;

            $path = 'public/uploads/diagnosis/'.$diagnosisId.'/';
                    if (!file_exists($path)) {
                            mkdir($path, 0755);
                     } 

                     $file = $request->file('diet_chart');
                                        if($file!='') {   
                                                $newname = "diet_chart_".$name;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                }

                                                $diagnosisUpdate = Diagnosis::find($diagnosisId);
                                                $diagnosisUpdate->diet_chart = $newFilename;
                                                $diagnosisUpdate->save();
                                        }

            if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];

                        if($value!='' && $desc!='') {
                        
                            $diagnosisBlock = new DiagnosisBlock;
                            $diagnosisBlock->fkdiagnosis_id = $diagnosisId;
                            $diagnosisBlock->title = $value;
                            $diagnosisBlock->description = $desc;
                            $diagnosisBlock->save();

                        }
                    }
                }
            
            if(!empty($region)) {
                foreach ($region as $key => $value) {
                    $productValue = 'products_'.$value;
                    $products = $request->$productValue;
                    $proValue = 'product_'.$value;
                    $pro = $request->$proValue;
                   // return $pro;
                    if(!empty($products)) {
                            //if(isset($product_.$value[$value.'_'.$value1])) {
                                $prodArr = explode('_', $products);
                                $arrInd = $prodArr[0];
                               // if($prodArr[1]==$value) {

                                //    if(!empty($pro)) {
                                     //   foreach($pro as $k=>$v) {
                                            $diaProducts = new DiagnosisProducts;
                                            $diaProducts->fkdiagnosis_id = $diagnosisId;
                                            $diaProducts->fkproduct_id = $prodArr[0];
                                            $diaProducts->fkregion_id = $value;
                                            $diaProducts->product_type = $prodArr[1];
                                            $diaProducts->save();

                                            $diaProductId = $diaProducts->id;
                                      //  }
                                  //  }

                                    if(!empty($pro)) {
                                        foreach($pro as $k=>$v) {
                                            $diaProductsCont = new DiagnosisProductsContent;
                                            $diaProductsCont->fkdiagnosisproduct_id = $diaProductId;
                                            $diaProductsCont->product_id = $k;
                                            $diaProductsCont->product_content = $v;
                                            $diaProductsCont->save();
                                        }
                                    }

                                   // return $pro[$prodArr[0]];
                                //}
                               
                    }//if products
                }//foreach region
            }       
            //die();

                // return redirect('admin/catalog/diagnosis/list')->with('success_msg','Diagnosis Added Successfully'); 
				return redirect('admin/catalog/diagnosis/edit/'.$diagnosisId)->with(array('diagnosisSlug'=>$diagnosis_slug,'success_msg'=>'Diagnosis Added Successfully')); 

        } else {
            $productBundles = $this->products->where('delete_status',0)->where('product_type',2)->get();
            $regions = $this->regions->getRegionsCurrency();
            //return $regions;
            return view('admin/diagnosis/addDiagnosis')->with(array('productBundles'=>$productBundles,'regions'=>$regions));
        }
    }


    public function editDiagnosis(Request $request,$id){
        if($request->isMethod('post')){

            $name = strtolower($request->name);
            $diagnosis_title = $request->diagnosis_title;
            $disease_short_description = $request->disease_short_description;
            $disease_description = $request->disease_description;
            $how_to_heal_naturally = $request->how_to_heal_naturally;
            $how_is_it_caused = $request->how_is_it_caused;           
            $how_it_works = $request->how_it_works;
			$did_you_know = $request->did_you_know;
            $ad_link = $request->ad_link;
            $no_side_effect = $request->no_side_effect;
            $no_added_steroids = $request->no_added_steroids;
            $fda_approved = $request->fda_approved;
            $worldwide_shipping = $request->worldwide_shipping;
            $research_based = $request->research_based;
            $no_dietary_restrictions = $request->no_dietary_restrictions;
            $note = $request->note;
            //content settings
            $tiles = $request->tiles;
            $title = $request->title;
            $description = $request->description;
            $region = $request->region;
            $diet_chart_content = $request->diet_chart_content;
            $diet_chart_visible = $request->diet_chart_visible;

            $seo_title = $request->seo_title;
            $meta_keywords = $request->meta_keywords;
            $meta_description = $request->meta_description;

            $diet_chart = $request->file('diet_chart');

            //$slug = Diagnosis::getSlug($name);

            $diagnosis_slug = $request->diagnosis_slug;
        
            $slugCount = Diagnosis::whereRaw("diagnosis_slug REGEXP '^{$diagnosis_slug}(-[0-9]*)?$'")->where('id','!=',$id)->where('delete_status',0)->count();
            
            $slugValue =  ($slugCount > 0) ? "{$diagnosis_slug}-{$slugCount}" : $diagnosis_slug;

            $diagnosisUpdate = Diagnosis::find($id);
            $old_title = $diagnosisUpdate->name;
            /*if($old_title!=$name){
                $diagnosisUpdate->diagnosis_slug = $slug;
            }*/
            $diagnosisUpdate->diagnosis_slug = $slugValue;
            $diagnosisUpdate->name = trim($name);
            $diagnosisUpdate->title = trim($diagnosis_title);
            $diagnosisUpdate->seo_title = trim($seo_title);
            $diagnosisUpdate->meta_keywords = trim($meta_keywords);
            $diagnosisUpdate->meta_description = trim($meta_description);
            $diagnosisUpdate->how_it_works = trim($how_it_works);
            $diagnosisUpdate->disease_short_description = trim($disease_short_description);
            $diagnosisUpdate->disease_description = trim($disease_description);
            $diagnosisUpdate->how_is_it_caused = trim($how_is_it_caused);
            $diagnosisUpdate->how_it_works = trim($how_it_works);
            $diagnosisUpdate->did_you_know = trim($did_you_know);
            $diagnosisUpdate->ad_link = trim($ad_link);
            $diagnosisUpdate->how_to_heal_naturally = trim($how_to_heal_naturally);
            $diagnosisUpdate->no_side_effect = $no_side_effect;
            $diagnosisUpdate->no_added_steroids = $no_added_steroids;
            $diagnosisUpdate->fda_approved = $fda_approved;
            $diagnosisUpdate->worldwide_shipping = $worldwide_shipping;
            $diagnosisUpdate->research_based = $research_based;
            $diagnosisUpdate->no_dietary_restrictions = $no_dietary_restrictions;
            $diagnosisUpdate->diet_chart_visible = $diet_chart_visible;
            $diagnosisUpdate->die_chart_content = trim($diet_chart_content);
            $diagnosisUpdate->note = trim($note);
            $diagnosisUpdate->save();

            $diagnosisId = $id;
            $diagnosis_slug = $diagnosisUpdate->diagnosis_slug;

            $path = 'public/uploads/diagnosis/'.$diagnosisId.'/';
                    if (!file_exists($path)) {
                            mkdir($path, 0755);
                     } 

                     $file = $request->file('diet_chart');
                                        if($file!='') {   
                                                $fname = str_replace('/', '', $name);
                                                $newname = "diet_chart_".$fname;
                                                $ext = $file->getClientOriginalExtension();
                                                $filename = $newname.$ext;
                                                 $newFilename = $newname.'.'.$ext;
                     
                                                if($file->move($path, $newFilename)){
                                                    copy($path . $newFilename, $path . $newFilename);
                                                   // echo "string";
                                                }
                                                //echo $newFilename;
                                                $diagnosisUpdate = Diagnosis::find($diagnosisId);
                                                $diagnosisUpdate->diet_chart = $newFilename;
                                                $diagnosisUpdate->save();
                                        }
//die();

            if(!empty($title)) {
                    foreach ($title as $key => $value) {

                        $desc = $description[$key];

                        if($value!='' && $desc!='') {

                            if(isset($tiles[$key])) {

                                $diagnosisBlock = DiagnosisBlock::find($tiles[$key]);
                                $diagnosisBlock->title = $value;
                                $diagnosisBlock->description = $desc;
                                $diagnosisBlock->save();

                            } else {
                      
                                $diagnosisBlock = new DiagnosisBlock;
                                $diagnosisBlock->fkdiagnosis_id = $diagnosisId;
                                $diagnosisBlock->title = $value;
                                $diagnosisBlock->description = $desc;
                                $diagnosisBlock->save();

                            }

                        }
                    }
                }


            if(!empty($region)) {
                foreach ($region as $key => $value) {

                    $enableCheck = 'enable_region_'.$value;

                    $getEnable = $request->$enableCheck;

                    if($getEnable!=2) {

                    $productValue = 'products_'.$value;
                    $products = $request->$productValue;
                    $proValue = 'product_'.$value;
                    $pro = $request->$proValue;
                    if(!empty($products)) {
                                $prodArr = explode('_', $products);
                                $arrInd = $prodArr[0];

                                            $checkReg = DiagnosisProducts::where('fkdiagnosis_id',$diagnosisId)->where('fkregion_id',$value)->first();

                                            if(isset($checkReg->id)) {

                                                    $diaProducts = DiagnosisProducts::find($checkReg->id);
                                                    $diaProducts->fkdiagnosis_id = $diagnosisId;
                                                    $diaProducts->fkproduct_id = $prodArr[0];
                                                    $diaProducts->fkregion_id = $value;
                                                    $diaProducts->product_type = $prodArr[1];
                                                    $diaProducts->save();

                                                    $diaProductId = $checkReg->id;

                                            } else {

                                                    $diaProducts = new DiagnosisProducts;
                                                    $diaProducts->fkdiagnosis_id = $diagnosisId;
                                                    $diaProducts->fkproduct_id = $prodArr[0];
                                                    $diaProducts->fkregion_id = $value;
                                                    $diaProducts->product_type = $prodArr[1];
                                                    $diaProducts->save();

                                                    $diaProductId = $diaProducts->id;

                                            }
                                      
                                    $proArray = array();
                                    if(!empty($pro)) {
                                        foreach($pro as $k=>$v) {

                                            $checkPro = DiagnosisProductsContent::where('fkdiagnosisproduct_id',$diaProductId)->where('product_id',$k)->first();

                                            if(isset($checkPro->id)) {
                                                $diaProductsCont = DiagnosisProductsContent::find($checkPro->id);
                                                $diaProductsCont->fkdiagnosisproduct_id = $diaProductId;
                                                $diaProductsCont->product_id = $k;
                                                $diaProductsCont->product_content = $v;
                                                $diaProductsCont->save();

                                                $proArray[] = $checkPro->id;

                                            } else {
                                                $diaProductsCont = new DiagnosisProductsContent;
                                                $diaProductsCont->fkdiagnosisproduct_id = $diaProductId;
                                                $diaProductsCont->product_id = $k;
                                                $diaProductsCont->product_content = $v;
                                                $diaProductsCont->save();

                                                $proArray[] = $diaProductsCont->id;
                                            }
                                        }
                                    } else {
                                        $deleteDiaCont = DiagnosisProductsContent::where('fkdiagnosisproduct_id',$diaProductId)->delete();
                                    }

                                    if(!empty($proArray)) {
                                        $deleteDiaCont = DiagnosisProductsContent::where('fkdiagnosisproduct_id',$diaProductId)->whereNotIn('id',$proArray)->delete();
                                    }

                                   
                               
                    } else {
                        $checkReg = DiagnosisProducts::where('fkdiagnosis_id',$diagnosisId)->where('fkregion_id',$value)->first();
                        if(isset($checkReg->id)) {
                            $deletePro = DiagnosisProductsContent::where('fkdiagnosisproduct_id',$checkReg->id)->delete();
                            $deleteReg = DiagnosisProducts::where('id',$checkReg->id)->delete();
                        }

                    }


                    } else {
                        $checkReg = DiagnosisProducts::where('fkdiagnosis_id',$diagnosisId)->where('fkregion_id',$value)->first();
                        if(isset($checkReg->id)) {
                            $deletePro = DiagnosisProductsContent::where('fkdiagnosisproduct_id',$checkReg->id)->delete();
                            $deleteReg = DiagnosisProducts::where('id',$checkReg->id)->delete();
                        }
                    }
                }
            }       

                return redirect('admin/catalog/diagnosis/edit/'.$id)->with(array('diagnosisSlug'=>$diagnosis_slug,'success_msg'=>'Diagnosis Updated Successfully')); 
				// return redirect('admin/catalog/product/simple/edit/'.$id)->with(array('previewSlug'=>$product_slug,'success_msg'=>'Product Updated Successfully'));

        } else {
            $productBundles = $this->products->where('delete_status',0)->where('product_type',2)->get();
            $diagnosis      = Diagnosis::where('id',$id)->first();
            $diagnosisBlock = DiagnosisBlock::where('fkdiagnosis_id',$id)->get();
            $regions = $this->regions->getRegionsCurrency();
            return view('admin/diagnosis/editDiagnosis')->with(array('productBundles'=>$productBundles,'diagnosis'=>$diagnosis,
                   'diagnosisBlock'=>$diagnosisBlock,'regions'=>$regions));
        }
    }


    public function deleteDiagnosis($id){
        $deleteDiagnosis = Diagnosis::find($id);
        $deleteDiagnosis->delete_status = 1;
        if($deleteDiagnosis->save()) {
            return redirect('admin/catalog/diagnosis/list')->with('success_msg','Diagnosis Deleted Successfully'); 
        } else {
            return redirect('admin/catalog/diagnosis/list')->with('success_msg','Please try again later'); 
        }
    }


    public function deleteBlock($id){
        $deleteTiles = DiagnosisBlock::where('id',$id)->delete();
        return "success";
    }

    public function getBundleProducts($id,$regionId){
        $products = $this->products->getBundleProducts($id,$regionId);
        return $products;
    }

}