<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
25.12.2023
Класс для получения типов идентификаторов
*/

class Cardtype
{
	public static function getList()
	{
		$query = DB::query(Database::SELECT,
			'SELECT * FROM cardtype order by NAME')
			->execute(Database::instance('fb'));
		return $query->as_array();
	}
	
}
