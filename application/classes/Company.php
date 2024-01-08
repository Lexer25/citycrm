<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Company - свойства и методы организации
*/

class Company
{
	
	public $name;
	public $id_org;// id_org организации
	public $id_parent;//id родительской организации
	public $divcode;//код подразделения
	public $time_stamp;//метка времени последнего действия
	
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
	
	
	/*
	7.01.2024 
	Добавление новой организации
	*/
	
	public function addOrg()
	{
		//получаею очередной id_org
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_org_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'))
			->get('GEN_ID');
		
		$this->id_org=$query;//получил id вставляемой организации
		$sql=__('INSERT INTO organization (id_org, name, id_parent) VALUES (:id, \':name\', :parent)',
			array(
				':id'		=> $this->id_org,
				':name'		=> $this->name,
				':parent'	=>  $this->id_parent,
				));
		//echo Debug::vars('80', $sql); exit;
		try{
			//echo Debug::vars('490', $parent,($parent == '')? null : $parent,  $sql); exit;
			$query=DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			return 0;
			
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());
			return 3;
		}	
	}
	
	
	
	
	/*
	8.01.2024 
		Добавление новой организации с указанынм divcode в id_parent родительскую организацию
		организация будет добавлена в указанный id_parent
	*/
	
	public function addOrgWithDivcodeInParentId()
	{
		//проверка на уникальность divcode
		if(!$this->checkDivCode($this->divcode)) return 1;//divcode уже имеется

		//получаею очередной id_org
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_org_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'))
			->get('GEN_ID');
		
		$this->id_org=$query;//получил id вставляемой организации
		$sql=__('INSERT INTO organization (id_org, name, id_parent, divcode) VALUES (:id, \':name\', :id_parent, \':divcode\')',
			array(
				':id'		=> $this->id_org,
				':name'		=> $this->name,
				':id_parent'	=>  $this->id_parent,
				':divcode'	=>  $this->divcode,
				));
		//echo Debug::vars('80', $sql); exit;
		try{
			//echo Debug::vars('490', $parent,($parent == '')? null : $parent,  $sql); exit;
			$query=DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			return 0;
			
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());
			return 3;
		}	
	}
	
	/*
	8.01.2024 
		Добавление новой организации с указанынм divcode в родительскую организацию,  для которой указан divcode
		
	*/
	
	public function addOrgWithDivcodeInParentDivcode($divcode_parent)
	{
		//проверка на уникальность divcode
		if(!$this->checkDivCode($this->divcode)) return 1;//выход, divcode уже имеется
		if($this->checkDivCode($divcode_parent)) return 2;//выход, divcode родительской организации отсутвует
		
		$this->id_parent=$this->getIdOnDivCode($divcode_parent);
		
		$this->addOrgWithDivcodeInParentId();
	}
	
	
	/*
		8.01.2024
		поиск id_org по указанному divcode
		
	*/
	public function getIdOnDivCode($divcode)
	{
		$sql = 'select first 1 o.id_org from organization o
		where o.divcode=\''.$divcode.'\'';
		
		try {		
		
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('ID_ORG');
		
			return $query;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	/*
		8.01.2024
		проверка: есть ли divcode?
		Если divcode не найдет, то ответ TRUE
		Если найдет - ответ FALSE
		
	*/
	public function checkDivCode($divcode)
	{
		$sql = 'select o.id_org from organization o
		where o.divcode=\''.$divcode.'\'';
		
		try {		
		
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->count();
			if($query == 0) return TRUE;
			return FALSE;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	
	
	
	

	/*
	7.01.2024 
	Обновление  организации
	*/
	
	public function updateOrg()
	{
		
		$sql=__('UPDATE organization SET name = \':name\', id_parent = :parent WHERE id_org = :id',array(
				':name' 	=> $this->name,
				':parent'	=> $this->id_parent,
				':id'		=> $this->id_org)
				);
		//echo Debug::vars('118', $this, $sql); exit;		
			try{
			
			$query=DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			return 0;
			
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());
			return 3;
		}	
	}
	
	/*
	7.01.2024 
		Обновление  parent организации
	*/
	
	public function setIdParentOrg()
	{
		
		$sql=__('UPDATE organization SET id_parent = :parent WHERE id_org = :id',array(
				':parent'	=> $this->id_parent,
				':id'		=> $this->id_org)
				);
		//echo Debug::vars('118', $this, $sql); exit;		
			try{
			
			$query=DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('fb'));
			return 0;
			
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());
			return 3;
		}	
	}
	
	
	/*
	7.01.2024
		замена организации для всех пиплов
	*/
	
	public function setNewOrgForPeople($id_org_new_parent)
	{
		//перенос гостя в Архив
		$sql = 'update people p
				set p.id_org='.$id_org_new_parent.'
				where p.id_org='.$this->id_org;
		//echo Debug::vars('145', $sql); exit;
		try {		
		
			$query = DB::query(Database::UPDATE, $sql)
				->execute(Database::instance('fb'));
			return 0;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	/*
	7.01.2024
		Получить список дочерних организаций
	*/
	
	public function getChildIdOrg()
	{
		
		$sql = 'select o.id_org from organization o
			where o.id_parent='.$this->id_org;
		//echo Debug::vars('190',$sql); exit;
		try {		
		
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
			return $query;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	/*
	8.01.2024
		Получить список контактов, входящих в организацию
	*/
	
	public function getChildId_pepList()
	{
		
		$sql = 'select * from people p
where p.id_org='.$this->id_org;
		//echo Debug::vars('190',$sql); exit;
		try {		
		
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
			return $query;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	
	/*
	8.01.2024
		Удалить организацию из таблицы по её id_org
	*/
	
	public function delOrgId()
	{
		
		$sql = 'delete from organization o
				where o.id_org='.$this->id_org;
		//echo Debug::vars('190',$sql); exit;
		try {		
		
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			return $query;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	/*
	8.01.2024
		Удалить организацию из таблицы по её divcode
	*/
	
	public function delOrgDivcode($divcode)
	{
		
		$sql = 'delete from organization o
				where o.divcode=\''.$divcode.'\'';
		//echo Debug::vars('190',$sql); exit;
		try {		
		
			$query = DB::query(Database::DELETE, $sql)
				->execute(Database::instance('fb'));
			return $query;	
				
					} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			
		}	
		
	}
	
	
	
	
	
	
}
