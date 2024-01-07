<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	//'fb' => Arr::get(Arr::get(Arr::get(Kohana::$config->load('skud'),'skud_list'), Session::instance()->get('skud_number')), 'fb_connection'),
	// в Рубитехе выбор базы данных фиксирован.
	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			'dsn'		=> 'odbc:vnii_local',
			//'dsn'		=> 'odbc:parsec',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			//'password'	=> 'masterkey',
			'charset'   => 'windows-1251',
			)
		),

	'cdb' => array(
		'type'       => 'pdo',
		'connection' => array(
        'dsn'        => 'sqlite:/path/to/file.sqlite',
        'dsn'        => 'sqlite:C:\\xampp\\htdocs\\citycrm\\application\\classes\\Kohana\\Config\\config.sqlite',
        'persistent' => FALSE,
    )),

	

	
);

