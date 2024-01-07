<?php defined('SYSPATH') or die('No direct script access.');
class Controller_People extends Controller_Template { 

	public function before()
	{
			
			parent::before();
			$session = Session::instance();
			
	}
	
	
	
	public function action_index()
	{
		$_SESSION['menu_active']='people';
		
		$content = View::factory('people/search');
        $this->template->content = $content;
	}
	

	 
	public function action_setAuthmetod()//Установка метода авториза
	{
		//echo Debug::vars('24', $this->request->post());exit;
		$post=Validation::factory($this->request->post());
		$post->rule('Authmode', 'not_empty')
						->rule('Authmode', 'digit')
						->rule('id_pep', 'not_empty')
						->rule('id_pep', 'digit')
						->rule('id_card', 'not_empty')
						;
		if($post->check())
				{
					Model::Factory('People')->setAuthMetod(Arr::get($post, 'id_pep'), Arr::get($post, 'Authmode'));
					$this->redirect('people/peopleInfo/'.Arr::get($post, 'id_pep').'/'.Arr::get($post, 'id_card'));
				}
				else {
					echo Debug::vars('37'); exit;
				}
		
	}
	 
	 
	 
	 public function action_people_delete()
	 {
	 	//echo Debug::vars('23', $_POST); exit;
	 	if (Arr::get($_POST, 'people_delete')) Model::Factory('People')->People_delete(Arr::get($_POST, 'id_pep'));
	 	if (Arr::get($_POST, 'people_long')) Model::Factory('People')->People_long(Arr::get($_POST, 'id_pep'), Arr::get($_POST, 'timeTo'));
	 	if (Arr::get($_POST, 'card_late_save_to_file')) $this->action_card_late_save_to_file();
	 	if (Arr::get($_POST, 'people_unactive')) Model::Factory('People')->people_unactive(Arr::get($_POST, 'id_pep'));
	 	if (Arr::get($_POST, 'card_delete')) Model::Factory('People')->card_delete(Arr::get($_POST, 'id_pep'));
	 	
	 	$this->redirect('people/find_card_late');
	 	
	 }
	
	 
	 public function action_find()
	 {
	 	//echo Debug::vars('61', $_GET); exit;
		$search=Arr::get($_GET, 'peopleInfo');
	 	$_SESSION['peopleEventsTimeFrom']=Arr::get($_GET, 'timeFrom', Date::formatted_time('-2 days', "d.m.Y H:i:s"));
	 	$_SESSION['peopleEventsTimeTo']=Arr::get($_GET, 'timeTo',Date::formatted_time('now', "d.m.Y H:i:s"));
		$result=Model::Factory('People')->findIdPep($search);// поиск жильцов, совпадающих с введенным именем
		if(count($result)>0)
		 {
			$content=View::Factory('people/select', array(
			'list' => $result,
			));
		 $this->template->content = $content;
		 
		 } else {
		 $content=View::Factory('people/search');
		 $this->template->content = $content;
		 }
	 }
	 
	 
	 public function action_findID()//13.02.2022 поиск пользователя по ID_PEP
	 {
	 	
		$post=Validation::factory($this->request->post());
		//echo Debug::vars('82', $_POST, $post); exit;
		$post->rule('idPepInfo', 'not_empty')
				->rule('idPepInfo', 'digit')
				->rule('idPepInfo', 'Model_People::unique_username')
						;
		if($post->check())
		{
			//echo Debug::vars('95');exit;
			$id_pep=Arr::get($post, 'idPepInfo');
			$_SESSION['peopleEventsTimeFrom']=Arr::get($_GET, 'timeFrom', Date::formatted_time('-2 days', "d.m.Y H:i:s"));
			$_SESSION['peopleEventsTimeTo']=Arr::get($_GET, 'timeTo',Date::formatted_time('now', "d.m.Y H:i:s"));
			$result=Model::Factory('People')->findIdPepInfo(array($id_pep));// поиск ID жильцов по указанном ID_PEP
		
			$content=View::Factory('people/select', array(
				'list' => $result,
				));
			 $this->template->content = $content;
		 } else {
			 //пользователь не найден. Иду опять на поиск
				Session::instance()->set('e_mess', $post->errors('Valid_mess'));
				$this->redirect('people/find');	
		 }
	 }
	 
	public function action_findGRZ()//25.03.2022 поиск пользателей по ГРЗ или его части
	{
		$post=Validation::factory($this->request->post());
				$post->rule('getCardInfo', 'not_empty')
					->rule('getCardInfo', 'regex', array(':value', '/^[A,B,C,D,E,F,H,K,M,O,P,T,X,Y,0-9]+$/'));
								;
				if($post->check())// номер передан в формате GRZ
				{
					//echo Debug::vars('116 OK GRZ,');exit;
					$key=Arr::get($post, 'getCardInfo');
					
					
				} else {
					//echo Debug::vars('121 ERR GRZ,');exit;
					Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('people/find');	
				}
				
				//echo Debug::vars('160 STOP', $key);exit;
			$data=Validation::factory(array('id_pep'=>Model::factory('People')->getIdPepFromGRZ($key)));
			$data->rule('id_pep', 'not_empty')
				->rule('id_pep', 'digit')
				;
			if($data->check())// проверка, что id_pep существует
			{	
				//Пользователь найден, надо выводить по нему информацию
				$_SESSION['peopleEventsTimeFrom']=Arr::get($_GET, 'timeFrom', Date::formatted_time('-2 days', "d.m.Y H:i:s"));
				$_SESSION['peopleEventsTimeTo']=Arr::get($_GET, 'timeTo',Date::formatted_time('now', "d.m.Y H:i:s"));
				$result=Model::Factory('People')->findIdPepInfo(array(Arr::get($data, 'id_pep')));// поиск ID жильцов по указанном ID_PEP
				$content=View::Factory('people/select', array(
					'list' => $result,
					));
				$this->template->content = $content;
						
			} else {
				//пользователь не найден. Иду опять на поиск
				Session::instance()->set('e_mess', $data->errors('Valid_mess'));
				$this->redirect('people/find');	
								
			}	
	}

	
	public function action_findAnyCard()//13.02.2022 поиск пользователя по номеру карты
	 {
	 	//echo Debug::vars($this->request->post()); exit;
		//$key='';
		$post=Validation::factory($this->request->post());
		
		$post->rule('getCardInfo', 'not_empty')
						->rule('getCardInfo', 'digit')
						;
		if($post->check())// проверка на то, что номер передан в формате целого десятичного числа
		{
			//преобразую целое длинное число к формат 001А
			$key=Model::Factory('Stat')->decDigitTo001A(Arr::get($post, 'getCardInfo'));
			
		
			
		 } else {
				//	Номер передан как цифры через запятую 123,45678	
				$post=Validation::factory($this->request->post());
				$post->rule('getCardInfo', 'not_empty')
					->rule('getCardInfo', 'regex', array(':value', '/^\d{1,3}[,][0-9]{1,5}+$/'));
								;
				if($post->check())// номер передан в формате 001A
				{
					//echo Debug::vars('157 DEC,');exit;
					$key=Model::Factory('Stat')->decCommaTo001A(Arr::get($post, 'getCardInfo'));
					
					
				} else {
				//Номер передан в формате 001A	
					 $post=Validation::factory($this->request->post());
					 $post->rule('getCardInfo', 'not_empty')
							->rule('getCardInfo', 'regex', array(':value', '/^[A-F0-9]+$/'));
									;
					if($post->check())// номер передан в формате 001A
					{
						//echo Debug::vars('147 001A');exit;
						$key=Arr::get($post, 'getCardInfo');
					} else {
						Session::instance()->set('e_mess', $post->errors('Valid_mess'));
						$this->redirect('people/find');						
					}
				}
			}
		
		
	
			
			//echo Debug::vars('160 STOP', $key);exit;
			$data=Validation::factory(array('id_pep'=>Model::factory('People')->getIdPepFromCard($key)));
			$data->rule('id_pep', 'not_empty')
				->rule('id_pep', 'digit')
				;
			if($data->check())// проверка, что id_pep существует
			{	
				//Пользователь найден, надо выводить по нему информацию
				$_SESSION['peopleEventsTimeFrom']=Arr::get($_GET, 'timeFrom', Date::formatted_time('-2 days', "d.m.Y H:i:s"));
				$_SESSION['peopleEventsTimeTo']=Arr::get($_GET, 'timeTo',Date::formatted_time('now', "d.m.Y H:i:s"));
				$result=Model::Factory('People')->findIdPepInfo(array(Arr::get($data, 'id_pep')));// поиск ID жильцов по указанном ID_PEP
				$content=View::Factory('people/select', array(
					'list' => $result,
					));
				$this->template->content = $content;
						
			} else {
				//пользователь не найден. Иду опять на поиск
				Session::instance()->set('e_mess', $data->errors('Valid_mess'));
				$this->redirect('people/find');	
								
			}		
			
			
	}
		
	 
	
	public function action_card_late_save_to_file()
	{
		Model::Factory('stat')->card_late_save_to_file();
		$content =Model::Factory('Log')->send_file(Kohana::find_file('downloads','Late_card_befor', 'csv'));		
		$this->template->content = $content;
	}
	
	public function action_card_late_next_week_save_to_file()
	{
		Model::Factory('stat')->card_late_next_week_save_to_file();
		$content =Model::Factory('Log')->send_file(Kohana::find_file('downloads','Late_card_next_week', 'csv'));		
		$this->template->content = $content;
	}
	
	
	public function action_find_card_late()
	{
		$t1=microtime(1);
		$result=Model::Factory('stat')->Get_people_late();
		$t2=microtime(1);
		$content=View::Factory('people/card_late', array(
			'list' => $result,
			'delay'=>$t2-$t1,
			'title'=>'card_late_info'
			
		));
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	
		public function action_find_unActiveCard()
	{
		$t1=microtime(1);
		$result=Model::Factory('stat')->Get_unActiveCard();
		$t2=microtime(1);
		$content=View::Factory('people/card_late', array(
			'list' => $result,
			'delay'=>$t2-$t1,
			'title'=>'unActiveCard'
		));
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
	}
	
	
	public function action_find_card_late_next_week()
	{
		$result=Model::Factory('stat')->Get_people_late_next_week();
	
			$content=View::Factory('people/card_late_next_week', array(
			'list' => $result,
			));
		 $this->template->content = $content;
		 
		 
	}
	
	
	public function action_peopleInfo($id_pep=false)//подготовка информации по выбранному пользователю
	{
			$id_pep = $this->request->param('id');
			$id_card = $this->request->param('card');
			$_SESSION['menu_active']='people';
			//echo Debug::vars('44 peopleInfo', 'POST:', $_POST, 'GET:', $_GET,'id_pep:',  $id_pep, 'SESSION:', $_SESSION); exit;
			if ($id_pep == NULL) $this->redirect('people/find');
			$people_data=Model::Factory('People')->getPeople($id_pep, $id_card);//персональные данные
			$people_door=Model::Factory('People')->getPeopleDoor($id_pep, $id_card);//Точки прохода, куда может ходить пользователь
			$people_event=Model::Factory('Event') -> event_people($id_pep, $id_card);//события по пользователю за последние 24 часа.
			$people_parking=Model::Factory('Parking') -> event_people($id_pep);//Информация о нахождении на парковке
			$people_parking_errors=Model::Factory('Parking') -> parking_error($id_pep);//Информация о нарушениях парковки
			//echo Debug::vars('125', $people_data, $id_pep); exit;
		$content=View::Factory('people/view', array(
			'contact'	=> $people_data,
			'doors'	=> $people_door,
			'events'	=> $people_event,
			'parking'	=> $people_parking,
			'people_parking_errors'	=> $people_parking_errors,
			
			));
			
		$this->template->content = $content;
	}
	
	public function action_people_without_card()//список пользователей без карты 
	{
		$people_without_card=Model::Factory('People')->getPeople_without_card();
		$content=View::factory('people/people_without_card', array(
				'list'=>$people_without_card,
		));
		$this->template->content = $content;
	}
	
	
	public function action_people_without_card_delete()//Удаление указанных пользователй без карты
	{
		$people_for_del=Arr::get($_POST, 'id_pep');
		Log::instance()->add(Log::NOTICE, 'Удадлены пользователи :user', array(
				'user' => implode(",",$people_for_del),
				));
		Model::Factory('People')->People_delete($people_for_del);//удаление указанних пользователей
		//echo Debug::vars('131', $_SESSION, $_POST, $people_for_del); exit;	
		$this->redirect('/');
	}
	
	
	public function action_people_without_events()//список пользователей без событий в журнале событий
	{
		$people_without_card=Model::Factory('People')->getPeople_without_events();
		$content=View::factory('people/people_without_events', array(
				'list'=>$people_without_card,
		));
		$this->template->content = $content;
	}
	
	
}