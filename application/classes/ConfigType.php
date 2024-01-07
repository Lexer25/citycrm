<?php defined('SYSPATH') OR die('No direct access allowed.');

class ConfigType
{
	/*
	Версия программного пакета
	*/
	public static function getCityCrmVer()
	{
		return Arr::get(Kohana::$config->load('config_newcrm')->version, 'minor').'.'.Arr::get(Kohana::$config->load('config_newcrm')->version, 'major');
	}
	
	/*
	Список типов устройств
	*/
	public static function getDeviceTypeList1()
	{
		$query = DB::query(Database::SELECT,
			'select dt.id_devtype, dt.name, dt.standalone from DEVTYPE dt  order by dt.id_devtype')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}
	
	/*
	Список типов устройств
	*/
	public static function getDeviceTypeList()
	{
		$query = DB::query(Database::SELECT,
			'select dt.id_devtype, dt.name from DEVTYPE dt  order by dt.id_devtype')
			->execute(Database::instance('fb'))
			->as_array();
			foreach ($query as $key=>$value)
			{
				$res[Arr::get($value, 'ID_DEVTYPE')]= iconv('CP1251', 'UTF-8',Arr::get($value, 'NAME'));
			}
			
		return $res;
	}
	
	
	
	/*
	Список типов транспортных серверов
	*/
	public static function getTSTypeList()
	{
		$tsType= array(
			array('id'=>1,
					'type'=>'shelt',
					'name'=>'Транспортные сервер тип 1 и 2 Артонит',
					),
			array('id'=>2,
					'type'=>'bioitv',
					'name'=>'Биометрический сервер ITV',
					),
			array('id'=>3,
					'type'=>'parkrubic',
					'name'=>'Сервер парковочной системы Артонит ПаркОфис',
					),
			array('id'=>4,
					'type'=>'over',
					'name'=>'OVER полноростовые турникеты. Запрет повторного прохода в течении заданного времени.',
					),
			array('id'=>5,
					'type'=>'passitv',
					'name'=>'Интеграция passcontroller с ITV.',
					),
			array('id'=>6,
					'type'=>'over2',
					'name'=>'Over2',
					),
			array('id'=>8,
					'type'=>'bas',
					'name'=>'Bas-IP v12',
					),
			array('id'=>12,
					'type'=>'parkresident',
					'name'=>'Сервер парковочной системы Артонит Парк ЖК',
					),
					);
			
		return $tsType;
	}
	
	public static function getTSTypeList_2()
	{
		$tsType= array(
			array('id'=>1,
					'type'=>'shelt',
					'name'=>'Транспортные сервер тип 1 и 2 Артонит',
					),
			array('id'=>2,
					'type'=>'bioitv',
					'name'=>'Биометрический сервер ITV',
					),
			array('id'=>3,
					'type'=>'parkrubic',
					'name'=>'Сервер парковочной системы Артонит ПаркОфис',
					),
			array('id'=>4,
					'type'=>'over',
					'name'=>'OVER полноростовые турникеты. Запрет повторного прохода в течении заданного времени.',
					),
			array('id'=>5,
					'type'=>'passitv',
					'name'=>'Интеграция passcontroller с ITV.',
					),
			array('id'=>6,
					'type'=>'over2',
					'name'=>'Over2',
					),
			array('id'=>8,
					'type'=>'bas',
					'name'=>'Bas-IP v12',
					),
			array('id'=>12,
					'type'=>'parkresident',
					'name'=>'Сервер парковочной системы Артонит Парк ЖК',
					),
					);
			
		return $tsType;
	}
	
	/*
		Действия при удалении сотрудника
		0 - не удалять, делать ACTIVE=0
		1- удалять строку.
		
	*/
	public static function howDeletePeople()
	{
		$query = DB::query(Database::SELECT,
			'select s.value_int as data from setting s
			where s.name=\'howDeletePeople\'')
			->execute(Database::instance('fb'))
			->get('DATA');
		$query=Kohana::$config->load('guest')->get('howDeletePeople');
	
			
		return $query;
	}
	
	 /*
		Показывать удаленных сотрудников в общих списках
		
	*/
	public static function viewDeletePeopleOnForm()
	{
		$query = DB::query(Database::SELECT,
			'select s.value_int as data from setting s
			where s.name=\'viewDeletePeopleOnForm\'')
			->execute(Database::instance('fb'))
			->get('DATA');
		
			
		return $query;
	}
	
	 const CONST_VALUE = 'Значение константы';
}
