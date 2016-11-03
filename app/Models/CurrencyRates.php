<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class CurrencyRates extends Model {

	protected $table = 'currency_rates';

	public function getCurrencyRates(){
		$getCurrencyRates = CurrencyRates::where('delete_status',0)->get();
		return $getCurrencyRates;
	}	
       
}
