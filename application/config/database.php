<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(

	'fb' => array(
		'type'			=> 'pdo',
		'connection'	=> array(
			//'dsn'		=> 'odbc:parsec',
			//'dsn'		=> 'odbc:kalibr_001A',
			//'dsn'		=> 'odbc:kalibr',
			'dsn'		=> 'odbc:vnii_local',
			//'dsn'		=> 'odbc:wg',
			'username'	=> 'SYSDBA',
			'password'	=> 'temp',
			//'password'	=> 'masterkey',
			'charset'   => 'windows-1251',
			)
		),
);

