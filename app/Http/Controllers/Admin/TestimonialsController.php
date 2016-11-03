<?php namespace App\Http\Controllers\Admin;

use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Diagnosis;
use App\Helper\FrontHelper;
use Session;

class TestimonialsController extends Controller {

	public function __construct()
    {
    	
        $this->testimonials = new Testimonials;
        $this->diagnosis = new Diagnosis;
    	/*echo Session::get('active_country').'<br/>';
    	echo Session::get('active_currency').'<br/>';
    	echo Session::get('active_region').'<br/>';
    	echo Session::get('active_symbol').'<br/>';
    	die();*/
    }

    public function testimonialsList(Request $request)
    {
        $testimonials = $this->testimonials->getTestimonials();
        $diagnosis = $this->diagnosis->getDiagnosis();
        return view('admin/testimonials/list')->with(array('testimonials'=>$testimonials,'diagnosis'=>$diagnosis));
    }

    public function addTestimonial(Request $request){
        if($request->isMethod('post')){
            $type = $request->testi_type;
            $testi_for = $request->testi_for;
            $testi_from = $request->testi_name;
            $homepage_view = $request->homepage_view;
            $disease_view = $request->disease_view;
            if($type==1){
                $testi_content = $request->testi_content;
            }
            else if($type==2){
                $path = 'public/uploads/testimonials/';
                if (!file_exists($path)) {
                        mkdir($path, 0755);
                 } 

                $file = $request->file('testi_audioclip');                
                if($file!='') {   
                    $newname = "testi_audio_".str_random(5);
                    $ext = $file->getClientOriginalExtension();
                    $filename = $newname.$ext;
                    $newFilename = $newname.'.'.$ext;

                    if($file->move($path, $newFilename)){
                        copy($path . $newFilename, $path . $newFilename);
                        $testi_content=$newFilename;
                    }
                }
            }

            else if(($type==3)){
                $path = 'public/uploads/testimonials/';
                if (!file_exists($path)) {
                        mkdir($path, 0755);
                 } 

                $file = $request->file('testi_img');                
                if($file!='') {   
                    $newname = "testi_img_".str_random(5);
                    $ext = $file->getClientOriginalExtension();
                    $filename = $newname.$ext;
                    $newFilename = $newname.'.'.$ext;

                    if($file->move($path, $newFilename)){
                        copy($path . $newFilename, $path . $newFilename);
                        $testi_content=$newFilename;
                    }
                }
            }

            $testimonial = new Testimonials;
            $testimonial->type = $type;
            $testimonial->testi_for = $testi_for;
            $testimonial->testi_from = $testi_from;
            $testimonial->testi_content = $testi_content;
            if($homepage_view==1) {
                $testimonial->homepage_view = $homepage_view;
            }
            if($disease_view==1) {
                $testimonial->disease_view = $disease_view;
            }
            $created_at = date('Y-m-d H:i:s');
            $testimonial->created_at = $created_at;
            if($testimonial->save()){
                return redirect('admin/testimonials/list')->with('success_msg','Testimonial Added Successfully'); 
            }
            else{
                return redirect('admin/testimonials/add')->with('error_msg','Testimonial could not be added. Please try again');
            }

        }
        else{
            $diagnosis = $this->diagnosis->getDiagnosis();
            return view('admin/testimonials/addTestimonial')->with(array('diagnosis'=>$diagnosis));
        }
    }

    public function editTestimonial(Request $request,$id){
        if($request->isMethod('post')){
            //return $request->all();
            $type = $request->testi_type;
            $testi_for = $request->testi_for;
            $testi_from = $request->testi_name;
            $homepage_view = $request->homepage_view;
            $disease_view = $request->disease_view;
            if($type==1){
                $testi_content = $request->testi_content;
            }
            else if(($type==2)){
                $path = 'public/uploads/testimonials/';
                if (!file_exists($path)) {
                        mkdir($path, 0755);
                 } 

                $file = $request->file('testi_audioclip');                
                if($file!='') {   
                    $newname = "testi_audio_".str_random(5);
                    $ext = $file->getClientOriginalExtension();
                    $filename = $newname.$ext;
                    $newFilename = $newname.'.'.$ext;

                    if($file->move($path, $newFilename)){
                        copy($path . $newFilename, $path . $newFilename);
                        $testi_content=$newFilename;
                    }
                }
                else{
                    $testi_content = $request->testi_content;
                }
            }

            else if(($type==3)){
                //return 'update';
                $path = 'public/uploads/testimonials/';
                if (!file_exists($path)) {
                        mkdir($path, 0755);
                 } 

                $file = $request->file('testi_img');                
                if($file!='') {   
                    //return 'update';
                    $newname = "testi_img_".str_random(5);
                    $ext = $file->getClientOriginalExtension();
                    $filename = $newname.$ext;
                    $newFilename = $newname.'.'.$ext;

                    if($file->move($path, $newFilename)){
                        copy($path . $newFilename, $path . $newFilename);
                        $testi_content=$newFilename;
                    }
                }
                else{
                    $testi_content = $request->testi_content;
                }
            }

            $testimonial = Testimonials::find($id);
            $testimonial->type = $type;
            $testimonial->testi_for = $testi_for;
            $testimonial->testi_from = $testi_from;
            $testimonial->testi_content = $testi_content;
            if($homepage_view==1) {
                $testimonial->homepage_view = $homepage_view;
            } else {
                $testimonial->homepage_view = 0;
            }
            if($disease_view==1) {
                $testimonial->disease_view = $disease_view;
            } else {
                $testimonial->disease_view = 0;
            }
            if($testimonial->save()){
                return redirect('admin/testimonials/edit/'.$id)->with('success_msg','Testimonial Added Successfully'); 
            }
            else{
                return redirect('admin/testimonials/add')->with('error_msg','Testimonial could not be added. Please try again');
            }

        }
        else{
            $diagnosis = $this->diagnosis->getDiagnosis();
            $testimonial = $this->testimonials->getTestimonials($id);
            return view('admin/testimonials/editTestimonial')->with(array('testimonial'=>$testimonial,'diagnosis'=>$diagnosis));
        }
    }

    public function deleteTestimonial($id){
        $testimonial = Testimonials::find($id);
        $testimonial->delete_status = 1;
        if($testimonial->save()){
            return redirect('admin/testimonials/list')->with('success_msg','Testimonial deleted Successfully'); 
        }
        else{
            return redirect('admin/testimonials/add')->with('error_msg','Testimonial could not be deleted. Please try again');
        }
    }
    

}