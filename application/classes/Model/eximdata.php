<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_eximdata extends Model
{
	
	//обновление времени начала и окончания работы для пиплов указанной организации 23.08.2022
	public function setWorkTime($timeStart, $timeEnd, $id_org)
	{
		$sql='update people p set
		p.workstart=\''.$timeStart.'\',
		p.workend=\''.$timeEnd.'\'
		where p.id_org='.$id_org;
		//echo Debug::vars('13', $sql); exit;
		Log::instance()->add(Log::NOTICE, 'Обновление времен работы для отчетов: '.$sql);
		$query = DB::query(Database::UPDATE, $sql)
		->execute(Database::instance('fb'));
		return;
	}
	
	
	public static function surnameSet ($header) // проверка наличия значения surname в массиве полученных данных
	{
		
		$breakFlag=false;
		foreach($header as $key=>$value)
			{
				if ($value == 'surname') $breakFlag = true; 
			}
			
		return $breakFlag;
			
	}
	

	public static function unique_org ($id_org) // проверка наличия id_org в базе данных. Вдруг такого id_org нет...
	{
				// Check if the id_org already exists in the database
	$sql='select ID_ORG from ORGANIZATION where ID_ORG='.$id_org;
	return $id_org == DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID_ORG');
	}
	
	
	public static function unique_card ($card) // проверка наличия номера карты. Вдруг такой идентификатор уже у кого-то есть. True - идентификатор есть в БД, false - идентификатора нет в БД
	{
				
	$sql='select ID_CARD from CARD where ID_CARD=\''.$card.'\'';
	//echo Debug::vars('20', $sql); exit;
	return !($card == DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID_CARD'));
	}
	
	public function send_file ($file)// скачать указанный файл в браузер
	{
		//https://habr.com/ru/post/151795/
		/* $file = $name;
		header ("Content-Type: application/force-download");
		header ("Accept-Ranges: bytes");
		header ("Content-Length: ".filesize($file));
		header ("Content-Disposition: attachment; filename=".basename($file));  
		readfile($file);
		return basename($file); */
		
		if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    readfile($file);
    exit;
  }
  
	}
	
	
	public  function getFileNameFromIdOrg ($id_org) // получение название организации по id_org
	{
				
	$sql='select NAME from ORGANIZATION where ID_org='.$id_org;
	//echo Debug::vars('20', $sql); exit;
	return iconv('windows-1251','UTF-8', DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('NAME'));

	}
	
	
	

	public function export ($id_org) // экспорт людей из указанной организации
	{
		$sql='select p.id_pep, p.surname, p.name, p.patronymic, p.note, c.id_card, c.id_cardtype from people p
			join card c on c.id_pep=p.id_pep
			where p.id_org='.$id_org;
			//echo Debug::vars('30', $sql); //exit;
		return DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	}
	
	public function editOrg ($id_org) // редактирование данных организации 23.08.2022
	{
		$sql='select p.id_pep, p.surname, p.name, p.patronymic, p.note, c.id_card, c.id_cardtype from people p
			join card c on c.id_pep=p.id_pep
			where p.id_org='.$id_org;
			//echo Debug::vars('30', $sql); exit;
		return DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	}
	
	

	public function getChild ($id_org = 1) // получить список дочерних организаций
	{
		if(is_null($id_org)) $id_org=1;
		$sql='select * from organization o 
		where o.id_parent='.$id_org;
		$sql='select o.id_org, o.name, count(o2.id_org) from organization o
		left join organization o2 on o2.id_parent=o.id_org
        where o.id_parent='.$id_org.'
        group by  o.id_org, o.name';
		return DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
	}

	public function countChild ($id_org = 1) // количество дочерних организаций
	{
		if(is_null($id_org)) $id_org=1;
		$sql='select count(*) from organization o 
		where o.id_parent='.$id_org;
		return DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
	}

	public function countPeopleInOrg () // подстчет количество людей в организации
	{
		$sql='select p.id_org, count(p.id_pep) from people p
		group by p.id_org';
		$temp= DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$resalt=array();
		foreach ($temp as $key=>$value)
		{
			$result[Arr::get($value, 'ID_ORG')]=Arr::get($value, 'COUNT');
		}
		return $result;
	}
	
	
	public function getNewIdPep()// получить ID_pep для вновь вставляемого пользователя
	{
		$sql='select gen_id(gen_people_id, 1) FROM RDB$DATABASE';
		return DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('GEN_ID');
	}


	public function insertPeople($list, $id_org)// добавить ФИО в таблицы PEOPLE и CARD
	{
		foreach ($list as $key=>$value)
		{
			
			if(!$this->checkCard(Arr::get($value, 5), Arr::get($value, 6)))  //проверка ключа на уникальность.
			{
				//echo Debug::vars('104', 'No card. Go to insert data.'); //exit;
				$new_id_pep=$this->getNewIdPep();
				//$id_org=226;
				$this->insertFIO($value, $new_id_pep, $id_org);// добавление в СКУД ФИО, Note для указанного id_pep
				$this->addCard($value, $new_id_pep);// присвоение номера карты указанном пользователю
				//$this->addSysnote();// добавление записи в поле SYSNOTE по результатам вставки пользователя.
			} else {
				
				echo Debug::vars('110', 'Card is present!', $value); exit;
			}
		}
		
		
		

	}

	public function checkCard($card, $cardType)// проверка наличия вновь добавляемого номера карты. TRUE - карта имеется в БД, FALSE - карты нет в БД
	{
		$sql='select id_card from card c
		where c.id_card=\''.$card.'\' and c.id_cardtype='.$cardType;
		//echo Debug::vars('123', $sql); //exit;
		return strtoupper($card) == DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('ID_CARD');
	}

	public function insertFIO($value, $id_pep, $id_org)// вставка ФИО в таблицу people
	{
		$sysnote='Old id_pep='.Arr::get($value, 0).', old card="'.Arr::get($value, 5).'", old cardtype='.Arr::get($value, 6);
		$sysnote='insert from eximdata';
		
		$sql='INSERT INTO PEOPLE (ID_PEP,ID_DB,ID_ORG,SURNAME,NAME,PATRONYMIC,NOTE,SYSNOTE)
		VALUES ('.$id_pep.',1,'.$id_org.',\''.Arr::get($value, 'surname', '').'\',\''.Arr::get($value, 'name', '').'\', \''.Arr::get($value, 'patronymic', '').'\',\''.Arr::get($value, 'note', '').'\', \''.$sysnote.'\')';
		//echo Debug::vars('135 insert people', $sql); exit;
		try
			{
			DB::query(Database::INSERT, iconv('UTF-8','windows-1251',$sql))
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
			
			}
		
		return true;
	}
	
	public function addCard($value, $id_pep)//добавление карты для указанного id_pep. тут может быть card_type_1 - идентификатора типа 1 (RFID) и card_type_4 - идентификатор типа 3 4 (ГРЗ)
	{
		$note='Old id_pep='.Arr::get($value, 0).', old card="'.Arr::get($value, 5).'", old cardtype='.Arr::get($value, 6);
		$note='Import';
		//echo Debug::vars('232', $value); exit;
		if(Arr::get($value, 'card_type_1') <> '')
		{
			$card=Arr::get($value, 'card_type_1');
			$cardtype=1;
			
			$sql='INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP,ID_ACCESSNAME,TIMESTART,TIMEEND,NOTE,STATUS,"ACTIVE",FLAG,ID_CARDTYPE) 
			VALUES (\''.$card.'\',1,'.$id_pep.',NULL,\'now\',\'now\',\'insert from eximdata\',0,1,0,'.$cardtype.')';
			try
			{
			DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::ERROR, '245 ошибка импорта RFID: '.$e->getMessage());
			}
		}
		
		
		if(Arr::get($value, 'card_type_4') <> '')
		{
			$card=Arr::get($value, 'card_type_4');
			$cardtype=4;
			
			$sql='INSERT INTO CARD (ID_CARD,ID_DB,ID_PEP,ID_ACCESSNAME,TIMESTART,TIMEEND,NOTE,STATUS,"ACTIVE",FLAG,ID_CARDTYPE) 
			VALUES (\''.$card.'\',1,'.$id_pep.',NULL,\'now\',\'now\',\'insert from eximdata\',0,1,0,'.$cardtype.')';
			try
			{
			DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
			} catch (Exception $e) {
				Log::instance()->add(Log::ERROR, '245 ошибка импорта ГРЗ: '.$e->getMessage());
			}
		}
		
		
		
	
		return true;
	}
	
	

	
}
	

