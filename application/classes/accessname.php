<?php defined('SYSPATH') OR die('No direct access allowed.');

class AccessName
{
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM accessname order by NAME')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}
	
}
