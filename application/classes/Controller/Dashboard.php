<?php defined('SYSPATH') or die('No direct script access.');
/*
22.06.2023 
newcrm

*/

class Controller_Dashboard extends Controller_Template {

   public $template = 'template';
	
	public function before()
	{
			Log::instance()->add(Log::NOTICE, 'Получил запрос в dashboard');
			parent::before();
			
			if (!Auth::instance()->logged_in()) $this->redirect('login'); 
		$session = Session::instance();
		$this->user = Auth::instance()->get_user();
		I18n::$lang = $session->get('language', 'en-us');
		
		
			$session = Session::instance();
			
		
			//Log::instance()->add(Log::NOTICE, 'База данных вот какая: '.Session::instance()->get('skud_number'));
			include Kohana::find_file('classes/controller','check_db_connect');
			
			Session::instance()->set('peopleEventsTimeFrom', date("d.m.Y H:m:s",strtotime("-1 days")));
			Session::instance()->set('peopleEventsTimeTo', date("d.m.Y H:m:s",strtotime("now")));
		/* 	
			$sql='select * from users';
			$query = DB::query(Database::SELECT, $sql)
			->execute(Database::instance('exportedDatabase'))
			->as_array();
			echo Debug::vars('45', $query); */
			
	}
	
	public function action_services()
	{
		
		$serverList=Model::factory('Check')->getServerList();// получили список транспортных серверов
		$content = View::factory('services', array(
			'serverList'=>$serverList,
			));
		$this->template->content = $content;
		
	}

	
	
	public function action_index()
	{	
		$t1=microtime(1);
		//echo Debug::vars('47', $_SESSION);	
		//Проверка авторизации
		if (!empty($_POST)) {
             	$username = Arr::get($_POST, 'username');
                $password = Arr::get($_POST, 'password');
			
               // if (Auth::instance()->login($username, $password)) {
                if (Auth::instance()->force_login($username, $password)) {
                $user = Auth::instance()->get_user();
				}
			}
			$config_windows=Kohana::$config->load('artonitcity_config')->main_windows;
			
			
		
		$content = View::factory('dashboard')
			->bind('user', $this->user);
		
		$this->template->content = $content;
		//echo View::factory('profiler/stats');
		
	}

	public function action_log()// просмотр лог-файлы
	{
		$_SESSION['menu_active']='log';
		$res1=Model::Factory('Log')->getList();
		$res2=Model::Factory('Log')->getListCompare();
		
		$content=View::factory('Log', array(
			'list1'=> $res1,
			'list2'=> $res2,
			));
		$this->template->content = $content;
	}
	
	public function action_sendFile ()//передача данных пользователю
	{
		$file=Arr::get($_GET, 'name');	
		//echo Debug::vars('58', $file); exit;
		$content = Model::Factory('Log')->send_file($file);
		$this->template->content = $content;
	}
	
	public function action_load() //таблица загрузки контроллеров
	{
        $_SESSION['menu_active']='load';
		$brows=Arr::get($_POST, 'browser');
		
		if(array_key_exists('browser',$_POST)) $_SESSION['brows']=Arr::get($_POST, 'browser');
		$list=Model::Factory('Stat')->load_table();
		$date_stat=Model::Factory('Stat')->date_stat();//получение даты и времени выбора статистики
		//echo Debug::vars('109', $list); exit;
		$content = View::factory('load_table', array(
			'list' => $list,
			'brows'=>$brows,
			'date_stat' =>$date_stat,
		));
        $this->template->content = $content;
        //echo View::factory('profiler/stats');
		
	}
	
	public function action_load_order()
	{
		
		$_SESSION['menu_active']='load_order';
		
		if(!empty($_POST['stop_load'])) Model::Factory('Stat')->stop_load($_POST['stop_load']);
		if(Arr::get($_POST, 'reload', 0)) Model::Factory('Stat')->repeat_load(Arr::get($_POST, 'reload'));
		if(Arr::get($_POST, 'del_queue', 0)) Model::Factory('Stat')->del_queue(Arr::get($_POST, 'reload'));
		
		$b=Model::Factory('Stat')->load_order();
		$c=Model::Factory('Stat')->load_order_overcount();
		
		$content = View::factory('order_table', array(
			'list' => $b,
			'overcount'=>$c,
		));
        $this->template->content = $content;
		
		
	}
    
	public function ErrMess ($err=false)
	{
		$content = View::factory('errorpage');
		$this->template->content = $content;
	}
	
	public function action_opendoor()// обработка команды открывания дверей
	{
		Log::instance()->add(Log::NOTICE, 'Получил запрос opendoor');
		$res=Model::Factory('Device')->sendCommand('127.0.0.1', 1967, '333', 'opendoor door=0');
		$content = View::factory('result', array(
			'content' => $res,
		));
	    $this->template->content = $content;
	}
	
	
	
	public function action_device_control()// обработка кнопок рыботы с контролерами
	{
		$_SESSION['menu_active']='device_control';
		//echo Debug::vars('144', $_POST, $_SESSION, Arr::get(Arr::get($_POST, 'id_dev4'), 'id_dev')); exit;
		$res='';
		if(array_key_exists('all',$_POST)) 
			{
				$id_dev=Model::Factory('Device')->getdeviceList();
			} else {
			
				$id_dev=Arr::get($_POST, 'id_dev'); 
			}

		if (Arr::get($_POST, 'synctime'))
		{
				if(is_null(Arr::get($_POST, 'id_dev'))) $this->redirect('errorpage?err='.__('no device id for synctime'));
				Log::instance()->add(Log::NOTICE, 'Synctime for device :user', array(
					'user' => implode(",",$id_dev),
				));
				
				
				$res=$res.Model::Factory('Device')->synctime($id_dev);
				
		}
		
		if (Arr::get($_POST, 'checkStatus'))// запись состояния контроллера в БД: версия контроллера, контроль линии связи, кол-во карт в указаанной канале (только в одном!!!), кол-во карт двери по базе данных.
		{
				
				//echo Debug::vars('173', $_POST, $id_dev); exit;
				$res=Model::Factory('Device')->insertStatusIdDev_arr($id_dev);
		}
		
		if (Arr::get($_POST, 'checkStatusOnLine'))// проверка статуса он-лайн. Делается вычитка количества карт по базе данных и из контроллера и заносится в базу данных.
		{
				//echo Debug::vars('178', 'checkStatus'); exit;
				$res=Model::Factory('Device')->checkStatusOnLine($id_dev);
				$b=Model::Factory('Stat')->load_table($id_dev, $res);
				
		}
		
		if (Arr::get($_POST, 'load_card'))// загрузить карты в контроллер 
		{

				if(is_null(Arr::get($_POST, 'id_dev'))) $this->redirect('errorpage?err='.__('no device id for load'));
				$res=Model::Factory('Device')->load_card_arr($id_dev);
		}
		
		
		if (Arr::get($_POST, 'clear_device'))
		{
				if(is_null(Arr::get($_POST, 'id_dev'))) $this->redirect('errorpage?err='.__('no device id for clear'));
				$res=Model::Factory('Device')->clear_device_arr($id_dev);
		}
		
		if (Arr::get($_POST, 'control_door'))
		{
				
				if(is_null(Arr::get($_POST, 'id_dev'))) $this->redirect('errorpage?err='.__('no device id for clear'));
				$res=Model::Factory('Device')->unlock_door_arr($id_dev, Arr::get($_POST, 'control_door'));
		}
		
		if (Arr::get($_POST, 'settz'))
		{
				if(is_null(Arr::get($_POST, 'id_dev'))) $this->redirect('errorpage?err='.__('no device id for settz'));
				$res=Model::Factory('Device')->settz_arr($id_dev);
		}
		
		if (Arr::get($_POST, 'readkey'))//вычитать данные из контроллеров, сравнить с базой данных, найти "лишние" карты и выставить их в очередь на удалдение.
		{
				
				$post=Validation::factory($_POST);
				$post->rule('id_dev', 'not_empty')
					->rule('readkey', 'digit');
					//echo Debug::vars('205', $_POST, $post->check() ); exit;
					if($post->check())
			{
				
					//echo Debug::vars('213', 'OK',  $post ); exit;
					$res=Model::Factory('Device')->readkey_arr(Arr::get($post, 'id_dev'));
				
			} else {
				
				//echo Debug::vars('218','Not valid',  $post ); exit;
				$res=$post->errors('validation');
				$res='post->errors(validation)';
			}
					
					
		}
		
		if (Arr::get($_POST, 'checkkey'))//8.07.2020 вычитать данные из контроллера по списку из БД, найти карты, которых нет в контроллере, и выставить их на запись в контроллеры
		{
				echo Debug::vars('205', $_POST ); exit;
				//$res=Model::Factory('Check')->checkKey($id_dev, NULL);
				$res=Model::Factory('Device')->readkey_arr(Arr::get($post, 'id_dev'));
		}
		
		
		
		$content = View::factory('result', array(
			'content' => $res,
		));
		
        $this->template->content = $content;
	}

	
}