<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * File Auth driver.
 * [!!] this Auth driver does not support roles nor autologin.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 * 
 * Этот файл сформировал Бухаров А.В. 12 авг 2017 г. Пароль и логин пользователя берутся из БД СКУД.
 Класс File переименовал в City 25.06.2023 г.
 */
class Auth_City extends Auth {
	
	// User list
	protected $_users;
	public $id_pep;//id_pep авторизованного пользователя
	public $id_org;//id_org головной организации, которой управляет пользователь
	
	
	
	/**
	 * Logs a user in.
	 *
	 * @param   string   $username  Username
	 * @param   string   $password  Password
	 * @param   boolean  $remember  Enable autologin (not supported)
	 * @return  boolean
	 */
	protected function _login($username, $password, $remember)
	{
		
		$sql='select p.id_pep, p.id_org, p.surname, p.name, p.patronymic, p.tabnum, p.login, p.flag, p.id_orgctrl  from people p
			where p.login=\''.$username.'\'
			and p.pswd=\''.$password.'\'
			and p."ACTIVE">0';
		//echo Debug::vars('36', $sql); exit;	
		try 
		{
			$user = DB::query(Database::SELECT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'))
			->as_array();
			
			if(count($user) > 0)
			{
				foreach($user as $key=>$value)
				{
				
					$this->id_pep=Arr::get($value, 'ID_PEP');
					$this->id_org=Arr::get($value, 'ID_ORG');
					
				}
				
				$this->complete_login(Arr::flatten($user));
				
				return TRUE;
			}
		} catch (Exception $e) { 
		
		
		
		// Login failed
		return FALSE;
		}
	}
	
	/**
	 * Forces a user to be logged in, without specifying a password.
	 *
	 * @param   mixed    $username  Username
	 * @return  boolean
	 */
	public function force_login($username)
	{
		// Complete the login
		return $this->complete_login($username);
	}
	
	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   $username  Username
	 * @return  string
	 */
	public function password($username)
	{
		return Arr::get($this->_users, $username, FALSE);
	}
	
	/**
	 * Compare password with original (plain text). Works for current (logged in) user
	 *
	 * @param   string   $password  Password
	 * @return  boolean
	 */
	public function check_password($password)
	{
		$username = $this->get_user();
		
		if ($username === FALSE)
		{
			return FALSE;
		}
		
		return ($password === $this->password($username));
	}
	
} // End Auth City
