<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\CmsBulletins;
use App\Models\CmsAboutTaglinks;
use App\Models\CmsFaq;
use App\Models\CmsDistribute;
use App\Models\Seo;
use App\Helper\AppHelper;
use Session;
use Response;
use Config;
use Hash;
use Input;
use Image;
use Mail;
use DB;
use Auth;
use Validator;


class CmsController extends Controller {

	public function __construct()
    {
    	if(isset(Auth::user()->id)) {
          return true;
        } else {
          return false;
        }
		$this->cms = new Cms;
    }
	
	public function aboutus(Request $request){
		 if($request->isMethod('post')){
			 
			
			$aboutus = $request->aboutus;
			
			$aboutus = json_encode($aboutus);
			
			$aboutus_enval = AppHelper::sanitizebg_values($aboutus);			
					
			$Cms = Cms::where('active_status',1)->where('id',1)->get();			
			$enval = json_decode(stripslashes($aboutus_enval));
			$cms_eval = json_decode($enval);	
			
			foreach($Cms as $Cms){
					$Cms = Cms::where('active_status',1)->where('id',1)
								->update(['aboutus' => $aboutus_enval]);
				
			}
			$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get();
			return redirect('admin/cms/aboutus')->with('success_msg','Successfully updated');
			
		 }else{
			 
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$aboutus = $Cms['aboutus'];				
			
			$enval = json_decode(stripslashes($aboutus));
			$cms_eval = json_decode($enval);
			$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get();
			return view('admin/cms/aboutus')->with(array('cms_eval'=>$cms_eval,'cms_taglinks'=>$cms_taglinks));
			
		 }
	}
	
	public function contactlist(Request $request){
		if($request->isMethod('post')){
			
			$contactlist = $request->contactlist;
			$contactlist = json_encode($contactlist);
			
			$contactlist_enval = AppHelper::sanitizebg_values($contactlist);			
					
			$Cms = Cms::where('active_status',1)->where('id',1)->get();	
			$enval = json_decode(stripslashes($contactlist_enval));
			$cms_eval = json_decode($enval);
			foreach($Cms as $Cms){
					$Cms = Cms::where('active_status',1)->where('id',1)
								->update(['contactlist' => $contactlist_enval]);
				
			}
			return redirect('admin/cms/contactlist')->with('success_msg','Successfully updated');
		}else{
			 
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$contactlist = $Cms['contactlist'];				
			
			$enval = json_decode(stripslashes($contactlist));
			$cms_eval = json_decode($enval);			
			return view('admin/cms/contactlist')->with(array('cms_eval'=>$cms_eval));
			
		 }
	}
	
	public function supportlist(Request $request){
		if($request->isMethod('post')){
			$support = $request->support;
			$support = json_encode($support);
			
			$support_enval = AppHelper::sanitizebg_values($support);			
					
			$Cms = Cms::where('active_status',1)->where('id',1)->get();	
			$enval = json_decode(stripslashes($support_enval));
			$cms_eval = json_decode($enval);
			foreach($Cms as $Cms){
					$Cms = Cms::where('active_status',1)->where('id',1)
								->update(['supportlist' => $support_enval]);
				
			}
			return redirect('admin/cms/supportlist')->with('success_msg','Successfully updated');
		}
		else{
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$supportlist = $Cms['supportlist'];				
			
			$enval = json_decode(stripslashes($supportlist));
			$cms_eval = json_decode($enval);	
			return view('admin/cms/supportlist')->with(array('cms_eval'=>$cms_eval));
		}
	}
	
	public function deleteBlock($id){
        $cms_bulletins = CmsBulletins::where('id',$id)->delete();
        return "success";
    }
	
	public function aboutustaglinks(Request $request){
			if($request->isMethod('post')){			
				$title = $request->title;
				$descp = $request->descp;
				$file = $request->file('file');
						$destination = 'public/uploads/cms/taglinks/';
						$newname = str_random(5);
						$ext = $file->getClientOriginalExtension();
						$filename = $newname.'-original.'.$ext;
						$newFilename = $newname.'.'.$ext;
						if($file->move($destination, $filename)){
							copy($destination . $filename, $destination . $newFilename);
							
										$cms_taglinks = new CmsAboutTaglinks;
										$cms_taglinks->title = $title;
										$cms_taglinks->descp = $descp;
										$cms_taglinks->image_path = $newFilename;										
										$cms_taglinks->active_status = 1;
										$cms_taglinks->delete_status = 1;
										$cms_taglinks->save();				
									
							$Cms = Cms::where('active_status',1)->where('id',1)->first();
							$aboutus = $Cms['aboutus'];	
							$enval = json_decode(stripslashes($aboutus));
							$cms_eval = json_decode($enval);
							$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get();
							return redirect('admin/cms/aboutus')->with('success_msg','Successfully addded');
						}
			}else{
				return view('admin/cms/aboutus_taglinks');
			}
	}
	
	public function viewtag($id){
		
		$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$id)->first();
		return view('admin/cms/viewtag')->with(array('cms_taglinks'=>$cms_taglinks));
		
	}
	
	public function edittag(Request $request,$id){		
		 if($request->isMethod('post')){	
				$title = $request->title;
				$descp = $request->descp;
				$edit_id = $request->edit_id;
				$file = $request->file('file');	
				if($file!='') {			
					$destination = 'public/uploads/cms/taglinks/';
					$newname = str_random(5);
					$ext = $file->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;
						if($file->move($destination, $filename)){
						copy($destination . $filename, $destination . $newFilename);
						$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$edit_id)
							->update(['title' => $title,'descp'=>$descp,'image_path'=>$newFilename]);
							return redirect('admin/cms/aboutus')->with('success_msg','Successfully updated');
						}
				}else{
					$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$edit_id)
								->update(['title' => $title,'descp'=>$descp]);
					return redirect('admin/cms/aboutus')->with('success_msg','Successfully updated');
					
				}
										
		}else{
			$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$id)->first();
			return view('admin/cms/edittag')->with(array('cms_taglinks'=>$cms_taglinks));
		}
	}
	
	public function edittagimage(Request $request,$id){
			 if($request->isMethod('post')){
				$file = $request->file('file');	
				if($file!='') {			
				$destination = 'public/uploads/cms/taglinks/';
				$newname = str_random(5);
				$ext = $file->getClientOriginalExtension();
				$filename = $newname.'-original.'.$ext;
				$newFilename = $newname.'.'.$ext;
				if($file->move($destination, $filename)){
					copy($destination . $filename, $destination . $newFilename);
					$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$id)
						->update(['image_path'=>$newFilename]);	
				}
				}
				$Cms = Cms::where('active_status',1)->where('id',1)->first();
				$aboutus = $Cms['aboutus'];	
				$enval = json_decode(stripslashes($aboutus));
				$cms_eval = json_decode($enval);
				$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get(); 

				return redirect('admin/cms/aboutus')->with('success_msg','Successfully updated');
			 }else{
				$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->where('id',$id)->first();
				return view('admin/cms/edittagimage')->with(array('cms_taglinks'=>$cms_taglinks));
			 }
	}
	
	public function deletetag(Request $request,$id){
		$cms_taglinksdelete = CmsAboutTaglinks::where('id',$id)->delete();
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$aboutus = $Cms['aboutus'];	
		$enval = json_decode(stripslashes($aboutus));
		$cms_eval = json_decode($enval);
		$cms_taglinks = CmsAboutTaglinks::where('active_status',1)->get();		
		return redirect('admin/cms/aboutus')->with('delete_msg','Successfully Deleted');
	}
	
	public function faq(Request $request){
		if($request->isMethod('post')){
			
			$faq = $request->faq;
			$faq = json_encode($faq);
			
			$faq_enval = AppHelper::sanitizebg_values($faq);		
			$Cms = Cms::where('active_status',1)->where('id',1)->get();	
			$enval = json_decode(stripslashes($faq_enval));
			$cms_eval = json_decode($enval);
			foreach($Cms as $Cms){
					$Cms = Cms::where('active_status',1)->where('id',1)
								->update(['faq' => $faq_enval]);
				
			}
			$CmsFaq = CmsFaq::where('active_status',1)->get();
			return view('admin/cms/faq')->with(array('cms_eval'=>$cms_eval,'CmsFaq'=>$CmsFaq));
				
		}else{
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$faq = $Cms['faq'];				
			
			$enval = json_decode(stripslashes($faq));
			$cms_eval = json_decode($enval);	
			$CmsFaq = CmsFaq::where('active_status',1)->get();
			return view('admin/cms/faq')->with(array('cms_eval'=>$cms_eval,'CmsFaq'=>$CmsFaq));
		}
	}
	
	public function addfaq(Request $request){
		 if($request->isMethod('post')){	
				$question = $request->question;
				$answer = $request->answer;
				$CmsFaq = new CmsFaq;
				$CmsFaq->question = $question;
				$CmsFaq->answer = $answer;
				$CmsFaq->active_status = 1;
				$CmsFaq->delete_status = 1;
				$CmsFaq->save();	
				
				$Cms = Cms::where('active_status',1)->where('id',1)->first();
				$faq = $Cms['faq'];				

				$enval = json_decode(stripslashes($faq));
				$cms_eval = json_decode($enval);
				$CmsFaq = CmsFaq::where('active_status',1)->get();
				return redirect('admin/cms/faq')->with('success_msg','Successfully Added');
		 }else{			 
			 return view('admin/cms/addfaq');			 
		 }
	}
	
	public function viewfaq($id){
		
		$CmsFaq = CmsFaq::where('active_status',1)->where('id',$id)->first();
		return view('admin/cms/viewfaq')->with(array('CmsFaq'=>$CmsFaq));
		
	}
	
	public function editfaq(Request $request,$id){	
		 if($request->isMethod('post')){	
				$question = $request->question;
				$answer = $request->answer;
				$edit_id = $request->edit_id;
				$CmsFaq = CmsFaq::where('active_status',1)->where('id',$edit_id)
								->update(['question' => $question,'answer'=>$answer]);
								
				$CmsFaq = CmsFaq::where('active_status',1)->where('id',$edit_id)->first();
				return redirect('admin/cms/faq')->with('success_msg','Successfully updated');
		 }else{
			 
			 $CmsFaq = CmsFaq::where('active_status',1)->where('id',$id)->first();
			return view('admin/cms/editfaq')->with(array('CmsFaq'=>$CmsFaq));
		 }
	}
	
	
	public function deletefaq(Request $request,$id){
		$CmsFaq_delete = CmsFaq::where('id',$id)->delete();
		
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$faq = $Cms['faq'];	
		$enval = json_decode(stripslashes($faq));
		$cms_eval = json_decode($enval);
		$CmsFaq = CmsFaq::where('active_status',1)->get();
		return redirect('admin/cms/faq')->with('delete_msg','Successfully Deleted');
	}
	
	public function distribute(Request $request){
		if($request->isMethod('post')){
			
			$distribute = $request->distribute;
			$distribute = json_encode($distribute);
			
			$distribute_enval = AppHelper::sanitizebg_values($distribute);			
					
			$Cms = Cms::where('active_status',1)->where('id',1)->get();	
			$enval = json_decode(stripslashes($distribute_enval));
			$cms_eval = json_decode($enval);
			foreach($Cms as $Cms){
					$Cms = Cms::where('active_status',1)->where('id',1)
								->update(['distribute' => $distribute_enval]);
				
			}
			$CmsDistribute = CmsDistribute::where('active_status',1)->get();
			return redirect('admin/cms/distribute')->with('success_msg','Successfully updated');
			
		}else{
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$distribute = $Cms['distribute'];				
			
			$enval = json_decode(stripslashes($distribute));
			$cms_eval = json_decode($enval);	
			$CmsDistribute = CmsDistribute::where('active_status',1)->get();
			return view('admin/cms/distribute')->with(array('cms_eval'=>$cms_eval,'CmsDistribute'=>$CmsDistribute));
		}
	
	}
	
	public function adddistribute(Request $request){
		if($request->isMethod('post')){
			$title = $request->title;
			$description = $request->description;
			$CmsDistribute = new CmsDistribute;
			$CmsDistribute->title = $title;
			$CmsDistribute->description = $description;
			$CmsDistribute->active_status = 1;
			$CmsDistribute->delete_status = 1;
			$CmsDistribute->save();
			
			$Cms = Cms::where('active_status',1)->where('id',1)->first();
			$distribute = $Cms['distribute'];				
			
			$enval = json_decode(stripslashes($distribute));
			$cms_eval = json_decode($enval);	
			$CmsDistribute = CmsDistribute::where('active_status',1)->get();
			return redirect('admin/cms/distribute')->with('success_msg','Successfully Added');
		}
		else{
			return view('admin/cms/adddistribute');
		}
	}
	
	public function viewdistribute($id){
		
		$CmsDistribute = CmsDistribute::where('active_status',1)->where('id',$id)->first();
		return view('admin/cms/viewdistribute')->with(array('CmsDistribute'=>$CmsDistribute));
		
	}
	
	public function editdistribute(Request $request,$id){	
		 if($request->isMethod('post')){	
				$title = $request->title;
				$description = $request->description;
				$edit_id = $request->edit_id;
				$CmsDistribute = CmsDistribute::where('active_status',1)->where('id',$edit_id)
								->update(['title' => $title,'description'=>$description]);
								
				$CmsDistribute = CmsDistribute::where('active_status',1)->where('id',$edit_id)->first();				
				return redirect('admin/cms/distribute')->with('success_msg','Successfully updated');
		 }else{
			 
			 $CmsDistribute = CmsDistribute::where('active_status',1)->where('id',$id)->first();
			return view('admin/cms/editdistribute')->with(array('CmsDistribute'=>$CmsDistribute));
		 }
	}
	
	public function deletedist($id){
		$CmsDistribute_delete = CmsDistribute::where('id',$id)->delete();		
		$Cms = Cms::where('active_status',1)->where('id',1)->first();
		$distribute = $Cms['distribute'];
		$enval = json_decode(stripslashes($distribute));
		$cms_eval = json_decode($enval);	
		$CmsDistribute = CmsDistribute::where('active_status',1)->get();
		return redirect('admin/cms/distribute')->with('delete_msg','Successfully Deleted');
	}
	
	public function technicalbulletins(Request $request){
		if($request->isMethod('post')){
					/* $title = $request->$title;
					$file = $request->file('file');
					$destination = 'public/uploads/cms/technicalbulletins/';
					
					$newname = str_random(5);
					$ext = $file->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;

					if($file->move($destination, $filename)){
								$cms_bulletins = new CmsBulletins;
								$cms_bulletins->title = $j;
								$cms_bulletins->bulletins = $newFilename;
								$cms_bulletins->active_status = 1;
								$cms_bulletins->delete_status = 1;
								$cms_bulletins->save();		
					}*/
		}else{	
			$CmsBulletins = CmsBulletins::where('active_status',1)->get();
			
			return view('admin/cms/technicalbulletins')->with(array('CmsBulletins' => $CmsBulletins));
		}
	}
	
	public function addtechnicalbulletins(Request $request){
		if($request->isMethod('post')){
			
			$title = $request->title;
			$file = $request->file('file');
			$destination = 'public/uploads/cms/technicalbulletins/';
			
			$newname = str_random(5);
			$ext = $file->getClientOriginalExtension();
			$filename = $newname.'-original.'.$ext;
			$newFilename = $newname.'.'.$ext;

			if($file->move($destination, $filename)){
						copy($destination . $filename, $destination . $newFilename);
						$cms_bulletins = new CmsBulletins;
						$cms_bulletins->title = $title;
						$cms_bulletins->bulletins = $newFilename;
						$cms_bulletins->active_status = 1;
						$cms_bulletins->delete_status = 1;
						$cms_bulletins->save();		
			}
			$CmsBulletins = CmsBulletins::where('active_status',1)->get();
			return redirect('admin/cms/technicalbulletins')->with('success_msg','Successfully Added');
		}else{
			return view('admin/cms/addtechnicalbulletins');
		}
	}
	
	public function viewtechnicalbulletins($id){
		
		$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$id)->first();
		return view('admin/cms/viewtechnicalbulletins')->with(array('CmsBulletins'=>$CmsBulletins));
		
	}
	
	public function edittechnicalbulletins(Request $request,$id){	
		 if($request->isMethod('post')){	
				$title = $request->title;
				$edit_id = $request->edit_id;
				$file = $request->file('file');
				if($file!='') {	
					$destination = 'public/uploads/cms/technicalbulletins/';
					$newname = str_random(5);
					$ext = $file->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;
					if($file->move($destination, $filename)){
						copy($destination . $filename, $destination . $newFilename);
						$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$edit_id)
										->update(['title' => $title,'bulletins'=>$newFilename]);	
						return redirect('admin/cms/technicalbulletins')->with('success_msg','Successfully updated');
					}
				}else{
					$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$edit_id)
									->update(['title' => $title]);	
					return redirect('admin/cms/technicalbulletins')->with('success_msg','Successfully updated');
				}
		 }else{
			$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$id)->first();
			return view('admin/cms/edittechnicalbulletins')->with(array('CmsBulletins'=>$CmsBulletins));
		 }
	}
	
	public function edittechnicalbulletinsfile(Request $request,$id){	
		 if($request->isMethod('post')){	
				$file = $request->file('file');
				$edit_id = $request->edit_id;
				$destination = 'public/uploads/cms/technicalbulletins/';
				$newname = str_random(5);
				$ext = $file->getClientOriginalExtension();
				$filename = $newname.'-original.'.$ext;
				$newFilename = $newname.'.'.$ext;
				if($file->move($destination, $filename)){
					copy($destination . $filename, $destination . $newFilename);
					$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$edit_id)
										->update(['bulletins'=>$newFilename]);	
							
				}
				$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$edit_id)->first();
				return redirect('admin/cms/technicalbulletins')->with('success_msg','Successfully updated');
		}else{
			$CmsBulletins = CmsBulletins::where('active_status',1)->where('id',$id)->first();
			return view('admin/cms/edittechnicalbulletinsfile')->with(array('CmsBulletins'=>$CmsBulletins));
		}
	}
	
	public function deletetechnicalbulletins(Request $request,$id){
		$CmsBulletins_del = CmsBulletins::where('id',$id)->delete();
		
		$CmsBulletins = CmsBulletins::where('active_status',1)->get();
		return redirect('admin/cms/technicalbulletins')->with('delete_msg','Successfully Deleted');
	}
	
	public function seo(Request $request){
		if($request->isMethod('post')){	
		
			//echo '<pre>'; print_r($request->all()); echo '</pre>';
			
			$seo_id = $request->seo_id;
			$seo_title = $request->seo_title;
			$meta_description = $request->meta_description;
			
			if(!empty($seo_id)) {
				foreach($seo_id as $key=>$value) {
					$title = $seo_title[$key];
					$description = $meta_description[$key];
					if(!empty($value)) {
						$seo = Seo::find($value);
					} else {
						$seo = new Seo;
					}
					
					$seo->seo_title = $title;
					$seo->meta_description = $description;
					$seo->page = $key;
					$seo->save();
				}
			}
			
			return redirect('admin/seo')->with('success_msg','Successfully Updated');
		
		} else {
			$home = Seo::where('page',0)->first();
			$aboutus = Seo::where('page',1)->first();
			$testimonials = Seo::where('page',2)->first();
			$contact = Seo::where('page',3)->first();
			$distribute = Seo::where('page',4)->first();
			$technicalbulletins = Seo::where('page',5)->first();
			$faq = Seo::where('page',6)->first();
			$store = Seo::where('page',7)->first();
			$selfdiagnosis = Seo::where('page',8)->first();
			$scholarship = Seo::where('page',9)->first();
			return view('admin/cms/seo')->with(array('home'=>$home,'aboutus'=>$aboutus,'testimonials'=>$testimonials,'contact'=>$contact,'distribute'=>$distribute,'technicalbulletins'=>$technicalbulletins,'faq'=>$faq,'store'=>$store,'selfdiagnosis'=>$selfdiagnosis,'scholarship'=>$scholarship));
		}
	}
}










