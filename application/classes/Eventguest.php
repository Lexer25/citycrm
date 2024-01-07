<?php defined('SYSPATH') OR die('No direct access allowed.');

/*

12.11.2023 Класс Eventguest сделан для понятного отражения событий в Гостях.

*/

class Eventguest
{
	public $id_event;//id события
	public $eventtime;//метка времени события
	public $eventtype;//код события события
	public $eventname;//названия события
	public $eventnameadd='history.no';//добавочное названия события
	public $evendesc;//дополнительные параметры события (в зависимости от типа события)
	public $evendescadd;//дополнительные параметры события (в зависимости от типа события)
	
	
	
	public function __construct($id_event)
	{
		
		$sql='select e.*, et.name as e_name, et.color as e_color from events e
			join eventtype et on et.id_eventtype=e.id_eventtype
			where e.id_event='.$id_event;
		//echo Debug::vars('25', $sql); exit;	
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$query=Arr::flatten($query);
		//echo Debug::vars('34',$id_event, $sql, $query); exit;	
			$this->id_event=Arr::get($query, 'ID_EVENT');
			$this->eventtime=Arr::get($query, 'DATETIME');
			$this->eventtype=Arr::get($query, 'ID_EVENTTYPE');
			$this->eventname=Arr::get($query, 'E_NAME');
			
		
		switch (Arr::get($query, 'ID_EVENTTYPE')){
				case 32:
					$this->evendesc=Arr::get($query, 'ESS1');
				
				break;
				case 40:
				
					$this->evendesc=Arr::get($query, 'ESS2');
					switch(Arr::get($query, 'NOTE')){
						case 'change_org': // изменена организация. В ESS2 находится id_org новой организации. Надо взять её название
							//$this->evendesc=Arr::get($query, 'ESS2');
							$this->eventnameadd='history.'.Arr::get($query, 'NOTE');
							$sql='select o.name from organization o
								where o.id_org='.Arr::get($query, 'ESS2');
							$query = DB::query(Database::SELECT, $sql)
							->execute(Database::instance('fb'))
							->get('NAME');	
							$this->evendesc=$query;	
						
						break;
						
						case 'add_accessname': // добавлена категория доступа. В ESS2 находится id_accessname. Надо взять её название
							//$this->evendesc=Arr::get($query, 'ESS2');
							$this->eventnameadd='history.'.Arr::get($query, 'NOTE');
							$sql='select an.name from accessname an
								where an.id_accessname='.Arr::get($query, 'ESS2');
							$query = DB::query(Database::SELECT, $sql)
							->execute(Database::instance('fb'))
							->get('NAME');	
							$this->evendesc=$query;	
						
						break;
						
						
						
					}
					
				
					
				break;
				case 17:
					$this->evendesc=Arr::get($query, 'ID_CARD');
				
				break;
				case 18:
					$this->evendesc=Arr::get($query, 'ID_CARD');
				
				break;
				case 50:
				case 65:	
					$sql='select d.name from device d
							where d.id_dev='.Arr::get($query, 'ID_DEV');
					$query = DB::query(Database::SELECT, $sql)
							->execute(Database::instance('fb'))
							->get('NAME');	
					$this->evendesc=$query;			
					
				
				break;
				
			
			
		}
		
		
		
	}
	
}
