<?php namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;

use Response;
use Input;
use Hash;
use Mail;
use Session;
use Auth;
use Config;

class UserController extends Controller {

	public function __construct()
    {
    	$this->siteTitle = Config::get('custom.siteTitle');
    }
	
	function fbLogin(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
		// Send an array of permissions to request
		$login_url = $fb->getLoginUrl(['email']);

		// Obviously you'd do this in blade :)
		echo '<a href="' . $login_url . '">Login with Facebook</a>';
	}
	
	public function login()
	{
		if(isset(Auth::user()->id)) {
			//echo "string"; die();
    		return redirect('my-profile');
    	}
    	$pageTitle = "Login | ".$this->siteTitle;
		return redirect('auth/login')->with(array('pageTitle'=>$pageTitle));
	}
	
	public function register(Request $request)
	{
		if(isset(Auth::user()->id)) {
    		return redirect('my-profile');
    	}
		if($request->isMethod('post')){
			
			$email=trim($request->email);
			$display_name=trim($request->display_name);
			$user=User::where('email',$email)->where('delete_status',0)->first();
			if($user){
				return redirect('/register')->with('error_msg',' Email Id Already Exists!');
			}
			$user = new User;
			$password=trim($request->password);
			$user->name=trim($request->username);
			$user->email=$email;
			$user->password=Hash::make($password);
			$user->password_text=$password;
			$user->display_name=$display_name;
			$user->age=trim($request->age);
			$user->facebook_url=trim($request->facebook_url);
			$user->twitter_url=trim($request->twitter_url);
			$user->google_url=trim($request->google_url);
			$user->website_url=trim($request->website_url);
			$user->user_type=2;
			if($user->save()) {
				$path = 'public/uploads/user/'.$user->id.'/';
				if (!file_exists($path)) {
						mkdir($path, 0755);
				 } 
				$image = $request->file('image');
				if($image!='') { 
					$newname = str_random(5);
					$ext = $image->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;

					if($image->move($path, $filename)){
						copy($path . $filename, $path . $newFilename);						
						$user=User::find($user->id);
						$user->image=$newFilename;
						$user->save();
					}
					
				}
    			//mail the user details
    			$data['username'] = $email;
    			$data['password'] = $password;
    			$data['display_name'] = $display_name;
    			$subject = 'Welcome to the World of Grocare India!';
    			Mail::send('emails.userReg', $data, function($message)
                        use($email,$subject){
          			$message->from('no-reply@grocare.com', 'GROCARE');
          			$message->to($email)->subject($subject);
          		});
    			return redirect('/register')->with('success_msg','Thanks for Registering Please check Your Mail.');
    		} else {
    			return redirect('/register')->with('error_msg','Could not Register! Please try again.');
    		}
			
		}else{
			$pageTitle = "Login | ".$this->siteTitle;
			return view('front/register')->with(array('pageTitle'=>$pageTitle));
		}
		
	}
	public function editProfile(Request $request){		
		$id=Auth::user()->id;
		if(!$id){
			return redirect('auth/login');
		}
		if($request->isMethod('post')){
			
			// $email=trim($request->email);
			$display_name=trim($request->display_name);
			$password=trim($request->password);
			// $user=User::where('email',$email)->where('delete_status',0)->first();
			// if($user){
				// return redirect('/register')->with('error_msg',' Email Id Already Exists! Please try again.');
			// }
			$user = User::find($id);
			$user->name=trim($request->username);
			// $user->email=$email;
			if($password){
				$user->password=Hash::make($password);
				$user->password_text=$password;
			}
			$user->display_name=$display_name;
			$user->age=trim($request->age);
			$user->facebook_url=trim($request->facebook_url);
			$user->twitter_url=trim($request->twitter_url);
			$user->google_url=trim($request->google_url);
			$user->website_url=trim($request->website_url);
			$user->user_type=2;
			
				$path = 'public/uploads/user/'.$id.'/';
				if (!file_exists($path)) {
						mkdir($path, 0755);
				 } 
				$image = $request->file('image');
				if($image!='') { 
					$newname = str_random(5);
					$ext = $image->getClientOriginalExtension();
					$filename = $newname.'-original.'.$ext;
					$newFilename = $newname.'.'.$ext;

					if($image->move($path, $filename)){
						copy($path . $filename, $path . $newFilename);	
						$user->image=$newFilename;
					}
					
				}
				
				$user->save();
				
				return redirect('/edit-profile')->with('success_msg','Your Profile Successfully Updated.');
			}else{
				$user=User::where('id',$id)->where('delete_status',0)->first();
				$pageTitle = "Profile | ".$this->siteTitle;
				return view('front/profile/edit-profile')->with(array('pageTitle'=>$pageTitle,'user'=>$user));			
			}
	}
	
	public function forgotPassword(Request $request){
		if(isset(Auth::user()->id)) {
    		return redirect('my-profile');
    	}
		if($request->isMethod('post')){
			$email=trim($request->email);
			$user=User::where('email',$email)->where('delete_status',0)->where('user_type',2)->first();
			if(!$user){
				return redirect('/forgot-password')->with('error_msg',' Email Id Not Exists! Please try again.');
			}else{
				if($user->login_type==2) {
					return redirect('/forgot-password')->with('error_msg','You are registered using your facebook account. Please login using facebook');
				} else if($user->login_type==3) {
					return redirect('/forgot-password')->with('error_msg','You are registered using your google account. Please login using google');
				} else if($user->login_type==4) {
					return redirect('/forgot-password')->with('error_msg','You are registered using your twitter account. Please login using twitter');
				}
				$data['username'] = $user->email;
    			$data['password'] = $user->password_text;
    			$data['name'] = $user->name;
    			$subject = 'Forgot Password';
    			Mail::send('emails.forgot_password', $data, function($message)
                        use($email,$subject){
          			$message->from('no-reply@grocare.com', 'GROCARE');
          			$message->to($email)->subject($subject);
          		});
    			return redirect('auth/login')->with('success_msg','Password Send to your Email Id, Please check Your Mail.');
			}
			
		}else{
			$pageTitle = "Profile | ".$this->siteTitle;
			return view('auth/forgot_password')->with(array('pageTitle'=>$pageTitle));	
		}
		
	}



}