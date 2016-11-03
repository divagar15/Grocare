<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class Courses extends Model {

	protected $table = 'courses';

	public function getCourses(){
		$getCourses = Courses::where('delete_status',0)->get();
		return $getCourses;
	}	

	public function getCoursesProduct($id){
		$getProductCourses = Courses::leftJoin('product_course as t2', function($join) use ($id)
							    {
							        $join->on('t2.fkcourse_id', '=', 'courses.id');
							        $join->on('t2.fkproduct_id', '=', DB::raw($id));
							    })
								//leftJoin('product_course as t2','t2.fkcourse_id','=','courses.id')
				      		 ->select('courses.id','courses.course_name','t2.fkcourse_id','t2.quantity')->where('courses.delete_status',0)
				      		// ->where('t2.fkproduct_id',$id)
				      		 ->get();
		return $getProductCourses;
	}
       
}
