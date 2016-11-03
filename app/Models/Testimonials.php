<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Str;
class Testimonials extends Model {

	protected $table = 'testimonials';

	public function getTestimonials($id=''){
		if($id != ''){
			$result = $this->where('id',$id)->where('delete_status',0)->first();
			return $result;
		}
		else{
			$result = $this->where('delete_status',0)->get();
			return $result;
		}
		
	}

	public function getFrontTestimonials($page=''){

		$result = $this->where('delete_status',0)->get();
		return $result;		
	}
       
}
