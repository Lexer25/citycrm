<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.04.2024 Класс для работы с категориями доступа.
*/
class Access
{
	public $id_accessname;
	public $name;
	public $time_stamp;
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	public $dataResult=0;// результат выполнения команд
	
	public function __construct($id_org = null)
	{
		if(!is_null($id_org)){
			
		$sql='select an.id_accessname, an.id_db, an.name, an.time_stamp from ACCESSNAME  an
where an.id_accessname='.$id_org;
		
		$query= Arr::flatten(DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
				);
		$this->id_accessname=Arr::get($query, 'ID_ACCESSNAME');
		$this->name=Arr::get($query, 'NAME');
		$this->time_stamp=Arr::get($query, 'TIME_STAMP');
		try {
			$query = DB::query(Database::INSERT, $sql)
				->execute(Database::instance('fb'));
			$this->actionResult=0;
					
		} catch (Exception $e) {
			$this->actionResult=3;
			$this->actionDesc=$e->getMessage();
		}	
		
		
		} else {
			$this->id_org=0;
		}
	}
	
	
	/*
	25.04.2024 
	получить массив точек прохода, вхощяих в указанную категорию доступа
	*/
	public function  getDoorIdList()
	{
		$result=array();
		$sql='select distinct a.id_dev from access a
			where a.id_accessname='.$this->id_accessname;
		
		try {
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				foreach($query as $key=>$value){
					
					$result[]= Arr::get($value,'ID_DEV'); 
				}
			$this->actionResult=0;
			$this->dataResult=$result;
			return 0;
					
		} catch (Exception $e) {
			$this->actionResult=3;
			$this->actionDesc=$e->getMessage();
			return 3;
		}	
		
	}
	
	
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM accessname order by NAME')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}
	
}
