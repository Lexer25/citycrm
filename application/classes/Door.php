<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.08.2023 
Класс Door - точки прохода СКУД.
*/
class Door
{
	public $id;
	public $name;//название двери
	public $ctrl;//контрол как признак объединенеия с контроллером.
	public $reader;//номер канала. Для точек прохода он не может быть NULL
	public $is_active=0;//признак активности
	public $is_present;//признак наличия двери для указанного id. Если двери нет, то значение = NULL, если дверь есть, то значение True
	public $parent;//id родительского контроллера
	
	
	private $result_ok='OK';
	private $result_err='Err';
	public $result;//результат выполнение метода OK - выполнен правильно, Err - выполнен с ошибкой
	public $rdesc;// результат выполнения метода: набор данных или ошибок
	public $contactCount;// Количество контактов в организации
	
	
	/*
	9.09.2023
	получить список карт для указанной точки прохода
	*/
	public function getKeyList(){
		$res=array();
		$sql='select c.id_card from access a
			join ss_accessuser ssa on ssa.id_accessname=a.id_accessname
			join card c on c.id_pep=ssa.id_pep
			join people p on p.id_pep=ssa.id_pep
			where a.id_dev='.$this->id.'
			and c."ACTIVE">0
			 and (c.timestart<\'NOW\'  or (c.timeend is null))
            and ((c.timeend>\'NOW\') or (c.timeend is null))
			and p."ACTIVE">0
			and c.id_cardtype=1';
			
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array()
			;
		foreach($query as $key=>$value)
		{
			$res[Arr::get($value, 'ID_CARD')]=Arr::get($value, 'ID_CARD');
			
		}
		
		} catch (Exception $e) {

			Log::instance()->add(Log::DEBUG, 'Line 54 '. $e->getMessage());
		}
		
		return $res;
		
	}
	
	/*
	13.09.2023
	получить список карт для указанной точки прохода
	*/
	public function getCardIdxList(){
		$res=array();
		$sql='select cd.id_card from cardidx cd
		where cd.id_dev='.$this->id;
			
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array()
			;
		foreach($query as $key=>$value)
		{
			$res[Arr::get($value, 'ID_CARD')]=Arr::get($value, 'ID_CARD');
			
		}
		
		} catch (Exception $e) {

			Log::instance()->add(Log::DEBUG, 'Line 84 '. $e->getMessage());
		}
		
		return $res;
		
	}
	
	public function __construct($id=null)
    {
		if(!is_null($id))//если указан id, то создаю экземпляр класса с данными из БД.
	   {
			$this->id = $id;       
			$sql='select d.id_dev, d.name, d.id_devtype, d.id_ctrl, d.id_reader, d."ACTIVE" as is_active, d2.id_dev as parent from device d
				left join device d2 on d2.id_ctrl=d.id_ctrl and d2.id_reader is null
				where d.id_reader is not null
                and d.id_dev='.$this->id;
				//Log::instance()->add(Log::DEBUG, 'Line 70 '. $sql);
		try
		{
			$query = Arr::flatten(DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array()
			);
			$this->name=Arr::get($query, 'NAME');
			$this->ctrl=Arr::get($query, 'ID_CTRL');
			$this->reader=Arr::get($query, 'ID_READER');
			$this->is_active=Arr::get($query, 'IS_ACTIVE');
			$this->parent=Arr::get($query, 'PARENT');
			$this->is_present = (Arr::get($query, 'ID_DEV'))? TRUE : FALSE;
			$this->getContactCount();// подсчитал количество контактов в точке прохода
		
		} catch (Exception $e) {

			Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
		}
		 } else { // если не указан id, то создаю пустой экземпляр класса
			
	   }
	}
	
	/*
	26.08.2023
	Сохранение данных о новой двери
	
	
	Добавление двери для контроллера СКУД в БД СКУД.
	id_ctrl является общим элементом.
	
	
	*/
	
	public function save()
	{
		
	//echo Debug::vars('99', $id_dev,  $ctrl); exit;
		$id_dev = DB::query(Database::SELECT,
			'SELECT gen_id(GEN_DEV_ID, 1) FROM rdb$database')
			->execute(Database::instance('fb'))
			->get('GEN_ID');
			
			
		$sql=__('INSERT INTO DEVICE (ID_DEV, ID_DB, ID_SERVER, ID_DEVTYPE, ID_CTRL, ID_READER,  NAME, "VERSION", INTERVAL, DSS1, DSS2, "ACTIVE")
		VALUES (:ID_DEV, :ID_DB, :ID_SERVER, :ID_DEVTYPE, :ID_CTRL, :ID_READER, \':NAME\', :"VERSION", :INTERVAL, :DSS1, :DSS2, :"ACTIVE")', 
		array(
			':ID_DEV'=>$id_dev,
			':ID_DB'=>1,
			':ID_SERVER'=>1,
			':ID_DEVTYPE'=>$this->devtype,
			':ID_CTRL'=>$this->ctrl,
			':ID_READER'=>$this->reader,
			':NETADDR'=>null,
			':NAME'=>$this->name,
			':"VERSION"'=>1,
			':INTERVAL'=>0,
			':DSS1'=>0,
			':DSS2'=>0,
			':FLAG'=>NULL,
			':ID_PLAN'=>NULL,
			':POS_X'=>NULL,
			':POS_Y'=>NULL,
			':PSW'=>NULL,
			':"ACTIVE"'=>1,
			':CONFIG'=>NULL,
			':PARAM'=>NULL,
			':TAGNAME'=>NULL,
			':ID_GUIDE'=>NULL,
			':ID_OBJECT'=>NULL,
			':ID_PARENT'=>NULL
			));
		//echo Debug::vars('42', $this->ctrl, $sql); exit;	
		try
			{
				$query = DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
				->execute(Database::instance('fb'));
				$this->result=$this->result_ok;
				$this->rdesc='OK';
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 83 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->rdesc=$e->getMessage();				
			}
	}
	
	/*
	26.08.2023
	Изменение данных для указанного id
	*/
	public function update()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='UPDATE DEVICE
				SET NAME = \''.$this->name.'\',
				"ACTIVE" = '.$this->is_active.'
			WHERE (ID_DEV = '.$this->id.') AND (ID_DB = 1)';
		Log::instance()->add(Log::DEBUG, 'Line 125 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->rdesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 112 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->rdesc=$e->getMessage();				
			}
	}
	
	
	/*
	13.09.2023
	Обновление записи в cardidx для этой двери. После обновления будет выполнена загрузка карты в контроллер
	*/
	public function updateCard($id_card)
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='UPDATE CARDIDX
				SET ID_DB = 1,
				TIME_STAMP = \'NOW\',
				USERNAME = \'check\'
			WHERE (ID_CARD = \''.$id_card.'\') AND (ID_DEV = '.$this->id.')';
		//Log::instance()->add(Log::DEBUG, 'Line 125 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		//Log::instance()->add(Log::NOTICE, $sql);
		try
			{
			$query = DB::query(Database::UPDATE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->rdesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 112 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->rdesc=$e->getMessage();				
			}
	}
	
	
	/*
	16.09.2023
	Уделние карты из точки прохода. Карта записыватся на удаление в таблицук cardindev
	*/
	public function delCard($id_card)
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
			$sql='INSERT INTO CARDINDEV (ID_DB,ID_CARD,ID_DEV,OPERATION) 
				VALUES (1,\''.$id_card.'\','.$this->id.',2)';
		//Log::instance()->add(Log::DEBUG, 'Line 125 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		Log::instance()->add(Log::NOTICE, '266 '.$sql);
		try
			{
			$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->rdesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 112 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->rdesc=$e->getMessage();				
			}
	}
	
	
	/*
	26.08.2023
	Удаление данных для указанного id
	*/
	public function delete()
	{
		//echo Debug::vars('36', $this->name, $this->standalone);
		
		$sql='delete from ...';
		Log::instance()->add(Log::DEBUG, 'Line 72 '. $sql);
		//echo Debug::vars('65', $sql); exit;
		try
			{
			$query = DB::query(Database::DELETE, iconv('UTF-8', 'CP1251',$sql))
			->execute(Database::instance('fb'));
			
			$this->result=$this->result_ok;
			$this->rdesc=$this->id;
			
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, 'Line 139 '. $e->getMessage());
				$this->result=$this->result_err;
				$this->rdesc=$e->getMessage();				
			}
	}
	
	
	public function getContactCount()
	{
	        
	        $sql='select count(ssa.id_pep) from access ac
                join ss_accessuser ssa on ssa.id_accessname=ac.id_accessname
                join people p on p.id_pep=ssa.id_pep and p."ACTIVE">0
                where ac.id_dev='.$this->id;
	        //Log::instance()->add(Log::DEBUG, 'Line 70 '. $sql);
	        try
	        {
	            $query = DB::query(Database::SELECT, $sql)
	                ->execute(Database::instance('fb'))
	                ->get('COUNT')
	                ;
	           
	                $this->contactCount=$query;
	           
	            
	        } catch (Exception $e) {
	            
	            Log::instance()->add(Log::DEBUG, 'Line 40 '. $e->getMessage());
	            $this->$contactCount=-1;
	        }
	  
	        
	    
	}
	
}
