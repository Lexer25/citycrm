<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			//'dsn'		=> 'odbc:kalibr_001A',
			//'dsn'		=> 'odbc:kalibr',
			//'dsn'		=> 'odbc:vnii_local',
			'dsn'		=> 'odbc:SDUO',
			
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			//'password'	=> 'masterkey',
			'charset'   => 'windows-1251',
			)
		),
);

