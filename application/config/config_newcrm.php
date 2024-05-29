<?php defined('SYSPATH') or die('No direct access allowed.');
return array(

		'view_settings'=>false,// показывать ли пункт Настройка в хидере?
		//'iphost'=>'10.110.161.2',// тут надо указать IP адрес сервера СКУД
		'iphost'=>'127.0.0.1',// тут надо указать IP адрес сервера СКУД
		'contactListIdView'=> true,// показывать id_pep в листе контактов
		'contactListTabNumView'=> false,// показывать id_pep в листе контактов

	'version' => array(
		'minor' => '3',
		'major' => '3.vnii',
	),
	

	'module'=>array(
		'org'=>true,
		'contact'=>true,
		'card'=>true,
		'guest'=>false,
		'event'=>false,
		'queue'=>false,
		'user'=>false,
		'stat'=>false,
		'devices'=>false,
		'doors'=>true,
		),
	
		
	
);