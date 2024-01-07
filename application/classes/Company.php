<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Company - свойства и методы организации
*/

class Company
{
	public $idOrgGuest=2;//id_org организации, используемой в качестве гостевой
	private $idOrgGuestParamName='idOrgGuest';//название параметра в таблице setting БД СКУД
	
	public $name;
	public $id_org;// id_org организации
	public $id_parent;//id родительской организации
	public $divcode;//код подразделения
	public $time_stamp;//метка времени последнего действия
	
	
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	
	
	public function __construct($id_org = null)
	{
		if(!is_null($id_org)){
			
		$sql='select o.id_org, o.name, o.id_parent, o.flag, o.divcode, o.time_stamp from organization  o
		where o.id_org='.$id_org;
		
		$query= Arr::flatten(DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
				);
		$this->id_org=$id_org;
		$this->name=Arr::get($query, 'NAME');
		$this->id_parent=Arr::get($query, 'ID_PARENT');
		$this->flag=Arr::get($query, 'FLAG');
		$this->divcode=Arr::get($query, 'DIVCODE');
		$this->time_stamp=Arr::get($query, 'TIME_STAMP');
		try {
			$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
			//$this->actionDesc=__('', array(':'=>''));
				
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			HTTP::redirect('errorpage?err=37'.Text::limit_chars($e->getMessage(), 50));
			$this->actionResult=3;
			//$this->actionDesc=__('', array(':'=>''));
		}	
		
		
		}
	}
	
	
}
