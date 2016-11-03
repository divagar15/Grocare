<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;
class Products extends Model {

	protected $table = 'products';

	public function getSimpleProductList(){
		$getProductList = Products::select('id','name','stock_status')->where('delete_status',0)->where('product_type',1)->orderBy('id','DESC')->get();
		return $getProductList;
	}	
	public function getBundleProductList(){
		$getProductList = Products::select('id','name','stock_status')->where('delete_status',0)->where('product_type',2)->orderBy('id','DESC')->get();
		return $getProductList;
	}	


	public static function getSlug($title){
        $slug = Str::slug($title);
        
        $slugCount = Products::whereRaw("product_slug REGEXP '^{$slug}(-[0-9]*)?$'")->where('delete_status',0)->count();
        
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    public function getBundleProducts($prodId,$regId){ 
		$result = $this->select('product_region.id')
						->join('product_region','product_region.fkproduct_id','=','products.id')
						->where('product_region.fkproduct_id',$prodId)
						->where('product_region.fkregion_id',$regId)
						->get();
		$id=array();
		foreach($result as $val){
			$id[]=$val->id;
		}
		$result = $this->select('product_bundle.fksimpleproduct_id')
						->join('product_bundle','product_bundle.fksimpleproduct_id','=','products.id')
						->whereIn('product_bundle.fkbundleproductregion_id',$id)
						->groupBy('product_bundle.fksimpleproduct_id')
						->get();
		$id=array();
		foreach($result as $val){
			$id[]=$val->fksimpleproduct_id;
		}
		$result = $this->select('product_region.fkproduct_id as id','product_region.sku_name as name')
						->join('product_region','product_region.fkproduct_id','=','products.id')
						->whereIn('product_region.fkproduct_id',$id)
						->where('product_region.fkregion_id',$regId)
						->get();
		return $result;
	}
       
}
