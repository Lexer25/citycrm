<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			//'dsn'		=> 'odbc:SDUO',
			'dsn'		=> 'odbc:vnii_local',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			'charset'   => 'windows-1251',
			)
		),
);

