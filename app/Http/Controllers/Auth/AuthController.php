<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderSession;
use App\Models\OrderDetails;
use Activity;
use Auth;
use Session;
use Input;
use Socialite;
use Config;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->siteTitle = Config::get('custom.siteTitle');
		$this->middleware('guest', ['except' => 'getLogout']);
	}
	
	public function postLogin(Request $request){
		
		$email = trim($request->email);
		$password = trim($request->password);
		$page = trim($request->page);

		if($page=='admin') {

			$loginData = array('email'=>$email,'password'=>$password,'user_type'=>1,'delete_status'=>0);

			$checkMail = User::where('email',$email)->where('delete_status',0)->first();

			if(isset($checkMail->id) && $checkMail->user_type==3){
				$loginData = array('email'=>$email,'password'=>$password,'user_type'=>3,'delete_status'=>0);
			} elseif(isset($checkMail->id) && $checkMail->user_type==4){
				$loginData = array('email'=>$email,'password'=>$password,'user_type'=>4,'delete_status'=>0);
			}


		} else {

			$loginData = array('email'=>$email,'password'=>$password,'user_type'=>2,'delete_status'=>0);

		}

		//return $loginData;
		
		$remember = ($request->remember) ? true : false;
		//echo $remember; die();
 
        // attempt to do the login
        // $auth = Auth::attempt(
            // [
                // 'email'  => $email,
                // 'password'  => $password    
            // ]
        // );
		//$this->auth->attempt($loginData)
		
		if($this->auth->attempt($loginData,$remember)){
			/*if($remember){
				//echo 'expire_on_close:false';
				Config::set('session.expire_on_close', false);
			}else{
				//echo 'expire_on_close:true';
				Config::set('session.expire_on_close', true);
			}*/
			//echo '<pre>';print_r(Config::get('session'));exit;
			//echo Auth::user()->id; die();
	    	$userId = Auth::user()->id;
	    	$userType = Auth::user()->user_type;
	    	$userEmail = Auth::user()->email;
	    	$userName = Auth::user()->name;

	    	if($page=='admin' && $userType==1) {
	    		return redirect('admin/dashboard');
	    	} 

	    	if($page=='admin' && $userType==3) {
	    		return redirect('admin/order-tracking');
	    	} 

                if($page=='admin' && $userType==4) {
	    		return redirect('admin/catalog/diagnosis/list');
	    	}

	    	$order_session_id = Auth::user()->order_session_id;
	    	Session::put('user_type',$userType);
	    	Session::put('user_id',$userId);
	    	Session::put('user_email',$userEmail);
	    	Session::put('user_name',$userName);
			if(!empty($order_session_id)){		
				if(!Session::has('order_id')) {
$curDate = date('Y-m-d 23:59:59');
$pastDate = date('Y-m-d 00:00:00',strtotime('-7 days'));
					$orderSessionCheck = OrderSession::where('id',$order_session_id)->where('order_status','!=',2)->whereBetween('created_at',array($pastDate,$curDate))->count();
					$orderDetailsCheck = OrderDetails::where('oid',$order_session_id)->whereNotNull('order_status')->where('delete_status',0)->count();
					if($orderSessionCheck!=0 && $orderDetailsCheck==0) {
						$orderSessionCheck = OrderSession::where('id',$order_session_id)->where('order_status','!=',2)->first();
						Session::put('order_id',$order_session_id);
						Session::put('active_country',strtoupper($orderSessionCheck->order_country));
					}
				}
				return redirect('/cart');	
			}else{
				if(Session::has('order_id')) {	
					return redirect('/checkout');
				}
				return redirect('/my-profile');				
			}
	    }
	    else{
	    	if($page=='admin') {
	    		return redirect('admin')->with('error_msg','Invalid Login Credentials');
	    	} 
	    	//return 'invalid';
	    	$pageTitle = "Login | ".$this->siteTitle;
	    	return redirect('auth/login')->with(array('pageTitle'=>$pageTitle))->with('error_msg','Invalid Login Credentials. Please try again.');
	    }
	}

	public function getLogout()
    {
        $this->auth->logout();
		Session::flush(); 
        return redirect('/');
    }

        protected $redirectPath = '/';
 
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
 
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('auth/'.$provider);
        }
//return $user;
        $authUser = $this->findOrCreateUser($user,$provider);
 
        Auth::login($authUser, true);

//return redirect('/');

        $userId = Auth::user()->id;
	    	$userType = Auth::user()->user_type;
	    	$userEmail = Auth::user()->email;
	    	$userName = Auth::user()->name;
	    	$order_session_id = Auth::user()->order_session_id;
	    	Session::put('user_type',$userType);
	    	Session::put('user_id',$userId);
	    	Session::put('user_email',$userEmail);
	    	Session::put('user_name',$userName);
			/*if($order_session_id){			
				Session::put('order_id',$order_session_id);	
				return redirect('/cart');	
			}else{
				return redirect('/my-profile');				
			}*/
			if(!empty($order_session_id)){		
				if(!Session::has('order_id')) {	
$curDate = date('Y-m-d 23:59:59');
$pastDate = date('Y-m-d 00:00:00',strtotime('-7 days'));
					$orderSessionCheck = OrderSession::where('id',$order_session_id)->where('order_status','!=',2)->whereBetween('created_at',array($pastDate,$curDate))->count();
					$orderDetailsCheck = OrderDetails::where('oid',$order_session_id)->whereNotNull('order_status')->where('delete_status',0)->count();
					if($orderSessionCheck!=0 && $orderDetailsCheck==0) {
						$orderSessionCheck = OrderSession::where('id',$order_session_id)->where('order_status','!=',2)->first();
						Session::put('order_id',$order_session_id);
						Session::put('active_country',strtoupper($orderSessionCheck->order_country));	
					}
				}
				return redirect('/cart');	
			}else{
				if(Session::has('order_id')) {	
					return redirect('/checkout');
				}
				return redirect('/my-profile');				
			}
 
        //return redirect()->route('my-profile');
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($userDetail,$provider)
    {
    	//return $facebookUser;
    	if($provider!='twitter') {
        	$authUser = User::where('email', $userDetail->email)->first();
        } else {
        	$authUser = User::where('social_id', $userDetail->id)->first();
        }
 
        if ($authUser){
            return $authUser;
        }

        $login_type = 1;
/*
if($userDetail->email=='diwagar.nsdd@gmail.com') {
echo '<pre>'; print_r($userDetail); echo '</pre>';
echo $userDetail->user['gender'];
die('ok');
}*/

        if($provider=='facebook') {
 
	        $login_type = 2;

$gender = '';
if(isset($userDetail->user['gender'])) {
$gender = $userDetail->user['gender'];
} 

	    return User::create([
	            'name' => $userDetail->name,
	            'email' => $userDetail->email,
	            'social_id' => $userDetail->id,
	            'image' => $userDetail->avatar,
'gender' => $gender,
	            'login_type' => $login_type
	        ]);

	    } else if($provider=='google') {

	    	$login_type = 3;	
$gender = '';
$displayName = '';
$gurl = '';
if(isset($userDetail->user['gender'])) {
$gender = $userDetail->user['gender'];
} 

if(isset($userDetail->user['displayName'])) {
$displayName = $userDetail->user['displayName'];
} 


if(isset($userDetail->user['url'])) {
$gurl = $userDetail->user['url'];
} 


	    return User::create([
	            'name' => $userDetail->name,
	            'email' => $userDetail->email,
	            'social_id' => $userDetail->id,
	            'image' => $userDetail->avatar,
'gender' => $gender,
'display_name' => $displayName,
'google_url' => $gurl,
	            'login_type' => $login_type
	        ]);    	

	    } else if($provider=='twitter') {



	    	$login_type = 4;	

	    	return User::create([
	            'name' => $userDetail->name,
	            //'email' => $userDetail->email,
	            'social_id' => $userDetail->id,
	            'image' => $userDetail->avatar,
	            'login_type' => $login_type
	        ]);    	

	    }



    }


}
