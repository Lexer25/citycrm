<?php defined('SYSPATH') OR die('No direct access allowed.');

class History
{
	public static function getHistory($user)
	{
		
					
		$sql=' SELECT              
                    e.id_event,
                    e.id_eventtype,
                    e.ESS1,
                    e.ESS2,
                    p.surname,
                    p.name,
                    p.patronymic,
                    et.name || \' \' || COALESCE(e.note,\'\') AS eventname,
                    e.datetime,
                    COALESCE (e.id_card, e.ESS2) as id_card,
                    d.name AS devicename
                              FROM
                   events e
                   INNER JOIN eventtype et ON e.id_eventtype = et.id_eventtype
                   join people p on p.id_pep=e.ess1
                   left join device d on d.id_dev=e.id_dev

                WHERE
					e.ess1 = ' . $user . '
				ORDER BY
					e.id_event DESC';			
		
		$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('fb'));
		
		return $query->as_array();
	}
	
}
