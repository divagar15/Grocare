<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\Courses;
use Hash;
use Auth;
use Session;
use Config;

class ConfigurationController extends Controller {

    public function __construct()
    {
        $this->countries = Config::get('custom.country');
        $this->currencies = Config::get('custom.currency');
        $this->enabledCurrency = new EnabledCurrency;
        $this->currencyRates   = new CurrencyRates;
        $this->regions         = new Regions;
        $this->courses         = new Courses;
    }

    public function currencies(Request $request){

        $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();

        if($request->isMethod('post')){

            $enabledCurrencyArray = $request->enabled_currencies;
            $enabled_currencies = implode(',', $request->enabled_currencies);

            if(!empty($enabledCurrencyArray)) {
                if(!in_array('INR', $enabledCurrencyArray)) {
                    return redirect('admin/configuration/currencies')->with('error_msg','You cannot remove INR from enabled currencies.');
                }
            }

            $this->enabledCurrency = $this->enabledCurrency->find(1);
            $this->enabledCurrency->currencies = $enabled_currencies;
            if($this->enabledCurrency->save()) {

                

                if(!empty($enabledCurrencyArray)) {
                    foreach($enabledCurrencyArray as $key=>$value) {
                        $checkCurrencyRates = $this->currencyRates->select('id')->where('from_currency',$value)->where('delete_status',0)->first();
                        if(!isset($checkCurrencyRates->id)) {
                            $currencyRates = new CurrencyRates;
                            $currencyRates->from_currency = $value;
                            $currencyRates->to_currency = $enabledCurrency->base_currency;
                            $currencyRates->save();
                        }
                    }

                    $checkDisabledCurrencyRates = $this->currencyRates->select('id')->whereNotIn('from_currency',$enabledCurrencyArray)->where('delete_status',0)->get();
                    if(!empty($checkDisabledCurrencyRates)) {
                        foreach($checkDisabledCurrencyRates as $rates) {
                            $this->currencyRates = $this->currencyRates->find($rates->id);
                            $this->currencyRates->delete_status = 1;
                            $this->currencyRates->save(); 
                        }
                    }
                }

                return redirect('admin/configuration/currencies')->with('success_msg','Currencies updated successfully');
            } else {
                return redirect('admin/configuration/currencies')->with('error_msg','Please try again later');
            }
        } else {
            
            $currencyRates   = $this->currencyRates->getCurrencyRates();
        	return view('admin/configuration/currencies')->with(array('enabledCurrency'=>$enabledCurrency,
                   'currencies'=>$this->currencies, 'currencyRates'=>$currencyRates));
        }
    }


    public function currencyRates(Request $request){
        if($request->isMethod('post')){

            $counter = $request->counter;

            for($i=0;$i<$counter;$i++) {

                $rateId = "rateId_".$i;
                $exchangeRate = "exchange_rate_".$i;
                $symbol = "symbol_".$i;

                $this->currencyRates = $this->currencyRates->find($request->$rateId);
                $this->currencyRates->rate = $request->$exchangeRate;
                $this->currencyRates->symbol = $request->$symbol;
                $this->currencyRates->save(); 
            }

            return redirect('admin/configuration/currencies')->with('success_msg','Exchange Rates updated successfully');

        } else {
            return redirect('admin/configuration/currencies');
        }
    }


    public function regions(Request $request){
        if($request->isMethod('post')){

            $counter = $request->counter;

            for($i=1;$i<=$counter;$i++) {

                $rid = "rid_".$i;
                $regionName = "region_".$i;
                $countries = "countries".$i;
                $currencies = "currencies_".$i;
                $shipping_charge = "shipping_charge_".$i;
                $minimum_amount = "minimum_amount_".$i;

                $country =  $request->$countries;


                if($request->$regionName!='' && $request->$currencies!='' && !empty($country)) {
                    //echo $request->$region.'--'.$request->$currencies; var_dump($request->$countries);
                    $selectedCountries = implode(',', $request->$countries);

                    if($request->$rid!=0) {

                        $region = $this->regions->find($request->$rid);
                        $region->region = trim($request->$regionName);
                        $region->countries = $selectedCountries;
                        $region->currency = $request->$currencies;
                        $region->shipping_charge = trim($request->$shipping_charge);
                        $region->minimum_amount = trim($request->$minimum_amount);
                        $region->save();

                    } else {

                        $region = new Regions;
                        $region->region = trim($request->$regionName);
                        $region->countries = $selectedCountries;
                        $region->currency = $request->$currencies;
                        $region->shipping_charge = trim($request->$shipping_charge);
                        $region->minimum_amount = trim($request->$minimum_amount);
                        $region->save();
                        //die;
                    }

                }

            }

            return redirect('admin/configuration/regions')->with('success_msg','Regions updated successfully');

        } else {
            $regions = $this->regions->getRegions();
            $enabledCurrency = $this->enabledCurrency->getEnabledCurrency();
            $enabledCurrencies = explode(',', $enabledCurrency->currencies);
            return view('admin/configuration/regions')->with(array('regions'=>$regions, 'countries'=>$this->countries,
                   'currencies'=>$this->currencies,'enabledCurrencies'=>$enabledCurrencies));
        }
    }

    public function deleteRegion($id){
        $regionDelete = $this->regions->find($id);
        $regionDelete->delete_status = 1;
        if($regionDelete->save()) {
            return redirect('admin/configuration/regions')->with('success_msg','Region deleted successfully');
        } else {
            return redirect('admin/configuration/regions')->with('error_msg','Please try again later');
        }
    }


    public function courses(Request $request){
        if($request->isMethod('post')){

            $counter = $request->counter;

            for($i=1;$i<=$counter;$i++) {

                $cid = "cid_".$i;
                $courseName = "course_".$i;



                if($request->$courseName!='') {


                    if($request->$cid!=0) {

                        $course = $this->courses->find($request->$cid);
                        $course->course_name = trim($request->$courseName);
                        $course->save();

                    } else {

                        $course = new Courses;
                        $course->course_name = trim($request->$courseName);
                        $course->save();

                    }

                }

            }

            return redirect('admin/configuration/courses')->with('success_msg','Courses updated successfully');

        } else {
            $courses = $this->courses->getCourses();
            return view('admin/configuration/courses')->with(array('courses'=>$courses));
        }
    }


    public function deleteCourse($id){
        $courseDelete = $this->courses->find($id);
        $courseDelete->delete_status = 1;
        if($courseDelete->save()) {
            return redirect('admin/configuration/courses')->with('success_msg','Course deleted successfully');
        } else {
            return redirect('admin/configuration/courses')->with('error_msg','Please try again later');
        }
    }

}