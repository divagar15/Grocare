<?php namespace App\Http\Controllers\Front;

use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Diagnosis;
use App\Models\Seo;
use App\Helper\FrontHelper;
use Session;
use Config;

class TestimonialsController extends Controller {

	public function __construct()
    {
    	
        $this->testimonials = new Testimonials;
        $this->diagnosis = new Diagnosis;
        $this->siteTitle = Config::get('custom.siteTitle');
    	/*echo Session::get('active_country').'<br/>';
    	echo Session::get('active_currency').'<br/>';
    	echo Session::get('active_region').'<br/>';
    	echo Session::get('active_symbol').'<br/>';
    	die();*/
    }

    public function index()
    {
        $testimonials = $this->testimonials->getFrontTestimonials(0);   
        $pageTitle = "Testimonials | ".$this->siteTitle;
		$meta_description = '';
		$seoDetails = Seo::where('page',2)->first();
		if(isset($seoDetails->id)) {
			if(!empty($seoDetails->seo_title)) {
				$pageTitle = $seoDetails->seo_title;
			}
			if(!empty($seoDetails->meta_description)) {
				$meta_description = $seoDetails->meta_description;
			}
		}
        //return $testimonials;     
        return view('front/testimonials')->with(array('pageTitle'=>$pageTitle,'testimonials'=>$testimonials,'meta_description'=>$meta_description));
    }

}