<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Queue extends Model //
{
	
	
	public function getCountQueue($filter) //Количество очередей (равно количеству контроллеров, в которые надо грузить карты
	{
		$sql = 'select count(distinct cardindev.id_dev) from cardindev' . ($filter ? " join device on device.id_dev=cardindev.id_dev
		where device.name containing '$filter'" : '');
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			
		return $query['COUNT'];
	}

	public function getCountList($filter) //Количество строк для записи в контроллеры. Выводится весь список.
	{
		$sql = iconv('UTF-8', 'CP1251', 'select count(cardindev.id_cardindev)
                    from cardindev
                    join device d1 on device.id_dev=cardindev.id_dev
					join device d2 on (d1.id_ctrl=d2.id_ctrl and d2.id_reader is null)
                    join cardidx on cardidx.id_cardindev=cardindev.id_cardindev'.
					($filter ? ' where 
					d1.name containing \''.$filter.'\' 
					or d2.name containing \''.$filter.'\' 
					or cardidx.load_result containing \''.$filter.'\' 
					or cardidx.id_card containing \''.$filter.'\'' : ''));
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			$a=$query['COUNT'];
		return $query['COUNT'];
	}
	
	public function getQueue($page = 1, $perpage = 10, $filter) // получаю список строк карт, стоящих в очереди
	{
		$sql = 'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					cardindev.id_dev as door_id, 
					d1.name as door_name,
					d1.id_dev as id_dev,					
					count (cardindev.id_dev) as door_count, 
					d1."ACTIVE" as door_isactive, 
					d2.name as controller_name, 
					d2."ACTIVE" as controller_isactive
					from cardindev
					join device d1 on device.id_dev=cardindev.id_dev
					join device d2 on (d1.id_ctrl=d2.id_ctrl and d2.id_reader is null)' .
					($filter ? " where d1.name containing '$filter'" : '') .
					' group by cardindev.id_dev, d1.name, d1.id_dev, d1."ACTIVE", d2.name , d2."ACTIVE"';
			$query = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
		//выборка причин отказа стояния в очереди
		
		$sql='select  distinct ci.id_dev, ci.load_result from cardidx ci
			join cardindev cd on cd.id_cardindev=ci.id_cardindev
			group by ci.id_dev, ci.load_result';
		$mess = DB::query(Database::SELECT, $sql)
				->execute(Database::instance('fb'))
				->as_array();
				
				foreach ($query as $key=>$value){// добавляю комментарии об ошибках
					$data_out[$key]=$value;
						foreach ($mess as $data){
							if ($data['ID_DEV']==$value['DOOR_ID']) $data_out[$key]['err_desc'][]=$data['LOAD_RESULT'];
						
						}
					if ($value['DOOR_ISACTIVE'] ==0);
					if ($value['CONTROLLER_ISACTIVE']==0);
					
				}
			
			return $data_out;
	}
	
	public function getCountIddev($id_dev)// количество записей для текущего iddev
	{
	$sql = iconv('UTF-8', 'CP1251', 'select count(cardindev.id_cardindev)
                    from cardindev
					where cardindev.id_dev='.$id_dev.'\'');
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			$a=$query['COUNT'];
		return $query['COUNT'];
	}
	
	
	public function getQueueIddev($page, $perpage , $id_dev) // 
	{
		$sql = 'SELECT  FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					cardindev.id_cardindev,
                    cardindev.id_card,
                    cardindev.id_dev,
                    cardindev.operation,
                    cardindev.attempts,
                    d1.name as door_name,
					
					d1."ACTIVE" as door_isactive,
					d2.name as controller_name,
					d2."ACTIVE" as controller_isactive,
                    cardidx.load_time,
                    cardidx.load_result
                    from cardindev
                    join device d1 on device.id_dev=cardindev.id_dev
					join device d2 on (d1.id_ctrl=d2.id_ctrl and d2.id_reader is null)
                    join cardidx on (cardidx.id_card=cardindev.id_card and cardidx.id_dev=cardindev.id_dev)
					where cardindev.id_dev='.$id_dev;
			$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	
	
	public function getList($page = 1, $perpage = 10, $filter) // 
	{
		$sql = 'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					cardindev.id_cardindev,
                    cardindev.id_card,
                    cardindev.id_dev,
                    cardindev.operation,
                    cardindev.attempts,
                    d1.name as door_name,
					
					d1."ACTIVE" as door_isactive,
					d2.name as controller_name,
					d2."ACTIVE" as controller_isactive,
                    cardidx.load_time,
                    cardidx.load_result
                    from cardindev
                    join device d1 on device.id_dev=cardindev.id_dev
					join device d2 on (d1.id_ctrl=d2.id_ctrl and d2.id_reader is null)
                    join cardidx on cardidx.id_cardindev=cardindev.id_cardindev'.
					($filter ? ' where 
					d1.name containing \''.$filter.'\' 
					or d2.name containing \''.$filter.'\' 
					or cardidx.load_result containing \''.$filter.'\' 
					or cardidx.id_card containing \''.$filter.'\'' : '');

			$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	public function start_load_controller($list_id)
	{
	$sql='update cardindev set cardindev.attempts=0 where cardindev.id_dev in('.implode(",",array_unique($list_id)).')';
	//echo Kohana::Debug($sql);
	DB::query(Database::UPDATE, $sql) 
			->execute(Database::instance('fb'));
	
	}
	
	public function stop_load_controller($list_id)
	{
	$sql='update cardindev set cardindev.attempts=100 where cardindev.id_dev in('.implode(",",array_unique($list_id)).')';
	//echo Kohana::Debug($sql);
	DB::query(Database::UPDATE, $sql) 
			->execute(Database::instance('fb'));
	
	}
	
	
	public function start_load_cards($list)
	{
		foreach ($list as $key=>$value)
		{
				echo $sql='update cardindev set cardindev.attempts=0 where cardindev.id_card=\''.$key.'\' and cardindev.id_dev in ('.implode(',', $value).')';
				Kohana::$log->add(Kohana::ERROR, $sql);
				//echo Kohana::Debug($sql);
				DB::query(Database::UPDATE,$sql)
				->execute(Database::instance('fb'));
		}
	}
	
	public function stop_load_cards($list)
	{
		foreach ($list as $key=>$value)
		{
				echo $sql='update cardindev set cardindev.attempts=777 where cardindev.id_card=\''.$key.'\' and cardindev.id_dev in ('.implode(',', $value).')';
				Kohana::$log->add(Kohana::ERROR, $sql);
				//echo Kohana::Debug($sql);
				DB::query(Database::UPDATE,$sql)
				->execute(Database::instance('fb'));
		}
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
			'SELECT  l.id_card, l.load_time, l.load_result, d.name ' .
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