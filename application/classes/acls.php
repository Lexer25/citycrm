<?php defined('SYSPATH') OR die('No direct access allowed.');

class Acls
{
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM roles WHERE id > 2')
			->execute();
		return $query->as_array();
	}

	public static function canAddCompany($user)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM usersgroups WHERE id_user = ' . $user . ' AND "O_ADD" = 1')
			->execute(Database::instance('fb'));
		
		return $query->count() > 0;
	}
	
	public static function canAddContact($user)
	{
		$query = DB::query(Database::SELECT, 
			'SELECT * FROM usersgroups WHERE id_user = ' . $user . ' AND "P_ADD" = 1')
			->execute(Database::instance('fb'));
		
		return $query->count() > 0;
	}
	
	public static function getGroupId($user)
	{
		$query = DB::query(Database::SELECT,
			'SELECT FIRST 1 id_group FROM usersgroups WHERE id_user = ' . $user . ' AND "O_ADD" = 1')
			->execute(Database::instance('fb'))
			->current();

		return $query['ID_GROUP'];
	}
}
