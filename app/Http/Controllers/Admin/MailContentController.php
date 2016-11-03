<?php namespace App\Http\Controllers\Admin;
use Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\Courses;
use App\Models\Products;
use App\Models\ProductCourse;
use App\Models\ProductRegion;
use App\Models\ProductImages;
use App\Models\ProductTiles;
use App\Models\ProductBundle;
use App\Models\OrderAddress;
use App\Models\MailContents;
use App\Models\MailContentsTracking;
use Hash;
use Auth;
use Session;
use Config;
use Image;
use Mail;

class MailContentController extends Controller {
	
	public function __construct(){
        $this->countries = Config::get('custom.country');
        $this->currencies = Config::get('custom.currency');
        $this->productType = Config::get('custom.productType');
        $this->stockStatus = Config::get('custom.stockStatus');
        $this->enabledCurrency = new EnabledCurrency;
        $this->currencyRates   = new CurrencyRates;
        $this->regions         = new Regions;
        $this->courses         = new Courses;
        $this->products        = new Products;
    }
	
	public function mailsContents(Request $request){
		$mailContents=MailContents::selectRaw('id,title,subject')->where('delete_status',0)->get();
		return view('admin.mailContents.mailsContents')->with(array('mailContents'=>$mailContents));
	}
	
	public function addMailContent(Request $request){

        if($request->isMethod('post')){		
		
			$title=trim($request->title);
			$subject=trim($request->subject);
			$mail_contents=trim($request->mail_contents);
			
			$mailContents=new MailContents;
			$mailContents->title=$title;
			$mailContents->subject=$subject;
			$mailContents->mail_contents=$mail_contents;
			if($mailContents->save()){
				return redirect('admin/mail-contents/list')->with('success_msg','Mail Content Added Successfully');
			}else{
				return redirect('admin/mail-contents/list')->with('error_msg','Mail Content Add has Failed!,Please try Again.');
			}
		}else{			
			return view('admin.mailContents.addMailContent');
		}
	}
      
	public function editMailContent($id,Request $request){

        if($request->isMethod('post')){		
		
			$title=trim($request->title);
			$subject=trim($request->subject);
			$mail_contents=trim($request->mail_contents);
			
			$mailContents=MailContents::find($id);
			$mailContents->title=$title;
			$mailContents->subject=$subject;
			$mailContents->mail_contents=$mail_contents;
			if($mailContents->save()){
				return redirect()->back()->with('success_msg','Mail Content Updated Successfully');
			}else{
				return redirect()->back()->with('error_msg','Mail Content Edit has Failed!,Please try Again.');
			}
		}else{		
			$mailContents=MailContents::selectRaw('id,title,subject,mail_contents')->where('id',$id)->where('delete_status',0)->first();
			return view('admin.mailContents.editMailContent')->with(array('mailContents'=>$mailContents));
		}
	}
      
	public function deleteMailContent($id){
		$mailContents=MailContents::find($id);
		$mailContents->delete_status=1;
		if($mailContents->save()){
			return redirect('admin/mail-contents/list')->with('success_msg','Mail Content Deleted Successfully');
		}else{
			return redirect('admin/mail-contents/list')->with('error_msg','Mail Content Delete has Failed!,Please try Again.');
		}
	}
	
	public function sendMailContent(Request $request,$id){	

        if($request->isMethod('post')){		
			
			
            $orderAddress = OrderAddress::selectRaw('billing_name,billing_email')->where('order_id',$id)->first();
			
			
			$email_content_id=trim($request->email_content_id);			
			$mailContents=MailContents::selectRaw('id,title,subject,mail_contents')->where('id',$email_content_id)->where('delete_status',0)->first();
				$email=$orderAddress->billing_email;
				$data['name'] = $orderAddress->billing_name;
				$data['email'] = $email;
                $data['title'] = $mailContents->title;
                $data['mail_contents'] = $mailContents->mail_contents;

                $subject = $mailContents->subject;
				Mail::send('emails.emailContents', $data, function($message)
						use($email,$subject){
					$message->from('no-reply@grocare.com', 'Grocare');
					// $message->cc('info@grocare.com');
					// $message->bcc('neethicommunicate@gmail.com');
					// $message->bcc('ummemark@gmail.com');
					$message->to($email)->subject($subject);
					// $message->to('tvkumaran007@gmail.com')->subject($subject);
				});
				
						
			$mailContentsTracking=new MailContentsTracking;
			$mailContentsTracking->order_id=$id;
			$mailContentsTracking->email_content_id=$email_content_id;
			$success=$mailContentsTracking->save();
			if($success){
				return redirect()->back()->with('success_msg','Successfully Email Sent.');
			}else{
				return redirect()->back()->with('error_msg','Please Try Again.');
			}
			
			
		}else{
			return redirect()->back()->with('error_msg','Please Try Again.');
		} 
			
	}
               
}