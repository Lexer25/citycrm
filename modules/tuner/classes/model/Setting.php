<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
Модель для работы с конфигурацией как с единым целым
*/

class Model_Setting extends Model
{
	
	public function getgrouplist(){// получить список групп конфигурации
		
		
			$sql='select distinct group_name from config';
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('cdb'))
			->as_array();
			//echo Debug::vars('16', $groupList);exit;
			foreach($query as $key=>$value){
				
				$groupList[]=Arr::get($value, 'group_name');
			}
			return $groupList;
	}
	
	
	public function getKeyCountInGroup($group){// получить количество ключей в указанной группе
		
		
			$sql='select count(*) as COUNT  from config
			where group_name=\''.$group.'\'';
			
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('cdb'))
			->get('COUNT');
	
			return $query;
	}
	
	public function deleteKeyfromGroup($group, $key){// получить количество ключей в указанной группе
		
		
			$sql='delete from config
			where group_name=\''.$group.'\' 
			and config_key=\''.$key.'\'';
			
			$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('cdb'));
	
			return $query;
	}
	
	
	/*
	17.12.2023
	Изменение имени ключа
	$group - название группы, 
	$key - старое название ключа, 
	$name - новое название ключа
	*/
	public function updateKeyName($group, $key, $name){// получить количество ключей в указанной группе
		
		
			$sql='update config
			set config_key=\''.$name.'\'
			where group_name=\''.$group.'\' 
			and config_key=\''.$key.'\'';
			//echo Debug::vars('63', $sql); exit;
			$query = DB::query(Database::UPDATE, $sql)
			->execute(Database::instance('cdb'));
	
			return $query;
	}
	
	
	
}
	

