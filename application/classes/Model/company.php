<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_Company extends Model
{
	public function getOrgList() //список организаций (квартир).
	{
		
		/* $remote  = Database::instance('fb');
		echo $remote->list_tables();exit;
		echo $remote->list_columns('people');exit; */
		
		//https://xhtml.ru/2022/html/tree-views/		
		$sql='SELECT  og.id_org, og.name, og.id_parent, og.flag FROM ORGANIZATION_GETCHILD(1, 1)  og order by og.name';
             //   left join  hl_orgaccess hlo on hlo.id_org = og.id_org
             //   order by og.id_org ';
		try
		{
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			$res=array();
			$res[1]= array(
			"id" => 1,
			"title" => "Все",
			"parent" => 0
			);
		foreach ($query as $key=>$value)
		{
			//echo Debug::vars('58', $value); exit;
			$res[Arr::get($value, 'ID_ORG')]['id']=Arr::get($value, 'ID_ORG');
			$res[Arr::get($value, 'ID_ORG')]['title']=iconv('windows-1251','UTF-8', Arr::get($value, 'NAME'));
			$res[Arr::get($value, 'ID_ORG')]['parent']=Arr::get($value, 'ID_PARENT');
			$res[Arr::get($value, 'ID_ORG')]['busy']=Arr::get($value, 'ID_GARAGE');
			
		}
			$res[1]['parent']=0;
			//echo Debug::vars('126', Arr::get($res, 1), $res); exit;
			return $res;
		} catch (Exception $e) {
			Log::instance()->add(Log::ERROR, $e);
		}
		
	}
	
	public function getCountAdmin($filter)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT COUNT(*) FROM organization' . ($filter ? " WHERE name containing '$filter'" : ''))
			->execute(Database::instance('fb'))
			->current();

		return $query['COUNT'];
	}
	
	/*
	Подготовка списка категорий доступа, выданных организации
	*/
	public function company_acl($id_org)
	{
			$sql = 'select * from ss_accessorg ssa
where ssa.id_org='. $id_org;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->as_array();
			
		return $query;
	}
	
	/*
	10.08.2023 
	Добавление категории доступа для организации в таблицу ss_accessorg
	*/
	public function add_company_acl($id_org, $id_accessname)
	{
		$sql='INSERT INTO SS_ACCESSORG ( ID_DB, ID_ORG, ID_ACCESSNAME) 
				VALUES (1, '.$id_org.', '.$id_accessname.')';
		
		//echo Debug::vars('42',$sql); exit;
		
		$query = DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'))
			;
		
		return $query;
	}
	
	/*
	10.08.2023 
	Удаление указанной категории доступа у организации из таблицу ss_accessorg
	*/
	public function del_company_acl($id_org, $id_accessname)
	{
		$sql = 'delete from ss_accessorg  ssa
				where ssa.id_org='.$id_org.'
				and ssa.id_accessname='.$id_accessname;
		
		//echo Debug::vars('42',$id_pep, $id_accessname, $sql, Arr::get(Auth::instance()->get_user(), 'LOGIN')); exit;
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	/*
	10.08.2023 
	Удаление всех категорий доступа для организации из таблицу ss_accessuser
	*/
	public function clear_company_acl($id_org)
	{
		$sql = 'delete from ss_accessorg  ssa
				where ssa.id_org='.$id_org;
				
		
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
				
		return $query;
	}
	
	/**
	5.12.2023
	Подсчет количества дотупных организаций для авторизованного пользователя. Это необходимо для расчета количества страниц для pagination
	$user - id_pep авторизованного пользователя
	$parent - организацию, по которой надо вывести информацию, и по ее дочкам
	$filter - если указана - то вывести информацию именно по этой организации
	
	*/
	public function getCountUser($user,$parent_org, $filter=null)
	{
		//echo Debug::vars('125', Arr::get(Auth::instance()->get_user(), 'ID_ORG'));
		//echo Debug::vars('125', Auth::instance());
		$id_org_control=Arr::get(Auth::instance()->get_user(), 'ID_ORGCTRL');
		//echo Debug::vars('135', Auth::instance()->get_user(), $id_org_control); exit;
		if(is_null($filter))//если фильтра нет, то надо выбрать все организации для этого пользователя
		{
		$sql = 'select count(o.id_org) from organization o
			join organization_getchild (1,'.$id_org_control.') og on og.id_org =o.id_org'. 
			($parent_org ? " where o.id_parent = '$parent_org'" : '');
			
	
			
		} else { //если фильтр указан, то надо выбрать эту организацию из списка разрешенных для этого пользователя
			$sql = 'select count(o.id_org) from organization o
			join organization_getchild (1,'.$id_org_control.') og on og.id_org =o.id_org '.
			($filter ? " where o.name containing '$filter'" : '');
			
			
		}
			//echo Debug::vars('50', $sql); exit;
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->get('COUNT');
		
		
		return $res;
	}
	
	public function getListF($user, $page = 1, $perpage = 10,  $filter)
	{
		$g = array();
		$s = "SELECT DISTINCT id_group FROM users_groups WHERE id_user = $user";
		$q = DB::query(Database::SELECT, $s)
			->execute(Database::instance('default'));
		foreach ($q->as_array() as $key => $value) {
			$g[] = $value['id_group'];
		}

		$sql =  'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' o.*, p.name AS parent, a.name AS accessname ' .
				'FROM organization o INNER JOIN organization p ON o.id_parent = p.id_org LEFT OUTER JOIN accessname a ON o.id_def_accessname = a.id_accessname ' .
				'WHERE o.id_group IN (' . join(',', $g) . ')' . ($filter ? " AND o.name containing '$filter' " : '') .
				'ORDER BY o.id_org';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public static function getListAccessName()//
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM accessname')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}

	public function getListUser($user, $page = 1, $perpage = 10, $filter)
	{
		$sql = '
				SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . '
					o.id_org, o.name, o.divcode,
					SUM(ug."O_VIEW") sumoview,
					SUM(ug."O_EDIT") sumoedit,
					SUM(ug."O_ADD") sumoadd,
					SUM(ug."O_DELETE") sumodelete,
					SUM(ug."P_EDIT") sumpedit,
					SUM(ug."P_ADD") sumpadd,
					SUM(ug."P_DELETE") sumpdelete,
					SUM(ug."C_EDIT") sumcedit,
					SUM(ug."C_ADD") sumcadd,
					SUM(ug."C_DELETE") sumcdelete
				FROM
					usersgroups ug
					INNER JOIN organizationgroup og ON ug.id_group = og.id_group
					INNER JOIN organization o ON og.id_org = o.id_org
				WHERE
					ug.id_user = ' . $user . '
					AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' . ($filter ? "AND o.name containing '$filter'" : '') . '
				GROUP BY
					o.id_org, o.name, o.divcode
				ORDER BY
					o.id_org';
		//echo Kohana::Debug('User_sql_'.$sql);
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	public function getListUser1($user, $page = 1, $perpage = 10, $filter)
	{
		$g = array();
		$a = array();
		$s = "SELECT * FROM users_groups WHERE id_user = 2 AND `view`+`edit`+`add`+`delete` > 0";
		$q = DB::query(Database::SELECT, $s)
			->execute(Database::instance('default'));
		foreach ($q->as_array() as $key => $value) {
			$g[] = $value['id_group'];
			$a[$value['id_group']] = array(
				'view'		=> $value['view'],
				'edit'		=> $value['edit'],
				'add'		=> $value['add'],
				'delete'	=> $value['delete']);
		}
		
		$sql =	'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' o.*, g.id_group AS "GROUP", p.name AS parent, a.name AS accessname ' .
				'FROM organization o ' .
				'INNER JOIN organizationgroup g ON g.id_org = o.id_org ' .
				'INNER JOIN organization p ON o.id_parent = p.id_org ' .
				'LEFT OUTER JOIN accessname a ON o.id_def_accessname = a.id_accessname ' .
				'WHERE g.id_group IN (' . join(',', $g) . ')' . ($filter ? " AND o.name containing '$filter' " : '') .
				'ORDER BY o.id_org';

		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		$tmp = $res->as_array();
		$res = array();

		foreach ($tmp as $row) {
			if (!array_key_exists($row['ID_ORG'], $res)) {
				$res[$row['ID_ORG']] = array(
					'ID_ORG'	=> $row['ID_ORG'],
					'NAME'		=> $row['NAME'],
					'DIVCODE'	=> $row['DIVCODE'],
					'view'		=> $a[$row['GROUP']]['view'],
					'edit'		=> $a[$row['GROUP']]['edit'],
					'add'		=> $a[$row['GROUP']]['add'],
					'delete'	=> $a[$row['GROUP']]['delete']
				);
			} else {
				if ($a[$row['GROUP']]['view'] == 1)
					$res[$row['ID_ORG']]['view'] = 1;
				if ($a[$row['GROUP']]['edit'] == 1)
					$res[$row['ID_ORG']]['edit'] = 1;
				if ($a[$row['GROUP']]['add'] == 1)
					$res[$row['ID_ORG']]['add'] = 1;
				if ($a[$row['GROUP']]['delete'] == 1)
					$res[$row['ID_ORG']]['delete'] = 1;
			}
		}
		
		return $res;
	}
	
	/**
	5.12.2023 
	Получить список организаций
	$user - id_pep авторизованного пользователя
	$page - начиная с какой страницы
	$perpage - количество записей
	$parent_org - с какой родительской организации надо выводить данные
	$filter - фильтр - организация, по которой надо вывести информацию
	
	*/
	public function getListAdmin($user, $page = 1, $perpage = 10, $parent_org, $filter=null)
	{
		//$filter=null;
		
		//echo Debug::vars('161', $user, $page , $perpage , 'parent_org='.$parent_org, 'filter bool='.$filter.'_'.is_string($filter).'_'.is_null(true).'_'.is_null(false)); exit;
		$id_org_control=Arr::get(Auth::instance()->get_user(), 'ID_ORG');
		if(is_null($filter)) //если это не поиск конкретной оргназации, то вывожу всех дочек parent_org
		{			
		$sql = 'select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' o.*, p.name AS parent, p.id_org AS parentid, p.name AS accessname ' . 
			'from organization o
			join organization_getchild (1,'.$id_org_control.') og on og.id_org =o.id_org 
			INNER JOIN organization p ON o.id_parent = p.id_org 
			where o.id_org>0 '.
			($parent_org ? " and o.id_parent = '$parent_org'" : '') . '
			ORDER BY  o.name COLLATE PXW_CYRL';
			//echo Debug::vars('177 нет фильтра', $sql, $filter);
		} else {//если строго по фильтру, то вывожу эту организацию (если она разрешена текущего пользователю)
			$sql = 'select FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' o.*, p.name AS parent, p.id_org AS parentid, p.name AS accessname ' . 
			'from organization o
			join organization_getchild (1,'.$id_org_control.') og on og.id_org =o.id_org 
			INNER JOIN organization p ON o.id_parent = p.id_org 
			where o.id_org>0 '.
			($filter ? " and o.name containing '$filter'" : '') .'
			ORDER BY  o.name COLLATE PXW_CYRL';
			//echo Debug::vars('186 есть фильтр', $sql, $filter);
		}
		//echo Debug::vars('308 ', $sql, $filter);exit;
		$query = DB::query(Database::SELECT, iconv('UTF-8', 'windows-1251', $sql))
			->execute(Database::instance('fb'));
		//echo Debug::vars('177',$sql, $query ); exit;	
		return $query->as_array();
	}

	public function getNamesWithGroup($group)
	{
		$sql = "SELECT DISTINCT
					o.id_org,
					o.name,
					o.id_parent,
					o1.name as parentname,
					0 as lvl,
					(select count(id_org) from organization o2 where o2.id_parent = o.id_org and o2.id_org <> o.id_org) qty,
					(SELECT DISTINCT 
						id_group 
					FROM 
						organizationgroup 
					WHERE 
						id_org = o.id_org AND id_group = $group 
					) gr
				FROM
					organizationgroup g
					RIGHT OUTER JOIN organization o ON g.id_org = o.id_org
					INNER JOIN organization o1 on o.id_parent = o1.id_org
				ORDER BY
					o1.id_org,
					o.id_org";
		
		$res = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $res->as_array();
	}
	
	public function addToGroup($org, $group)
	{
		$sql = "INSERT INTO organizationgroup (id_org, id_group) VALUES ($org, $group)";
		
		DB::query(Database::INSERT, $sql)
			->execute(Database::instance('fb'));
	}
	
	public function removeFromGroup($org, $group)
	{
		$sql = "DELETE FROM organizationgroup WHERE id_org = $org AND id_group = $group";
		
		DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
	}
	
	public function getCountByGroup($group, $filter)
	{
		$sql =	'SELECT COUNT(o.id_group) ' .
				'FROM organization o LEFT OUTER JOIN organizationgroup g ON o.id_org = g.id_org ' .
				'WHERE g.id_group = ' . $group . ($filter ? " AND o.name containing '$filter'" : '');
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'))
			->current();
			
		return $query['COUNT'];
	}
	
	public function getListByGroup($group, $page = 1, $perpage = 10, $filter)
	{
		$sql =	'SELECT FIRST ' . $perpage . ' SKIP ' . ($page - 1) * $perpage . ' o.*, p.name AS parent, a.name AS accessname ' .
				'FROM organization o INNER JOIN organization p ON o.id_parent = p.id_org ' .
				'LEFT OUTER JOIN accessname a ON o.id_def_accessname = a.id_accessname ' .
				'LEFT OUTER JOIN organizationgroup g ON o.id_org = g.id_org ' .
				'WHERE g.id_group = ' . $group . ($filter ? " AND o.name containing '$filter' " : '') .
				'ORDER BY o.id_org';
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
			
		return $query->as_array();
	}
	
	public function getNames($user)
	{
		if ($user) {
			$g = array();
			$s = "SELECT DISTINCT id_group FROM users_groups WHERE id_user = $user";
			
			$q = DB::query(Database::SELECT, $s)
				->execute(Database::instance('default'));
			foreach ($q->as_array() as $key => $value) {
				$g[] = $value['id_group'];
			}

			$query = DB::query(Database::SELECT,
				'SELECT id_org, name FROM organization WHERE id_group IN (' . join(', ', $g) . ')')
				->execute(Database::instance('fb'));
		} else
			$query = DB::query(Database::SELECT,
				'SELECT ID_ORG, NAME FROM organization order by NAME')
				->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
	public function getGroups()
	{
		$query = DB::query(Database::SELECT,
			'SELECT g.*, COUNT(DISTINCT o.id_org) AS qty ' .
			'FROM "GROUP" g LEFT OUTER JOIN organizationgroup og on (g.id_group = og.id_group) ' .
   			'LEFT OUTER JOIN organization o ON (og.id_org = o.id_org) ' .
			'GROUP BY g.id_group, g.name, g.description')
			->execute(Database::instance('fb'));
			
		return $query->as_array();
	}
	
	public function deleteGroup($id)
	{
		$query = DB::query(Database::DELETE,
			'DELETE FROM organizationgroup WHERE id_group = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));

		$query = DB::query(Database::DELETE,
			'DELETE FROM "GROUP" WHERE id_group = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	public function getGroup($id)
	{
		$query = DB::query(Database::SELECT, 'SELECT * FROM "GROUP" WHERE id_group = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
			
		if ($query->count() == 0) return FALSE;
		return $query->current();
	}
	
	public function getCompany($id)
	{
		if (!is_numeric($id)) return false;
		
		if (Auth::instance()->logged_in('admin'))
			$sql = 'SELECT o.*, 1 AS canedit FROM organization o WHERE o.id_org = ' . $id;
		else
			/*
			$sql =	'SELECT o.*, ug."O_EDIT" AS canedit ' .
					'FROM usersgroups ug ' .
	    			'INNER JOIN organizationgroup og ON (ug.id_group = og.id_group) ' .
	    			'INNER JOIN organization o ON (og.id_org = o.id_org) ' .
	    			'WHERE ug.id_user = ' . Auth::instance()->get_user() . 
	    			' AND (ug."O_VIEW" + ug."O_EDIT" + ug."O_ADD" + ug."O_DELETE" > 0) ' .
					'AND o.id_org = ' . $id;
			*/
			$sql = 'SELECT o.*, 1 AS canedit FROM organization o WHERE o.id_org = ' . $id;
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		if ($query->count() == 0) return false;
		return $query->current();
	}
	
	public function save($name, $code, $access, $parent, $group)
	{
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_org_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();//получил id вставляемой организации
		
		try{
		$sql=__('INSERT INTO organization (id_org, name, id_parent, divcode, id_def_accessname) VALUES (:id, \':name\', :parent, null, null)',
			array(
				':id'		=> $result['GEN_ID'],
				':name'		=> $name,
				':parent'	=> ($parent == '')? 1 : $parent ,
				//':divcode'	=> ($code == '')? null : $code,
				//':group'	=> $group == 0 ? null : $group,
				':access'	=> $access));
				
		
		//echo Debug::vars('490', $parent,($parent == '')? null : $parent,  $sql); exit;
		$query=DB::query(Database::INSERT, iconv('UTF-8', 'CP1251',$sql))
			
			->execute(Database::instance('fb'));
		} catch (Exception $e) {
			Log::instance()->add(Log::DEBUG, '#31 '.$e->getMessage());

		}	

		$id = $result['GEN_ID'];
			
		if (!Auth::instance()->logged_in('admin')) {
			$group = Acls::getGroupId(Auth::instance()->get_user());
			$query = DB::query(Database::INSERT,
				"INSERT INTO organizationgroup (id_org, id_group) VALUES ($id, $group)")
				->execute(Database::instance('fb'));
		}
			
		return $id;
		
		
	}
	
	public function saveGroup($name, $desc)
	{
		$query = DB::query(Database::SELECT,
			'SELECT gen_id(gen_group_id, 1) FROM rdb$database')
			->execute(Database::instance('fb'));
		$result = $query->current();
		$query = DB::query(Database::INSERT,
			'INSERT INTO "GROUP" (id_group, name, description) VALUES (:id, :name, :desc)')
			->parameters(array(
				':id'		=> $result['GEN_ID'],
				':name'		=> $name,
				':desc'		=> $desc))
			->execute(Database::instance('fb'));
		return $result['GEN_ID'];
	}
	
	public function update($id, $name, $parent, $code, $access, $group)
	{
		$query = DB::query(Database::UPDATE,
			'UPDATE organization SET name = :name, id_parent = :parent, divcode = :code, id_def_accessname = :access WHERE id_org = :id')
			->parameters(array(
				':name' 	=> $name,
				':parent'	=> $parent,
				':code'		=> $code,
				':access'	=> $access,
				':id'		=> $id))
			->execute(Database::instance('fb'));
	}

	public function updateGroup($id, $name, $desc)
	{
		$query = DB::query(Database::UPDATE,
			'UPDATE "GROUP" SET name = :name, description = :desc WHERE id_group = :id')
			->parameters(array(
				':name'		=> $name,
				':desc'		=> $desc,
				':id'		=> $id))
			->execute(Database::instance('fb'));
	}
	
	public function _delete($id)
	{
		$query = DB::query(Database::DELETE,
			'DELETE FROM organization WHERE id_org = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	/*
	Изменение родительской организации для дочерних
	
	*/
	public function changeParentForChild($id)
	{
		$query = DB::query(Database::DELETE,
			'DELETE FROM organization WHERE id_org = :id')
			->param(':id', $id)
			->execute(Database::instance('fb'));
	}
	
	
	
}