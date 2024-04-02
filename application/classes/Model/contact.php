<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Contact extends Model
{
	
	public $peopleIsActive = 1;
	
	
	
	
	
	
	public function getCountByOrg($org)
	{
		$sql = 'SELECT COUNT (*) FROM people WHERE "ACTIVE"='.$this->peopleIsActive.' and id_org = ' . $org;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			
		return $query['COUNT'];
	}
	
	/*
	17.08.2023 
	Сверка категориий доступа контакта и родительской организации.
	0 - совпадает,
	1 - больше, чем в организации.
	2 - меньше, чем в организации
	*/
	public function check_acl($id_pep)
	{
		if($id_pep>0)
		{
			$peopleacl=array();
			$orgacl=array();
			$sql='select ssa.id_accessname from ss_accessuser ssa
				where ssa.id_pep='.$id_pep;
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				$peopleacl[]=Arr::get($value, 'ID_ACCESSNAME');
			}
		
			$sql='select sso.id_accessname from ss_accessorg sso
			join people p on p.id_org=sso.id_org
			where p.id_pep='.$id_pep;
			
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			foreach($query as $key=>$value)
			{
				$orgacl[]=Arr::get($value, 'ID_ACCESSNAME');
			}
			
			$diff1=	count(array_diff($peopleacl, $orgacl));//на сколько категорий контакта больше, чем у организации
			$diff2=	count(array_diff($orgacl, $peopleacl));//на сколько категорий больше в организации, чем у контакта
			$res=-1;
			if(($diff1==0) and ($diff2 == 0)) $res=0;//все совпадает.
			if(($diff1>0) and ($diff2 == 0)) $res=1;//категорий контакта больше, чем у организации
			if(($diff1==0) and ($diff2 > 0)) $res=2;//категорий больше в организации, чем у контакта
			if(($diff1>0) and ($diff2 > 0)) $res=3;//количество категорий контакта совпадает с количество категорий организации, но они разные
			
			//echo Debug::vars('49',$id_pep, $peopleacl, $orgacl, count(array_diff($peopleacl, $orgacl)),  count(array_diff($orgacl, $peopleacl))); exit;
		} else {
			$res=-1;
		}
		return $res;
	}
	/*
	8.08.2023 
	Список категорий доступа, выданных пиплу
	*/
	public function contact_acl($id_pep)
	{
		$sql = 'select ssa.id_accessuser, ssa.id_pep, ssa.id_accessname, ssa.username, an.name from ss_accessuser ssa
				join accessname an on ssa.id_accessname=an.id_accessname
                where ssa.id_pep=' . $id_pep;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		return $query;
	}
	
	/*
	8.08.2023 
	Добавление категории доступа для пипла в таблицу ss_accessuser
	*/
	public function add_contact_acl($id_pep, $id_accessname)
	{
		//echo Debug::vars('40',$id_pep, $id_accessname, Arr::get(Auth::instance()->get_user(), 'LOGIN')); exit;
		$sql = 'INSERT INTO SS_ACCESSUSER (ID_DB, ID_PEP, ID_ACCESSNAME, USERNAME)
					VALUES ( 1, '.$id_pep.', '.$id_accessname.', \''.Arr::get(Auth::instance()->get_user(), 'LOGIN').'\')';
		
		$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
		
		return $query;
	}
	
	/*
	9.08.2023 
	Удаление указанной категории доступа для пипла из таблицу ss_accessuser
	*/
	public function del_contact_acl($id_pep, $id_accessname)
	{
		$sql = 'delete from ss_accessuser  ssa
				where ssa.id_pep='.$id_pep.'
				and ssa.id_accessname='.$id_accessname;
		
		//echo Debug::vars('42',$id_pep, $id_accessname, $sql, Arr::get(Auth::instance()->get_user(), 'LOGIN')); exit;
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	/*
	9.08.2023 
	Удаление всех категорий доступа для пипла из таблицу ss_accessuser
	*/
	public function clear_contact_acl($id_pep)
	{
		$sql = 'delete from ss_accessuser  ssa
				where ssa.id_pep='.$id_pep;
				
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	
	/*
	11.08.2023 
	Устанвока категорий доступа для пипла по умолчанию (от родительской организации)
	*/
	public function setInheritAcl($id_pep)
	{
		$sql = 'select ssa.id_accessname from ss_accessorg ssa
				join people p on ssa.id_org=p.id_org
				where p.id_pep='.$id_pep;

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
		
		if($query)
		{
			foreach($query as $key=>$value)
			{
				//echo Debug::vars('100', $id_pep, Arr::get($value, 'ID_ACCESSNAME'));exit;	
				$this->add_contact_acl($id_pep,  Arr::get($value, 'ID_ACCESSNAME'));
			}
		}
				
		return $query;
	}
	
	
	/**
		
	*/
	
	
	public function getCountAdmin($filter)
	{
		$sql = 'SELECT COUNT (*) FROM people ' . ($filter ? " WHERE upper(surname) containing upper('$filter') OR upper(name) containing upper('$filter')" : '');
		echo Debug::vars('167', $sql); //exit;
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
		
		return $query['COUNT'];
	}
	
	/**
	Подсчет количества людей в указанной родительской организации
	$id_org - родительская организация
	$filter - текст, которого идем
	*/
	
	public function getCountUser($id_org, $filter)
	{
		//echo Debug::vars('186', $id_org, $filter,  $filter=''); exit;
			
		if(is_null($filter) or $filter=='') {// если фильтра нет, то выбираем всех пиплов из родительской и подчиненной организаций
		$sql='select count(*) from people p
		join organization_getchild (1, ' . $id_org . ') og on og.id_org = p.id_org
		where p."ACTIVE"='.$this->peopleIsActive;
		
		} else {
			$sql='select count(*) from people p
			join organization_getchild (1, ' . $id_org . ') og on og.id_org = p.id_org
            where p."ACTIVE"='.$this->peopleIsActive.'
			and (upper(p.name) containing upper(\''.$filter.'\'))
            or (upper(p.surname) containing upper(\''.$filter.'\'))
            or (upper(p.patronymic) containing  upper(\''.$filter.'\'))';
		}
		
		
		//echo Debug::vars('190', $sql); exit;
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
			
		return $res;
	}
	
	public function getListF($user, $page = 1, $perpage = 10, $filter)
	{
		$g = array();
		$s = "SELECT DISTINCT id_group FROM users_groups WHERE id_user = $user";
		$q = DB::query(Database::SELECT, $s)
			->execute(Database::instance('default'));
		foreach ($q->as_array() as $key => $value) {
			$g[] = $value['id_group'];
		}

		$sql =	'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.*, o.name AS oname ' . 
    			'FROM organization o INNER JOIN people p  ON (p.id_org = o.id_org) ' .
				'WHERE o.id_group IN (' . join(', ', $g) . ') ' . ($filter ? " AND (p.surname containing '$filter' OR p.name containing '$filter')" : '') .
				'ORDER BY id_pep';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getListByOrg($page = 1, $perpage = 10, $org)
	{
		

		$sql =  'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' 	o.id_org,
    				o.name AS oname,
    				p.id_pep,
					p."ACTIVE" as is_active,
    				p.tabnum,
    				p.surname,
    				p.name, 
	
	o.name AS oname, 1 AS canedit ' .
				'FROM people p INNER JOIN organization o ON p.id_org = o.id_org ' .
				'WHERE p.id_org = ' . $org .  
				' and p."ACTIVE" = '.$this->peopleIsActive.
				' ORDER BY id_pep'; 
		

		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getListAdmin($page = 1, $perpage = 10, $filter)
	{
		$sql =  'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' p.*, o.name AS oname ' .
				'FROM people p INNER JOIN organization o ON p.id_org = o.id_org ' .
				($filter ? " WHERE upper(p.surname) containing upper('$filter') OR upper(p.name) containing upper('$filter')" : '') . 
				'ORDER BY id_pep'; 
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getPicture($id)
	{
		$sql = 'SELECT photo FROM people WHERE id_pep =' .$id;
		
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('PHOTO');
		//echo Debug::vars('271', $sql, $res); exit;
		return $res;
	} 
	
	/**
	5.12.2023 
		Список пиплов для текущего авторизованного пользователя
	
	*/
	
	public function getListUser($id_org, $page = 1, $perpage = 10, $filter)
	{
		
				
		if(is_null($filter) or $filter=='') {// если фильтра нет, то выбираем всех пиплов из родительской и подчиненной организаций
		$sql='select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' 
				o.id_org,
				o.name AS oname,
                p.id_pep,
                p."ACTIVE" as is_active,
                p.tabnum,
                p.surname,
                p.name from people p
                join organization o on o.id_org=p.id_org
		join organization_getchild (1, ' . $id_org . ') og on og.id_org = p.id_org
		where p."ACTIVE"='.$this->peopleIsActive.'';
		} else {
			$sql='select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' 
				o.id_org,
				o.name AS oname,
                p.id_pep,
				p."ACTIVE" as is_active,
                p.tabnum,
                p.surname,
                p.name from people p
                join organization o on o.id_org=p.id_org
		join organization_getchild (1, ' . $id_org . ') og on og.id_org = p.id_org
            where p."ACTIVE"='.$this->peopleIsActive.'
			and (p.name containing \''.$filter.'\'
            or (p.surname containing \''.$filter.'\')
            or (p.patronymic containing  \''.$filter.'\'))';
			
			
		}
		//echo Debug::vars('306', $sql); exit;
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	public function getContact($id)
	{
		if (!is_numeric($id)) return false;
		
		$sql='SELECT people.*, 1 AS canedit FROM people WHERE id_pep ='. $id;
		if (Auth::instance()->logged_in('admin'))
			$query = DB::query(Database::SELECT, $sql)				
				->execute(Database::instance('fb'));
		else
			$query = DB::query(Database::SELECT,
				'SELECT p.*, ug."P_EDIT" AS canedit ' .
    			'FROM usersgroups ug ' .
    			'INNER JOIN organizationgroup og ON (ug.id_group = og.id_group) ' .
    			'INNER JOIN organization o ON (og.id_org = o.id_org) ' .
    			'INNER JOIN people p ON p.id_org = o.id_org ' .
    			'WHERE ug.id_user = ' . Auth::instance()->get_user() . ' ' .
    			'AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' .
				'AND p.id_pep = :id')
				->param(':id', $id)
				->execute(Database::instance('fb'));
		
		if ($query->count() == 0) return false;
		return $query->current();
	}
	
	public function save($surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note)
	{
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_people_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		//echo Debug::vars('210', Arr::get($result, 'GEN_ID')); exit;
		$query = DB::query(Database::INSERT,
			'INSERT INTO people (id_pep, id_db, surname, name, patronymic, datebirth, numdoc, datedoc, workstart, workend, "ACTIVE", peptype, post, login, pswd, id_org, tabnum, note) ' .
			'VALUES (:id,1, :surname, :name, :patronymic, :datebirth, :numdoc, :datedoc, :workstart, :workend, :active, :peptype, :post, :login, :password, :org, :tabnum, :note)')
			->parameters(array(
				':id'			=> Arr::get($result, 'GEN_ID'),
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				//':datedoc'		=> $datedoc,
				':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> 'tabnum_'.Arr::get($result, 'GEN_ID'),
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251','USER'.Arr::get($result, 'GEN_ID')),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note))
				)
			->execute(Database::instance('fb'));
		return $result['GEN_ID'];
	}
	
	public function update($id, $surname, $name, $patronymic, $datebirth, $numdoc, $datedoc, $workstart, $workend, $active, $peptype, $post, $tabnum, $org, $login, $password, $note)
	{
		$query = DB::query(Database::UPDATE,
			'UPDATE people SET surname = :surname, name = :name, patronymic = :patronymic, datebirth = :datebirth, numdoc = :numdoc, datedoc = :datedoc, ' .
			'workstart = :workstart, workend = :workend, "ACTIVE" = :active, peptype = :peptype, post = :post, login = :login, pswd = :password, id_org = :org, note = :note
			WHERE id_pep = :id')
			->parameters(array(
				':surname'		=> iconv('UTF-8', 'CP1251',$surname),
				':name'			=> iconv('UTF-8', 'CP1251',$name),
				':patronymic'	=> iconv('UTF-8', 'CP1251',$patronymic),
				//':datebirth'	=> $datebirth,
				':datebirth'	=> 'now',
				':numdoc'		=> $numdoc,
				//':datedoc'		=> $datedoc,
				':datedoc'		=> 'now',
				':workstart'	=> $workstart,
				':workend'		=> $workend,
				':active'		=> $active,
				':peptype'		=> $peptype,
				':post'			=> iconv('UTF-8', 'CP1251',$post),
				':tabnum'		=> $tabnum,
				':org'			=> $org,
				':login'		=> iconv('UTF-8', 'CP1251',$login),
				':password'		=> iconv('UTF-8', 'CP1251',$password),
				':note'			=> iconv('UTF-8', 'CP1251',$note),
				':id'			=> $id))
			->execute(Database::instance('fb'));
	}

	/**
		Механизм удаления карты зависит от настройки howDeletePeople в таблице setting
		0 - не удалять, делать ACTIVE=0. Карты удаляются
		1- удалять строку.
	*/
	public function _delete($id)
	{
		
		echo Debug::vars('428', ConfigType::howDeletePeople(), $id); exit;
		switch(ConfigType::howDeletePeople()){
			case 0:// удаляю карту, активность пипла ставлю 0.
				$sql='delete from card c
				where c.id_pep='. $id;
			DB::query(Database::DELETE,$sql)	
				->execute(Database::instance('fb'));
				
			$sql='update people p
					set p."ACTIVE"=0
					where p.id_pep='. $id;
			DB::query(Database::UPDATE,$sql)	
				->execute(Database::instance('fb'));
			break;
			case 1:
				$sql='DELETE FROM people WHERE id_pep ='. $id;
				/* DB::query(Database::DELETE,$sql)			
				->execute(Database::instance('fb')); */
			break;
			default:
			
			break;
		}
	}
				
		/**
		Восстановление пользователя. "ACTIVE" устанавливается в 1.
	*/
	public function restore($id)
	{
			$sql='update people p
					set p."ACTIVE"=1
					where p.id_pep='. $id;
			DB::query(Database::UPDATE,$sql)	
				->execute(Database::instance('fb'));
	}
}