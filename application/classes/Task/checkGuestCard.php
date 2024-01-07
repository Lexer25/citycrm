    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Проверка срока действия гостевых карт.
	 По истечению срока действия карта удаляется.
     */
     
    class Task_checkGuestCard extends Minion_Task {
		
        
        protected function _execute(array $params)
        {
			$guest=Model::factory('Guest');
			$sql='delete from card c
			where c.id_pep in (
			select p.id_pep from people p
			where p.id_org in ('.$guest->idOrgGuest.'))
			and c.timeend<\'now\'';	
			
		Log::instance()->add(Log::DEBUG, 'Line 23 '. $sql);	
		$query = DB::query(Database::DELETE, $sql)
			->execute(Database::instance('fb'));
			
		}
    }