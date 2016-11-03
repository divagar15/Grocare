<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use App,Redirect,Request,Mail,Config,Route,Auth,URL,Session,Agent;
use DB;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
	
	
	//$debugSetting	= Config::get('app.debug');
		
		$data['previous']	= URL::previous();
		$data['url'] 		= Request::url();
		$data['device'] 		= $_SERVER['HTTP_USER_AGENT'];
		$data['ip']             = $_SERVER['REMOTE_ADDR'];
		$data['true_error']     = $e->getMessage();
		$data['file_name']      = $e->getFile();
		$data['line_number']    = $e->getLine();
		
		$sd = new SymfonyDisplayer(true);
		$data['desc']           = (string) $sd->createResponse($e);
		
	
		Mail::queue('emails.errors', $data, function ($message) {
    		$message->from('no-reply@grocare.com', 'Grocare');
                                    
                                    $message->to('divagar.umm@gmail.com')->subject('error found in grocare');
                                    $message->bcc('info@grocare.com')->subject('error found in grocare');
                                 //   $message->bcc('divagar@ummtech.com')->subject('error found in grocare');
		});
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
	
			
		return parent::render($request, $e);
	}

}