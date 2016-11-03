<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;
class Diagnosis extends Model {

	protected $table = 'diagnosis';

	public function getDiagnosis(){
		$getDiagnosis = Diagnosis::where('delete_status',0)->get();
		return $getDiagnosis;
	}	

	public static function getSlug($title){
        $slug = Str::slug($title);
        
        $slugCount = Diagnosis::whereRaw("diagnosis_slug REGEXP '^{$slug}(-[0-9]*)?$'")->where('delete_status',0)->count();
        
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

}