<?php defined('SYSPATH') or die('No direct access allowed.');
return array(
		'contactListIdView'=> true,// показывать id_pep в листе контактов
		'contactListTabNumView'=> false,// показывать id_pep в листе контактов

	'version' => array(
		'minor' => '3',
		'major' => '3.vnii',
	),
	
	'test_mode' => array(
	),
	'all_card_eventtype'	=> array(46, 47, 48, 50, 65
	),
	'alarm_card_eventtype'	=> array(47, 48, 65
	),
	'app_base_dir'=>'C:\Program Files (x86)\CardSoft\DuoSE\Access',
	'app_name'=>array( 
		'Monitor.exe', 
		'Mancard.exe',
		'Report.exe',
		'Manuser.exe',
		'Config.exe',
		'Lostevent.exe',
		'ASCfg.exe',
		'SheltControl.exe',
		'DeviceConsole.exe',
	),
	'db_base_dir'=>'C:\Program Files\CardSoft\DuoSE\Access',
	'db_name'=> 'shieldpro.GDB',
	'module'=>array(
		'org'=>true,
		'contact'=>true,
		'card'=>true,
		'guest'=>true,
		'event'=>false,
		'queue'=>false,
		'user'=>false,
		'stat'=>false,
		'devices'=>false,
		),
	
		
	
);