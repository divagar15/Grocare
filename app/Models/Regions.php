<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class Regions extends Model {

	protected $table = 'regions';

	public function getRegions(){
		$getRegions = Regions::where('delete_status',0)->get();
		return $getRegions;
	}	

	public function getRegionsCurrency(){
		$getRegionsCurrency = Regions::join('currency_rates as t2','t2.from_currency','=','regions.currency')
							  ->select('regions.*','t2.symbol')
							  ->where('regions.delete_status',0)->get();
		return $getRegionsCurrency;
	}

	public function getRegionsProductCurrency($id){
		$getRegionsProductCurrency = Regions::join('currency_rates as t2','t2.from_currency','=','regions.currency')
									//->leftJoin('product_region as t3','t3.fkregion_id','=','regions.id')
									->leftJoin('product_region as t3', function($join) use ($id)
									    {
									        $join->on('t3.fkregion_id', '=', 'regions.id');
									        $join->on('t3.fkproduct_id', '=', DB::raw($id));
									    })
							  		->select('regions.*','t2.symbol','t3.regular_price','t3.sales_price','t3.enable','t3.id as regId','t3.sku_name')
							  		->where('regions.delete_status',0)//->where('t3.fkproduct_id',$id)
							  		->get();
		return $getRegionsProductCurrency;
	}
       
}
