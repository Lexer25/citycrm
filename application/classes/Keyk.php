<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс Keyk - инфомрация об идентификаторе 
*/

class Keyk
{
	
	public $id_pep;
	public $id_card;
	public $timeend;
	public $timestart;

	public $note;
	public $status;
	public $is_active;
	public $flag;
	public $id_cardtype;
	public $createdat;
	
	public $actionResult=0;// результат выполнения команд
	public $actionDesc=0;// пояснения к результату выполнения команд
	

	
	
	public function __construct($card = null)
	{
		if(!is_null($card)){
		$sql='select * from card c
			where c.id_card=\''.$card.'\'';
		try {
			$query= Arr::flatten(DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array()
		);
		} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
			}
		
		//echo Debug::vars('29', $sql, $query); exit;
		$this->id_card=$card;
		$this->id_pep=Arr::get($query, 'ID_PEP');
		$this->id_card=Arr::get($query, 'ID_CARD');
		$this->timestart=Arr::get($query, 'TIMESTART');
		$this->timeend=Arr::get($query, 'TIMEEND');
		$this->status=Arr::get($query, 'STATUS');
		$this->is_active=Arr::get($query, 'ACTIVE');
		$this->flag=Arr::get($query, 'FLAG');
		$this->id_cardtype=Arr::get($query, 'ID_CARDTYPE');
		$this->createdat=Arr::get($query, 'CREATEDAT'); 
		}
	}
	
	
	
	
	
	/*
	возвращает список идентификаторов указанного типа
	*/
	public function getTypeCardList($type) //возвращает список идентификаторов указанного типа
	{
		$sql='select  c.id_card from card c
		where c.id_pep='.$this->id_pep.'
		and c.id_cardtype='.$type;
		try {
		return DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'));
			} catch (Exception $e) {
				$this->actionResult=3;
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
			}	
	}
	
	/*
	проверяет наличие указанного идентификатора указанного типа.
	*/
	public function check($type) //проверяет наличие указанного идентификатора указанного типа.
	{
		$sql='select c.id_pep from card c
            where c.id_card=\''.$this->id_card.'\'
            and c.id_cardtype='.$type;
			//echo Debug::vars('87', $sql); exit;
			try{
		return DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->get('ID_PEP');
		} catch (Exception $e) {
			$this->actionResult=3;
			Log::instance()->add(Log::DEBUG, $e->getMessage());
			$this->actionDesc=$e->getMessage();
		}	
				
	}
	
	
	
	/*
	28.12.2023 функция сохранения карты.
	*/
	public function addRfid() //функция сохранения карты.
	{
		
		$sql=__('INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP, TIMESTART,TIMEEND,STATUS,"ACTIVE",FLAG,ID_CARDTYPE)
					VALUES (
					\':id_card\'
					,:id_db
					,:id_pep
					, \':timestart\'
					,\':timeend\'
					
					,:status
					,:is_active
					,:flag
					,:id_cardtype
					)', array
					(
					':id_card'=>$this->id_card
					,':id_db'=>1
					,':id_pep'=>$this->id_pep
					,':timestart'=>$this->timestart
					,':timeend'=>$this->timeend
					,'note'=>$this->note
					,':status'=>0
					,':is_active'=>1
					,':flag'=>1
					,':id_cardtype'=>1
					));
		//echo Debug::vars('161', $this, $sql); exit; 
		try {
				$query = DB::query(Database::INSERT, $sql)
					->execute(Database::instance('fb'));
				$this->actionResult=0;
				//$this->actionDesc=__('guest.addRfidOk', array(':id_card'=>$this->id_card));
			
			} catch (Exception $e) {
				
				$this->actionResult=3;
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				$this->actionDesc=$e->getMessage();
				
				
				
			}	
				
	}
	
	
	/*
	получить список идентификаторов для указанного пипла
	*/
	
	public function getListByPeople($id_pep, $cardType)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM card WHERE id_pep = :id_pep and id_cardtype=:cardType')
			->param(':id_pep', $id_pep)
			->param(':cardType', $cardType)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	/*
		Удаление указанного идентификатора
	*/
	
	public function delCard($id_pep, $cardType)
	{
		
		
		return true;
	}
	
	
	/*
		Удаление всех идентификаторов для указанного id_pep
	*/
	
	public function delCardForPeople($id_pep)
	{
			$sql='delete from card c
				where c.id_pep='. $id_pep;
			try {
				DB::query(Database::DELETE,$sql)	
				->execute(Database::instance('fb'));
				return 0;	
				
			} catch (Exception $e) {
				Log::instance()->add(Log::DEBUG, $e->getMessage());
				
				return 3;
			}
	}
	
}
