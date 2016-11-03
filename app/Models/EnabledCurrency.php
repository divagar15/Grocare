<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class EnabledCurrency extends Model {

	protected $table = 'enabled_currencies';

	public function getEnabledCurrency(){
		$getEnabledCurrency = EnabledCurrency::where('id',1)->first();
		return $getEnabledCurrency;
	}	
       
}
