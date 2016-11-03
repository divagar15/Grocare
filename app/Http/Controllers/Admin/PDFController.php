<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\EnabledCurrency;
use App\Models\CurrencyRates;
use App\Models\Regions;
use App\Models\Courses;
use App\Models\Couriers;
use App\Models\Products;
use App\Models\ProductCourse;
use App\Models\ProductRegion;
use App\Models\ProductImages;
use App\Models\ProductTiles;
use App\Models\ProductBundle;
use App\Models\OrderSession;
use App\Models\OrderProducts;
use App\Models\OrderBundleProducts;
use App\Models\OrderDetails;
use App\Models\OrderAddress;
use App\Models\OrderNotes;
use App\Models\OrderTracking;
use Session;
use Response;
use Config;
use Hash;
use Mail;
use DB;

class PDFController extends Controller {

	public function __construct()
    {
    	$this->orderStatus = Config::get('custom.orderStatus');
    	$this->countries = Config::get('custom.country');
    	$this->paymentType = Config::get('custom.paymentType');
    	$this->currencies = Config::get('custom.currency');
    }

    public function downloadInvoice($id){
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)/*->where('order_status','<=',3)*/->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/all');
    	}
    	$orderSession = OrderSession::where('id',$orderDetail->oid)->first();
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	$orderProducts = OrderProducts::where('oid',$orderDetail->oid)->get();
        $orderBundleProducts = OrderBundleProducts::where('oid',$orderDetail->oid)->get();
        $couriers = Couriers::where('delete_status',0)->get();
        $orderTracking = OrderTracking::where('order_id',$id)->get();
        $orderNotes = OrderNotes::where('order_id',$id)->get();
		
		$data['orderStatus']=$this->orderStatus;$orderStatus=$this->orderStatus;
		$data['countries']=$this->countries;$countries=$this->countries;
		$data['paymentType']=$this->paymentType;$paymentType=$this->paymentType;
		$data['currencies']=$this->currencies;$currencies=$this->currencies;
		$data['orderDetail']=$orderDetail;
		$data['orderSession']=$orderSession;
		$data['orderAddress']=$orderAddress;
		$data['orderProducts']=$orderProducts;
		$data['orderBundleProducts']=$orderBundleProducts;
		$data['couriers']=$couriers;
		$data['orderTracking']=$orderTracking;
		$data['orderNotes']=$orderNotes;
		
		$pdf = \App::make('dompdf.wrapper');
		$view =  \View::make('pdf.download_invoice', compact('orderStatus','countries','paymentType','currencies','orderDetail','orderSession','orderAddress','orderProducts','orderBundleProducts','couriers','orderTracking','orderNotes'))->render();
		// echo $view;exit;
		$pdf = \PDF::loadView('pdf.download_invoice', $data);
		return $pdf->stream('Invoice.pdf');
    }
    public function downloadPackingSlip($id){
		
    	$orderDetail = OrderDetails::where('id',$id)->where('delete_status',0)/*->where('order_status','<=',3)*/->first();
    	if(!isset($orderDetail->id)) {
    		return redirect('admin/orders/all');
    	}
    	$orderAddress = OrderAddress::where('order_id',$orderDetail->id)->first();
    	//$orderSession = OrderAddress::where('order_id',$orderDetail->id)->first();
		$countries=$this->countries;
		$data['orderAddress']=$orderAddress;
		$data['countries']=$countries;
		$pdf = \App::make('dompdf.wrapper');
		$view =  \View::make('pdf.download_packing_slip', compact('orderAddress','countries'))->render();
		// echo $view;exit;
		$pdf = \PDF::loadView('pdf.download_packing_slip', $data);
		$pdfname = strtolower($orderAddress->shipping_city)."-".strtolower($orderAddress->shipping_name)."-label";
		return $pdf->setPaper('a6')->stream($pdfname.'.pdf');
    }
	
}