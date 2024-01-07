    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Сверка таблицы cardidx а карт в контроллере
     */
     
    class Task_checkDeviceCountCard extends Minion_Task {
		
        
        protected function _execute(array $params)
        {
				$devList=Model::factory('device')->getDoorList(2);
				//Log::instance()->add(Log::NOTICE, Debug::vars('Список контроллеров для проверки ',$devList));
				
				$commandCount=0;
				$devList_=array(
					'108' =>  '108',
					//'109' =>  '109',
					//'111' =>  '111',
					//'112' =>  '112',
					
					);
					
					$stopList=array(
						'581'=>'581',
						'584'=>'584',
						'587'=>'587',
						'590'=>'590',
						
					);
				$testCount=1000;//счетчик для тестов: сколько итераций надо сделать
				foreach($devList as $key0=>$value0)
				{
					$door=new Door($key0);
					$dev= new Device($door->parent);
					//формирование списка карт 
					//$keyList=$door->getKeyList();// это список с учетом категорий доступа
					$keyList=$door->getCardIdxList();// это список прямо из таблицы cardidx
					
					//Log::instance()->add(Log::NOTICE, Debug::vars('Точка прохода' .$key0, $keyList));

					$ts2client=new TS2client();
					$ts2client->startServer();
					$t1=microtime(true);

					if($dev->checkConnect())
					//if(false)
					{
						Log::instance()->add(Log::NOTICE, 'Связь с контроллером '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' есть. Count='.$testCount);
						$errCount=0;
						foreach($keyList as $key=>$value)
						{
							
							$message='t56 exec device="'.$dev->name.'", command="readkey key=""'.$key.'"", door='.$door->reader.'"';
							//Log::instance()->add(Log::NOTICE, '49 '. iconv('CP1251', 'UTF-8', $message));
							$message='readkey key=""'.$key.'"", door='.$door->reader;
							//Log::instance()->add(Log::NOTICE, $message);
							$aaa=$dev->XXX($dev->name, $message, $ts2client);
							//$dev->sendMessage($message);
							//$dev->readMessage();
							//Log::instance()->add(Log::NOTICE, '55 '. iconv('CP1251', 'UTF-8', $aaa));
							if(strpos ($aaa, 'Access=Yes'))
								{
									
									//Log::instance()->add(Log::NOTICE, 'Карта '.$key.' есть в контроллере '.iconv('CP1251', 'UTF-8',$dev->name).' '.$dev->id);
								
								
								} else {
									Log::instance()->add(Log::NOTICE, 'Карты '.$key.' нет в контроллере '.iconv('CP1251', 'UTF-8',$dev->name).' '.$door->id);
										
									if(!array_key_exists ($dev->id, $stopList)) 
									{
										$door->updateCard($key); //Если контроллер не в стоп-листе, то выполнять обновление (с целью повторной записи карты
										//Log::instance()->add(Log::NOTICE, '77 Карта '.$key.' в контроллер '.iconv('CP1251', 'UTF-8',$dev->name).' '.$door->id.' будет записана повторно.');
									} else 
									{
										//Log::instance()->add(Log::NOTICE, '80 Карты '.$key.' нет в контроллере '.iconv('CP1251', 'UTF-8',$dev->name).' '.$door->id.' Запись в контроллер не выполнена, т.к. контроллер находится в стоп-листе.');
									}
									$errCount++;
								}
								
						}
					} else {
						Log::instance()->add(Log::NOTICE, 'Связи с контроллером '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' нет.');
					}
					
				
					Log::instance()->add(Log::NOTICE, 'Проверка в контроллере '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' завершена. Проверено '. count($keyList).', ошибок '.$errCount.', время проверки '.(microtime(true)-$t1));
				$testCount--;	
				if($testCount==0) 
					{
						
						Log::instance()->add(Log::NOTICE, 'Тестирование завершил, счетчик testCount='.$testCount);
						exit;
					}
					$ts2client->stopClient();
				}	
				
			
				
							
			
			/* $url = 'http://localhost/city/index.php/Dashboard/device_control';

				$request = Request::factory($url)
					->method('POST')
					->post('id_dev', $devList )
					->post('readkey', 1 );

				$response = $request->execute();

				echo $response->body(); */
		
		
		
		}
    }