<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Kohana exception class. Translates exceptions using the [I18n] class.
 *
 * @package    Kohana
 * @category   Exceptions
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Exception extends Kohana_Kohana_Exception {

	
	/**
	 * @var  string  error rendering view
	 */
	//public static $error_view = 'kohana/error';

	/**
	 * @var  string  error view content type
	 */
	
	

	/**
	 * Exception handler, logs the exception and generates a Response object
	 * for display.
	 *
	 * @uses    Kohana_Exception::response
	 * @param   Exception  $e
	 * @return  Response
	 */
	public static function _handler(Exception $e)
	{
		//echo Debug::vars('106D _handler');//exit;
		try
		{
			// Log the exception
			Kohana_Exception::log($e);

			// Generate the response
			$response = Kohana_Exception::response($e);

			return $response;
		}
		catch (Exception $e)
		{
			/**
			 * Things are going *really* badly for us, We now have no choice
			 * but to bail. Hard.
			 */
			// Clean the output buffer if one exists
			ob_get_level() AND ob_clean();

			// Set the Status code to 500, and Content-Type to text/plain.
			header('Content-Type: text/plain; charset='.Kohana::$charset, TRUE, 500);

			echo Kohana_Exception::text($e);

			exit(1);
		}
	}


}
