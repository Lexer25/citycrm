<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Eventlog extends Model
{
	

	
	public function getCountEvent ($hour)
	{
		$date_calc=date('d.m.Y H:i:s', mktime(date('H'),date('i'), date('s'),date('m'),date('d'), date('Y'))-$hour*3600);
		$config = Kohana::$config->load('config_newcrm');
		$test_mode_id = $config->get('test_mode');//получение списка контроллеров, которые не надо обрабатывать
		$all_card_eventtype=implode(",",$config->get('all_card_eventtype'));
		$sql = 'select count(events.id_event)
			 FROM events
			where events.datetime >\''.$date_calc.'\' 
			and events.datetime<\'now\' 
			and events.id_eventtype in ('.$all_card_eventtype.')';
	//Kohana::$log->add(Kohana::ERROR, $sql);
		//$query = DB::query(Database::SELECT, $sql);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb')) 
			->current();
		return $query['COUNT'];
		
	}
	
	public function getCountAdminAlarm($hour)//подсчет количества (!) авариных событий. Удалить после отладки 10.02.2014
	{	
		$config = Kohana::$config->load('config_newcrm');
		$test_mode_id = $config->get('test_mode');//получение списка контроллеров, которые не надо обрабатывать
		$alarm_card_eventtype=implode(",",$config->get('alarm_card_eventtype'));
		$date_calc=date('d.m.Y H:i:s', mktime(date('H'),date('i'), date('s'),date('m'),date('d'), date('Y'))-$hour*3600);
		$sql = 'select
        events.id_event,
        events.id_eventtype,
        events.id_dev,
        events.datetime,
        events.id_card,
        events.note,
        events.id_pep,
        events.ess1,
        events.ess2,
        eventtype.name, 
        eventtype.color,
        device.name as devname,
        card.id_card as card_id_card,
        card.id_accessname,
        access.id_dev as access_id_dev,
        cardidx.load_time,
        cardidx.load_result,
        cardidx.devidx,
		people.tabnum,
		people.id_pep,
		organization.name as orgname,
		\'-1\' as analytic1, 
		\'-1\' as analytic2
     FROM events
    join eventtype on (events.id_eventtype=eventtype.id_eventtype)
    left join device on device.id_dev=events.id_dev
    left join card on card.id_card=events.id_card
    left join access on (access.id_accessname=card.id_accessname and access.id_dev=events.id_dev)
    left join cardidx on (cardidx.id_card=events.id_card and cardidx.id_dev=events.id_dev)
	left join people on (people.id_pep=card.id_pep)
	left join organization on (organization.id_org=events.ess2)
    where events.datetime >\''.$date_calc.'\' 
	and events.datetime<\'now\'
	and events.id_eventtype in ('.$alarm_card_eventtype.')
	order by events.id_event desc';
	
	$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		$event1=$query->as_array();// получил массив событий.
		
		$need_list=array();
		foreach ($event1 as &$value){
			$value['ANALYTIC2']='eventlog.analityc_4';
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==50)  {//Проход действительно разрешен
				$value['ANALYTIC1']="0"; 
				$value['ANALYTIC2']='eventlog.analityc_0';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==50)  {//Ошибка! Пустила, хотя не должна!
				$value['ANALYTIC1']="1"; 
				$value['ANALYTIC2']='eventlog.analityc_1';
				$need_list[]=$value;}// добавляю элемент в массив для рассчета количества ошибочных элементов.
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Ошибка! Не пустила, ходя карта имеет право ходить.
				$value['ANALYTIC1']="2"; 
				$value['ANALYTIC2']='eventlog.analityc_2';
				$need_list[]=$value;}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="3"; 
				$value['ANALYTIC2']='eventlog.analityc_3';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			if (!isset($value['CARD_ID_DCARD']) and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			}
		
		
		return count($need_list);
		
	}
	
	public function getEventAlarmList($page = 1, $perpage = 10, $hour, $filters = false)//получение списка аварийных событий
	{
// analityc1 - код результата анализа
// analityc2 - описание результата анализа, выводимый на экрана
        $config       = Kohana::$config->load('config_newcrm'); //подключение файла конфигурации
        $event_list   = implode(",", $config->get('all_card_eventtype'));
        $test_mode_id = $config->get('test_mode'); //получение списка контроллеров, которые не надо обрабатывать
        $date_calc    = date('d.m.Y H:i:s', mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')) - $hour * 3600);
		$sql = 'select 
        events.id_event,
        events.id_eventtype,
        events.id_dev,
        events.datetime as datetime,
        events.id_card,
        events.note,
        events.id_pep,
        events.ess1,
        events.ess2,
        eventtype.name, 
        eventtype.color,
        device.name as devname,
        card.id_card as card_id_card,
        card.id_accessname,
        access.id_dev as access_id_dev,
        cardidx.load_time as load_time,
        cardidx.load_result,
        cardidx.devidx,
		people.tabnum,
		people.id_pep,
		organization.name as orgname,
		\'-1\' as analytic1, 
		\'-1\' as analytic2
     FROM events
    join eventtype on (events.id_eventtype=eventtype.id_eventtype)
    left join device on device.id_dev=events.id_dev
    left join card on card.id_card=events.id_card
    left join access on (access.id_accessname=card.id_accessname and access.id_dev=events.id_dev)
    left join cardidx on (cardidx.id_card=events.id_card and cardidx.id_dev=events.id_dev)
	left join people on (people.id_pep=card.id_pep)
	left join organization on (organization.id_org=events.ess2)
    where events.datetime >\''.$date_calc.'\'
	and events.datetime<\'now\'
    and eventtype.id_eventtype in (50, 65, 46, 47, 48, 65, 47, 48)';

    if ($filters) {
        if (isset($filters['device']) and sizeof($filters['device']) > 0) {
            $sql .= ' AND device.id_dev IN (' . implode(', ', $filters['device']) . ')';
        }
    }

	$sql .=' order by events.id_event desc';

//    echo Kohana::Debug($sql);

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));// получил массив событий.
		
		$event1=$query->as_array();
		$need_list=array();// массив с результатами обработки
		foreach ($event1 as &$value){
			$value['ANALYTIC2']='eventlog.analityc_4';
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==50)  {//Проход действительно разрешен
				$value['ANALYTIC1']="0"; 
				$value['ANALYTIC2']='eventlog.analityc_0';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and (isset($value['ACCESS_ID_DEV']))  and $value['ID_EVENTTYPE']==50)  {//Ошибка! Пустила, хотя не должна!
				$value['ANALYTIC1']="1"; 
				$value['ANALYTIC2']='eventlog.analityc_1';
				$need_list[]=$value;}//добавляю элемент в массив для рассчета количества ошибочных элементов.
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==65 and (strtotime($value['DATETIME'])-strtotime($value['LOAD_TIME'])>0))  {//Ошибка! Не пустила, ходя карта имеет право ходить.
				$value['ANALYTIC1']="2"; 
				$value['ANALYTIC2']='eventlog.analityc_2';
				$need_list[]=$value;}
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==65 and (strtotime($value['DATETIME'])-strtotime($value['LOAD_TIME'])<0))  {//Правильно! Не пустила, т.к. на момент прохода карта не была загружена в контроллер.
				$value['ANALYTIC1']="5";
				$value['ANALYTIC2']='eventlog.analityc_5';
				$need_list[]=$value;}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="3"; 
				$value['ANALYTIC2']='eventlog.analityc_3';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			if (!isset($value['CARD_ID_DCARD']) and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			}
		
		$err_event_count=count($need_list);
		if(($err_event_count)>0){
		$result1 = array_chunk ($need_list, $perpage, TRUE);//разбиваю массив на части
		//echo Kohana::Debug($result1, 'count='.$err_event_count);
		$result=$result1[$page-1];//вывожу только нужную часть массива
		}
		else {
		$result=array();
		}
		$a['alarm_event_list']=$result;
		$a['alarm_event_count']=$err_event_count;		
		return $a;
		
	}

    public function getDeviceList() {
        $sql = 'SELECT id_dev, name FROM device where id_reader is not null order by name';

        //Kohana::$log->add(Kohana::ERROR, $sql);
        $query = DB::query(Database::SELECT, $sql)
                   ->execute(Database::instance('fb'));

        $results = $query->as_array();

        return $results;
    }
	
	public function getEventList($page = 1, $perpage = 10, $hour, $filters = false)//получение списка  событий
	{

		$config = Kohana::$config->load('config_newcrm');//подключение файла конфигурации
		$test_mode_id = $config->get('test_mode');//получение списка контроллеров, которые не надо обрабатывать
		$date_calc=date('d.m.Y H:i:s', mktime(date('H'),date('i'), date('s'),date('m'),date('d'), date('Y'))-$hour*3600);
		$sql = ' select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
        events.id_event,
        events.id_eventtype,
        events.id_dev,
        events.datetime,
        events.id_card,
        events.note,
        events.id_pep,
        events.ess1,
        events.ess2,
        eventtype.name, 
        eventtype.color,
        device.name as devname,
        card.id_card as card_id_card,
        card.id_accessname,
        access.id_dev as access_id_dev,
        cardidx.load_time,
        cardidx.load_result,
        cardidx.devidx,
		people.tabnum,
		people.id_pep,
		organization.name as orgname,
		\'-1\' as analytic1, 
		\'-1\' as analytic2
     FROM events
    join eventtype on (events.id_eventtype=eventtype.id_eventtype)
    left join device on device.id_dev=events.id_dev
    left join card on card.id_card=events.id_card
    left join access on (access.id_accessname=card.id_accessname and access.id_dev=events.id_dev)
    left join cardidx on (cardidx.id_card=events.id_card and cardidx.id_dev=events.id_dev)
	left join people on (people.id_pep=card.id_pep)
	left join organization on (organization.id_org=events.ess2)
    where events.datetime >\''.$date_calc.'\'
	and events.datetime<\'now\'
    and events.id_eventtype in (50, 65, 46, 47, 48, 65, 47, 48)';

            if ($filters) {
                if (isset($filters['device']) and sizeof($filters['device']) > 0) {
                    $sql .= ' AND device.id_dev IN (' . implode(', ', $filters['device']) . ')';
                }
            }

	$sql .=' order by events.id_event desc';
	
		//Kohana::$log->add(Kohana::ERROR, $sql);
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));// получил массив событий.
		
		$event1=$query->as_array();
		$need_list=array();
		foreach ($event1 as &$value){
			$value['ANALYTIC2']='eventlog.analityc_4';
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==50)  {//Проход действительно разрешен
				$value['ANALYTIC1']="0"; 
				$value['ANALYTIC2']='eventlog.analityc_0';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==50)  {//Ошибка! Пустила, хотя не должна!
				$value['ANALYTIC1']="1"; 
				$value['ANALYTIC2']='eventlog.analityc_1';
				$need_list[]=$value;}
			if ($value['ACCESS_ID_DEV'] == $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Ошибка! Не пустила, ходя карта имеет право ходить.
				$value['ANALYTIC1']="2"; 
				$value['ANALYTIC2']='eventlog.analityc_2';
				$need_list[]=$value;}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="3"; 
				$value['ANALYTIC2']='eventlog.analityc_3';}
			if ($value['ACCESS_ID_DEV'] != $value['ID_DEV'] and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			if (!isset($value['CARD_ID_DCARD']) and $value['ID_EVENTTYPE']==65)  {//Правильно не пустила!
				$value['ANALYTIC1']="4"; 
				$value['ANALYTIC2']='eventlog.analityc_4';}
			}
		return $event1;
		
	}
	
	
	public function getCountAdmin($filter)
	{
		
		
		$sql = 'SELECT COUNT (*) FROM card WHERE id_accessname IS NOT null' . ($filter ? " AND id_card containing '$filter'" : '');
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			
		return $query['COUNT'];
	}
	
	public function getListAdmin($page = 1, $perpage = 10, $filter)
	{
		$sql = 'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					c.id_card,
					c.id_db,
					c.id_pep,
					c.id_accessname,
					c.timestart,
					c.timeend,
					c.note,
					c.status,
					c."ACTIVE",
					c.flag,
					p.id_org,
					p.name,
					p.surname,
					o.name AS cname
				FROM organization o
					INNER JOIN people p ON (o.id_org = p.id_org)
					INNER JOIN card c ON (p.id_pep = c.id_pep)
				WHERE
					c.id_accessname IS NOT null' .
				($filter ? " AND c.id_card containing '$filter' " : '') . '
				ORDER BY
					c.id_card';

			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'));
				
			return $query->as_array();
	}
	
	public function getListUser($user, $page = 1, $perpage = 10, $filter)
	{
		$sql = '
				SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					c.id_card,
					c.id_db,
					c.id_pep,
					c.id_accessname,
					c.timestart,
					c.timeend,
					c.note,
					c.status,
					c."ACTIVE",
					c.flag,
					p.id_org,
					p.name,
					p.surname,
					o.name AS cname
				FROM
    				usersgroups ug
    				INNER JOIN organizationgroup og ON (ug.id_group = og.id_group)
    				INNER JOIN organization o ON (og.id_org = o.id_org)
    				INNER JOIN people p ON p.id_org = o.id_org
    				INNER JOIN card c ON c.id_pep = p.id_pep
    			WHERE
    				ug.id_user = ' . $user . '
    				AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' . ($filter ? "AND (p.name containing '$filter' OR p.surname containing '$filter')" : '') . '
				ORDER BY
					c.id_card';

		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	public function getCountUser($user, $filter)
	{
		$sql = '
				SELECT 
    				c.id_card
    			FROM
    				usersgroups ug
    				INNER JOIN organizationgroup og ON (ug.id_group = og.id_group)
    				INNER JOIN organization o ON (og.id_org = o.id_org)
    				INNER JOIN people p ON p.id_org = o.id_org
    				INNER JOIN card c ON c.id_pep = p.id_pep 
    			WHERE
    				ug.id_user = ' . $user . '
    				AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' . ($filter ? "AND (p.name containing '$filter' OR p.surname containing '$filter')" : '') . '
				ORDER BY
					c.id_card';

		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->count();
			}
	
	public function getCountByPeople($id)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT COUNT (*) FROM card WHERE id_pep = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'))
			->current();
		
		return $query['COUNT'];
	}

	public function getCard($id)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM card WHERE id_card = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));

		if ($query->count() == 0) return false;
		return $query->current();
	} 
	
	public function getCardByPeople($id)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM card WHERE id_pep = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
			
		if ($query->count() == 0) return false;
		return $query->current();
	}
	
	public function getListByPeople($id)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM card WHERE id_pep = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}

	public function save($idpeople, $idcard, $datestart, $dateend, $useenddate, $state, $isactive, $idaccess)
	{
		DB::query(Database::INSERT, 
			'INSERT INTO card (id_pep, id_card, timestart, timeend, flag, status, "ACTIVE", id_accessname) ' .
			'VALUES (:people, :card, :tstart, :tend, :flag, :status, :active, :access)')
			->parameters(array(
				':people'		=> $idpeople,
				':card'			=> $idcard,
				':tstart'		=> $datestart,
				':tend'			=> $dateend == '' ? null : $dateend,
				':flag'			=> $useenddate,
				':status'		=> $state,
				':active'		=> $isactive,
				':access'		=> $idaccess
			))
			->execute(Database::instance('fb'));
	}
	
	public function update($idpeople, $idcard, $datestart, $dateend, $useenddate, $cardstate, $isactive, $idaccess)
	{
		DB::query(Database::UPDATE, 
			'UPDATE card SET id_pep = :people, timestart = :start, timeend = :end, flag = :flag, status = :status, "ACTIVE" = :active, id_accessname = :access WHERE id_card = :card')
			->parameters(array(
				':people' 	=> $idpeople,
				':start'	=> $datestart,
				':end'		=> $dateend == '' ? null : $dateend,
				':flag'		=> $useenddate,
				':status'	=> $cardstate,
				':active'	=> $isactive,
				':access'	=> $idaccess,
				':card'		=> $idcard))
			->execute(Database::instance('fb'));
	}
	
	public function getLoads($id)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT l.id_card, l.load_time, l.load_result, d.name ' .
			'FROM cardidx l INNER JOIN device d ON l.id_dev = d.id_dev ' .
			"WHERE id_card = '$id' " .
			'ORDER BY l.load_time DESC')
			->execute(Database::instance('fb')); 
		
		return $query->as_array();
	}
	
	public function delete($id)
	{
		$query = DB::query(Database::DELETE, 
			'DELETE FROM card WHERE id_card = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	public function toggleCard($id, $newvalue)
	{
		DB::query(Database::UPDATE, 
			'UPDATE card SET "ACTIVE" = :active WHERE id_card = :id')
			->parameters(array(
			':id'		=> $id,
			':active'	=> $newvalue))
			->execute(Database::instance('fb'));
		
	}
	
}