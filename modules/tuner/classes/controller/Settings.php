<?php defined('SYSPATH') or die('No direct script access.');
/**
Настройка системы: ввод и просмотр разных параметров.

*/
class Controller_Settings extends Controller_Template { 
		private $session;
	

	public function before()
	{
			
			parent::before();
			$this->session = Session::instance();
			//echo Debug::vars('9controller', $_POST, $_GET);
			
			
	}
	
	/*
	17.12.2023 Доступ к изменению конфигурационных данных возможен только после ввода пароля.
	Сам пароль пока константа.
	*/
	
	public function action_auth()
	{
		$canMod=$this->session->get('canModSetting');
		
		// $array=array('llog'=>Arr::get($_POST, 'llog', 0),'ttt'=>'123');
		// $field='llog';
		// $match='ttt';
		// echo Debug::vars('27',$_POST, $array,$field, $match,  $array[$field] , $array[$match],  $array[$field] === $array[$match]); exit;
		$post=Validation::factory($_POST);
				$post->rule('llog', 'not_empty')
				->rule('llog', 'matches', array(array('llog'=>Arr::get($_POST, 'llog', 0),'ttt'=>'123'), ':field', 'ttt'))
				
				;
					
			if($post->check())
			{
						Session::instance()->set('canModSetting', true);
						//echo Debug::vars('52',$post); exit;
						
			} else {
					Session::instance()->set('canModSetting', false);
					//echo Debug::vars('56',$post, $post->errors('validation')); exit;
			}				
			
			
		$this->redirect('settings/list');
		
	}
	
	
	/*
	17.12.2023 вывод списка конфигурационных групп
	*/
	
	public function action_list()
	{
	
		//if(Arr::get(Auth::instance()->get_user(), 'ID_PEP') == 1)
		
			//получил список групп конфигурации
		$groupList=Model::factory('setting')->getgrouplist();//список зарегистрированных групп конфигурации
		
		$fl = $this->session->get('alert', '');
		$this->session->delete('alert');
		
		$content = View::factory('setting/list')
			->bind('alert', $fl)
			->bind('groupList', $groupList)
			;
        $this->template->content = $content;
	}
	
	
	public function action_index()
	{
	
		$this->redirect('settings/list');
	}
	
	
	
	 
	 /*
	 17.12.2023
	 просмотр и редактирование группы конфигурационных параметров.
	 */
	 public function action_edit()
	{
		
		$group=$this->request->param('id'); 
		//echo Debug::vars('89', $group);exit;
		
		$keyList=Kohana::$config->load($group);
		
		$fl = $this->session->get('alert', '');
		
		$this->session->delete('alert');
		
		
		
		$content = View::factory('setting/edit')
			->bind('alert', $fl)
			->bind('group', $group)
			->bind('keyList', $keyList);
        $this->template->content = $content;
	}
	 
	

	/*
	 17.12.2023
	 Сохранение значения указанного ключа key для группы group
	 */
	public function action_save()
	 {
		
		$data=$_POST;
		//echo debug::vars('122', $data, Arr::get($data, 'group'), Arr::get($data, 'key'));exit; 
		
		$post=Validation::factory($_POST);
				$post->rule('group', 'not_empty')
					->rule('key', 'not_empty')
					->rule('key', 'is_array');
			if($post->check())
			{
						foreach (Arr::get($post, 'key') as $key=>$value) {
							Log::instance()->add(Log::DEBUG, '89 '.Debug::vars($key,$value));
						
							Kohana::$config->_write_config(Arr::get($post, 'group'), $key, $value);
						
					}
				$this->session->set('alert', 'Конфигурация сохранена успешно');
			} else {
				Log::instance()->add(Log::DEBUG, '89 '.Debug::vars($post->errors('validation')));
				$this->session->set('alert', $post->errors('validation'));
				//$this->session->set('alert', 'bla');
			}
		$this->redirect('settings/main/'.Arr::get($post, 'group'));
		 
	 }
	 
	public function action_save_2()//исходный код сохранения конфигурации, где было по одному элементу
	 {
		
		$data=$_POST;
		//echo debug::vars('122', $data, Arr::get($data, 'group'), Arr::get($data, 'key'));exit; 
		
		$post=Validation::factory($_POST);
				$post->rule('group', 'not_empty')
					->rule('key', 'not_empty')
					->rule('key', 'is_array');
			if($post->check())
			{
						foreach (Arr::get($post, 'key') as $key=>$value) {
							Log::instance()->add(Log::DEBUG, '89 '.Debug::vars($key,$value));
						
							Kohana::$config->_write_config(Arr::get($post, 'group'), $key, $value);
						
					}
				$this->session->set('alert', 'Конфигурация сохранена успешно');
			} else {
				Log::instance()->add(Log::DEBUG, '89 '.Debug::vars($post->errors('validation')));
				$this->session->set('alert', $post->errors('validation'));
				//$this->session->set('alert', 'bla');
			}
		$this->redirect('settings/main/'.Arr::get($post, 'group'));
		 
	 }
	 
	/*
	17.12.2023
	Изменение key_config:
	- изменение названия ключа
	- удаление ключа из базы данных
	*/ 
	   public function action_changekey()
	 {
		$data=$_POST;
		//echo debug::vars('141', $data);exit; 
		if(is_null(Arr::get($data, 'selectKey'))){
			
			$this->session->set('alert', 'Не указано название ключа.');
			$this->redirect('settings/edit/'.Arr::get($data, 'group'));
		}

		$group=Arr::get($data, 'group');
		
		//Удаление ключа из базы данных
		if(Arr::get($data, 'deleteKey')) { 
				
			$query=Model::factory('setting')->deleteKeyfromGroup($group, Arr::get($data, 'selectKey') );
			
				if($query){
					
					$this->session->set('alert', 'Удаление ключа '.Arr::get($data, 'selectKey').' прошла успешно');
				} else {
					$this->session->set('alert', 'Не удалось удалить ключ '.Arr::get($data, 'selectKey').'  из конфигурационных параметров.');
				} 
		}
		
		
		//Изменить имя ключа
		if(Arr::get($data, 'updateKeyName')) { //переименовать ключ в базе данных
			
			$query=Model::factory('setting')->updateKeyName($group, Arr::get($data, 'selectKey'), Arr::get(Arr::get($data, 'key'), Arr::get($data, 'selectKey')) );
		
		if($query){
					
					$this->session->set('alert', 'Удаление ключа '.Arr::get($data, 'selectKey').' прошла успешно');
				} else {
					$this->session->set('alert', 'Не удалось удалить ключ '.Arr::get($data, 'selectKey').'  из конфигурационных параметров.');
				} 
				
		
		}
		$this->redirect('settings/edit/'.$group);
	 }
	 
	 
	/*
	17.12.2023
	Изменение group_name:
	- изменение названия группы
	- удаление группы возможно только при отсутствии config_key в группе
	*/ 
	   public function action_changegroup()
	 {
		$data=$_POST;
		//echo debug::vars('198', $data);exit; 

		$group=Arr::get($data, 'selectKey', null);

		//если группа не указана, то сразу  переход на список групп.
		if(is_null($group)) {
			$this->session->set('alert', 'Необходимо указать группу');
			$this->redirect('settings/list');
		}
		
		if(Arr::get($data, 'editgroup')) { //редактирование ключей в группе
			//echo 'editgroup '.$group; exit;

				$this->redirect('settings/edit/'.$group);
		
		}
		
		$this->redirect('settings/list');
	 }
	 
	 
	 
	 /*
	17.12.2023
	Добавить новый ключ
	$group - название группы
	$key - название ключа
	$newKeyValue - значение ключа
	
	*/ 
	 
	 public function action_addNewKey()
	 {
		
		$data=$_POST;
		//echo debug::vars($data);exit; 
		
		$post=Validation::factory($_POST);
				$post->rule('group', 'not_empty')
					->rule('key', 'not_empty')
					->rule('key', 'is_array');
					if($post->check())
			{
				
					//echo Debug::vars('213', 'OK',  Arr::get($post, 'key') ); exit;
					$newKeyValue = 'newKey';
					foreach (Arr::get($post, 'key') as $key=>$value) {
						
						Kohana::$config->_write_config(Arr::get($post, 'group'), $value, $newKeyValue);
						
					}
				$this->session->set('alert', 'Конфигурация сохранена успешно');
			} else {
				
				//echo Debug::vars('218','Not valid',  $post ); exit;
				$this->session->set('alert', $post->errors('validation'));
				
			}
		$this->redirect('settings/edit/'.Arr::get($post, 'group'));
		 
	 }
	 
	 /*
	 17.12.2023
	 Добавление нового группы конфигураций
	 */
	 public function action_addNewGroup()
	 {
		
		$data=$_POST;
		
		//echo debug::vars($data);exit; 
		
		$var=array();
		$post=Validation::factory($_POST);
				$post->rule('group', 'not_empty')
					->rule('key', 'not_empty')
					->rule('key', 'is_array');
			if($post->check())
			{
			//echo Debug::vars('213', 'OK',  Arr::get($post, 'key') ); exit;
					
					foreach (Arr::get($post, 'key') as $key=>$value) {
						
							Kohana::$config->_write_config($value, 'id', 0);
							$this->session->set('alert', 'Конфигурация сохранена успешно');
							//echo debug::vars('132', $var);exit;
							}
				
			} else {
				
				//echo Debug::vars('218','Not valid',  $post ); exit;
				$this->session->set('alert', $post->errors('validation'));
				
			}
		$this->redirect('settings/list');
		 
	 }
	 
	 
	 
	 
	 /*
	 Общие настройки системы:
	 -название организации (оно же в названии закладки в браузере),
	 - лицензия
	 - путь к базе данных
	 - версия системы
	 
	 */
	
	public function action_main()
	 {
		
		
		//echo debug::vars('58', $_POST);exit; 
	
		//echo Debug::vars('22', $this->session->get('alert', ''));exit;
		//$group='main';
		$group='main';
		$mainConfg=Kohana::$config->load($group);
			

		$fl = $this->session->get('alert', '');
		$this->session->delete('alert');
		
		//echo Debug::vars('22', $this->session->get('alert', ''), $fl);exit;	
		$content = View::factory('setting/main')
			->bind('alert', $fl)
			->bind('group', $group)
			->bind('mainConfg', $mainConfg)
			;
        $this->template->content = $content;

		 
	 }
	 
	
	

}