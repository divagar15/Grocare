<?php namespace App\Helper;
use Auth;
use Session;
use DB;

class AppHelper {
    public static function checkLoginStatus() {
        
        if(isset(Auth::user()->id)) {
          return true;
        } else {
          return false;
        }
        
    }
	
	 public static function sanitizebg_values($val) {
	 
		if (is_array($val)) {
			foreach ($val as $k => $v) {
				$val[$k] = str_replace("\r\n", " ", $v);
			}
		} else {
			$val = str_replace("\r\n", " ", $val);
		}
		$val = addslashes(json_encode($val, JSON_HEX_APOS));
//$val = json_encode($val, JSON_HEX_APOS);
		return $val;
		
	 }
}
