    <?php defined('SYSPATH') or die('No direct script access.');
     
    /**
     * Test class
     *
     * @author Бухаров
	 Поиск и удаление лишних карт в контроллере
	 Сверка таблицы cardidx а карт в контроллере
     */
     
    class Task_findAndDeleteExtraCard extends Minion_Task {
		
        
        protected function _execute(array $params)
        {
				$devList=Model::factory('device')->getDoorList(2);
				//Log::instance()->add(Log::NOTICE, Debug::vars('Список контроллеров для проверки ',$devList));exit;
				
				$commandCount=0;
				$devList=array(
				//	'108' =>  '108',
				//	'109' =>  '109',
				//	'111' =>  '111',
				//	'112' =>  '112',
					'324' =>  '324',
					'325' =>  '325',
					
					);


				$testCount=1000;//счетчик для тестов: сколько итераций надо сделать
				foreach($devList as $key0=>$value0)
				{
					$door=new Door($key0);
					$dev= new Device($door->parent);
					//формирование списка карт 
					//$keyList=$door->getKeyList();// это список с учетом категорий доступа
					$keyList_1=$door->getCardIdxList();// это список прямо из таблицы cardidx
					//дополнение номеров карт слева нулями
					$keyList=array();//создаю (и тем самым обнуляю) массив
					//exit;
					foreach($keyList_1 as $key=>$value)
					{
						$keyList[ str_pad ($key, 8,"0",STR_PAD_LEFT)] = str_pad ($key, 8,"0",STR_PAD_LEFT);
					}
					
					//Log::instance()->add(Log::NOTICE, Debug::vars('Точка прохода' , $keyList, $res));
					//Log::instance()->add(Log::NOTICE, Debug::vars('Точка прохода' .$key0, $keyList));
					//exit;
					$ts2client=new TS2client();
					$ts2client->startServer();
					$t1=microtime(true);

					if($dev->checkConnect())
					//if(false)
					{
						Log::instance()->add(Log::NOTICE, 'Связь с контроллером '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' есть. Count='.$testCount);
						$errCount=0;
						$cell=0;
						for($cell=0; $cell<100; $cell++)
						{
							$message='readkey cell='.$cell.', door='.$door->reader;
							Log::instance()->add(Log::NOTICE, '54 '. $message);
							$aaa=$dev->XXX($dev->name, $message, $ts2client);
							Log::instance()->add(Log::NOTICE, '55 '. $aaa); 
							//Log::instance()->add(Log::NOTICE, '56 '. Debug::vars(str_getcsv($aaa))); 
							foreach (str_getcsv($aaa) as $key=>$value)
							{
								$ddata=explode("=", $value);
								if(trim(Arr::get($ddata, 0))=='Key') 
								{
									//выделил номер карты. Теперь надо проверить ее наличие в списке карт проверяемой двери
									$key=str_replace('"', '', Arr::get($ddata,1));
									
									//Log::instance()->add(Log::NOTICE, '62 '. Debug::vars($key, array_key_exists($key, $keyList )));
									if($key!='00000000')
									{
										if(array_key_exists($key, $keyList ) )
										{
											//Log::instance()->add(Log::NOTICE, '66 Карта '. $key .' действительно должна быть в контроллере.');
										} else {
											Log::instance()->add(Log::NOTICE, '69 запрос '. $message);
											Log::instance()->add(Log::NOTICE, '70 ответ контроллера '. $aaa);
											Log::instance()->add(Log::NOTICE, '68 Карту '. $key .' надо удалять из контроллера.');
											$door->delCard($key);
												$message='deletekey  key=""'.$key.'"", door='.$door->reader;
												//Log::instance()->add(Log::NOTICE, $message);
												Log::instance()->add(Log::NOTICE, '76 Ответ на команду '.$message.' такой '. $dev->XXX($dev->name, $message, $ts2client));
											$errCount++;
										}
									}
								} else {
									//$message='deletekey cell='.$cell.', door='.$door->reader;
									//Log::instance()->add(Log::NOTICE, '94 '. $message);
									//$aaa=$dev->XXX($dev->name, $message, $ts2client);
									//Log::instance()->add(Log::NOTICE, '96 '. $aaa); 
									//Log::instance()->add(Log::NOTICE, '97 '. Debug::vars($ddata));
								}
							}
							//exit;
							
								
						}
					} else {
						Log::instance()->add(Log::NOTICE, 'Связи с контроллером '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' нет.');
					}
					
				
					Log::instance()->add(Log::NOTICE, 'Проверка в контроллере '. iconv('CP1251', 'UTF-8', $dev->name).' '.$dev->id.' точка прохода  '. iconv('CP1251', 'UTF-8', $door->name).' '.$door->id.' завершена. Проверено '. $cell.' по списку из '. count($keyList).' карт , ячеек, найдено  '.$errCount.' карт для удалдения, время проверки '.(microtime(true)-$t1));
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