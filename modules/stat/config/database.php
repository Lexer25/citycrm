<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	
	'cdb' => array(
		'type'       => 'pdo',
		'connection' => array(
       		'dsn'        => 'sqlite:'.MODPATH .'\\tuner\\classes\\Kohana\\Config\\config.sqlite',
			'persistent' => FALSE,
    )),

	

	
);

