<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
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

		$this->middleware('guest', ['except' => 'getLogout']);
	}
	
	public function postLogin(Request $request){
		
		$email = trim($request->email);
		$password = trim($request->password);
		$loginData = array('email'=>$email,'password'=>$password,'delete_status'=>0);
		
		$remember = ($request->remember);
 
        // attempt to do the login
        // $auth = Auth::attempt(
            // [
                // 'email'  => $email,
                // 'password'  => $password    
            // ]
        // );
		//$this->auth->attempt($loginData)
		
		if($this->auth->attempt($loginData)){
			if($remember){
				echo 'expire_on_close:false';
				Config::set('session.expire_on_close', false);
			}else{
				echo 'expire_on_close:true';
				Config::set('session.expire_on_close', true);
			}
			exit;
	    	$userId = Auth::user()->id;
	    	$userType = Auth::user()->user_type;
	    	$userEmail = Auth::user()->email;
	    	$userName = Auth::user()->name;
	    	$order_session_id = Auth::user()->order_session_id;
	    	Session::put('user_type',$userType);
	    	Session::put('user_id',$userId);
	    	Session::put('user_email',$userEmail);
	    	Session::put('user_name',$userName);
			if($order_session_id){			
				Session::put('order_id',$order_session_id);	
				return redirect('/cart');	
			}else{
				return redirect('/my-profile');				
			}
	    }
	    else{
	    	//return 'invalid';
	    	return redirect('auth/login')->with('error_msg','Invalid Login Credentials. Please try again.');
	    }
	}

	public function getLogout()
    {
        $this->auth->logout();
		Session::flush(); 
        return redirect('/');
    }

        protected $redirectPath = '/home';
 
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }
 
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }
 
        $authUser = $this->findOrCreateUser($user);
 
        Auth::login($authUser, true);
 
        return redirect()->route('home');
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
    	return $facebookUser;
        $authUser = User::where('facebook_id', $facebookUser->id)->first();
 
        if ($authUser){
            return $authUser;
        }
 
        return User::create([
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'social_id' => $facebookUser->id,
            'image' => $facebookUser->avatar,
            'login_type' => 2
        ]);
    }


}
