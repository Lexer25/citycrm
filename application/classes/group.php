<?php defined('SYSPATH') OR die('No direct access allowed.');

class Group
{
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM organizationgroup')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}

	public static function getAcls($user)
	{
		$list = Group::getList();
		for ($i = 0; $i < count($list); $i++) {
			$query = DB::query(Database::SELECT,
				'SELECT * FROM users_groups WHERE id_user = :user AND id_group = :group')
				->parameters(array(
					':user'		=> $user,
					':group'	=> $list[$i]['ID_GROUP']))
				->execute(Database::instance('default'));
			if ($query->count() > 0)
				$list[$i]['CHECK'] = true;
			else 
				$list[$i]['CHECK'] = false;
		}
		return $list;
	}
	
	public static function addAcl($user, $group)
	{
		$query = DB::query(Database::INSERT,
			'INSERT INTO users_groups (id_user, id_group) VALUES (:user, :group)')
			->parameters(array(
				':user'		=> $user,
				':group'	=> $group))
			->execute(Database::instance('default'));
	}
	
	public static function delAcl($user, $group)
	{
		$query = DB::query(Database::DELETE, 'DELETE FROM users_groups WHERE id_user = :user AND id_group = :group')
			->parameters(array(
				':user'		=> $user,
				':group'	=> $group))
			->execute(Database::instance('default'));
	}}
